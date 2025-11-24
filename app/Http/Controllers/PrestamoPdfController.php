<?php

namespace App\Http\Controllers;

use App\Models\Prestamo;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;
use App\Models\Estudiante;
use App\Services\UNASAMHelpers;
use App\Models\Item;


class PrestamoPdfController extends Controller
{
    // boleta individual
    public function imprimir(Prestamo $prestamo)
    {
        // datos relacionados ale prestamo
        $prestamo->load(['estudiante.escuela.facultad', 'item.tablet', 'item.tesis']);

        //crear el pdf
        $pdf = Pdf::loadView('pdf.boleta', ['prestamo'=> $prestamo]);

        $pdf->setOption([ // cargar imagenes locale sy externas
            'isRemoteEnabled' => true,
            'isHtml5ParserEnabled' => true
        ]);
               // tamaño de papael en A4
        $pdf->setPaper('a4', 'portrait');

        // para abrir una nueva pestaña con pdf
        return $pdf->stream("boleta-prestamo-{$prestamo->id}.pdf");
        //return view('pdf.boleta', ['prestamo' => $prestamo]);
    }

    // boleta en lista
    public function imprimirListado(Request $request)
    {
        // recibir los filtros desde la URL
            $filtros = $request->input('filtros', []);

            // consulta Base
            $query = Prestamo::query()
                ->with(['estudiante.escuela.facultad', 'item.tablet', 'item.tesis'])
                ->orderBy('created_at', 'desc');

            $textosFiltros = []; // para guardar los filtros

            // para reconstruir el manual de filtro y llevarlos a eloquent
            // filtro de prestamos activos
            if (!empty($filtros['prestamos_activos']['isActive'])) {
                $query->whereNull('momento_entrega');
                $textosFiltros[] = "Estado: Solo Préstamos Activos (No devueltos)";
            }

            // filtro de estudiantes
            if (!empty($filtros['estudiante_id']['value'])) {
                //$query->where('estudiante_id', $filtros['estudiante_id']['value']);
                $estId = $filtros['estudiante_id']['value'];
                $query->where('estudiante_id', $estId);

                // buscamos el nombre para el reporte
                $estudiante = Estudiante::find($estId);
                if ($estudiante) {
                    $textosFiltros[] = "Estudiante: {$estudiante->apellidos}, {$estudiante->nombres}";
                }
            }

            // filtro de carnet
            if (!empty($filtros['carnet']['valor'])) {
                $carnet = $filtros['carnet']['valor'];
                $query->whereHas('estudiante', function($q) use ($carnet) {
                    $q->where('carnet', 'like', "%{$carnet}%");
                });
                $textosFiltros[] = "Carnet contiene: '{$carnet}'";
            }

            // filtro de fechas
            if (!empty($filtros['rango_fechas'])) {
                $datosFecha = $filtros['rango_fechas'];

                // para determinar que campo filtra
                $campo = ($datosFecha['tipo_fecha'] ?? 'prestamo') === 'devolucion'
                    ? 'momento_entrega'
                    : 'momento_prestamo';

                $tipoFechaTexto = ($campo === 'momento_entrega') ? 'Devolución' : 'Préstamo';

                if (!empty($datosFecha['desde'])) {
                    $query->whereDate($campo, '>=', $datosFecha['desde']);

                    $fechaDesde = Carbon::parse($datosFecha['desde'])->format('d/m/Y');
                    $textosFiltros[] = "Fecha {$tipoFechaTexto} Desde: {$fechaDesde}";
                }
                if (!empty($datosFecha['hasta'])) {
                    $query->whereDate($campo, '<=', $datosFecha['hasta']);

                    $fechaHasta = Carbon::parse($datosFecha['hasta'])->format('d/m/Y');
                    $textosFiltros[] = "Fecha {$tipoFechaTexto} Hasta: {$fechaHasta}";
                }
            }

                // ---------------------------------------------------------
                // Imprimible por rol
                // ---------------------------------------------------------

                /** @var \App\Models\User $user */
                $user = Auth::user();

                if ($user) {
                    // para el Encargado de TABLET
                    if ($user->hasRole('Encargado de Tablet')) {
                        $query->whereHas('item', function (Builder $q) {
                            $q->where('tipo', 'Tablet');
                        });

                        $textosFiltros[] = "Área: Gestión de Tablets";
                    }

                    // para el Encargado de TESIS
                    if ($user->hasRole('Encargado de Tesis')) {
                        $query->whereHas('item', function (Builder $q) {
                            $q->where('tipo', 'Tesis');
                        });

                        $textosFiltros[] = "Área: Gestión de Tesis";
                    }
                }
                // ---------------------------------------------------------

            // obtener los resultados
            $prestamos = $query->get();

            //prestamos para el pdf
            /*$resumen = [
                'total_tablets' => Item::where('tipo', 'Tablet')->count(),
                'tablets_prestadas' => Item::where('tipo', 'Tablet')->where('estado_disponibilidad', 'Prestado')->count(),
                'tablets_disponibles' => Item::where('tipo', 'Tablet')->where('estado_disponibilidad', 'Disponible')->count(),

                'total_tesis' => Item::where('tipo', 'Tesis')->count(),
                'tesis_prestadas' => Item::where('tipo', 'Tesis')->where('estado_disponibilidad', 'Prestado')->count(),
                'tesis_disponibles' => Item::where('tipo', 'Tesis')->where('estado_disponibilidad', 'Disponible')->count(),
            ];*/

            // generar el pdf
            $pdf = Pdf::loadView('pdf.prestamos-listado', [
                'prestamos' => $prestamos,
                'filtrosTexto' => $textosFiltros, // para mostrar los filtro en el pdf arriba
                //'resumen' => $resumen// para contador pdf
            ]);

            $pdf->setPaper('a4', 'landscape');

            return $pdf->stream('reporte_general.pdf');
    }
}