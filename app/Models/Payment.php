<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'payment_control_id',
        'user_id',
        'payment_date',
        'amount',
        'payment_method',
        'reference',
        'notes',
        'patient_signature',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'amount'       => 'float',
    ];

    // ─── Relaciones ───────────────────────────────────────────

    public function paymentControl(): BelongsTo
    {
        return $this->belongsTo(PaymentControl::class);
    }

    public function registeredBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // ─── Accessors ────────────────────────────────────────────

    public function getMethodLabelAttribute(): string
    {
        return match($this->payment_method) {
            'efectivo'        => 'Efectivo',
            'transferencia'   => 'Transferencia',
            'tarjeta_credito' => 'Tarjeta de Crédito',
            'tarjeta_debito'  => 'Tarjeta de Débito',
            'cheque'          => 'Cheque',
            default           => $this->payment_method,
        };
    }
}