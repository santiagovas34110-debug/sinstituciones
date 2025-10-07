@extends('layouts.app')

@section('title', 'Checklist - ' . $escuela->nombre)

@section('content')
<div class="container-fluid">

  <h1 class="mb-4">
    🏫 Checklist de la institución: <strong>{{ $escuela->nombre }}</strong>
  </h1>

  {{-- MOMENTO 1: CONEXIÓN --}}
  <div class="card mb-4 border-primary">
    <div class="card-header bg-primary text-white">1️⃣ Momento: Conexión</div>
    <div class="card-body">
      @if($checklist->fecha_agendamiento)
        <p><strong>Fecha agendada:</strong> {{ $checklist->fecha_agendamiento }}</p>
        <p>📄 <a href="{{ asset('storage/'.$checklist->documento_estudiantes) }}" target="_blank">Base estudiantes</a></p>
        <p>📄 <a href="{{ asset('storage/'.$checklist->documento_docentes) }}" target="_blank">Base docentes</a></p>
        <div class="alert alert-success">✅ Conexión completada</div>
      @else
        <form action="{{ route('checklist.conexion', $escuela->id) }}" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="mb-3">
            <label class="form-label">Fecha de agendamiento</label>
            <input type="date" name="fecha_agendamiento" class="form-control" required>
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
      @elseif($checklist->estudiantes_asistieron)
        <p><strong>Estudiantes que asistieron:</strong> {{ $checklist->estudiantes_asistieron }}</p>
        <p><strong>Docentes que asistieron:</strong> {{ $checklist->docentes_asistieron }}</p>
        <div class="alert alert-success">✅ Experiencia completada</div>
      @else
        <form action="{{ route('checklist.experiencia', $escuela->id) }}" method="POST">
          @csrf
          <div class="mb-3">
            <label class="form-label">Cantidad de estudiantes que asistieron</label>
            <input type="number" name="estudiantes_asistieron" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Cantidad de docentes que asistieron</label>
            <input type="number" name="docentes_asistieron" class="form-control" required>
          </div>
          <button class="btn btn-success">Guardar Experiencia</button>
        </form>
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