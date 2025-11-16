<?php

namespace App\Filament\Resources\Prestamos\Pages;

use App\Filament\Resources\Prestamos\PrestamoResource;
use Filament\Resources\Pages\CreateRecord;
use App\Models\Item;
use Carbon\Carbon;

class CreatePrestamo extends CreateRecord
{
    protected static string $resource = PrestamoResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (empty($data['momento_prestamo'])) {
            $data['momento_prestamo'] = Carbon::now();
        }
        return $data;
    }

    // --- 6. LÓGICA DESPUÉS DE GUARDAR ---
    protected function afterCreate(): void
    {
        // Obtener el registro que acabamos de crear
        $prestamo = $this->getRecord();

        // Actualizar el Item relacionado
        $item = $prestamo->item;
        $item->estado_disponibilidad = 'Prestado';
        $item->save();
    }
}