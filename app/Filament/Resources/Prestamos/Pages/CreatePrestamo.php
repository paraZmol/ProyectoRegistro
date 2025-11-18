<?php

namespace App\Filament\Resources\Prestamos\Pages;

use App\Filament\Resources\Prestamos\PrestamoResource;
use Filament\Resources\Pages\CreateRecord;
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

    // logica despues de guardar
    protected function afterCreate(): void
    {
        // obtener el registro que se creo
        $prestamo = $this->getRecord();

        // actualizr el item relacionado
        $item = $prestamo->item;
        $item->estado_disponibilidad = 'Prestado';
        $item->save();
    }
}
