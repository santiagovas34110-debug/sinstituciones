@extends('layouts.app')

@section('title', 'Panel de Escuelas')

@section('content')
<div class="container-fluid">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3">📋 Seguimiento de Escuelas</h1>
    <a href="{{ route('checklist.panel') }}" class="btn btn-outline-secondary">Actualizar</a>
  </div>

  <table class="table table-striped table-bordered align-middle text-center shadow-sm">
    <thead class="table table-striped">
      <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Código DANE</th>
        <th>Conexión</th>
        <th>Experiencia</th>
        <th>Reflexión</th>
        <th>Acción</th>
      </tr>
    </thead>
    <tbody>
      @foreach($escuelas as $escuela)
        <tr>
          <td>{{ $escuela->id }}</td>
          <td>{{ $escuela->nombre }}</td>
          <td>{{ $escuela->codigo_dane }}</td>

          <td>
            @if($escuela->conexion)
              <span class="badge bg-success">✅ Completado</span>
            @else
              <span class="badge bg-secondary">Pendiente</span>
            @endif
          </td>

          <td>
            @if($escuela->experiencia)
              <span class="badge bg-success">✅ Completado</span>
            @elseif($escuela->conexion)
              <span class="badge bg-warning text-dark">En proceso</span>
            @else
              <span class="badge bg-secondary">Bloqueado</span>
            @endif
          </td>

          <td>
            @if($escuela->reflexion)
              <span class="badge bg-success">✅ Completado</span>
            @elseif($escuela->experiencia)
              <span class="badge bg-warning text-dark">En proceso</span>
            @else
              <span class="badge bg-secondary">Bloqueado</span>
            @endif
          </td>

          <td>
            <a href="{{ route('checklist.show', $escuela->id) }}" class="btn btn-sm btn-outline-primary">
              ✏️ Abrir Checklist
            </a>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>
@endsection