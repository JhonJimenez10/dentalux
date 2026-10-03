<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BudgetItem extends Model
{
    protected $fillable = [
        'budget_id',
        'description',
        'quantity',
        'unit_price',
        'total',
    ];

    protected $casts = [
        'unit_price' => 'float',
        'total'      => 'float',
    ];

    // ─── Relaciones ───────────────────────────────────────────

    public function budget(): BelongsTo
    {
        return $this->belongsTo(Budget::class);
    }

    // ─── Observer: recalcula totales al guardar o eliminar ────

    protected static function booted(): void
    {
        static::saved(function (BudgetItem $item) {
            $item->budget->recalculateTotals();
        });

        static::deleted(function (BudgetItem $item) {
            $item->budget->recalculateTotals();
        });
    }
}