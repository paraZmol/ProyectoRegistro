<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Prestamo extends Model
{
    use HasFactory;

    protected $fillable = [
        'momento_prestamo',
        'momento_entrega',
        'estudiante_id',
        'item_id',
        // nueva opcion para el desplegable
        'actividad_tablet',
        'actividad_tablet_otro',
    ];

    protected $casts = [
        'momento_prestamo' => 'datetime',
        'momento_entrega' => 'datetime',
    ];

    // 1 prestamo - 1 estudiante
    public function estudiante(): BelongsTo
    {
        return $this->belongsTo(Estudiante::class);
    }

    // 1 prestamo - 1 item
    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }
}
