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
        // capturar filtros desde el Request
        /*$filtros = [
            'fecha_inicio' => $request->input('fecha_inicio'),
            'fecha_fin'    => $request->input('fecha_fin'),
            'estudiante'   => $request->input('estudiante'),
            'escuela_id'   => $request->input('escuela_id'),
            'tipo_item'    => $request->input('tipo_item'),
        ];

        // construir el query con filtros
        $query = Prestamo::with(['estudiante.escuela', 'item.tablet', 'item.tesis']);

        if ($request->filled('fecha_inicio')) {
            $query->whereDate('momento_prestamo', '>=', $request->fecha_inicio);
        }

        if ($request->filled('fecha_fin')) {
            $query->whereDate('momento_prestamo', '<=', $request->fecha_fin);
        }

        if ($request->filled('estudiante')) {
            $query->whereHas('estudiante', function ($q) use ($request) {
                $q->where('apellidos', 'like', "%{$request->estudiante}%")
                ->orWhere('nombres', 'like', "%{$request->estudiante}%");
            });
        }

        if ($request->filled('escuela_id')) {
            $query->whereHas('estudiante.escuela', function ($q) use ($request) {
                $q->where('id', $request->escuela_id);
            });
        }

        if ($request->filled('tipo_item')) {
            $query->whereHas('item', function ($q) use ($request) {
                $q->where('tipo', $request->tipo_item);
            });
        }

        // obtener los registros
        $prestamos = $query->get();

        // enviar prestamos y filtros al blade del PDF
        $pdf = Pdf::loadView('pdf.prestamos-listado', [
            'prestamos' => $prestamos,
            'filtros'   => $filtros,
        ]);

        return $pdf->stream('prestamos.pdf');*/

// 1. Recibir los filtros desde la URL
    $filtros = $request->input('filtros', []);

    // 2. Consulta Base
    $query = Prestamo::query()
        ->with(['estudiante.escuela', 'item.tablet', 'item.tesis'])
        ->orderBy('created_at', 'desc');

    // 3. RECONSTRUCCIÓN MANUAL DE FILTROS
    // Filament envía los filtros en un array, hay que "traducirlos" a Eloquent

    // A. Filtro: Préstamos Activos (Checkbox/Toggle)
    if (!empty($filtros['prestamos_activos']['isActive'])) {
        $query->whereNull('momento_entrega');
    }

    // B. Filtro: Estudiante (Select)
    if (!empty($filtros['estudiante_id']['value'])) {
        $query->where('estudiante_id', $filtros['estudiante_id']['value']);
    }

    // C. Filtro: Carnet (Input text dentro de un form)
    if (!empty($filtros['carnet']['valor'])) {
        $carnet = $filtros['carnet']['valor'];
        $query->whereHas('estudiante', function($q) use ($carnet) {
            $q->where('carnet', 'like', "%{$carnet}%");
        });
    }

    // D. Filtro: Fechas (DatePicker dentro de un form)
    if (!empty($filtros['rango_fechas'])) {
        $datosFecha = $filtros['rango_fechas'];

        // Determinar qué campo filtrar (prestamo o devolucion)
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

    // 4. Obtener resultados
    $prestamos = $query->get();

    // 5. Generar PDF
    $pdf = Pdf::loadView('pdf.prestamos-listado', [
        'prestamos' => $prestamos,
        'filtros' => $filtros // Pasamos los filtros para mostrarlos en el header del PDF si quieres
    ]);

    $pdf->setPaper('a4', 'landscape');

    return $pdf->stream('reporte_general.pdf');
    }
}
