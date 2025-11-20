<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte General de Préstamos</title>
    <style>
        /* --- CONFIGURACIÓN GENERAL --- */
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 10px;
            color: #333;
            margin: 0;
            padding: 0;
        }

        /* --- CABECERA --- */
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
            font-weight: bold;
            color: #1A3E6C; /* Azul Institucional */
            text-transform: uppercase;
        }
        .header p {
            margin: 2px 0;
            font-size: 11px;
            color: #555;
        }
        .divider {
            border-bottom: 3px solid #1A3E6C;
            margin-top: 10px;
            margin-bottom: 20px;
        }

        /* --- FILTROS APLICADOS --- */
        .filtros-box {
            background-color: #f0f4f8;
            border: 1px solid #d1d9e6;
            padding: 8px 12px;
            margin-bottom: 15px;
            border-radius: 4px;
        }
        .filtros-title {
            font-weight: bold;
            font-size: 9px;
            color: #1A3E6C;
            margin-bottom: 5px;
            text-transform: uppercase;
        }
        .filtro-tag {
            display: inline-block;
            background-color: #fff;
            border: 1px solid #ccc;
            padding: 2px 6px;
            margin-right: 8px;
            font-size: 9px;
            border-radius: 3px;
        }

        /* --- TABLA DE DATOS --- */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        thead th {
            background-color: #1A3E6C;
            color: #ffffff;
            text-align: left;
            padding: 8px;
            font-size: 9px;
            text-transform: uppercase;
            border: 1px solid #0d2a4d;
        }
        tbody td {
            border: 1px solid #ddd;
            padding: 6px 8px;
            vertical-align: top; /* Alineación superior para que se vea ordenado */
        }
        /* Filas alternas (Zebra Striping) */
        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        /* --- ESTILOS ESPECÍFICOS --- */
        .text-bold { font-weight: bold; }
        .text-small { font-size: 8px; color: #666; }

        /* Etiquetas de Estado */
        .status-badge {
            display: inline-block;
            padding: 3px 6px;
            border-radius: 3px;
            font-weight: bold;
            font-size: 8px;
            text-align: center;
            width: 60px;
        }
        .status-devuelto {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .status-pendiente {
            background-color: #fff3cd;
            color: #856404;
            border: 1px solid #ffeeba;
        }

        /* --- PIE DE PÁGINA --- */
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 20px;
            font-size: 8px;
            color: #999;
            text-align: center;
            border-top: 1px solid #eee;
            padding-top: 5px;
        }
        .page-number:after { content: counter(page); }
        img{
            height: 90px;
            margin: 10px;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>Universidad Nacional Santiago Antúnez de Mayolo</h1>
        {{-- lgo de la unasam --}}
        <img
            src="{{ public_path('images/logo-unasam.png') }}"
            alt="Logo UNASAM"
        >

        <p>Biblioteca Central - Sistema de Gestión de Préstamos</p>
        <p style="font-weight: bold; font-size: 14px; margin-top: 5px;">REPORTE GENERAL DE PRÉSTAMOS</p>
        <p>Fecha de Generación: {{ date('d/m/Y h:i A') }}</p>
    </div>
    <div class="divider"></div>

    @if(collect($filtros)->filter()->isNotEmpty())
        <div class="filtros-box">
            <div class="filtros-title">Filtros Aplicados al Reporte:</div>

            @if(!empty($filtros['fecha_inicio']) || !empty($filtros['fecha_fin']))
                <span class="filtro-tag">
                    <strong>Fecha:</strong>
                    {{ $filtros['fecha_inicio'] ?? 'Inicio' }} - {{ $filtros['fecha_fin'] ?? 'Hoy' }}
                </span>
            @endif

            @if(!empty($filtros['estudiante']))
                <span class="filtro-tag"><strong>Estudiante:</strong> {{ $filtros['estudiante'] }}</span>
            @endif

            @if(!empty($filtros['tipo_item']))
                <span class="filtro-tag"><strong>Tipo:</strong> {{ ucfirst($filtros['tipo_item']) }}</span>
            @endif
        </div>
    @endif

    <table>
        <thead>
            <tr>
                <th style="width: 20px; text-align: center;">ID</th>
                <th style="width: 200px;">Estudiante</th>
                <th style="width: 150px;">Programa Académico</th>
                <th>Detalle del Ítem</th>
                <th style="width: 70px;">Préstamo</th>
                <th style="width: 70px;">Devolución</th>
            </tr>
        </thead>
        <tbody>
            @forelse($prestamos as $prestamo)
                <tr>
                    <td style="text-align: center;">{{ $prestamo->id }}</td>

                    <td>
                        <div class="text-bold">
                            {{ $prestamo->estudiante->apellidos }}, {{ $prestamo->estudiante->nombres }}
                        </div>
                        <div class="text-small">
                            ({{ $prestamo->estudiante->carnet }})
                        </div>
                    </td>

                    <td>
                        <div class="text-bold">
                            {{ $prestamo->estudiante->escuela->escuela ?? '-' }}
                        </div>
                        <div class="text-small" style="color: #666;">
                            {{ $prestamo->estudiante->escuela->facultad->facultad ?? '-' }}
                        </div>
                    </td>

                    <td>
                        @php
                            $item = $prestamo->item;
                            $tipo = trim($item->tipo ?? '');
                        @endphp

                        {{-- Mostrar tipo como prefijo --}}
                        <strong>{{ $tipo }}:</strong>

                        {{-- Mostrar detalle dependiendo del tipo --}}
                        @if($tipo === 'Tablet')
                            @if($item->tablet)
                                <strong>{{ $item->tablet->marca }} {{ $item->tablet->modelo }}</strong>
                                <br>
                                <span class="text-small">Cod: {{ $item->tablet->codigo }}</span>
                            @else
                                <span style="color:red;">Error: Datos no encontrados</span>
                            @endif

                        @elseif($tipo === 'Tesis')
                            @if($item->tesis)
                                <strong>{{ \Illuminate\Support\Str::limit($item->tesis->titulo, 60) }}</strong>
                                <br>
                                <span class="text-small">Autor: {{ $item->tesis->autor }}</span>
                            @else
                                <span style="color:red;">Error: Datos no encontrados</span>
                            @endif

                        @else
                            -
                        @endif
                    </td>

                    <td>
                        {{ $prestamo->momento_prestamo->format('d/m/Y') }}<br>
                        <span class="text-small">{{ $prestamo->momento_prestamo->format('H:i') }}</span>
                    </td>
                    <td>
                        @if($prestamo->momento_entrega)
                            {{ $prestamo->momento_entrega->format('d/m/Y') }}<br>
                            <span class="text-small">{{ $prestamo->momento_entrega->format('H:i') }}</span>
                        @else
                            -
                        @endif
                    </td>

                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center; padding: 20px; color: #777;">
                        No se encontraron registros con los filtros seleccionados.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Reporte generado automáticamente – Biblioteca Central UNASAM | Página <span class="page-number"></span>
    </div>

    <script type="text/php">
        if (isset($pdf)) {
            $text = "Página {PAGE_NUM} de {PAGE_COUNT}";
            $size = 8;
            $font = $fontMetrics->getFont("helvetica");
            $width = $fontMetrics->get_text_width($text, $font, $size) / 2;
            $x = ($pdf->get_width() - $width) / 2;
            $y = $pdf->get_height() - 20;
            $pdf->page_text($x, $y, $text, $font, $size);
        }
    </script>

</body>
</html>
