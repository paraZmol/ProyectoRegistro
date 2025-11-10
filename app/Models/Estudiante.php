<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Estudiante extends Model
{
    use HasFactory;

    protected $fillable = [
        'carnet',
        'apellidos',
        'nombres',
        'escuela_id',
    ];

    // 1 estudiante - 1 escuela
    public function escuela(): BelongsTo
    {
        return $this->belongsTo(Escuela::class);
    }

    // 1 estudiante - M prestamos
    public function prestamos(): HasMany
    {
        return $this->hasMany(Prestamo::class);
    }
}