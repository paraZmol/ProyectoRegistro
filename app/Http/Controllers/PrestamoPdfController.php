<?php

namespace App\Http\Controllers;

use App\Models\Prestamo;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class PrestamoPdfController extends Controller
{
    // boleta individual
    public function imprimir(Prestamo $prestamo)
    {
        // datos relacionados ale prestamo
        $prestamo->load(['estudiante.escuela', 'item.tablet', 'item.tesis']);

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

            // para reconstruir el manual de filtro y llevarlos a eloquent
            // filtro de prestamos activos
            if (!empty($filtros['prestamos_activos']['isActive'])) {
                $query->whereNull('momento_entrega');
            }

            // filtro de estudiantes
            if (!empty($filtros['estudiante_id']['value'])) {
                $query->where('estudiante_id', $filtros['estudiante_id']['value']);
            }

            // filtro de carnet
            if (!empty($filtros['carnet']['valor'])) {
                $carnet = $filtros['carnet']['valor'];
                $query->whereHas('estudiante', function($q) use ($carnet) {
                    $q->where('carnet', 'like', "%{$carnet}%");
                });
            }

            // filtro de fechas
            if (!empty($filtros['rango_fechas'])) {
                $datosFecha = $filtros['rango_fechas'];

                // para determinar que campo filtra
                $campo = ($datosFecha['tipo_fecha'] ?? 'prestamo') === 'devolucion'
                    ? 'momento_entrega'
                    : 'momento_prestamo';

                if (!empty($datosFecha['desde'])) {
                    $query->whereDate($campo, '>=', $datosFecha['desde']);
                }
                if (!empty($datosFecha['hasta'])) {
                    $query->whereDate($campo, '<=', $datosFecha['hasta']);
                }
            }

            // obtener los resultados
            $prestamos = $query->get();

            // generar el pdf
            $pdf = Pdf::loadView('pdf.prestamos-listado', [
                'prestamos' => $prestamos,
                'filtros' => $filtros // para mostrar los filtro en el pdf arriba
            ]);

            $pdf->setPaper('a4', 'landscape');

            return $pdf->stream('reporte_general.pdf');
    }
}
