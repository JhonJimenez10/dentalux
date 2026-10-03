<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class BudgetController extends Controller
{
    // ─── Listado de presupuestos del paciente ─────────────────

    public function index(Patient $patient): View
    {
        $budgets = $patient->budgets()->with(['items', 'dentist'])->get();

        return view('budgets.index', compact('patient', 'budgets'));
    }

    // ─── Formulario de creación ───────────────────────────────

    public function create(Patient $patient): View
    {
        $dentists = User::where('active', true)
            ->whereIn('role', ['admin', 'dentist'])
            ->get();

        return view('budgets.create', compact('patient', 'dentists'));
    }

    // ─── Guardar presupuesto ──────────────────────────────────

    public function store(Request $request, Patient $patient): RedirectResponse
    {
        $data = $request->validate([
            'budget_date'       => 'required|date',
            'user_id'           => 'required|exists:users,id',
            'discount'          => 'nullable|numeric|min:0',
            'initial_payment'   => 'nullable|numeric|min:0',
            'monthly_payment'   => 'nullable|numeric|min:0',
            'notes'             => 'nullable|string',
            'odontogram'        => 'nullable|string',
            'patient_signature' => 'nullable|string',
            'items'             => 'required|array|min:1',
            'items.*.description' => 'required|string|max:200',
            'items.*.quantity'    => 'required|integer|min:1',
            'items.*.unit_price'  => 'required|numeric|min:0',
        ]);

        $budget = $patient->budgets()->create([
            'user_id'           => $data['user_id'],
            'budget_date'       => $data['budget_date'],
            'discount'          => $data['discount'] ?? 0,
            'initial_payment'   => $data['initial_payment'] ?? 0,
            'monthly_payment'   => $data['monthly_payment'] ?? 0,
            'notes'             => $data['notes'] ?? null,
            'odontogram'        => $data['odontogram'] ? json_decode($data['odontogram'], true) : null,
            'patient_signature' => $data['patient_signature'] ?? null,
            'status'            => 'pending',
            'subtotal'          => 0,
            'total'             => 0,
        ]);

        foreach ($data['items'] as $item) {
            $budget->items()->create([
                'description' => $item['description'],
                'quantity'    => (int) $item['quantity'],
                'unit_price'  => (float) $item['unit_price'],
                'total'       => (int) $item['quantity'] * (float) $item['unit_price'],
            ]);
        }

        return redirect()
            ->route('patients.budgets.show', [$patient, $budget])
            ->with('success', 'Presupuesto creado correctamente.');
    }

    // ─── Ver presupuesto ──────────────────────────────────────

    public function show(Patient $patient, Budget $budget): View
    {
        $budget->load(['items', 'dentist']);

        return view('budgets.show', compact('patient', 'budget'));
    }

    // ─── Formulario de edición ────────────────────────────────

    public function edit(Patient $patient, Budget $budget): View
    {
        $dentists = User::where('active', true)
            ->whereIn('role', ['admin', 'dentist'])
            ->get();

        $budget->load('items');

        return view('budgets.edit', compact('patient', 'budget', 'dentists'));
    }

    // ─── Actualizar presupuesto ───────────────────────────────

    public function update(Request $request, Patient $patient, Budget $budget): RedirectResponse
    {
        $data = $request->validate([
            'budget_date'       => 'required|date',
            'user_id'           => 'required|exists:users,id',
            'discount'          => 'nullable|numeric|min:0',
            'initial_payment'   => 'nullable|numeric|min:0',
            'monthly_payment'   => 'nullable|numeric|min:0',
            'status'            => 'required|in:pending,active,completed,cancelled',
            'notes'             => 'nullable|string',
            'odontogram'        => 'nullable|string',
            'patient_signature' => 'nullable|string',
            'items'             => 'required|array|min:1',
            'items.*.description' => 'required|string|max:200',
            'items.*.quantity'    => 'required|integer|min:1',
            'items.*.unit_price'  => 'required|numeric|min:0',
        ]);

        $budget->update([
            'user_id'           => $data['user_id'],
            'budget_date'       => $data['budget_date'],
            'discount'          => $data['discount'] ?? 0,
            'initial_payment'   => $data['initial_payment'] ?? 0,
            'monthly_payment'   => $data['monthly_payment'] ?? 0,
            'status'            => $data['status'],
            'notes'             => $data['notes'] ?? null,
            'odontogram'        => $data['odontogram'] ? json_decode($data['odontogram'], true) : null,
            'patient_signature' => $data['patient_signature'] ?? null,
        ]);

        $budget->items()->delete();

        foreach ($data['items'] as $item) {
            $budget->items()->create([
                'description' => $item['description'],
                'quantity'    => (int) $item['quantity'],
                'unit_price'  => (float) $item['unit_price'],
                'total'       => (int) $item['quantity'] * (float) $item['unit_price'],
            ]);
        }

        return redirect()
            ->route('patients.budgets.show', [$patient, $budget])
            ->with('success', 'Presupuesto actualizado correctamente.');
    }

    // ─── Eliminar presupuesto ─────────────────────────────────

    public function destroy(Patient $patient, Budget $budget): RedirectResponse
    {
        $budget->delete();

        return redirect()
            ->route('patients.show', $patient)
            ->with('success', 'Presupuesto eliminado.');
    }

    // ─── Vista de impresión ───────────────────────────────────

    public function print(Patient $patient, Budget $budget): View
    {
        $budget->load(['items', 'dentist']);

        return view('budgets.print', compact('patient', 'budget'));
    }
}