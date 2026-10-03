<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PaymentControl extends Model
{
    protected $fillable = [
        'patient_id',
        'budget_id',
        'treatment_amount',
    ];

    protected $casts = [
        'treatment_amount' => 'float',
    ];

    // ─── Relaciones ───────────────────────────────────────────

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function budget(): BelongsTo
    {
        return $this->belongsTo(Budget::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class)->orderBy('payment_date');
    }

    // ─── Accessors ────────────────────────────────────────────

    public function getTotalPaidAttribute(): float
    {
        return (float) $this->payments->sum('amount');
    }

    public function getBalanceAttribute(): float
    {
        return max(0, $this->treatment_amount - $this->total_paid);
    }

    public function getProgressPercentAttribute(): int
    {
        if ($this->treatment_amount <= 0) return 0;

        return (int) min(100, ($this->total_paid / $this->treatment_amount) * 100);
    }

    public function getIsFullyPaidAttribute(): bool
    {
        return $this->balance <= 0;
    }
}