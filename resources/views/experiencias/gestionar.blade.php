@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Registrar asistencia - {{ $escuela->nombre }}</h2>

    {{-- Selección de fecha --}}
    <form method="GET" action="{{ route('experiencias.gestionar', $escuela->id) }}">
        <div class="mb-3">
            <label for="fecha" class="form-label">Selecciona la fecha de la experiencia</label>
            <select name="fecha" id="fecha" class="form-select" onchange="this.form.submit()">
                <option value="">-- Elige una fecha --</option>
                @foreach($fechasExperiencia as $fecha)
                    <option value="{{ $fecha }}" @if(request('fecha') == $fecha) selected @endif>
                        {{ $fecha }}
                    </option>
                @endforeach
            </select>
        </div>
    </form>

    {{-- Solo mostrar estudiantes si hay fecha seleccionada --}}
    @if($fechaSeleccionada)
        <form method="POST" action="{{ route('experiencias.asistencia.store', $escuela->id) }}">
            @csrf

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Documento</th>
                        <th>Nombre</th>
                        <th>Grado</th>
                        <th>Asistencia</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($estudiantes as $estudiante)
                        <tr>
                            <td>{{ $estudiante->documento }}</td>
                            <td>{{ $estudiante->primer_nombre }} {{ $estudiante->primer_apellido }}</td>
                            <td>{{ $estudiante->grado }}</td>
                            <td>
                                <input type="checkbox" name="asistencia[{{ $estudiante->id }}]" value="1">
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <button type="submit" class="btn btn-success">Guardar asistencia</button>
        </form>
    @endif

    <hr>
    <a href="{{ route('experiencias.asistencias.guardadas', $escuela->id) }}" class="btn btn-primary">
        Ver asistencias guardadas
    </a>
</div>
@endsection