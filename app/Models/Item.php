<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'tipo',
        'estado_disponibilidad',
    ];

    // 1 item - M prestamos
    public function prestamos(): HasMany
    {
        return $this->hasMany(Prestamo::class);
    }

    // 1 item - 1 tablet
    public function tablet(): HasOne
    {
        return $this->hasOne(Tablet::class);
    }

    // 1 item - 1 tesis
    public function tesis(): HasOne
    {
        return $this->hasOne(Tesis::class);
    }
}
