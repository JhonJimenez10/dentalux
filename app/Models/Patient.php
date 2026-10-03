<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class Patient extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'history_number',           // ← nuevo
        'first_name',
        'last_name',
        'cedula',
        'birth_date',
        'age',
        'gender',
        'phone',
        'phone_whatsapp',
        'email',
        'address',
        'city',
        'representative_name',
        'representative_cedula',
        'representative_relationship',
        'representative_phone',
        'reason_for_consultation',
        'allergies',
        'pathologies',
        'observations',
        'whatsapp_notifications',
    ];

    protected $casts = [
        'birth_date'             => 'date',
        'whatsapp_notifications' => 'boolean',
    ];

    // ── Auto-asignar history_number al crear ──────────────────
    protected static function booted(): void
    {
        static::creating(function (Patient $patient) {
            if (empty($patient->history_number)) {
                // Obtener el mayor número actual (incluyendo soft-deleted)
                $max = DB::table('patients')->max('history_number') ?? 0;
                $patient->history_number = $max + 1;
            }
        });
    }

    // ─── Accessors ───────────────────────────────────────────

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    // Historia clínica formateada: HC-000001
    public function getHistoryCodeAttribute(): string
    {
        return 'HC-' . str_pad($this->history_number ?? 0, 6, '0', STR_PAD_LEFT);
    }

    public function getAgeCalculatedAttribute(): ?int
    {
        if ($this->birth_date) {
            return $this->birth_date->age;
        }
        return $this->age;
    }

    public function getInitialsAttribute(): string
    {
        return strtoupper(
            substr($this->first_name, 0, 1) .
            substr($this->last_name,  0, 1)
        );
    }

    // ─── Scopes ──────────────────────────────────────────────

    public function scopeSearch($query, string $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('first_name',      'like', "%{$term}%")
              ->orWhere('last_name',      'like', "%{$term}%")
              ->orWhere('cedula',         'like', "%{$term}%")
              ->orWhere('phone',          'like', "%{$term}%")
              ->orWhere('history_number', 'like', "%{$term}%"); // ← buscar por N° HC
        });
    }

    public function scopeActive($query)
    {
        return $query->whereNull('deleted_at');
    }

    // ─── Relations ───────────────────────────────────────────

    public function budgets(): HasMany
    {
        return $this->hasMany(Budget::class)->latest();
    }

    public function paymentControls(): HasMany
    {
        return $this->hasMany(PaymentControl::class)->latest();
    }

    public function latestPaymentControl()
    {
        return $this->hasOne(PaymentControl::class)->latest();
    }
}