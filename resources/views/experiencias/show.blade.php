@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Registrar asistencia - {{ $escuela->nombre }}</h2>

    {{-- Tabla de estudiantes --}}
    @if($experiencias->isEmpty())
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
                        <th>Asistencia</th>
                        <th>Fecha de asistencia</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($experiencias as $index => $exp)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $exp->estudiante->documento ?? '' }}</td>
                            <td>{{ $exp->estudiante->primer_nombre ?? '' }} {{ $exp->estudiante->segundo_nombre ?? '' }}</td>
                            <td>{{ $exp->estudiante->primer_apellido ?? '' }} {{ $exp->estudiante->segundo_apellido ?? '' }}</td>
                            <td>{{ $exp->estudiante->grado ?? '' }}</td>
                            <td>
                                <input type="checkbox" name="estudiantes[{{ $exp->id }}][asistencia]" value="1"
                                    {{ $exp->asistencia ? 'checked' : '' }}>
                            </td>
                            <td>
                                <<input type="date" name="estudiantes[{{ $exp->id }}][fecha]" class="form-control" value="{{ $exp->fecha_asistencia ?? '' }}">
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
@endsection