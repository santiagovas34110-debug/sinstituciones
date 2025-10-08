@extends('layouts.app')
@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3>Lista de Estudiantes</h3>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#agregarEstudiante">
                Agregar Estudiante
            </button>
        </div>

        <div class="card-body table-responsive">

            {{-- Mensajes --}}
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('warning'))
                <div class="alert alert-warning">{{ session('warning') }}</div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <table class="table table-striped dt">
                <thead>
                    <tr>
                        <th>Escuela</th>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Documento</th>
                        <th>fecha_nacimiento</th>
                        <th>Genero</th>
                        <th>Nombre_responsable</th>
                        <th>Telefono_responsable</th>
                        <th>Email_responsable</th>
                        <th>Parentesco_responsable</th>
                        <th>Direccion</th>
                        <th>Grado</th>
                        <th>Seccion</th>
                        <th>Fecha_inscripcion</th>
                        <th>Escuela</th>
                        <th>Profesor</th>
                        <th>Activo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($estudiantes as $estudiante)
                        <tr>
                            <td class="text-primary">{{ $estudiante->escuela->nombre }}</td>
                            <td>{{ $estudiante->nombre }}</td>
                            <td>{{ $estudiante->apellido }}</td>
                            <td>{{ $estudiante->documento }}</td>
                            <td>{{ $estudiante->fecha_nacimiento }}</td>
                            <td>{{ $estudiante->genero }}</td>
                            <td>{{ $estudiante->nombre_responsable }}</td>
                            <td>{{ $estudiante->telefono_responsable }}</td>
                            <td>{{ $estudiante->email_responsable }}</td>
                            <td>{{ $estudiante->parentesco_responsable }}</td>
                            <td>{{ $estudiante->direccion }}</td>
                            <td>{{ $estudiante->grado }}</td>
                            <td>{{ $estudiante->seccion }}</td>
                            <td>{{ $estudiante->fecha_inscripcion }}</td>
                            <td>{{ $estudiante->profesor->escuela->nombre ?? 'Sin escuela' }}</td>
                            <td>{{ $estudiante->profesor->nombre ?? 'Sin profesor' }}</td>
                            <td>{{ $estudiante->activo ? 'Sí' : 'No' }}</td>
                            <td>
                                {{-- Si tu ruta para eliminar es GET (como la tenías), queda así: --}}
                                <a href="{{ route('estudiantes.destroy', $estudiante->id) }}"
                                    class="text-danger me-2">Eliminar</a>

                                {{-- Botón editar: abre modal y carga valores en los inputs mediante JS --}}
                                <a href="#" data-bs-toggle="modal" data-bs-target="#updateEstudiantes"
                                    onclick="
                                       document.getElementById('idEdit').value='{{ $estudiante->id }}';
                                       document.getElementById('nombreEdit').value='{{ $estudiante->nombre }}';
                                       document.getElementById('apellidoEdit').value='{{ $estudiante->apellido }}';
                                       document.getElementById('documentoEdit').value='{{ $estudiante->documento }}';
                                       document.getElementById('fecha_nacimientoEdit').value='{{ $estudiante->fecha_nacimiento }}';
                                       document.getElementById('generoEdit').value='{{ $estudiante->genero }}';
                                       document.getElementById('nombre_responsableEdit').value='{{ $estudiante->nombre_responsable }}';
                                       document.getElementById('telefono_responsableEdit').value='{{ $estudiante->telefono_responsable }}';
                                       document.getElementById('email_responsableEdit').value='{{ $estudiante->email_responsable }}';
                                       document.getElementById('parentesco_responsableEdit').value='{{ $estudiante->parentesco_responsable }}';
                                       document.getElementById('direccion Edit').value='{{ $estudiante->direccion }}';
                                       document.getElementById('gradoEdit').value='{{ $estudiante->grado }}';
                                       document.getElementById('seccionEdit').value='{{ $estudiante->seccion }}';
                                       document.getElementById('activoEdit').value='{{ $estudiante->activo }}';
                                       document.getElementById('profesoresEdit').value='{{ $estudiante->id_profesor ?? '' }}';
                                   ">
                                    Editar
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- ================== MODAL CREAR Estudiantes ================== -->
    <form method="post" action="{{ route('estudiantes.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="modal fade" id="agregarEstudiante" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5">Agregar Estudiante</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body row g-3">
                        {{-- Información personal --}}
                        <div class="col-md-6">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" name="nombre" id="nombre" required>
                        </div>
                        <div class="col-md-6">
                            <label for="apellido" class="form-label">Apellido</label>
                            <input type="text" class="form-control" name="apellido" id="apellido" required>
                        </div>
                        <div class="col-md-6">
                            <label for="documento" class="form-label">Documento</label>
                            <input type="text" class="form-control" name="documento" id="documento" required>
                        </div>
                        <div class="col-md-6">
                            <label for="fecha_nacimiento" class="form-label">Fecha Nacimiento</label>
                            <input type="date" class="form-control" name="fecha_nacimiento" id="fecha_nacimiento">
                        </div>
                        <div class="col-md-6">
                            <label for="genero" class="form-label">Género</label>
                            <select class="form-control" name="genero" id="genero">
                                <option value="">Seleccionar</option>
                                <option value="Masculino">Masculino</option>
                                <option value="Femenino">Femenino</option>
                                <option value="Otro">Otro</option>
                            </select>
                        </div>

                        <div class="modal-body row g-3">
                            {{-- Información Responsable --}}
                            <div class="col-md-6">
                                <label for="nombre_responsable" class="form-label">nombre del responsable</label>
                                <input type="text" class="form-control" name="nombre_responsable" id="nombre_responsable"
                                    required>
                            </div>
                            <div class="mb-3">
                                <label for="telefono_responsable" class="form-label">Teléfono del responsable</label>
                                <input type="text" class="form-control" id="telefono_responsable"
                                    name="telefono_responsable" placeholder="Ingrese el teléfono del contacto">
                            </div>
                            <div class="col-md-6">
                                <label for="email_responsable" class="form-label">Correo del responsable</label>
                                <input type="email_responsable" class="form-control" name="email_responsable"
                                    id="email_responsable" required>
                            </div>
                            <div class="col-md-6">
                                <label for="parentesco_responsable" class="form-label">parentesco del responsable</label>
                                <input type="text" class="form-control" name="parentesco_responsable"
                                    id="parentesco_responsable" required>
                            </div>
                            <div class="mb-3">
                                <label for="ubicacion" class="form-label">Direccion</label>
                                <input type="text" class="form-control" id="ubicacion" name="ubicacion"
                                    placeholder="Ingrese la ubicación">
                            </div>

                            {{-- clase --}}
                            <div class="col-md-6">
                                <label for="grado" class="form-label">Grado</label>
                                <input type="text" class="form-control" name="grado" id="grado" required>
                            </div>
                            <div class="col-md-6">
                                <label for="seccion" class="form-label">Sección</label>
                                <input type="text" class="form-control" name="seccion" id="seccion" required>
                            </div>
                            <div class="col-md-6">
                                <label for="fecha_inscripcion" class="form-label">Fecha Inscripción</label>
                                <input type="date" class="form-control" name="fecha_inscripcion"
                                    id="fecha_inscripcion">

                            </div>

                            {{-- Relación: Profesor --}}
                            <div class="col-md-6">
                                <label for="id_profesor" class="form-label">Profesor</label>
                                <select class="form-control" name="id_profesor" id="id_profesor" required>
                                    <option value="">Seleccione un profesor</option>
                                    @foreach ($profesores as $profesor)
                                        <option value="{{ $profesor->id }}">{{ $profesor->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Estado --}}
                            <div class="col-md-6">
                                <label for="activo" class="form-label">Activo</label>
                                <select class="form-control" name="activo" id="activo">
                                    <option value="1" selected>Sí</option>
                                    <option value="0">No</option>
                                </select>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        </div>

                    </div>
                </div>
            </div>
    </form>

    <!-- ================== MODAL EDITAR Estudiantes ================== -->
    <form method="post" action="{{ route('estudiantes.update') }}" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="id" id="idEdit" />
        <div class="modal fade" id="updateEstudiantes" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5">Editar Profesor</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>


                    <div class="modal-body row g-3">
                        {{-- Información personal --}}
                        <div class="col-md-6">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" name="nombre" id="nombre" required>
                        </div>
                        <div class="col-md-6">
                            <label for="apellido" class="form-label">Apellido</label>
                            <input type="text" class="form-control" name="apellido" id="apellido" required>
                        </div>
                        <div class="col-md-6">
                            <label for="documento" class="form-label">Documento</label>
                            <input type="text" class="form-control" name="documento" id="documento" required>
                        </div>
                        <div class="col-md-6">
                            <label for="fecha_nacimiento" class="form-label">Fecha Nacimiento</label>
                            <input type="date" class="form-control" name="fecha_nacimiento" id="fecha_nacimiento">
                        </div>
                        <div class="col-md-6">
                            <label for="genero" class="form-label">Género</label>
                            <select class="form-control" name="genero" id="genero">
                                <option value="">Seleccionar</option>
                                <option value="Masculino">Masculino</option>
                                <option value="Femenino">Femenino</option>
                                <option value="Otro">Otro</option>
                            </select>
                        </div>

                        <div class="modal-body row g-3">
                            {{-- Información Responsable --}}
                            <div class="col-md-6">
                                <label for="nombre_responsable" class="form-label">nombre del responsable</label>
                                <input type="text" class="form-control" name="nombre_responsable"
                                    id="nombre_responsable" required>
                            </div>
                            <div class="mb-3">
                                <label for="telefono_responsable" class="form-label">Teléfono del responsable</label>
                                <input type="text" class="form-control" id="telefono_responsable"
                                    name="telefono_responsable" placeholder="Ingrese el teléfono del contacto">
                            </div>
                            <div class="col-md-6">
                                <label for="email_responsable" class="form-label">Correo del responsable</label>
                                <input type="email_responsable" class="form-control" name="email_responsable"
                                    id="email_responsable" required>
                            </div>
                            <div class="col-md-6">
                                <label for="parentesco_responsable" class="form-label">parentesco del responsable</label>
                                <input type="text" class="form-control" name="parentesco_responsable"
                                    id="parentesco_responsable" required>
                            </div>
                            <div class="mb-3">
                                <label for="ubicacion" class="form-label">Direccion</label>
                                <input type="text" class="form-control" id="ubicacion" name="ubicacion"
                                    placeholder="Ingrese la ubicación">
                            </div>

                            {{-- clase --}}
                            <div class="col-md-6">
                                <label for="grado" class="form-label">Grado</label>
                                <input type="text" class="form-control" name="grado" id="grado" required>
                            </div>
                            <div class="col-md-6">
                                <label for="seccion" class="form-label">Sección</label>
                                <input type="text" class="form-control" name="seccion" id="seccion" required>
                            </div>
                            <div class="col-md-6">
                                <label for="fecha_inscripcion" class="form-label">Fecha Inscripción</label>
                                <input type="date" class="form-control" name="fecha_inscripcion"
                                    id="fecha_inscripcion">

                            </div>

                            {{-- Relación: Profesor --}}
                            <div class="col-md-6">
                                <label for="id_profesor" class="form-label">Profesor</label>
                                <select class="form-control" name="id_profesor" id="id_profesor" required>
                                    <option value="">Seleccione un profesor</option>
                                    @foreach ($profesores as $profesor)
                                        <option value="{{ $profesor->id }}">{{ $profesor->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Estado --}}
                            <div class="col-md-6">
                                <label for="activo" class="form-label">Activo</label>
                                <select class="form-control" name="activo" id="activo">
                                    <option value="1" selected>Sí</option>
                                    <option value="0">No</option>
                                </select>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        </div>

                    </div>
                </div>
            </div>
    </form>

@endsection
