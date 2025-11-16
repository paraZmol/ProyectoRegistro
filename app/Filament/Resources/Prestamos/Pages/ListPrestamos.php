<?php

namespace App\Filament\Resources\Prestamos\Pages;

//use App\Filament\Resources\PrestamoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
// --- Imports para las Acciones ---
use App\Models\Prestamo;
use App\Models\Item;
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

    // --- INICIO DE SECCIÓN DE PRUEBA ---
    protected function getTableQuery(): Builder
    {
        // 1. Imprimir "Bandera 1" en el log
        //Log::info('BANDERA 1: getTableQuery() se ha llamado.');

        // 2. Ejecutar la consulta base (parent::)
        $query = parent::getTableQuery();

        // 3. Imprimir el SQL
        //Log::info('BANDERA 2: SQL Base (sin optimizar): ' . $query->toSql());

        // 4. Probar el Eager Loading (la parte que falla)
        try {
            // Intentamos cargar solo el estudiante
            $query->with(['estudiante']);
            //Log::info('BANDERA 3: Cargar "estudiante" FUNCIONÓ.');

            // Intentamos cargar el item
            $query->with(['item']);
            //Log::info('BANDERA 4: Cargar "item" FUNCIONÓ.');

            // Intentamos cargar las relaciones anidadas
            $query->with(['item.tablet', 'item.tesis']);
            //Log::info('BANDERA 5: Cargar "item.tablet" e "item.tesis" FUNCIONÓ.');

        } catch (\Exception $e) {
            // Si algo falla, lo veremos en el log
            Log::info('¡¡¡ERROR EN EAGER LOADING!!! ' . $e->getMessage());
        }

        //Log::info('BANDERA 6: Devolviendo consulta final.');
        return $query;
    }
    // --- FIN DE SECCIÓN DE PRUEBA ---

    // --- INICIO: Lógica para los Botones del Blade ---

    /**
     * Lógica para el botón 'Registrar Devolución'.
     */
    public function handleDevolverAction(string $recordId): void
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

        // Refrescar la tabla
        $this->js('window.location.reload()');
    }

    /**
     * Lógica para el botón 'Imprimir Boleta'.
     */
    public function handleImprimirAction(string $recordId): void
    {
        // Lógica futura para imprimir PDF

        Notification::make()
            ->title('Función no implementada')
            ->body("Lógica para imprimir el préstamo ID: $recordId")
            ->info()
            ->send();
    }
    // --- FIN: Lógica para los Botones del Blade ---
}
