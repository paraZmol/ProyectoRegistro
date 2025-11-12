<?php

namespace App\Filament\Resources\Tablets\Pages;

use App\Filament\Resources\Tablets\TabletResource;
use Filament\Resources\Pages\CreateRecord;
use App\Models\Item;

class CreateTablet extends CreateRecord
{
    protected static string $resource = TabletResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // para crear el e insertar datos a la tabla item
        $item = Item::create([
            'tipo' => 'Tablet',
            'estado_disponibilidad' => 'Disponible', // disponible por defecto
        ]);

        // luego de que se crea, se llama al ID para usarlo en la creacion de tablet
        $data['item_id'] = $item->id;

        return $data;
    }
}
