<?php

namespace App\Exports\Sheets;

use App\Models\Payment;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Maatwebsite\Excel\Events\AfterSheet;

class FinanceIngresosSheet implements FromArray, WithTitle, WithColumnWidths, WithEvents
{
    public function __construct(
        private readonly int $month,
        private readonly int $year,
    ) {}

    public function title(): string { return 'Ingresos'; }

    public function columnWidths(): array
    {
        return ['A' => 8, 'B' => 14, 'C' => 30, 'D' => 22, 'E' => 20, 'F' => 18, 'G' => 26];
    }

    public function array(): array
    {
        $date     = Carbon::createFromDate($this->year, $this->month, 1);
        $payments = Payment::whereMonth('payment_date', $this->month)
            ->whereYear('payment_date', $this->year)
            ->with('paymentControl.patient', 'registeredBy')
            ->orderBy('payment_date')
            ->get();

        $methodLabels = [
            'efectivo'        => 'Efectivo',
            'transferencia'   => 'Transferencia',
            'tarjeta_credito' => 'Tarjeta Crédito',
            'tarjeta_debito'  => 'Tarjeta Débito',
            'cheque'          => 'Cheque',
        ];

        $rows = [
            ['INGRESOS — ' . strtoupper($date->isoFormat('MMMM [de] YYYY')), '', '', '', '', '', ''],
            ['#', 'Fecha', 'Paciente', 'Cédula', 'Método de Pago', 'Monto', 'Referencia / Nota'],
        ];

        $total = 0;
        foreach ($payments as $i => $pay) {
            $patient = $pay->paymentControl?->patient;
            $rows[]  = [
                $i + 1,
                $pay->payment_date->format('d/m/Y'),
                $patient?->full_name ?? '—',
                $patient?->cedula ?? '—',
                $methodLabels[$pay->payment_method] ?? $pay->payment_method,
                $pay->amount,
                $pay->reference ?? $pay->notes ?? '',
            ];
            $total += $pay->amount;
        }

        if ($payments->isEmpty()) {
            $rows[] = ['', '', 'Sin ingresos en este período', '', '', '', ''];
        }

        $rows[] = ['', '', '', '', 'TOTAL', $total, ''];

        return $rows;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet   = $event->sheet->getDelegate();
                $lastRow = $sheet->getHighestRow();

                // Título
                $sheet->mergeCells('A1:G1');
                $sheet->getStyle('A1:G1')->applyFromArray([
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '059669']],
                    'font' => ['color' => ['rgb' => 'FFFFFF'], 'bold' => true, 'size' => 13],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                ]);
                $sheet->getRowDimension(1)->setRowHeight(30);

                // Header
                $sheet->getStyle('A2:G2')->applyFromArray([
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1A1A2E']],
                    'font' => ['color' => ['rgb' => 'FFFFFF'], 'bold' => true, 'size' => 10],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);
                $sheet->getRowDimension(2)->setRowHeight(20);

                // Datos alternos
                for ($r = 3; $r <= $lastRow - 1; $r++) {
                    $bg = ($r % 2 === 0) ? 'FFFFFF' : 'F0FDF4';
                    $sheet->getStyle("A{$r}:G{$r}")->applyFromArray([
                        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $bg]],
                        'font' => ['size' => 10],
                    ]);
                    $sheet->getStyle("A{$r}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle("B{$r}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    // Monto verde
                    $sheet->getStyle("F{$r}")->applyFromArray([
                        'font' => ['bold' => true, 'color' => ['rgb' => '059669']],
                        'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT],
                        'numberFormat' => ['formatCode' => '"$"#,##0.00'],
                    ]);
                }

                // Fila total
                $sheet->getStyle("A{$lastRow}:G{$lastRow}")->applyFromArray([
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'D1FAE5']],
                    'font' => ['bold' => true, 'size' => 11, 'color' => ['rgb' => '065F46']],
                ]);
                $sheet->getStyle("E{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle("F{$lastRow}")->applyFromArray([
                    'font' => ['bold' => true, 'size' => 13, 'color' => ['rgb' => '059669']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT],
                    'numberFormat' => ['formatCode' => '"$"#,##0.00'],
                ]);
                $sheet->getRowDimension($lastRow)->setRowHeight(24);

                // Bordes
                $sheet->getStyle("A1:G{$lastRow}")->applyFromArray([
                    'borders' => [
                        'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'E5E7EB']],
                        'outline'    => ['borderStyle' => Border::BORDER_MEDIUM, 'color' => ['rgb' => 'D1D5DB']],
                    ],
                ]);

                // Altura de filas
                for ($r = 3; $r <= $lastRow; $r++) {
                    $sheet->getRowDimension($r)->setRowHeight(18);
                }
            },
        ];
    }
}