<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Patient;
use App\Models\Budget;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Carbon\Carbon;

class DashboardReportController extends Controller
{
    public function index(Request $request): View
    {
        $year  = $request->get('year',  now()->year);
        $month = $request->get('month', now()->month);

        // Ingresos del mes seleccionado
        $paymentsThisMonth = Payment::whereMonth('payment_date', $month)
            ->whereYear('payment_date', $year)
            ->get();

        $totalMonth = $paymentsThisMonth->sum('amount');

        // Ingresos por método de pago
        $byMethod = $paymentsThisMonth->groupBy('payment_method')
            ->map(fn($g) => $g->sum('amount'));

        // Ingresos por día del mes (para gráfica)
        $byDay = $paymentsThisMonth->groupBy(
            fn($p) => $p->payment_date->format('d')
        )->map(fn($g) => $g->sum('amount'));

        // Ingresos de los últimos 6 meses
        $last6Months = collect();
        for ($i = 5; $i >= 0; $i--) {
            $date  = now()->subMonths($i);
            $total = Payment::whereMonth('payment_date', $date->month)
                ->whereYear('payment_date', $date->year)
                ->sum('amount');
            $last6Months->push([
                'label'  => $date->isoFormat('MMM YYYY'),
                'total'  => $total,
                'month'  => $date->month,
                'year'   => $date->year,
            ]);
        }

        // Últimos pagos
        $recentPayments = Payment::with([
                'paymentControl.patient',
                'registeredBy'
            ])
            ->latest()
            ->take(10)
            ->get();

        // Stats generales
        $stats = [
            'total_patients'    => Patient::count(),
            'active_budgets'    => Budget::where('status', 'active')->count(),
            'completed_budgets' => Budget::where('status', 'completed')->count(),
            'total_income'      => Payment::whereYear('payment_date', $year)->sum('amount'),
        ];

        return view('reports.income', compact(
            'totalMonth', 'byMethod', 'byDay',
            'last6Months', 'recentPayments', 'stats',
            'year', 'month'
        ));
    }
}