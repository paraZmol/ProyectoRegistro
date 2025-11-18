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
        .container {
            width: 90%;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #1A3E6C; /* color institucional */
        }
        .header h2 {
            margin: 5px 0 0;
            font-size: 16px;
            font-weight: normal;
        }
        .section {
            margin-bottom: 25px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .section-title {
            font-size: 15px;
            font-weight: bold;
            color: #1A3E6C;
            border-bottom: 2px solid #ccc;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th {
            text-align: left;
            width: 25%;
            padding: 4px 10px;
            font-weight: 500;
            color: #555;
            vertical-align: top;
        }
        td {
            padding: 4px 10px;
            vertical-align: top;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 10px;
            color: #888;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        img{
            height: 90px;
            margin: 10px;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="header">
            <h1>Biblioteca Central - UNASAM</h1>
            {{-- logo de la unasam --}}
            <img
            src="{{ public_path('images/logo-unasam.png') }}"
            alt="Logo UNASAM"
            >
            <h2>Comprobante de Préstamo</h2>
            <p><strong>N° de Préstamo:</strong> {{ str_pad($prestamo->id, 6, '0', STR_PAD_LEFT) }}</p>
        </div>

        @php
            $item = $prestamo->item;
            $estado = is_null($prestamo->momento_entrega) ? 'ACTIVO (Aún en posesión del estudiante)' : 'DEVUELTO';
            $estado_color = is_null($prestamo->momento_entrega) ? '#D35400' : '#27AE60';
        @endphp

        <div class="section">
            <div class="section-title">ESTADO DE LA TRANSACCIÓN</div>
            <table>
                <tr>
                    <th>Estado Actual:</th>
                    <td style="font-weight: bold; color: {{ $estado_color }};">
                        {{ $estado }}
                    </td>
                </tr>
                @if($estado === 'DEVUELTO')
                    <tr>
                        <th>Fecha de Devolución:</th>
                        <td>{{ $prestamo->momento_entrega->format('d/m/Y H:i A') }}</td>
                    </tr>
                @endif
            </table>
        </div>

        <div class="section">
            <div class="section-title">DATOS DEL ESTUDIANTE</div>
            <table>
                <tr>
                    <th>Apellidos y Nombres:</th>
                    <td>{{ $prestamo->estudiante->apellidos }}, {{ $prestamo->estudiante->nombres }}</td>
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
            <div class="section-title">DETALLES DEL ÍTEM ({{ $item->tipo }})</div>
            <table>
                <tr>
                    <th>Fecha de Préstamo:</th>
                    <td>{{ $prestamo->momento_prestamo->format('d/m/Y H:i A') }}</td>
                </tr>

                @if(trim($item->tipo) === 'Tablet')
                    <tr>
                        <th>Código:</th>
                        <td>{{ $item->tablet->codigo }}</td>
                    </tr>
                    <tr>
                        <th>Marca y Modelo:</th>
                        <td>{{ $item->tablet->marca }} / {{ $item->tablet->modelo }} (Color: {{ $item->tablet->color }})</td>
                    </tr>
                    @if($prestamo->actividad_tablet)
                        <tr>
                            <th>Actividad:</th>
                            <td>
                                {{ $prestamo->actividad_tablet }}
                                @if($prestamo->actividad_tablet_otro)
                                    ({{ $prestamo->actividad_tablet_otro }})
                                @endif
                            </td>
                        </tr>
                    @endif
                @elseif(trim($item->tipo) === 'Tesis')
                    <tr>
                        <th>Título:</th>
                        <td>{{ $item->tesis->titulo }}</td>
                    </tr>
                    <tr>
                        <th>Autor:</th>
                        <td>{{ $item->tesis->autor }}</td>
                    </tr>
                @endif
            </table>
        </div>

        <div class="footer">
            <p>Este documento es un comprobante generado automáticamente por el sistema de gestión de la biblioteca.</p>
            <p>Fecha de impresión: {{ date('d/m/Y H:i') }}</p>
        </div>
    </div>

</body>
</html>
