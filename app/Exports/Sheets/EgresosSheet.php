<?php

namespace App\Exports\Sheets;

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

class EgresosSheet implements FromArray, WithTitle, WithColumnWidths, WithEvents
{
    public function __construct(
        private readonly Carbon $date
    ) {}

    public function title(): string { return 'Egresos'; }

    public function columnWidths(): array
    {
        return ['A'=>10,'B'=>28,'C'=>22,'D'=>18,'E'=>18,'F'=>20,'G'=>28];
    }

    public function array(): array
    {
        $expenses = Expense::whereDate('expense_date', $this->date)
            ->with('registeredBy')
            ->orderBy('expense_date')
            ->get();

        $rows = [
            ['EGRESOS DEL DÍA — '.$this->date->format('d/m/Y'),'','','','','',''],
            ['#','Descripción','Categoría','Método Pago','Monto','Registrado por','Referencia'],
        ];

        $total = 0;
        foreach($expenses as $i => $exp){
            $rows[] = [
                $i + 1,
                $exp->description,
                $exp->category_label,
                $exp->method_label,
                $exp->amount,
                $exp->registeredBy?->name ?? '—',
                $exp->reference ?? '',
            ];
            $total += $exp->amount;
        }

        $rows[] = ['','','','TOTAL', $total,'',''];

        if($expenses->isEmpty()){
            $rows[] = ['','Sin egresos registrados en este día','','','','',''];
        }

        return $rows;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet   = $event->sheet->getDelegate();
                $lastRow = $sheet->getHighestRow();

                // Título
                $sheet->mergeCells('A1:G1');
                $sheet->getStyle('A1:G1')->applyFromArray([
                    'fill' => ['fillType'=>Fill::FILL_SOLID,'startColor'=>['rgb'=>'DC2626']],
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
                    $bg = ($r % 2 === 0) ? 'FFFFFF' : 'FFF5F5';
                    $sheet->getStyle("A{$r}:G{$r}")->applyFromArray([
                        'fill' => ['fillType'=>Fill::FILL_SOLID,'startColor'=>['rgb'=>$bg]],
                        'font' => ['size'=>10],
                    ]);
                    $sheet->getStyle("A{$r}")->getAlignment()
                          ->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle("E{$r}")->applyFromArray([
                        'font' => ['bold'=>true,'color'=>['rgb'=>'DC2626']],
                        'alignment' => ['horizontal'=>Alignment::HORIZONTAL_RIGHT],
                        'numberFormat' => ['formatCode'=>'"$"#,##0.00'],
                    ]);
                }

                // Total
                $sheet->getStyle("A{$lastRow}:G{$lastRow}")->applyFromArray([
                    'fill' => ['fillType'=>Fill::FILL_SOLID,'startColor'=>['rgb'=>'FEE2E2']],
                    'font' => ['bold'=>true,'size'=>11,'color'=>['rgb'=>'991B1B']],
                ]);
                $sheet->getStyle("D{$lastRow}")->getAlignment()
                      ->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle("E{$lastRow}")->applyFromArray([
                    'font'         => ['bold'=>true,'size'=>12,'color'=>['rgb'=>'DC2626']],
                    'alignment'    => ['horizontal'=>Alignment::HORIZONTAL_RIGHT],
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