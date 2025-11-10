<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tablet extends Model
{
    use HasFactory;

    protected $fillable = [
        'codigo',
        'marca',
        'modelo',
        'color',
        'estado_fisico',
        'item_id',
    ];

    // 1 tablet - 1 item
    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }
}