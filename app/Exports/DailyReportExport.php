<?php

namespace App\Exports;

use App\Models\Payment;
use App\Models\Expense;
use App\Models\Patient;
use App\Models\CashClosing;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class DailyReportExport implements WithMultipleSheets
{
    public function __construct(
        public readonly Carbon $date
    ) {}

    public function sheets(): array
    {
        return [
            new Sheets\ResumenSheet($this->date),
            new Sheets\IngresosSheet($this->date),
            new Sheets\EgresosSheet($this->date),
            new Sheets\PacientesSheet($this->date),
        ];
    }
}