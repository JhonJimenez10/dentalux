<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    protected $fillable = [
        'bank_name', 'account_type', 'account_number',
        'owner_name', 'owner_id', 'phone',
        'active', 'order',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('active', true)->orderBy('order');
    }

    public function getTypeIconAttribute(): string
    {
        return match(strtolower($this->account_type)){
            'cuenta de ahorros'  => '💰',
            'cuenta corriente'   => '🏦',
            default              => '🏧',
        };
    }
}