<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Expense extends Model
{
    protected $fillable = [
        'user_id', 'category', 'description',
        'amount', 'expense_date', 'payment_method',
        'reference', 'notes', 'receipt',
    ];

    protected $casts = [
        'expense_date' => 'date',
        'amount'       => 'decimal:2',
    ];

    public function registeredBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getCategoryLabelAttribute(): string
    {
        return match($this->category) {
            'materiales'   => 'Materiales',
            'equipos'      => 'Equipos',
            'servicios'    => 'Servicios',
            'arriendo'     => 'Arriendo',
            'sueldos'      => 'Sueldos',
            'publicidad'   => 'Publicidad',
            'laboratorio'  => 'Laboratorio dental',
            'mantenimiento'=> 'Mantenimiento',
            'otros'        => 'Otros',
            default        => ucfirst($this->category),
        };
    }

    public function getMethodLabelAttribute(): string
    {
        return match($this->payment_method) {
            'efectivo'        => 'Efectivo',
            'transferencia'   => 'Transferencia',
            'tarjeta_credito' => 'Tarjeta Crédito',
            'tarjeta_debito'  => 'Tarjeta Débito',
            'cheque'          => 'Cheque',
            default           => ucfirst($this->payment_method),
        };
    }
}