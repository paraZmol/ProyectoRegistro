<?php

namespace App\Filament\Resources\Tablets\Pages;

use App\Filament\Resources\Tablets\TabletResource;
use Filament\Resources\Pages\CreateRecord;
use App\Models\Item;

class CreateTablet extends CreateRecord
{
    protected static string $resource = TabletResource::class;

    /**
     * Mutar los datos del formulario antes de que se cree el registro.
     *
     * @param  array  $data
     * @return array
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // 2. Crear el 'Item' padre primero
        $item = Item::create([
            'tipo' => 'Tablet',
            'estado_disponibilidad' => 'Disponible', // Estado por defecto al crear
        ]);

        // 3. Añadir el ID del item_id a los datos del formulario
        // para que la Tablet sepa a qué padre pertenece.
        $data['item_id'] = $item->id;

        return $data; // 4. Devolver los datos listos para crear la Tablet
    }
}
