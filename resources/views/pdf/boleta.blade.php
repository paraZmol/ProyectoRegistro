<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Boleta de Préstamo #{{ $prestamo->id }}</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 14px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #444;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 20px;
            text-transform: uppercase;
        }
        .header p {
            margin: 5px 0 0;
            font-size: 12px;
            color: #666;
        }
        .section {
            margin-bottom: 20px;
        }
        .section-title {
            background-color: #f0f0f0;
            padding: 5px 10px;
            font-weight: bold;
            border-left: 4px solid #333;
            margin-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th {
            text-align: left;
            width: 150px;
            padding: 5px;
            font-weight: bold;
            color: #555;
        }
        td {
            padding: 5px;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 10px;
            color: #888;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>Biblioteca Central - UNASAM</h1>
        <p>Comprobante de Préstamo de Material</p>
        <p><strong>N° de Préstamo:</strong> {{ str_pad($prestamo->id, 6, '0', STR_PAD_LEFT) }}</p>
    </div>

    <div class="section">
        <div class="section-title">DATOS DEL ESTUDIANTE</div>
        <table>
            <tr>
                <th>Apellidos:</th>
                <td>{{ $prestamo->estudiante->apellidos }}</td>
            </tr>
            <tr>
                <th>Nombres:</th>
                <td>{{ $prestamo->estudiante->nombres }}</td>
            </tr>
            <tr>
                <th>Carnet/DNI:</th>
                <td>{{ $prestamo->estudiante->carnet }}</td>
            </tr>
            <tr>
                <th>Escuela:</th>
                <td>{{ $prestamo->estudiante->escuela->escuela }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">DETALLES DEL PRÉSTAMO</div>
        <table>
            <tr>
                <th>Fecha y Hora:</th>
                <td>{{ $prestamo->momento_prestamo->format('d/m/Y H:i A') }}</td>
            </tr>
            <tr>
                <th>Tipo de Ítem:</th>
                <td>{{ $prestamo->item->tipo }}</td>
            </tr>
            <tr>
                <th>Descripción:</th>
                <td>
                    {{-- Lógica para mostrar los detalles según el tipo --}}
                    @php
                        $item = $prestamo->item;
                        if (trim($item->tipo) === 'Tablet') {
                            if ($item->tablet) {
                                echo "<strong>Marca:</strong> {$item->tablet->marca}<br>";
                                echo "<strong>Modelo:</strong> {$item->tablet->modelo}<br>";
                                echo "<strong>Código:</strong> {$item->tablet->codigo}<br>";
                                echo "<strong>Color:</strong> {$item->tablet->color}";
                            } else {
                                echo "Error: Datos de Tablet no encontrados";
                            }
                        } elseif (trim($item->tipo) === 'Tesis') {
                            if ($item->tesis) {
                                echo "<strong>Título:</strong> {$item->tesis->titulo}<br>";
                                echo "<strong>Autor:</strong> {$item->tesis->autor}";
                            } else {
                                echo "Error: Datos de Tesis no encontrados";
                            }
                        }
                    @endphp
                </td>
            </tr>

            {{-- Mostrar Actividad solo si es Tablet --}}
            @if(trim($prestamo->item->tipo) === 'Tablet' && $prestamo->actividad_tablet)
                <tr>
                    <th>Actividad:</th>
                    <td>
                        {{ $prestamo->actividad_tablet }}
                        @if($prestamo->actividad_tablet_otro)
                            <br><em>(Detalle: {{ $prestamo->actividad_tablet_otro }})</em>
                        @endif
                    </td>
                </tr>
            @endif
        </table>
    </div>

    <div class="footer">
        <p>Este documento es un comprobante generado automáticamente por el sistema de gestión de la biblioteca.</p>
        <p>Fecha de impresión: {{ date('d/m/Y H:i') }}</p>
    </div>

</body>
</html>
