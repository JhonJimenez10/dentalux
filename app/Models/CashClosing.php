<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class CashClosing extends Model
{
    protected $fillable = [
        'closing_date', 'closed_by',
        'opening_balance',
        'income_cash', 'income_transfer', 'income_card', 'income_other',
        'total_income', 'total_expenses',
        'expected_cash', 'actual_cash', 'difference',
        'closing_balance',
        'status', 'notes', 'closed_at',
    ];

    protected $casts = [
        'closing_date' => 'date',
        'closed_at'    => 'datetime',
        'opening_balance'  => 'decimal:2',
        'income_cash'      => 'decimal:2',
        'income_transfer'  => 'decimal:2',
        'income_card'      => 'decimal:2',
        'income_other'     => 'decimal:2',
        'total_income'     => 'decimal:2',
        'total_expenses'   => 'decimal:2',
        'expected_cash'    => 'decimal:2',
        'actual_cash'      => 'decimal:2',
        'difference'       => 'decimal:2',
        'closing_balance'  => 'decimal:2',
    ];

    public function closedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'closed_by');
    }

    public function getStatusLabelAttribute(): string
    {
        return $this->status === 'closed' ? 'Cerrado' : 'Abierto';
    }

    public function getStatusBadgeAttribute(): string
    {
        return $this->status === 'closed' ? 'badge-success' : 'badge-warning';
    }

    public function getIsClosed(): bool
    {
        return $this->status === 'closed';
    }

    public function getDifferenceStatusAttribute(): string
    {
        if($this->difference == 0) return 'exact';
        return $this->difference > 0 ? 'surplus' : 'shortage';
    }
}