<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\PaymentControl;
use App\Models\Payment;
use App\Models\Budget;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Mail\VoucherMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    // ─── Ver control de pagos del paciente ───────────────────

    public function show(Patient $patient, PaymentControl $paymentControl): View
    {
        $paymentControl->load(['payments.registeredBy', 'budget', 'patient']);
        return view('payments.show', compact('patient', 'paymentControl'));
    }

    // ─── Formulario crear control de pagos ───────────────────

    public function create(Patient $patient): View
    {
        // Solo presupuestos pending/active que NO tienen control de pagos
        $budgetsWithControl = $patient->paymentControls()
            ->whereNotNull('budget_id')
            ->pluck('budget_id');

        $budgets = $patient->budgets()
            ->whereIn('status', ['pending', 'active'])
            ->whereNotIn('id', $budgetsWithControl) // ← excluir los que ya tienen control
            ->get();

        return view('payments.create', compact('patient', 'budgets'));
    }

    // ─── Guardar control de pagos ─────────────────────────────

    public function store(Request $request, Patient $patient): RedirectResponse
    {
        $data = $request->validate([
            'budget_id'        => 'nullable|exists:budgets,id',
            'treatment_amount' => 'required|numeric|min:1',
        ]);

        // Validación extra: si seleccionó presupuesto verificar que no tenga ya control
        if (!empty($data['budget_id'])) {
            $yaExiste = $patient->paymentControls()
                ->where('budget_id', $data['budget_id'])
                ->exists();

            if ($yaExiste) {
                return back()
                    ->withErrors(['budget_id' => 'Este presupuesto ya tiene un control de pagos.'])
                    ->withInput();
            }

            // Validar que el monto no sea menor al total del presupuesto
            $budget = Budget::find($data['budget_id']);
            if ($budget && $data['treatment_amount'] < $budget->total) {
                return back()
                    ->withErrors(['treatment_amount' => 'El monto no puede ser menor al total del presupuesto ($' . number_format($budget->total, 2) . ').'])
                    ->withInput();
            }

            Budget::find($data['budget_id'])->update(['status' => 'active']);
        }

        $paymentControl = $patient->paymentControls()->create($data);

        return redirect()
            ->route('patients.payments.show', [$patient, $paymentControl])
            ->with('success', 'Control de pagos creado correctamente.');
    }

    // ─── Formulario agregar pago ──────────────────────────────

    public function addPayment(Patient $patient, PaymentControl $paymentControl): View
    {
        return view('payments.add', compact('patient', 'paymentControl'));
    }

    // ─── Guardar pago con firma ───────────────────────────────

    public function storePayment(
        Request $request,
        Patient $patient,
        PaymentControl $paymentControl
    ): RedirectResponse {

        $balance = $paymentControl->balance;

        $request->validate([
            'payment_date'      => 'required|date',
            'amount'            => [
                'required',
                'numeric',
                'min:0.01',
                // No puede superar el saldo pendiente
                function($attr, $value, $fail) use ($balance) {
                    if ((float) $value > (float) $balance) {
                        $fail('El abono ($' . number_format($value, 2) . ') no puede superar el saldo pendiente ($' . number_format($balance, 2) . ').');
                    }
                },
            ],
            'payment_method'    => 'required|string',
            'reference'         => 'nullable|string|max:255',
            'notes'             => 'nullable|string',
            'patient_signature' => 'nullable|string',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $payment = $paymentControl->payments()->create([
            'user_id'           => $user->id,
            'payment_date'      => $request->payment_date,
            'amount'            => $request->amount,
            'payment_method'    => $request->payment_method,
            'reference'         => $request->reference,
            'notes'             => $request->notes,
            'patient_signature' => $request->patient_signature,
        ]);

        // ── Enviar voucher automáticamente si el paciente tiene correo ──
        $emailSent   = false;
        $emailAddress = null;

        if ($patient->email) {
            try {
                $paymentControl->load('payments');
                Mail::to($patient->email)->send(
                    new VoucherMail($patient, $paymentControl, $payment)
                );
                $emailSent    = true;
                $emailAddress = $patient->email;
            } catch (\Exception $e) {
                Log::error('Error enviando voucher automático: ' . $e->getMessage());
            }
        }

        $successMsg = 'Pago registrado correctamente.';
        if ($emailSent) {
            $successMsg .= ' Comprobante enviado a ' . $emailAddress . '.';
        }

        return redirect()
            ->route('patients.payments.show', [$patient, $paymentControl])
            ->with('success', $successMsg);
    }

    // ─── Eliminar un pago ─────────────────────────────────────

    public function destroyPayment(
        Patient $patient,
        PaymentControl $paymentControl,
        Payment $payment
    ): RedirectResponse {
        $payment->delete();
        return redirect()
            ->route('patients.payments.show', [$patient, $paymentControl])
            ->with('success', 'Pago eliminado.');
    }

    // ─── Imprimir control de pagos ────────────────────────────

    public function print(Patient $patient, PaymentControl $paymentControl): View
    {
        $paymentControl->load(['payments.registeredBy', 'budget', 'patient']);
        return view('payments.print', compact('patient', 'paymentControl'));
    }

    // ─── Voucher de pago ─────────────────────────────────────

    public function voucher(
        Patient $patient,
        PaymentControl $paymentControl,
        Payment $payment
    ): View {
        $paymentControl->load('payments');
        return view('payments.voucher', compact('patient', 'paymentControl', 'payment'));
    }

    // ─── Enviar voucher por correo ────────────────────────────

    public function sendVoucher(
        Request $request,
        Patient $patient,
        PaymentControl $paymentControl,
        Payment $payment
    ): JsonResponse {
        $request->validate(['email' => 'required|email']);

        $email = $request->input('email');

        try {
            Mail::to($email)->send(
                new VoucherMail($patient, $paymentControl, $payment)
            );
            return response()->json([
                'success' => true,
                'message' => "Voucher enviado correctamente a {$email}",
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al enviar: ' . $e->getMessage(),
            ], 500);
        }
    }
}