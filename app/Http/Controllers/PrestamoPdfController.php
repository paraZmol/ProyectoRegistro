<?php

namespace App\Http\Controllers;

use App\Models\Prestamo;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

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
        // obtener filtros aplicados desde la URL
        $filtros = $request->all();

        // para construir la query base
        $query = Prestamo::query()
            ->with(['estudiante.escuela', 'item.tablet', 'item.tesis']);

        // filtro 1 - prestamos activos por defecto
        if ($request->has('tableFilters.prestamos_activos.value')) {
            $value = $request->input('tableFilters.prestamos_activos.value');

            if ($value) {
                $query->whereNull('momento_entrega');
            }
        }

        // filtro 2 - estudiante
        if ($request->has('tableFilters.estudiante_id.value')) {
            $query->where('estudiante_id', $request->input('tableFilters.estudiante_id.value'));
        }

        // filtro 3 - carnet
        if ($request->has('tableFilters.carnet.value')) {
            $query->whereHas('estudiante', function ($q) use ($request) {
                $q->where('carnet', 'like', '%' . $request->input('tableFilters.carnet.value') . '%');
            });
        }

        // filtro 4 → rango de fechas
        if ($request->has('tableFilters.rango_fechas.value')) {
            $tipo = $request->input('tableFilters.rango_fechas.value.tipo_fecha');
            $desde = $request->input('tableFilters.rango_fechas.value.desde');
            $hasta = $request->input('tableFilters.rango_fechas.value.hasta');

            if ($tipo) {
                $campo = $tipo === 'prestamo' ? 'momento_prestamo' : 'momento_entrega';

                if ($desde) {
                    $query->whereDate($campo, '>=', $desde);
                }
                if ($hasta) {
                    $query->whereDate($campo, '<=', $hasta);
                }
            }
        }

        // obtener resultados filtrados
        $prestamos = $query->orderBy('created_at', 'desc')->get();

        // generar PDF
        $pdf = Pdf::loadView('pdf.prestamos-listado', [
            'prestamos' => $prestamos,
            'filtros' => $filtros,
        ]);

        $pdf->setOption([
            'isRemoteEnabled' => true,
            'isHtml5ParserEnabled' => true,
        ]);

        $pdf->setPaper('a4', 'portrait');

        return $pdf->stream("prestamos-filtrados.pdf");
    }

}