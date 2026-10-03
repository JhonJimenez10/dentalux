<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Budget extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'patient_id',
        'user_id',
        'budget_date',
        'subtotal',
        'discount',
        'total',
        'initial_payment',
        'monthly_payment',
        'status',
        'notes',
        'odontogram',
        'patient_signature',
    ];

    protected $casts = [
        'budget_date' => 'date',
        'odontogram'  => 'array',
        'subtotal'    => 'float',
        'discount'    => 'float',
        'total'       => 'float',
        'initial_payment'  => 'float',
        'monthly_payment'  => 'float',
    ];

    // ─── Relaciones ───────────────────────────────────────────

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function dentist(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(BudgetItem::class);
    }

    // ─── Accessors ────────────────────────────────────────────

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending'   => 'Pendiente',
            'active'    => 'Activo',
            'completed' => 'Completado',
            'cancelled' => 'Cancelado',
            default     => $this->status,
        };
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'pending'   => 'badge-warning',
            'active'    => 'badge-info',
            'completed' => 'badge-success',
            'cancelled' => 'badge-danger',
            default     => 'badge-gray',
        };
    }

    // ─── Métodos ──────────────────────────────────────────────

    public function recalculateTotals(): void
    {
        $subtotal = $this->items()->sum('total');

        $this->update([
            'subtotal' => $subtotal,
            'total'    => max(0, $subtotal - $this->discount),
        ]);
    }
}