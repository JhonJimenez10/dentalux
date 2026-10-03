<?php

namespace App\Exports;

use App\Models\Payment;
use App\Models\Expense;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class FinanceExport implements WithMultipleSheets
{
    public function __construct(
        public readonly int $month,
        public readonly int $year,
    ) {}

    public function sheets(): array
    {
        return [
            new Sheets\FinanceResumenSheet($this->month, $this->year),
            new Sheets\FinanceIngresosSheet($this->month, $this->year),
            new Sheets\FinanceEgresosSheet($this->month, $this->year),
        ];
    }
}