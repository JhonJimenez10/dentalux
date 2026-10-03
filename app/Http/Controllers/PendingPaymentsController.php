<?php

namespace App\Http\Controllers;

use App\Models\PaymentControl;
use App\Exports\PendingPaymentsExport;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Carbon\Carbon;

class PendingPaymentsController extends Controller
{
    // ── Vista del reporte ─────────────────────────────────────
    public function index(Request $request): View
    {
        $dateFrom = $request->get('date_from', now()->startOfMonth()->format('Y-m-d'));
        $dateTo   = $request->get('date_to',   now()->format('Y-m-d'));

        $pendingControls = $this->getQuery($dateFrom, $dateTo)->get();

        // Totales del filtro
        $totalTreatment = $pendingControls->sum('treatment_amount');
        $totalPaid      = $pendingControls->sum('total_paid');
        $totalPending   = $pendingControls->sum('balance');
        $totalPatients  = $pendingControls->count();

        // Agrupar por antigüedad del saldo
        $overdue30  = $pendingControls->filter(fn($pc) => $this->daysSinceLastPayment($pc) >= 30 && $this->daysSinceLastPayment($pc) < 60)->count();
        $overdue60  = $pendingControls->filter(fn($pc) => $this->daysSinceLastPayment($pc) >= 60 && $this->daysSinceLastPayment($pc) < 90)->count();
        $overdue90  = $pendingControls->filter(fn($pc) => $this->daysSinceLastPayment($pc) >= 90)->count();
        $recent     = $pendingControls->filter(fn($pc) => $this->daysSinceLastPayment($pc) < 30)->count();

        return view('reports.pending-payments', compact(
            'pendingControls', 'dateFrom', 'dateTo',
            'totalTreatment', 'totalPaid', 'totalPending', 'totalPatients',
            'overdue30', 'overdue60', 'overdue90', 'recent'
        ));
    }

    // ── Exportar Excel ────────────────────────────────────────
    public function export(Request $request): BinaryFileResponse
    {
        $dateFrom = $request->get('date_from', now()->startOfMonth()->format('Y-m-d'));
        $dateTo   = $request->get('date_to',   now()->format('Y-m-d'));

        $fileName = 'Pendientes_Pago_' .
                    Carbon::parse($dateFrom)->format('d-m-Y') . '_al_' .
                    Carbon::parse($dateTo)->format('d-m-Y') . '.xlsx';

        return Excel::download(
            new PendingPaymentsExport($dateFrom, $dateTo),
            $fileName
        );
    }

    // ── Query base ────────────────────────────────────────────
    private function getQuery(string $dateFrom, string $dateTo)
    {
        return PaymentControl::with(['patient', 'payments', 'budget'])
            ->where('treatment_amount', '>', 0)
            ->whereRaw('treatment_amount > COALESCE((
                SELECT SUM(amount) FROM payments
                WHERE payments.payment_control_id = payment_controls.id
            ), 0)')
            ->whereBetween('created_at', [
                Carbon::parse($dateFrom)->startOfDay(),
                Carbon::parse($dateTo)->endOfDay(),
            ])
            ->orderByRaw('(treatment_amount - COALESCE((
                SELECT SUM(amount) FROM payments
                WHERE payments.payment_control_id = payment_controls.id
            ), 0)) DESC');
    }

    // ── Días desde último pago ────────────────────────────────
    private function daysSinceLastPayment(PaymentControl $pc): int
    {
        $lastPayment = $pc->payments->sortByDesc('payment_date')->first();
        if (!$lastPayment) {
            return now()->diffInDays($pc->created_at);
        }
        return now()->diffInDays($lastPayment->payment_date);
    }
}