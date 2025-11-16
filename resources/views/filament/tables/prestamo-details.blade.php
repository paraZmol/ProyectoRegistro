{{-- /resources/views/filament/tables/prestamo-details.blade.php --}}

{{-- 1. Guardia para el render inicial nulo de Livewire --}}
@if ($record)
    <div class="p-4 bg-gray-100 dark:bg-gray-900 rounded-b-xl">
        <div class="space-y-4">

            {{-- SECCIÓN: Detalles del Ítem --}}
            <div>
                <h4 class="font-bold text-sm text-gray-700 dark:text-gray-300">Ítem Prestado:</h4>
                <p class="text-sm text-gray-900 dark:text-gray-100">

                    {{-- 2. Guardia para préstamos sin ítem (ESTA ES LA CLAVE) --}}
                    @if ($record->item)
                        @php
                            // Si estamos aquí, $record->item SÍ existe.
                            $item = $record->item;
                            $tipo = trim($item->tipo);
                        @endphp

                        @if ($tipo === 'Tablet')
                            @if ($item->tablet)
                                {{-- Usamos la sintaxis de Blade para imprimir de forma segura --}}
                                {{ "TABLET: {$item->tablet->marca} {$item->tablet->modelo} (Cod: {$item->tablet->codigo})" }}
                            @else
                                {{ "¡ERROR! Item Tablet huérfano (ID: {$item->id})" }}
                            @endif
                        @elseif ($tipo === 'Tesis')
                            @if ($item->tesis)
                                {{ "TESIS: {$item->tesis->titulo} (Autor: {$item->tesis->autor})" }}
                            @else
                                {{ "¡ERROR! Item Tesis huérfano (ID: {$item->id})" }}
                            @endif
                        @else
                            {{ "N/A" }}
                        @endif

                    @else
                        {{-- Esto se mostrará si $record->item es nulo --}}
                        ¡ERROR! Este préstamo (ID: {{ $record->id }}) no tiene un ítem asociado.
                    @endif

                </p>
            </div>

            {{-- SECCIÓN: Botones (Esto ya estaba bien) --}}
            <div class="flex space-x-2">

                {{-- Botón Imprimir --}}
                <x-filament::button
                    icon="heroicon-o-printer"
                    color="gray"
                    tag="button"
                    wire:click="handleImprimirAction('{{ $record->id }}')"
                >
                    Imprimir Boleta
                </x-filament::button>

                {{-- Botón Devolver (Condicional) --}}
                @if(is_null($record->momento_entrega))
                    <x-filament::button
                        icon="heroicon-o-check-circle"
                        color="success"
                        tag="button"
                        wire:click="handleDevolverAction('{{ $record->id }}')"
                        wire:confirm="¿Está seguro de que desea registrar la devolución de este ítem?"
                    >
                        Registrar Devolución
                    </x-filament::button>
                @endif
            </div>

        </div>
    </div>
@endif
