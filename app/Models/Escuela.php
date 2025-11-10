<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Escuela extends Model
{
    use HasFactory;

    protected $fillable = [
        'escuela',
        'sigla',
        'facultad_id',
    ];

    // 1 escuela - 1 facultad
    public function facultad(): BelongsTo
    {
        return $this->belongsTo(Facultad::class);
    }

    // 1 escuela - M estudiantes
    public function estudiantes(): HasMany
    {
        return $this->hasMany(Estudiante::class);
    }
}