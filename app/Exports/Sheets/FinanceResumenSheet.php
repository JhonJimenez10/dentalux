<?php

namespace App\Exports\Sheets;

use App\Models\Payment;
use App\Models\Expense;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Maatwebsite\Excel\Events\AfterSheet;

class FinanceResumenSheet implements FromArray, WithTitle, WithColumnWidths, WithEvents
{
    private Carbon $date;

    public function __construct(
        private readonly int $month,
        private readonly int $year,
    ) {
        $this->date = Carbon::createFromDate($year, $month, 1);
    }

    public function title(): string { return 'Resumen'; }

    public function columnWidths(): array
    {
        return ['A' => 36, 'B' => 20, 'C' => 20];
    }

    public function array(): array
    {
        $incomes  = Payment::whereMonth('payment_date', $this->month)
            ->whereYear('payment_date', $this->year)
            ->get();
        $expenses = Expense::whereMonth('expense_date', $this->month)
            ->whereYear('expense_date', $this->year)
            ->get();

        $totalInc = $incomes->sum('amount');
        $totalExp = $expenses->sum('amount');
        $balance  = $totalInc - $totalExp;

        // Ingresos por método
        $byMethod = $incomes->groupBy('payment_method')->map(fn($g) => $g->sum('amount'));

        // Egresos por categoría
        $byCat = $expenses->groupBy('category')->map(fn($g) => $g->sum('amount'));

        $methodLabels = [
            'efectivo'        => 'Efectivo',
            'transferencia'   => 'Transferencia',
            'tarjeta_credito' => 'Tarjeta Crédito',
            'tarjeta_debito'  => 'Tarjeta Débito',
            'cheque'          => 'Cheque',
        ];
        $catLabels = [
            'materiales'    => 'Materiales',
            'equipos'       => 'Equipos',
            'servicios'     => 'Servicios',
            'arriendo'      => 'Arriendo',
            'sueldos'       => 'Sueldos',
            'laboratorio'   => 'Laboratorio',
            'publicidad'    => 'Publicidad',
            'mantenimiento' => 'Mantenimiento',
            'otros'         => 'Otros',
        ];

        $rows = [
            // Título
            ['REPORTE FINANCIERO — DENTALUX ODONTOLOGÍA FAMILIAR', '', ''],
            [$this->date->isoFormat('MMMM [de] YYYY'), '', ''],
            ['Generado: ' . now()->format('d/m/Y H:i'), '', ''],
            ['', '', ''],

            // Resumen financiero
            ['RESUMEN DEL PERÍODO', '', ''],
            ['Concepto', 'Monto', 'Transacciones'],
            ['Total Ingresos', '$' . number_format($totalInc, 2), $incomes->count() . ' pago(s)'],
            ['Total Egresos',  '$' . number_format($totalExp, 2), $expenses->count() . ' gasto(s)'],
            ['BALANCE DEL MES', '$' . number_format($balance, 2), $balance >= 0 ? 'SUPERÁVIT' : 'DÉFICIT'],
            ['', '', ''],

            // Ingresos por método
            ['INGRESOS POR MÉTODO DE PAGO', '', ''],
            ['Método', 'Monto', 'Cantidad'],
        ];

        foreach ($byMethod as $method => $total) {
            $rows[] = [
                $methodLabels[$method] ?? ucfirst($method),
                '$' . number_format($total, 2),
                $incomes->where('payment_method', $method)->count(),
            ];
        }

        $rows[] = ['', '', ''];

        // Egresos por categoría
        $rows[] = ['EGRESOS POR CATEGORÍA', '', ''];
        $rows[] = ['Categoría', 'Monto', 'Cantidad'];

        foreach ($byCat as $cat => $total) {
            $rows[] = [
                $catLabels[$cat] ?? ucfirst($cat),
                '$' . number_format($total, 2),
                $expenses->where('category', $cat)->count(),
            ];
        }

        $rows[] = ['', '', ''];
        $rows[] = ['', '', ''];
        $rows[] = ['_________________________', '_________________________', ''];
        $rows[] = ['Firma Responsable', 'Fecha: ' . now()->format('d/m/Y'), ''];

        return $rows;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet   = $event->sheet->getDelegate();
                $dark    = '1A1A2E';
                $red     = 'C8395A';

                // Título
                foreach ([1, 2, 3] as $r) {
                    $sheet->mergeCells("A{$r}:C{$r}");
                }
                $sheet->getStyle('A1:C3')->applyFromArray([
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $dark]],
                    'font' => ['color' => ['rgb' => 'FFFFFF'], 'bold' => true],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);
                $sheet->getStyle('A1')->getFont()->setSize(13);
                $sheet->getRowDimension(1)->setRowHeight(28);

                // Sección títulos
                foreach ([5, 11] as $tr) {
                    $sheet->mergeCells("A{$tr}:C{$tr}");
                    $sheet->getStyle("A{$tr}:C{$tr}")->applyFromArray([
                        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $red]],
                        'font' => ['color' => ['rgb' => 'FFFFFF'], 'bold' => true, 'size' => 11],
                    ]);
                    $sheet->getRowDimension($tr)->setRowHeight(22);
                }

                // Headers
                foreach ([6, 12] as $hr) {
                    $sheet->getStyle("A{$hr}:C{$hr}")->applyFromArray([
                        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E5E7EB']],
                        'font' => ['bold' => true, 'size' => 10],
                        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                    ]);
                }

                // Fila balance (fila 9)
                $sheet->getStyle('A9:C9')->applyFromArray([
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'D1FAE5']],
                    'font' => ['bold' => true, 'size' => 11, 'color' => ['rgb' => '065F46']],
                ]);

                // Sección egresos por categoría — calcular fila
                $lastRow = $sheet->getHighestRow();
                $row = 14 + iterator_count((function() use ($event) {
                    yield from [];
                })());

                // Bordes generales
                $last = $sheet->getHighestRow();
                $sheet->getStyle("A5:C{$last}")->applyFromArray([
                    'borders' => [
                        'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'E5E7EB']],
                    ],
                ]);

                // Alineación montos columna B
                $sheet->getStyle("B6:B{$last}")->getAlignment()
                      ->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle("C6:C{$last}")->getAlignment()
                      ->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Altura filas
                for ($r = 4; $r <= $last; $r++) {
                    $sheet->getRowDimension($r)->setRowHeight(17);
                }

                // Encontrar y colorear la 2da sección de título (egresos)
                foreach ($sheet->getRowIterator() as $row) {
                    $ri  = $row->getRowIndex();
                    $val = $sheet->getCell("A{$ri}")->getValue();
                    if ($val === 'EGRESOS POR CATEGORÍA') {
                        $sheet->mergeCells("A{$ri}:C{$ri}");
                        $sheet->getStyle("A{$ri}:C{$ri}")->applyFromArray([
                            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'DC2626']],
                            'font' => ['color' => ['rgb' => 'FFFFFF'], 'bold' => true, 'size' => 11],
                        ]);
                        $sheet->getRowDimension($ri)->setRowHeight(22);
                        // Header siguiente fila
                        $sheet->getStyle("A" . ($ri + 1) . ":C" . ($ri + 1))->applyFromArray([
                            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FEE2E2']],
                            'font' => ['bold' => true, 'size' => 10],
                            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                        ]);
                    }
                }
            },
        ];
    }
}