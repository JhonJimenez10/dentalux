<?php

namespace App\Http\Controllers;

use App\Exports\FinanceExport;
use App\Models\Expense;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class FinanceController extends Controller
{
    // ── Dashboard financiero ──────────────────────────────────
    public function index(Request $request): View
    {
        $year  = (int) $request->get('year',  now()->year);
        $month = (int) $request->get('month', now()->month);

        // ── INGRESOS del mes ──
        $incomesMonth = Payment::whereMonth('payment_date', $month)
            ->whereYear('payment_date', $year)
            ->with('paymentControl.patient')
            ->latest('payment_date')
            ->get();

        $totalIncomeMonth = $incomesMonth->sum('amount');

        // ── EGRESOS del mes ──
        $expensesMonth = Expense::whereMonth('expense_date', $month)
            ->whereYear('expense_date', $year)
            ->with('registeredBy')
            ->latest('expense_date')
            ->get();

        $totalExpenseMonth = $expensesMonth->sum('amount');

        // ── Balance ──
        $balance      = $totalIncomeMonth - $totalExpenseMonth;

        // ── Últimos 6 meses ──
        $last6 = collect();
        for ($i = 5; $i >= 0; $i--) {
            $date    = now()->subMonths($i);
            $income  = Payment::whereMonth('payment_date', $date->month)
                ->whereYear('payment_date', $date->year)
                ->sum('amount');
            $expense = Expense::whereMonth('expense_date', $date->month)
                ->whereYear('expense_date', $date->year)
                ->sum('amount');
            $last6->push([
                'label'   => $date->isoFormat('MMM YY'),
                'month'   => $date->month,
                'year'    => $date->year,
                'income'  => (float) $income,
                'expense' => (float) $expense,
                'balance' => (float) $income - (float) $expense,
            ]);
        }

        // ── Ingresos por método ──
        $incomeByMethod = $incomesMonth->groupBy('payment_method')
            ->map(fn($g) => $g->sum('amount'));

        // ── Egresos por categoría ──
        $expenseByCategory = $expensesMonth->groupBy('category')
            ->map(fn($g) => $g->sum('amount'));

        // ── Año completo ──
        $totalIncomeYear  = Payment::whereYear('payment_date', $year)->sum('amount');
        $totalExpenseYear = Expense::whereYear('expense_date', $year)->sum('amount');
        $annualBalance    = $totalIncomeYear - $totalExpenseYear;

        return view('finance.index', compact(
            'year', 'month',
            'incomesMonth', 'totalIncomeMonth',
            'expensesMonth', 'totalExpenseMonth',
            'balance', 'last6',
            'incomeByMethod', 'expenseByCategory',
            'totalIncomeYear', 'totalExpenseYear',
            'annualBalance'
        ));
    }

    // ── Exportar Excel (ingresos + egresos del mes filtrado) ──
    public function exportExcel(Request $request): BinaryFileResponse
    {
        $year  = (int) $request->get('year',  now()->year);
        $month = (int) $request->get('month', now()->month);

        $monthName = now()->setMonth($month)->isoFormat('MMMM');
        $fileName  = 'Finanzas_Dentalux_' . ucfirst($monthName) . '_' . $year . '.xlsx';

        return Excel::download(new FinanceExport($month, $year), $fileName);
    }

    // ── Exportar solo Ingresos ────────────────────────────────
    public function exportIngresos(Request $request): BinaryFileResponse
    {
        $year  = (int) $request->get('year',  now()->year);
        $month = (int) $request->get('month', now()->month);

        $monthName = now()->setMonth($month)->isoFormat('MMMM');
        $fileName  = 'Ingresos_Dentalux_' . ucfirst($monthName) . '_' . $year . '.xlsx';

        return Excel::download(
            new \App\Exports\Sheets\FinanceIngresosSheet($month, $year),
            $fileName
        );
    }

    // ── Exportar solo Egresos ─────────────────────────────────
    public function exportEgresos(Request $request): BinaryFileResponse
    {
        $year  = (int) $request->get('year',  now()->year);
        $month = (int) $request->get('month', now()->month);

        $monthName = now()->setMonth($month)->isoFormat('MMMM');
        $fileName  = 'Egresos_Dentalux_' . ucfirst($monthName) . '_' . $year . '.xlsx';

        return Excel::download(
            new \App\Exports\Sheets\FinanceEgresosSheet($month, $year),
            $fileName
        );
    }

    // ── Crear egreso ──────────────────────────────────────────
    public function createExpense(): View
    {
        return view('finance.expense-create');
    }

    public function storeExpense(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'category'       => 'required|string',
            'description'    => 'required|string|max:200',
            'amount'         => 'required|numeric|min:0.01',
            'expense_date'   => 'required|date',
            'payment_method' => 'required|string',
            'reference'      => 'nullable|string|max:100',
            'notes'          => 'nullable|string',
        ]);

        $data['user_id'] = Auth::id();
        Expense::create($data);

        return redirect()
            ->route('finance.index', ['month' => now()->month, 'year' => now()->year])
            ->with('success', 'Egreso registrado correctamente.');
    }

    public function editExpense(Expense $expense): View
    {
        return view('finance.expense-edit', compact('expense'));
    }

    public function updateExpense(Request $request, Expense $expense): RedirectResponse
    {
        $data = $request->validate([
            'category'       => 'required|string',
            'description'    => 'required|string|max:200',
            'amount'         => 'required|numeric|min:0.01',
            'expense_date'   => 'required|date',
            'payment_method' => 'required|string',
            'reference'      => 'nullable|string|max:100',
            'notes'          => 'nullable|string',
        ]);

        $expense->update($data);

        return redirect()
            ->route('finance.index')
            ->with('success', 'Egreso actualizado.');
    }

    public function destroyExpense(Expense $expense): RedirectResponse
    {
        $expense->delete();

        return redirect()
            ->route('finance.index')
            ->with('success', 'Egreso eliminado.');
    }
}