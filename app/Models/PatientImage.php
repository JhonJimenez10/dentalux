<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class PatientImage extends Model
{
    protected $fillable = [
        'patient_id', 'user_id', 'title',
        'category', 'file_path', 'file_name',
        'file_type', 'file_size', 'notes', 'taken_at',
    ];

    protected $casts = [
        'taken_at'  => 'date',
        'file_size' => 'integer',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function uploadedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getUrlAttribute(): string
    {
        return asset('storage/'.$this->file_path);
    }

    public function getFileSizeHumanAttribute(): string
    {
        $bytes = $this->file_size;
        if($bytes >= 1048576) return round($bytes / 1048576, 1) . ' MB';
        if($bytes >= 1024)    return round($bytes / 1024, 1)    . ' KB';
        return $bytes . ' B';
    }

    public function getCategoryLabelAttribute(): string
    {
        return match($this->category) {
            'radiografia_periapical'  => 'Radiografía Periapical',
            'radiografia_panoramica'  => 'Radiografía Panorámica',
            'radiografia_bitewing'    => 'Radiografía Bitewing',
            'fotografia_frontal'      => 'Fotografía Frontal',
            'fotografia_lateral'      => 'Fotografía Lateral',
            'fotografia_intraoral'    => 'Fotografía Intraoral',
            'fotografia_extraoral'    => 'Fotografía Extraoral',
            'modelo_estudio'          => 'Modelo de Estudio',
            'otro'                    => 'Otro',
            default                   => ucfirst($this->category),
        };
    }

    public function getCategoryIconAttribute(): string
    {
        return match($this->category) {
            'radiografia_periapical',
            'radiografia_panoramica',
            'radiografia_bitewing'  => '🦷',
            'fotografia_frontal',
            'fotografia_lateral',
            'fotografia_intraoral',
            'fotografia_extraoral'  => '📷',
            'modelo_estudio'        => '🔬',
            default                 => '📎',
        };
    }

    public function getCategoryColorAttribute(): array
    {
        return match(true) {
            str_starts_with($this->category, 'radiografia') => ['#EFF6FF', '#1D4ED8'],
            str_starts_with($this->category, 'fotografia')  => ['#F0FDF4', '#059669'],
            default                                          => ['#F9FAFB', '#6B7280'],
        };
    }

    public function getIsImageAttribute(): bool
    {
        return in_array(strtolower(pathinfo($this->file_name, PATHINFO_EXTENSION)),
            ['jpg','jpeg','png','gif','webp']);
    }
}