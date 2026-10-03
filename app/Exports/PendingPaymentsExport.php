<?php

namespace App\Exports;

use App\Models\PaymentControl;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use Maatwebsite\Excel\Events\AfterSheet;

class PendingPaymentsExport implements FromArray, WithTitle, WithColumnWidths, WithEvents
{
    public function __construct(
        private readonly string $dateFrom,
        private readonly string $dateTo,
    ) {}

    public function title(): string { return 'Pendientes de Pago'; }

    public function columnWidths(): array
    {
        return [
            'A' => 6,
            'B' => 28,
            'C' => 16,
            'D' => 14,
            'E' => 16,
            'F' => 16,
            'G' => 16,
            'H' => 12,
            'I' => 16,
            'J' => 14,
        ];
    }

    public function array(): array
    {
        $from = Carbon::parse($this->dateFrom)->startOfDay();
        $to   = Carbon::parse($this->dateTo)->endOfDay();

        $controls = PaymentControl::with(['patient', 'payments', 'budget'])
            ->where('treatment_amount', '>', 0)
            ->whereRaw('treatment_amount > COALESCE((
                SELECT SUM(amount) FROM payments
                WHERE payments.payment_control_id = payment_controls.id
            ), 0)')
            ->whereBetween('created_at', [$from, $to])
            ->orderByRaw('(treatment_amount - COALESCE((
                SELECT SUM(amount) FROM payments
                WHERE payments.payment_control_id = payment_controls.id
            ), 0)) DESC')
            ->get();

        $rows = [
            // Título
            ['REPORTE DE PAGOS PENDIENTES — DENTALUX ODONTOLOGÍA FAMILIAR', '','','','','','','','',''],
            [
                'Período: ' . Carbon::parse($this->dateFrom)->format('d/m/Y') .
                ' al '      . Carbon::parse($this->dateTo)->format('d/m/Y'),
                '','','','','','','','',''
            ],
            ['Generado: ' . now()->format('d/m/Y H:i'), '','','','','','','','',''],
            ['','','','','','','','','',''],
            // Header
            [
                '#', 'Paciente', 'Cédula', 'Teléfono',
                'Total Tratamiento', 'Total Abonado', 'Saldo Pendiente',
                'Avance %', 'Último Pago', 'Días Sin Pago'
            ],
        ];

        $totalTreatment = 0;
        $totalPaid      = 0;
        $totalPending   = 0;

        foreach ($controls as $i => $pc) {
            $patient     = $pc->patient;
            $lastPayment = $pc->payments->sortByDesc('payment_date')->first();
            $daysSince   = $lastPayment
                ? now()->diffInDays($lastPayment->payment_date)
                : now()->diffInDays($pc->created_at);

            $paid    = $pc->total_paid;
            $balance = $pc->balance;
            $pct     = $pc->treatment_amount > 0
                ? round(($paid / $pc->treatment_amount) * 100, 1)
                : 0;

            $totalTreatment += $pc->treatment_amount;
            $totalPaid      += $paid;
            $totalPending   += $balance;

            $rows[] = [
                $i + 1,
                $patient?->full_name ?? '—',
                $patient?->cedula    ?? '—',
                $patient?->phone     ?? '—',
                $pc->treatment_amount,
                $paid,
                $balance,
                $pct . '%',
                $lastPayment ? $lastPayment->payment_date->format('d/m/Y') : 'Sin pagos',
                $daysSince . ' días',
            ];
        }

        // Fila de totales
        $rows[] = ['','','','TOTALES', $totalTreatment, $totalPaid, $totalPending, '','',''];

        // Si no hay datos
        if ($controls->isEmpty()) {
            $rows[] = ['','Sin registros pendientes en el período seleccionado','','','','','','','',''];
        }

        return $rows;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet   = $event->sheet->getDelegate();
                $lastRow = $sheet->getHighestRow();
                $dark    = '1A1A2E';
                $rose    = 'C8395A';

                // ── Título ──
                foreach ([1,2,3] as $r) {
                    $sheet->mergeCells("A{$r}:J{$r}");
                }
                $sheet->getStyle('A1:J3')->applyFromArray([
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $dark]],
                    'font' => ['color' => ['rgb' => 'FFFFFF'], 'bold' => true],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER,
                                    'vertical'   => Alignment::VERTICAL_CENTER],
                ]);
                $sheet->getStyle('A1')->getFont()->setSize(13);
                $sheet->getRowDimension(1)->setRowHeight(30);
                $sheet->getRowDimension(2)->setRowHeight(18);
                $sheet->getRowDimension(3)->setRowHeight(16);

                // ── Header columnas (fila 5) ──
                $sheet->getStyle('A5:J5')->applyFromArray([
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $rose]],
                    'font' => ['color' => ['rgb' => 'FFFFFF'], 'bold' => true, 'size' => 10],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER,
                                    'vertical'   => Alignment::VERTICAL_CENTER],
                ]);
                $sheet->getRowDimension(5)->setRowHeight(22);

                // ── Datos ──
                for ($r = 6; $r <= $lastRow - 1; $r++) {
                    $bg = ($r % 2 === 0) ? 'FFFFFF' : 'FFF5F7';
                    $sheet->getStyle("A{$r}:J{$r}")->applyFromArray([
                        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $bg]],
                        'font' => ['size' => 10],
                    ]);

                    // # centrado
                    $sheet->getStyle("A{$r}")->getAlignment()
                          ->setHorizontal(Alignment::HORIZONTAL_CENTER);

                    // Total tratamiento
                    $sheet->getStyle("E{$r}")->applyFromArray([
                        'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT],
                        'numberFormat' => ['formatCode' => '"$"#,##0.00'],
                    ]);
                    // Total abonado — verde
                    $sheet->getStyle("F{$r}")->applyFromArray([
                        'font' => ['color' => ['rgb' => '059669']],
                        'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT],
                        'numberFormat' => ['formatCode' => '"$"#,##0.00'],
                    ]);
                    // Saldo pendiente — rojo
                    $sheet->getStyle("G{$r}")->applyFromArray([
                        'font' => ['bold' => true, 'color' => ['rgb' => 'DC2626']],
                        'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT],
                        'numberFormat' => ['formatCode' => '"$"#,##0.00'],
                    ]);
                    // Avance %
                    $sheet->getStyle("H{$r}")->getAlignment()
                          ->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    // Último pago
                    $sheet->getStyle("I{$r}")->getAlignment()
                          ->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    // Días
                    $sheet->getStyle("J{$r}")->applyFromArray([
                        'font' => ['bold' => true],
                        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                    ]);

                    $sheet->getRowDimension($r)->setRowHeight(18);
                }

                // ── Fila totales ──
                $sheet->getStyle("A{$lastRow}:J{$lastRow}")->applyFromArray([
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FEE2E2']],
                    'font' => ['bold' => true, 'size' => 11, 'color' => ['rgb' => '991B1B']],
                ]);
                $sheet->getStyle("D{$lastRow}")->getAlignment()
                      ->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                foreach (['E','F','G'] as $col) {
                    $sheet->getStyle("{$col}{$lastRow}")->applyFromArray([
                        'font' => ['bold' => true, 'size' => 12],
                        'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT],
                        'numberFormat' => ['formatCode' => '"$"#,##0.00'],
                    ]);
                }
                $sheet->getStyle("G{$lastRow}")->getFont()->getColor()
                      ->setARGB('FFDC2626');
                $sheet->getRowDimension($lastRow)->setRowHeight(24);

                // ── Bordes ──
                $sheet->getStyle("A5:J{$lastRow}")->applyFromArray([
                    'borders' => [
                        'allBorders' => ['borderStyle' => Border::BORDER_THIN,
                                         'color'       => ['rgb' => 'E5E7EB']],
                        'outline'    => ['borderStyle' => Border::BORDER_MEDIUM,
                                         'color'       => ['rgb' => 'D1D5DB']],
                    ],
                ]);

                // Freeze primera fila con datos
                $sheet->freezePane('A6');
            },
        ];
    }
}