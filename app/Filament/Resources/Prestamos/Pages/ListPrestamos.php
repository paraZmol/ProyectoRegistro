<?php

namespace App\Filament\Resources\Prestamos\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Models\Prestamo;
use Carbon\Carbon;
use Filament\Notifications\Notification;
use App\Filament\Resources\Prestamos\PrestamoResource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;

class ListPrestamos extends ListRecords
{
    protected static string $resource = PrestamoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getTableQuery(): Builder
    {
        $query = parent::getTableQuery();

        try {
            // /todoo en una saola llamada
            $query->with(['estudiante', 'item.tablet', 'item.tesis']);
            /*$query->with(['estudiante']); // Sobrescribe
            $query->with(['item']);       // Sobrescribe
            $query->with(['item.tablet', 'item.tesis']);*/

        } catch (\Exception $e) {
            Log::info('¡¡¡ERROR EN EAGER LOADING!!! ' . $e->getMessage());
        }
        return $query;
    }

    // botno de registrar devolucion
    /*public function handleDevolverAction(string $recordId): void
    {
        $prestamo = Prestamo::find($recordId);

        $prestamo->momento_entrega = Carbon::now();
        $prestamo->save();

        $item = $prestamo->item;
        $item->estado_disponibilidad = 'Disponible';
        $item->save();

        Notification::make()
            ->title('Devolución Registrada')
            ->success()
            ->send();

        // recargar la tabla
        $this->js('window.location.reload()');
    }*/

    // imprimir boleta
    /*public function handleImprimirAction(string $recordId): void
    {
        Notification::make()
            ->title('Función no implementada')
            ->body("Lógica para imprimir el préstamo ID: $recordId")
            ->info()
            ->send();
    }*/
}
