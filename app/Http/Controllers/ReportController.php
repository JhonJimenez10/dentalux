<?php

namespace App\Http\Controllers;

use App\Exports\DailyReportExport;
use App\Models\Payment;
use App\Models\Expense;
use App\Models\Patient;
use App\Models\CashClosing;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ReportController extends Controller
{
    // ── Vista previa del reporte ──────────────────────────────
    public function daily(Request $request): View
    {
        $date = Carbon::parse($request->get('date', today()->format('Y-m-d')));

        $payments = Payment::whereDate('payment_date', $date)
            ->with('paymentControl.patient','registeredBy')
            ->get();

        $expenses = Expense::whereDate('expense_date', $date)
            ->with('registeredBy')
            ->get();

        $patients = Patient::whereDate('created_at', $date)->get();

        $closing = CashClosing::whereDate('closing_date', $date)->first();

        $totalIncome  = $payments->sum('amount');
        $totalExpense = $expenses->sum('amount');
        $balance      = $totalIncome - $totalExpense;

        $incomeByMethod = $payments->groupBy('payment_method')
            ->map(fn($g) => $g->sum('amount'));

        $expenseByCategory = $expenses->groupBy('category')
            ->map(fn($g) => $g->sum('amount'));

        return view('reports.daily', compact(
            'date', 'payments', 'expenses', 'patients', 'closing',
            'totalIncome', 'totalExpense', 'balance',
            'incomeByMethod', 'expenseByCategory'
        ));
    }

    // ── Exportar Excel ────────────────────────────────────────
    public function exportDaily(Request $request): BinaryFileResponse
    {
        $date     = Carbon::parse($request->get('date', today()->format('Y-m-d')));
        $fileName = 'Reporte_Diario_Dentalux_'.$date->format('Y-m-d').'.xlsx';

        return Excel::download(new DailyReportExport($date), $fileName);
    }
}