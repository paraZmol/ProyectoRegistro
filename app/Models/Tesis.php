<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tesis extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo',
        'autor',
        'item_id',
    ];

    // 1 tesis - 1 item
    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }
}