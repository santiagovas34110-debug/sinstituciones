@extends('layouts.app')

@section('title', 'Checklist - ' . $escuela->nombre)

@section('content')
<div class="container-fluid">

  <h1 class="mb-4">
    🏫 Checklist de la institución: <strong>{{ $escuela->nombre }}</strong>
  </h1>

{{-- MOMENTO 1: CONEXIÓN --}}
<div class="card mb-4 border-primary">
  <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
    <span>1️⃣ Momento: Conexión</span>
    @if($checklist && $checklist->fecha_preconexion)
      {{-- Botón Editar --}}
      <button class="btn btn-sm btn-warning" data-bs-toggle="collapse" data-bs-target="#editarConexion">Editar</button>
    @endif
  </div>

  <div class="card-body">
    @if($checklist && $checklist->fecha_preconexion)
      {{-- Mostrar fechas y documentos --}}
      <p><strong>Fecha de Preconexión:</strong> {{ $checklist->fecha_preconexion }}</p>
      <p><strong>Fecha Conexión:</strong> {{ $checklist->fecha_agendamiento }}</p>
      @if($checklist->documento_estudiantes)
        <p>📄 <a href="{{ asset('storage/'.$checklist->documento_estudiantes) }}" target="_blank">Base estudiantes</a></p>
      @endif
      @if($checklist->documento_docentes)
        <p>📄 <a href="{{ asset('storage/'.$checklist->documento_docentes) }}" target="_blank">Base docentes</a></p>
      @endif
      <div class="alert alert-success">✅ Conexión completada</div>

      {{-- Formulario para editar (colapsable) --}}
      <div class="collapse mt-3" id="editarConexion">
        <form action="{{ route('updateconexion', $escuela->id) }}" method="POST" enctype="multipart/form-data">
          @csrf
          @method('PUT')
          <div class="mb-3">
            <label class="form-label">Actualizar Fechas</label>
            <input type="date" name="fecha_preconexion" class="form-control mb-1" value="{{ $checklist->fecha_preconexion }}" required>
            <input type="date" name="fecha_agendamiento" class="form-control" value="{{ $checklist->fecha_agendamiento }}" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Documento Estudiantes (opcional)</label>
            <input type="file" name="documento_estudiantes" class="form-control">
          </div>
          <div class="mb-3">
            <label class="form-label">Documento Docentes (opcional)</label>
            <input type="file" name="documento_docentes" class="form-control">
          </div>
          <button class="btn btn-warning">Actualizar Conexión</button>
        </form>
      </div>

    @else
      {{-- Formulario para crear --}}
      <form action="{{ route('checklist.conexion', $escuela->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
          <label class="form-label">Fecha de Preconexión</label>
          <input type="date" name="fecha_preconexion" class="form-control mb-1" required>
          <label class="form-label mt-2">Fecha de Agendamiento</label>
          <input type="date" name="fecha_agendamiento" class="form-control mb-1" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Base de datos de estudiantes</label>
          <input type="file" name="documento_estudiantes" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Base de datos de docentes</label>
          <input type="file" name="documento_docentes" class="form-control" required>
        </div>
        <button class="btn btn-success">Guardar Conexión</button>
      </form>
    @endif
  </div>
</div>

{{-- MOMENTO 2: EXPERIENCIA --}}
<div class="card mb-4 border-warning">
  <div class="card-header bg-warning text-dark">2️⃣ Momento: Experiencia</div>
  <div class="card-body">
    @if(!$checklist->fecha_agendamiento)
      <div class="alert alert-secondary">⚠️ Debes completar primero el momento Conexión.</div>
    @else
      {{-- === FECHAS DE EXPERIENCIA (PRIMERO) === --}}
      <h5 class="mt-2">Fechas de la Experiencia</h5>

      <form action="{{ route('checklist.updateExperiencia', $escuela->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div id="contenedorFechas">
          @for($i = 1; $i <= 5; $i++)
            @php $campo = 'fecha_experiencia_' . $i; @endphp
            <div class="mb-2 fecha-item" id="fecha{{ $i }}"
                 style="{{ $checklist->$campo ? '' : ($i == 1 ? '' : 'display:none;') }}">
              <label>Fecha {{ $i }}{{ $i == 1 ? ' *' : '' }}</label>
              <div class="input-group">
                <input type="date" name="fecha_experiencia_{{ $i }}" class="form-control"
                       value="{{ $checklist->$campo }}">

                {{-- Botón borrar (cada uno en su form aparte) --}}
                @if($i > 1 && $checklist->$campo)
                  <button type="button" class="btn btn-danger ms-2"
                          onclick="document.getElementById('delete-fecha-{{ $i }}').submit();">
                    Borrar
                  </button>
                @endif
              </div>
            </div>
          @endfor
        </div>

        <button type="button" class="btn btn-secondary mt-2" id="btnAgregarFecha">+ Agregar otra experiencia</button>
        <button type="submit" class="btn btn-primary mt-2">Guardar Fechas</button>
      </form>

      {{-- Formularios ocultos para eliminar fechas --}}
      @for($i = 2; $i <= 5; $i++)
        <form id="delete-fecha-{{ $i }}" action="{{ route('checklist.deleteFechaExperiencia', [$escuela->id, $i]) }}" 
              method="POST" style="display:none;">
          @csrf
          @method('DELETE')
        </form>
      @endfor

      <script>
      document.addEventListener('DOMContentLoaded', () => {
          const maxFechas = 5;
          const btnAgregar = document.getElementById('btnAgregarFecha');

          btnAgregar.addEventListener('click', () => {
              for (let i = 2; i <= maxFechas; i++) {
                  const div = document.getElementById('fecha' + i);
                  if (div && div.style.display === 'none') {
                      div.style.display = '';
                      return;
                  }
              }
              alert('⚠️ Solo puedes agregar hasta 5 fechas.');
          });
      });
      </script>
      {{-- === FIN FECHAS DE EXPERIENCIA === --}}

      <hr>

      {{-- === DATOS DE ASISTENCIA (DESPUÉS DE FECHAS) === --}}
      @if($checklist->estudiantes_asistieron)
        <p><strong>Estudiantes que asistieron:</strong> {{ $checklist->estudiantes_asistieron }}</p>
        <p><strong>Docentes que asistieron:</strong> {{ $checklist->docentes_asistieron }}</p>
        <div class="alert alert-success">✅ Experiencia completada</div>
      @else
        <form action="{{ route('checklist.updateExperiencia', $escuela->id) }}" method="POST">
          @csrf
          @method('PUT')

          <div class="mb-3">
            <label class="form-label">Cantidad de estudiantes que asistieron</label>
            <input type="number" name="estudiantes_asistieron" class="form-control" >
          </div>
          <div class="mb-3">
            <label class="form-label">Cantidad de docentes que asistieron</label>
            <input type="number" name="docentes_asistieron" class="form-control" >
          </div>

          <button class="btn btn-success">Guardar Experiencia</button>
        </form>
      @endif
    @endif
  </div>
</div>

  {{-- MOMENTO 3: REFLEXIÓN --}}
  <div class="card mb-4 border-success">
    <div class="card-header bg-success text-white">3️⃣ Momento: Reflexión</div>
    <div class="card-body">
      @if(!$checklist->estudiantes_asistieron)
        <div class="alert alert-secondary">⚠️ Debes completar primero el momento Experiencia.</div>
      @elseif($checklist->documento_reflexion)
        <div class="alert alert-success">✅ Documento cargado correctamente.</div>
        <a href="{{ asset('storage/'.$checklist->documento_reflexion) }}" target="_blank" class="btn btn-outline-success">
          📄 Ver documento
        </a>
      @else
        <form action="{{ route('checklist.reflexion', $escuela->id) }}" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="mb-3">
            <label class="form-label">Documento de reflexión</label>
            <input type="file" name="documento_reflexion" class="form-control" required>
          </div>
          <button class="btn btn-success">Guardar Reflexión</button>
        </form>
      @endif
    </div>
  </div>

  <a href="{{ route('checklist.panel') }}" class="btn btn-outline-secondary">⬅️ Volver al panel</a>

</div>
@endsection