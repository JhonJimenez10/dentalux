<?php

namespace App\Exports\Sheets;

use App\Models\Payment;
use App\Models\Expense;
use App\Models\Patient;
use App\Models\CashClosing;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use Maatwebsite\Excel\Events\AfterSheet;

class ResumenSheet implements FromArray, WithTitle, WithStyles, WithColumnWidths, WithEvents
{
    public function __construct(
        private readonly Carbon $date
    ) {}

    public function title(): string { return 'Resumen'; }

    public function columnWidths(): array
    {
        return ['A' => 38, 'B' => 22, 'C' => 22];
    }

    public function array(): array
    {
        $payments  = Payment::whereDate('payment_date', $this->date)->get();
        $expenses  = Expense::whereDate('expense_date', $this->date)->get();
        $patients  = Patient::whereDate('created_at', $this->date)->get();
        $closing   = CashClosing::whereDate('closing_date', $this->date)->first();

        $totalInc  = $payments->sum('amount');
        $totalExp  = $expenses->sum('amount');
        $balance   = $totalInc - $totalExp;

        $incCash     = $payments->where('payment_method','efectivo')->sum('amount');
        $incTransfer = $payments->where('payment_method','transferencia')->sum('amount');
        $incCard     = $payments->whereIn('payment_method',['tarjeta_credito','tarjeta_debito'])->sum('amount');
        $incOther    = $payments->where('payment_method','cheque')->sum('amount');

        return [
            // Título
            ['REPORTE DIARIO — DENTALUX ODONTOLOGÍA FAMILIAR', '', ''],
            [$this->date->isoFormat('dddd, D [de] MMMM [de] YYYY'), '', ''],
            ['Generado: '.now()->format('d/m/Y H:i'), '', ''],
            ['', '', ''],

            // Resumen financiero
            ['RESUMEN FINANCIERO', '', ''],
            ['Concepto', 'Monto', 'Detalle'],
            ['Total Ingresos del Día', '$'.number_format($totalInc,2), $payments->count().' pagos'],
            ['   Efectivo', '$'.number_format($incCash,2), ''],
            ['   Transferencia', '$'.number_format($incTransfer,2), ''],
            ['   Tarjeta', '$'.number_format($incCard,2), ''],
            ['   Cheque / Otros', '$'.number_format($incOther,2), ''],
            ['Total Egresos del Día', '$'.number_format($totalExp,2), $expenses->count().' gastos'],
            ['BALANCE DEL DÍA', '$'.number_format($balance,2), $balance >= 0 ? 'SUPERÁVIT' : 'DÉFICIT'],
            ['', '', ''],

            // Arqueo de caja
            ['ARQUEO DE CAJA', '', ''],
            ['Concepto', 'Monto', ''],
            ['Efectivo esperado en caja', $closing ? '$'.number_format($closing->expected_cash,2) : 'Sin cierre', ''],
            ['Efectivo real contado', $closing ? '$'.number_format($closing->actual_cash,2) : 'Sin cierre', ''],
            ['Diferencia', $closing ? '$'.number_format($closing->difference,2) : '—', $closing ? ($closing->difference == 0 ? 'CUADRA ✓' : ($closing->difference > 0 ? 'SOBRANTE' : 'FALTANTE')) : ''],
            ['Estado del cierre', $closing ? strtoupper($closing->status_label) : 'NO REGISTRADO', ''],
            ['', '', ''],

            // Actividad del día
            ['ACTIVIDAD DEL DÍA', '', ''],
            ['Concepto', 'Cantidad', ''],
            ['Pacientes nuevos registrados', $patients->count(), ''],
            ['Pagos recibidos', $payments->count(), ''],
            ['Egresos registrados', $expenses->count(), ''],
            ['', '', ''],

            // Firma
            ['', '', ''],
            ['_________________________', '_________________________', ''],
            ['Firma Responsable', 'Fecha: '.$this->date->format('d/m/Y'), ''],
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $dark  = '1A1A2E';
                $red   = 'C8395A';
                $green = '059669';
                $light = 'F8F7F5';

                // ── Título principal ──
                $sheet->mergeCells('A1:C1');
                $sheet->mergeCells('A2:C2');
                $sheet->mergeCells('A3:C3');

                $sheet->getStyle('A1:C3')->applyFromArray([
                    'fill' => ['fillType'=>Fill::FILL_SOLID,
                               'startColor'=>['rgb'=>$dark]],
                    'font' => ['color'=>['rgb'=>'FFFFFF'],'bold'=>true],
                    'alignment' => ['horizontal'=>Alignment::HORIZONTAL_CENTER,
                                    'vertical'  =>Alignment::VERTICAL_CENTER],
                ]);
                $sheet->getStyle('A1')->getFont()->setSize(14);
                $sheet->getStyle('A2')->getFont()->setSize(11)->setBold(false);
                $sheet->getStyle('A3')->getFont()->setSize(10)->setBold(false);
                $sheet->getRowDimension(1)->setRowHeight(32);
                $sheet->getRowDimension(2)->setRowHeight(20);
                $sheet->getRowDimension(3)->setRowHeight(18);

                // ── Sección RESUMEN FINANCIERO ── fila 5
                foreach([5, 15, 21] as $titleRow){
                    $sheet->mergeCells("A{$titleRow}:C{$titleRow}");
                    $sheet->getStyle("A{$titleRow}:C{$titleRow}")->applyFromArray([
                        'fill' => ['fillType'=>Fill::FILL_SOLID,
                                   'startColor'=>['rgb'=>$red]],
                        'font' => ['color'=>['rgb'=>'FFFFFF'],'bold'=>true,'size'=>11],
                        'alignment' => ['horizontal'=>Alignment::HORIZONTAL_LEFT],
                    ]);
                    $sheet->getRowDimension($titleRow)->setRowHeight(22);
                }

                // ── Header de columnas ── filas 6, 16, 22
                foreach([6, 16, 22] as $hRow){
                    $sheet->getStyle("A{$hRow}:C{$hRow}")->applyFromArray([
                        'fill' => ['fillType'=>Fill::FILL_SOLID,
                                   'startColor'=>['rgb'=>'E5E7EB']],
                        'font' => ['bold'=>true,'size'=>10],
                        'alignment' => ['horizontal'=>Alignment::HORIZONTAL_CENTER],
                    ]);
                }

                // ── Fila de Balance (fila 13) ──
                $sheet->getStyle('A13:C13')->applyFromArray([
                    'fill' => ['fillType'=>Fill::FILL_SOLID,
                               'startColor'=>['rgb'=>'D1FAE5']],
                    'font' => ['bold'=>true,'color'=>['rgb'=>'065F46'],'size'=>11],
                ]);

                // ── Filas de datos alternas ──
                $dataRanges = [[7,12],[17,20],[23,25]];
                foreach($dataRanges as [$from, $to]){
                    for($r = $from; $r <= $to; $r++){
                        $bg = ($r % 2 === 0) ? 'FFFFFF' : 'F9FAFB';
                        $sheet->getStyle("A{$r}:C{$r}")->applyFromArray([
                            'fill' => ['fillType'=>Fill::FILL_SOLID,
                                       'startColor'=>['rgb'=>$bg]],
                            'font' => ['size'=>10],
                        ]);
                    }
                }

                // ── Sub-items indentados (8-11) ──
                $sheet->getStyle('A8:A11')->getFont()->setColor(
                    new Color('6B7280')
                );

                // ── Bordes generales ──
                foreach(['A5:C13','A15:C20','A21:C25'] as $range){
                    $sheet->getStyle($range)->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                                'color' => ['rgb' => 'E5E7EB'],
                            ],
                            'outline' => [
                                'borderStyle' => Border::BORDER_MEDIUM,
                                'color' => ['rgb' => 'D1D5DB'],
                            ],
                        ],
                    ]);
                }

                // ── Alineación montos ──
                $sheet->getStyle('B6:B25')->getAlignment()
                      ->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle('C6:C25')->getAlignment()
                      ->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // ── Altura de filas ──
                for($r = 6; $r <= 28; $r++){
                    $sheet->getRowDimension($r)->setRowHeight(18);
                }

                // ── Fila de firma ──
                $sheet->getStyle('A28:C28')->getFont()->setColor(new Color('9CA3AF'));
                $sheet->getStyle('A29:C29')->applyFromArray([
                    'font' => ['color'=>['rgb'=>'6B7280'],'size'=>9],
                    'alignment' => ['horizontal'=>Alignment::HORIZONTAL_CENTER],
                ]);
            },
        ];
    }
}