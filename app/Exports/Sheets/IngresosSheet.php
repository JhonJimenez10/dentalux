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

class IngresosSheet implements FromArray, WithTitle, WithColumnWidths, WithEvents
{
    public function __construct(
        private readonly Carbon $date
    ) {}

    public function title(): string { return 'Ingresos'; }

    public function columnWidths(): array
    {
        return ['A'=>10,'B'=>32,'C'=>20,'D'=>18,'E'=>18,'F'=>20,'G'=>30];
    }

    public function array(): array
    {
        $payments = Payment::whereDate('payment_date', $this->date)
            ->with('paymentControl.patient', 'registeredBy')
            ->orderBy('payment_date')
            ->get();

        $rows = [
            ['INGRESOS DEL DÍA — '.$this->date->format('d/m/Y'), '','','','','',''],
            ['#', 'Paciente', 'Cédula', 'Método de Pago', 'Monto', 'Registrado por', 'Referencia / Nota'],
        ];

        $total = 0;
        foreach($payments as $i => $pay){
            $patient = $pay->paymentControl?->patient;
            $rows[]  = [
                $i + 1,
                $patient?->full_name ?? '—',
                $patient?->cedula    ?? '—',
                $pay->method_label,
                $pay->amount,
                $pay->registeredBy?->name ?? '—',
                $pay->notes ?? '',
            ];
            $total += $pay->amount;
        }

        $rows[] = ['', '', '', 'TOTAL', $total, '', ''];

        if($payments->isEmpty()){
            $rows[] = ['', 'Sin ingresos registrados en este día', '','','','',''];
        }

        return $rows;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $lastRow = $sheet->getHighestRow();

                // Título
                $sheet->mergeCells('A1:G1');
                $sheet->getStyle('A1:G1')->applyFromArray([
                    'fill' => ['fillType'=>Fill::FILL_SOLID,'startColor'=>['rgb'=>'059669']],
                    'font' => ['color'=>['rgb'=>'FFFFFF'],'bold'=>true,'size'=>13],
                    'alignment' => ['horizontal'=>Alignment::HORIZONTAL_CENTER,
                                    'vertical'  =>Alignment::VERTICAL_CENTER],
                ]);
                $sheet->getRowDimension(1)->setRowHeight(30);

                // Header
                $sheet->getStyle('A2:G2')->applyFromArray([
                    'fill' => ['fillType'=>Fill::FILL_SOLID,'startColor'=>['rgb'=>'1A1A2E']],
                    'font' => ['color'=>['rgb'=>'FFFFFF'],'bold'=>true,'size'=>10],
                    'alignment' => ['horizontal'=>Alignment::HORIZONTAL_CENTER],
                ]);
                $sheet->getRowDimension(2)->setRowHeight(20);

                // Datos
                for($r = 3; $r <= $lastRow - 1; $r++){
                    $bg = ($r % 2 === 0) ? 'FFFFFF' : 'F0FDF4';
                    $sheet->getStyle("A{$r}:G{$r}")->applyFromArray([
                        'fill' => ['fillType'=>Fill::FILL_SOLID,'startColor'=>['rgb'=>$bg]],
                        'font' => ['size'=>10],
                    ]);
                    // Centrar # y método
                    $sheet->getStyle("A{$r}")->getAlignment()
                          ->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    // Monto en negrita y verde
                    $sheet->getStyle("E{$r}")->applyFromArray([
                        'font' => ['bold'=>true,'color'=>['rgb'=>'059669']],
                        'alignment' => ['horizontal'=>Alignment::HORIZONTAL_RIGHT],
                        'numberFormat' => ['formatCode'=>'"$"#,##0.00'],
                    ]);
                }

                // Fila total
                $sheet->getStyle("A{$lastRow}:G{$lastRow}")->applyFromArray([
                    'fill' => ['fillType'=>Fill::FILL_SOLID,'startColor'=>['rgb'=>'D1FAE5']],
                    'font' => ['bold'=>true,'size'=>11,'color'=>['rgb'=>'065F46']],
                ]);
                $sheet->getStyle("D{$lastRow}")->getAlignment()
                      ->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle("E{$lastRow}")->applyFromArray([
                    'font' => ['bold'=>true,'size'=>12,'color'=>['rgb'=>'059669']],
                    'alignment' => ['horizontal'=>Alignment::HORIZONTAL_RIGHT],
                    'numberFormat' => ['formatCode'=>'"$"#,##0.00'],
                ]);
                $sheet->getRowDimension($lastRow)->setRowHeight(22);

                // Bordes
                $sheet->getStyle("A1:G{$lastRow}")->applyFromArray([
                    'borders' => [
                        'allBorders' => ['borderStyle'=>Border::BORDER_THIN,
                                         'color'=>['rgb'=>'E5E7EB']],
                        'outline'    => ['borderStyle'=>Border::BORDER_MEDIUM,
                                         'color'=>['rgb'=>'D1D5DB']],
                    ],
                ]);
            },
        ];
    }
}