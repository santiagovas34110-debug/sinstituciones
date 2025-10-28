@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Editar asistencias - {{ $escuela->nombre ?? '' }}</h2>

    {{-- Mensajes de éxito --}}
    @if(session('success'))
        <div class="alert alert-success mt-2">{{ session('success') }}</div>
    @endif

    {{-- Formulario para agregar estudiante nuevo --}}
    <div class="card mb-4 p-3">
        <h5>Agregar estudiante nuevo</h5>
        <form method="POST" action="{{ route('experiencias.crearEstudiante', $escuela->id) }}">
            @csrf
            <div class="row">
                <div class="col-md-2 mb-2">
                    <input type="text" name="tipo_documento" class="form-control" placeholder="Tipo Doc." required>
                </div>
                <div class="col-md-2 mb-2">
                    <input type="text" name="documento" class="form-control" placeholder="Documento" required>
                </div>
                <div class="col-md-2 mb-2">
                    <input type="text" name="primer_nombre" class="form-control" placeholder="Primer Nombre" required>
                </div>
                <div class="col-md-2 mb-2">
                    <input type="text" name="segundo_nombre" class="form-control" placeholder="Segundo Nombre">
                </div>
                <div class="col-md-2 mb-2">
                    <input type="text" name="primer_apellido" class="form-control" placeholder="Primer Apellido" required>
                </div>
                <div class="col-md-2 mb-2">
                    <input type="text" name="segundo_apellido" class="form-control" placeholder="Segundo Apellido">
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-md-2 mb-2">
                    <input type="text" name="grado" class="form-control" placeholder="Grado">
                </div>
                <div class="col-md-10 mb-2">
                    <button type="submit" class="btn btn-primary">Agregar estudiante</button>
                </div>
            </div>
        </form>
    </div>

    {{-- Selección de fecha --}}
    <form action="{{ route('experiencias.gestionar', $escuela->id) }}" method="GET" class="mb-3">
        <label for="fecha">Selecciona fecha de experiencia:</label>
        <select name="fecha" id="fecha" class="form-control w-auto d-inline-block">
            @foreach($fechasExperiencia as $fecha)
                <option value="{{ $fecha }}" {{ $fecha == $fechaSeleccionada ? 'selected' : '' }}>
                    {{ $fecha }}
                </option>
            @endforeach
        </select>
        <button type="submit" class="btn btn-primary mt-2">Cargar asistencias</button>
    </form>

    @if(!$fechaSeleccionada)
        <div class="alert alert-info">Selecciona una fecha para ver los estudiantes.</div>
    @elseif($estudiantesPendientes->isEmpty())
        <div class="alert alert-secondary">No hay registros de asistencia para esta fecha.</div>
    @else
        {{-- Tabla de asistencias --}}
        <form action="{{ route('experiencias.storeAsistencia', $escuela->id) }}" method="POST" id="form-asistencias">
            @csrf
            <input type="hidden" name="fecha" value="{{ $fechaSeleccionada }}">
            {{-- enviamos id_experiencia para fiar el guardado --}}
            <input type="hidden" name="id_experiencia" value="{{ $experiencia->id ?? '' }}" id="id_experiencia">

            <div class="d-flex justify-content-between align-items-center mb-2">
                <div>
                    <button type="button" id="toggle-all" class="btn btn-sm btn-outline-secondary">Marcar todos</button>
                </div>
                <div>
                    <strong>Presentes:</strong> <span id="contador-presentes">0</span>
                    &nbsp;|&nbsp;
                    <strong>Ausentes:</strong> <span id="contador-ausentes">0</span>
                </div>
            </div>

            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Documento</th>
                        <th>Primer Nombre</th>
                        <th>Segundo Nombre</th>
                        <th>Primer Apellido</th>
                        <th>Segundo Apellido</th>
                        <th>Grado</th>
                        <th>Asistencia</th>
                        <th>Fecha de asistencia</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($estudiantesPendientes as $index => $est)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $est->estudiante->documento ?? '' }}</td>
                            <td>{{ $est->estudiante->primer_nombre ?? '' }}</td>
                            <td>{{ $est->estudiante->segundo_nombre ?? '' }}</td>
                            <td>{{ $est->estudiante->primer_apellido ?? '' }}</td>
                            <td>{{ $est->estudiante->segundo_apellido ?? '' }}</td>
                            <td>{{ $est->estudiante->grado ?? '' }}</td>
                            <td class="text-center">
                                {{-- name usa el id del registro EstudianteExperiencia para identificarlo correctamente --}}
                                <input type="checkbox" class="chk-asistencia" name="estudiantes[{{ $est->id }}][asistencia]" value="1"
                                    {{ $est->asistencia ? 'checked' : '' }}>
                            </td>
                            <td class="text-center">
                                {{ $est->fecha_asistencia ?? $fechaSeleccionada }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <button type="submit" class="btn btn-success mt-3">Guardar cambios</button>
            <a href="{{ route('checklist.show', $escuela->id) }}" class="btn btn-secondary mt-3">Volver al checklist</a>
        </form>
    @endif
</div>

{{-- JS para marcar todos y contador dinámico --}}
@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const toggler = document.getElementById('toggle-all');
    const checkboxes = document.querySelectorAll('.chk-asistencia');
    const contadorPresentes = document.getElementById('contador-presentes');
    const contadorAusentes = document.getElementById('contador-ausentes');

    function actualizarContadores() {
        let presentes = 0;
        checkboxes.forEach(cb => { if (cb.checked) presentes++; });
        const total = checkboxes.length;
        contadorPresentes.textContent = presentes;
        contadorAusentes.textContent = total - presentes;
    }

    // inicializar contador al cargar
    actualizarContadores();

    // event listeners para cada checkbox para actualizar contador
    checkboxes.forEach(cb => {
        cb.addEventListener('change', actualizarContadores);
    });

    // toggle all
    let todosMarcados = false;
    toggler.addEventListener('click', function () {
        todosMarcados = !todosMarcados;
        checkboxes.forEach(cb => cb.checked = todosMarcados);
        toggler.textContent = todosMarcados ? 'Desmarcar todos' : 'Marcar todos';
        actualizarContadores();
    });
});
</script>
@endsection

@endsection