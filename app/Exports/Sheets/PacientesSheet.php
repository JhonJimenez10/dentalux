<?php

namespace App\Exports\Sheets;

use App\Models\Patient;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Maatwebsite\Excel\Events\AfterSheet;

class PacientesSheet implements FromArray, WithTitle, WithColumnWidths, WithEvents
{
    public function __construct(
        private readonly Carbon $date
    ) {}

    public function title(): string { return 'Pacientes Nuevos'; }

    public function columnWidths(): array
    {
        return ['A'=>10,'B'=>28,'C'=>18,'D'=>16,'E'=>14,'F'=>20,'G'=>22];
    }

    public function array(): array
    {
        $patients = Patient::whereDate('created_at', $this->date)
            ->orderBy('created_at')
            ->get();

        $rows = [
            ['PACIENTES REGISTRADOS — '.$this->date->format('d/m/Y'),'','','','','',''],
            ['#','Nombre Completo','Cédula','Teléfono','Edad','Ciudad','Motivo de Consulta'],
        ];

        foreach($patients as $i => $p){
            $rows[] = [
                $i + 1,
                $p->full_name,
                $p->cedula    ?? '—',
                $p->phone     ?? '—',
                $p->age_calculated ? $p->age_calculated.' años' : '—',
                $p->city      ?? '—',
                $p->reason_for_consultation ?? '—',
            ];
        }

        if($patients->isEmpty()){
            $rows[] = ['','Sin pacientes nuevos registrados hoy','','','','',''];
        }

        $rows[] = ['','Total: '.$patients->count().' paciente(s)','','','','',''];

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
                    'fill' => ['fillType'=>Fill::FILL_SOLID,'startColor'=>['rgb'=>'0284C7']],
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
                    $bg = ($r % 2 === 0) ? 'FFFFFF' : 'EFF6FF';
                    $sheet->getStyle("A{$r}:G{$r}")->applyFromArray([
                        'fill' => ['fillType'=>Fill::FILL_SOLID,'startColor'=>['rgb'=>$bg]],
                        'font' => ['size'=>10],
                    ]);
                    $sheet->getStyle("A{$r}")->getAlignment()
                          ->setHorizontal(Alignment::HORIZONTAL_CENTER);
                }

                // Total
                $sheet->getStyle("A{$lastRow}:G{$lastRow}")->applyFromArray([
                    'fill' => ['fillType'=>Fill::FILL_SOLID,'startColor'=>['rgb'=>'DBEAFE']],
                    'font' => ['bold'=>true,'size'=>10,'color'=>['rgb'=>'1E40AF']],
                ]);

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