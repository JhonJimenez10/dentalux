<?php

namespace App\Http\Controllers;

use App\Models\CashClosing;
use App\Models\Payment;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CashClosingController extends Controller
{
    // ── Lista de cierres ──────────────────────────────────────
    public function index(Request $request): View
    {
        $year  = (int) $request->get('year',  now()->year);
        $month = (int) $request->get('month', now()->month);

        $closings = CashClosing::whereYear('closing_date', $year)
            ->whereMonth('closing_date', $month)
            ->with('closedBy')
            ->orderByDesc('closing_date')
            ->get();

        $totalIncome   = $closings->sum('total_income');
        $totalExpenses = $closings->sum('total_expenses');
        $totalBalance  = $totalIncome - $totalExpenses;
        $daysWithData  = $closings->count();

        $todayClosing = CashClosing::whereDate('closing_date', today())->first();

        return view('cash-closing.index', compact(
            'closings', 'year', 'month',
            'totalIncome', 'totalExpenses', 'totalBalance',
            'daysWithData', 'todayClosing'
        ));
    }

    // ── Preparar nuevo cierre del día ─────────────────────────
    public function create(Request $request): View|RedirectResponse
    {
        $date = $request->get('date', today()->format('Y-m-d'));
        $date = Carbon::parse($date);

        // Si ya existe cierre CERRADO → no se puede editar, redirigir a show
        $existing = CashClosing::whereDate('closing_date', $date)->first();
        if ($existing && $existing->status === 'closed') {
            return redirect()
                ->route('cash-closing.show', $existing)
                ->with('info', 'Este cierre ya está cerrado y bloqueado. No se puede modificar.');
        }

        // ── Calcular datos del día ──
        $payments = Payment::whereDate('payment_date', $date)
            ->with('paymentControl.patient')
            ->get();

        $expenses = Expense::whereDate('expense_date', $date)
            ->with('registeredBy')
            ->get();

        $incomeCash     = (float) $payments->where('payment_method', 'efectivo')->sum('amount');
        $incomeTransfer = (float) $payments->where('payment_method', 'transferencia')->sum('amount');
        $incomeCard     = (float) $payments->whereIn('payment_method', ['tarjeta_credito', 'tarjeta_debito'])->sum('amount');
        $incomeOther    = (float) $payments->where('payment_method', 'cheque')->sum('amount');
        $totalIncome    = (float) $payments->sum('amount');
        $totalExpenses  = (float) $expenses->sum('amount');

        $lastClosing = CashClosing::where('closing_date', '<', $date->format('Y-m-d'))
            ->where('status', 'closed')
            ->orderByDesc('closing_date')
            ->first();

        $openingBalance = $lastClosing ? (float) $lastClosing->closing_balance : 0.0;
        $expensesCash   = (float) $expenses->where('payment_method', 'efectivo')->sum('amount');
        $expectedCash   = $openingBalance + $incomeCash - $expensesCash;

        return view('cash-closing.create', compact(
            'date', 'payments', 'expenses',
            'incomeCash', 'incomeTransfer', 'incomeCard', 'incomeOther',
            'totalIncome', 'totalExpenses',
            'openingBalance', 'expectedCash', 'expensesCash',
            'existing'
        ));
    }

    // ── Guardar / cerrar caja ─────────────────────────────────
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'closing_date'    => 'required|date',
            'opening_balance' => 'required|numeric|min:0',
            'income_cash'     => 'required|numeric',
            'income_transfer' => 'required|numeric',
            'income_card'     => 'required|numeric',
            'income_other'    => 'required|numeric',
            'total_income'    => 'required|numeric',
            'total_expenses'  => 'required|numeric',
            'expected_cash'   => 'required|numeric',
            'actual_cash'     => 'required|numeric|min:0',
            'notes'           => 'nullable|string',
            'action'          => 'required|in:save,close',
        ]);

        // ── Bloquear si ya está cerrado ──
        $existing = CashClosing::whereDate('closing_date', $data['closing_date'])->first();
        if ($existing && $existing->status === 'closed') {
            return redirect()
                ->route('cash-closing.show', $existing)
                ->with('error', 'Este cierre ya está cerrado y no se puede modificar.');
        }

        // ── Validar que el efectivo real no sea negativo ──
        if ((float) $data['actual_cash'] < 0) {
            return back()
                ->withErrors(['actual_cash' => 'El efectivo real no puede ser negativo.'])
                ->withInput();
        }

        // ── Si es cierre definitivo, validar que el efectivo no deje saldo negativo ──
        if ($data['action'] === 'close') {
            $difference = (float) $data['actual_cash'] - (float) $data['expected_cash'];

            // No permitir cerrar si el efectivo físico sería negativo (diferencia mayor al esperado en negativo)
            if ((float) $data['actual_cash'] < 0) {
                return back()
                    ->withErrors(['actual_cash' => 'No se puede cerrar la caja con efectivo negativo.'])
                    ->withInput();
            }
        }

        $difference     = (float) $data['actual_cash'] - (float) $data['expected_cash'];
        $closingBalance = (float) $data['actual_cash'];

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $record = CashClosing::updateOrCreate(
            ['closing_date' => $data['closing_date']],
            [
                'closed_by'       => $user->id,
                'opening_balance' => $data['opening_balance'],
                'income_cash'     => $data['income_cash'],
                'income_transfer' => $data['income_transfer'],
                'income_card'     => $data['income_card'],
                'income_other'    => $data['income_other'],
                'total_income'    => $data['total_income'],
                'total_expenses'  => $data['total_expenses'],
                'expected_cash'   => $data['expected_cash'],
                'actual_cash'     => $data['actual_cash'],
                'difference'      => $difference,
                'closing_balance' => $closingBalance,
                'notes'           => $data['notes'] ?? null,
                'status'          => $data['action'] === 'close' ? 'closed' : 'open',
                'closed_at'       => $data['action'] === 'close' ? now() : null,
            ]
        );

        if ($data['action'] === 'close') {
            $diffText = $difference == 0
                ? 'La caja cuadró perfectamente.'
                : ($difference > 0
                    ? 'Sobrante de $' . number_format(abs($difference), 2) . '.'
                    : 'Faltante de $' . number_format(abs($difference), 2) . '.');

            return redirect()
                ->route('cash-closing.show', $record)
                ->with('success', '✅ Caja cerrada y bloqueada. ' . $diffText);
        }

        return redirect()
            ->route('cash-closing.show', $record)
            ->with('success', 'Cierre guardado como borrador. Puedes seguir editando.');
    }

    // ── Ver cierre ────────────────────────────────────────────
    public function show(CashClosing $cashClosing): View
    {
        $payments = Payment::whereDate('payment_date', $cashClosing->closing_date)
            ->with('paymentControl.patient')
            ->get();

        $expenses = Expense::whereDate('expense_date', $cashClosing->closing_date)
            ->with('registeredBy')
            ->get();

        return view('cash-closing.show', compact('cashClosing', 'payments', 'expenses'));
    }

    // ── Reabrir — DESHABILITADO ───────────────────────────────
    // La caja cerrada no se puede reabrir para garantizar integridad
    public function reopen(CashClosing $cashClosing): RedirectResponse
    {
        return redirect()
            ->route('cash-closing.show', $cashClosing)
            ->with('error', '🔒 Los cierres de caja no se pueden reabrir para garantizar la integridad del sistema. Contacta al administrador del sistema si necesitas hacer una corrección.');
    }
}