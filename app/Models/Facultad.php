<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Facultad extends Model
{
    use HasFactory;

    protected $fillable = [
        'facultad',
        'sigla',
    ];

    // 1 facultad - M escuelas
    public function escuelas(): HasMany
    {
        return $this->hasMany(Escuela::class);
    }
}
