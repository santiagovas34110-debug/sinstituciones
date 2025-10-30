@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Registrar asistencia - {{ $escuela->nombre }}</h2>

        {{-- Tabla de estudiantes --}}
        @if ($estudiantes->isEmpty())
            <div class="alert alert-info">No hay estudiantes registrados para esta escuela.</div>
        @else
            <form method="POST" action="{{ route('experiencias.asistencia.store', $escuela->id) }}">
                @csrf
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Documento</th>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Grado</th>
                            <th>Asistencia &nbsp; <input type="checkbox" id="check" /></th>
                            <th>Fecha de asistencia</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($estudiantes as $index => $estudiante)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $estudiante->documento ?? '' }}</td>
                                <td>{{ $estudiante->nombre ?? '' }}</td>
                                <td>{{ $estudiante->apellido ?? '' }}</td>
                                <td>{{ $estudiante->grado ?? '' }}</td>
                                <td>
                                    <input type="checkbox" name="estudiantes[{{ $estudiante->id }}][asistencia]" value="1"
                                        {{ $estudiante->asistencia ? 'checked' : '' }}>
                                </td>
                                <td>
                                    <input type="date" name="estudiantes[{{ $estudiante->id }}][fecha]"
                                        class="form-control" value="{{ $estudiante->fecha_asistencia ?? '' }}">
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <button type="submit" class="btn btn-success">Guardar asistencia</button>
            </form>
        @endif

        <hr>
        <a href="{{ route('experiencias.asistenciasGuardadas', $escuela->id) }}" class="btn btn-primary">
            Ver asistencias guardadas
        </a>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script>
        $("#check").change(function() {
            if ($(this).prop("checked")) {
                $("input[type=checkbox]").prop("checked", true);
            } else {
                $("input[type=checkbox]").prop("checked", false);
            }
        })
    </script>
@endsection
