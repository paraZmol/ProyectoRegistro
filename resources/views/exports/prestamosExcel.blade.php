<table>
    <thead>
        <tr>
            <th>DNI / Carnet</th>
            <th>Estudiante</th>
            <th>Facultad</th>
            <th>Escuela</th>
            <th>Tipo</th>
            <th>Detalle del Ítem</th>
            <th>Fecha Préstamo</th>
            <th>Fecha Devolución</th>
        </tr>
    </thead>

    <tbody>
        @foreach ($prestamos as $p)
        <tr>
            <td>{{ $p->estudiante->carnet }}</td>

            <td>{{ $p->estudiante->apellidos }} {{ $p->estudiante->nombres }}</td>

            <td>{{ $p->estudiante->escuela->facultad->facultad }}</td>

            <td>{{ $p->estudiante->escuela->escuela }}</td>

            <td>{{ $p->item->tipo }}</td>

            <td>
                @if($p->item->tipo === "Tablet")
                    {{ $p->item->tablet ? $p->item->tablet->marca . " " . $p->item->tablet->modelo : "-" }}
                @elseif($p->item->tipo === "Tesis")
                    {{ $p->item->tesis ? $p->item->tesis->titulo : "-" }}
                @else
                    -
                @endif
            </td>

            <td>{{ $p->momento_prestamo }}</td>

            <td>{{ $p->momento_entrega }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
