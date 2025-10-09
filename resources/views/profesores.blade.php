@extends("layouts.app")
@section("content")
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3>Lista de Profesores</h3>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#agregarProfesor">
                Agregar Profesor
            </button>
        </div>

        <div class="card-body table-responsive">

            {{-- Mensajes --}}
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('warning'))
                <div class="alert alert-warning">{{ session('warning') }}</div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Documento</th>
                        <th>Email</th>
                        <th>Teléfono</th>
                        <th>Especialidad</th>
                        <th>Fecha Nacimiento</th>
                        <th>Título</th>
                        <th>Fecha Ingreso</th>
                        <th>Salario</th>
                        <th>Escuela</th>
                        <th>grado</th>
                        <th>Activo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($profesores as $profesor)
                        <tr>
                            <td>{{ $profesor->nombre }}</td>
                            <td>{{ $profesor->apellido }}</td>
                            <td>{{ $profesor->documento }}</td>
                            <td>{{ $profesor->email }}</td>
                            <td>{{ $profesor->telefono }}</td>
                            <td>{{ $profesor->especialidad }}</td>
                            <td>{{ $profesor->fecha_nacimiento }}</td>
                            <td>{{ $profesor->titulo }}</td>
                            <td>{{ $profesor->fecha_ingreso }}</td>
                            <td>${{ number_format($profesor->salario ?? 0, 0, ',', '.') }}</td>
                            <td>{{ $profesor->escuela->nombre ?? 'Sin escuela' }}</td>
                            <td>{{ $profesor->grado }}</td>
                            <td>{{ $profesor->activo ? 'Sí' : 'No' }}</td>
                            <td>
                                {{-- Si tu ruta para eliminar es GET (como la tenías), queda así: --}}
                                <a href="{{ route('profesores.destroy', $profesor->id) }}" class="text-danger me-2">Eliminar</a>

                                {{-- Botón editar: abre modal y carga valores en los inputs mediante JS --}}
                                <a href="#"
                                   data-bs-toggle="modal"
                                   data-bs-target="#updateProfesor"
                                   onclick="
                                       document.getElementById('idEdit').value='{{ $profesor->id }}';
                                       document.getElementById('nombreEdit').value='{{ $profesor->nombre }}';
                                       document.getElementById('apellidoEdit').value='{{ $profesor->apellido }}';
                                       document.getElementById('documentoEdit').value='{{ $profesor->documento }}';
                                       document.getElementById('fechaNacimientoEdit').value='{{ $profesor->fecha_nacimiento }}';
                                       document.getElementById('generoEdit').value='{{ $profesor->genero }}';
                                       document.getElementById('emailEdit').value='{{ $profesor->email }}';
                                       document.getElementById('telefonoEdit').value='{{ $profesor->telefono }}';
                                       document.getElementById('direccionEdit').value='{{ $profesor->direccion }}';
                                       document.getElementById('tituloEdit').value='{{ $profesor->titulo }}';
                                       document.getElementById('especialidadEdit').value='{{ $profesor->especialidad }}';
                                       document.getElementById('fechaIngresoEdit').value='{{ $profesor->fecha_ingreso }}';
                                       document.getElementById('salarioEdit').value='{{ $profesor->salario }}';
                                       document.getElementById('gradoEdit').value='{{ $profesor->grado }}'; 
                                       document.getElementById('activoEdit').value='{{ $profesor->activo }}';
                                       document.getElementById('escuelaEdit').value='{{ $profesor->id_escuela ?? '' }}';
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

    <!-- ================== MODAL CREAR PROFESOR ================== -->
    <form method="post" action="{{ route('profesores.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="modal fade" id="agregarProfesor" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5">Agregar Profesor</h1>
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

                        {{-- Contacto --}}
                        <div class="col-md-6">
                            <label for="email" class="form-label">Correo</label>
                            <input type="email" class="form-control" name="email" id="email" required>
                        </div>
                        <div class="col-md-6">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input type="text" class="form-control" name="telefono" id="telefono">
                        </div>
                        <div class="col-md-6">
                            <label for="direccion" class="form-label">Dirección</label>
                            <input type="text" class="form-control" name="direccion" id="direccion">
                        </div>

                        {{-- Información laboral --}}
                        <div class="col-md-6">
                            <label for="titulo" class="form-label">Título</label>
                            <input type="text" class="form-control" name="titulo" id="titulo">
                        </div>
                        <div class="col-md-6">
                            <label for="especialidad" class="form-label">Especialidad</label>
                            <input type="text" class="form-control" name="especialidad" id="especialidad">
                        </div>
                        <div class="col-md-6">
                            <label for="fecha_ingreso" class="form-label">Fecha Ingreso</label>
                            <input type="date" class="form-control" name="fecha_ingreso" id="fecha_ingreso">
                        </div>
                        <div class="col-md-6">
                            <label for="salario" class="form-label">Salario</label>
                            <input type="number" class="form-control" name="salario" id="salario" step="0.01" min="0">
                        </div>

                        {{-- Relación: Escuela --}}
                        <div class="col-md-6">
                            <label for="id_escuela" class="form-label">Escuela</label>
                            <select class="form-control" name="id_escuela" id="id_escuela" required>
                                <option value="">Seleccione una escuela</option>
                                @foreach($escuelas as $escuela)
                                    <option value="{{ $escuela->id }}">{{ $escuela->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="grado" class="form-label">Grado</label>
                            <input type="text" class="form-control" name="grado" id="grado">
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

    <!-- ================== MODAL EDITAR PROFESOR ================== -->
    <form method="post" action="{{ route('profesores.update') }}" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="id" id="idEdit"/>
        <div class="modal fade" id="updateProfesor" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5">Editar Profesor</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body row g-3">
                        <div class="col-md-6">
                            <label for="nombreEdit" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="nombreEdit" name="nombre" required>
                        </div>
                        <div class="col-md-6">
                            <label for="apellidoEdit" class="form-label">Apellido</label>
                            <input type="text" class="form-control" id="apellidoEdit" name="apellido" required>
                        </div>
                        <div class="col-md-6">
                            <label for="documentoEdit" class="form-label">Documento</label>
                            <input type="text" class="form-control" id="documentoEdit" name="documento" required>
                        </div>
                        <div class="col-md-6">
                            <label for="fechaNacimientoEdit" class="form-label">Fecha Nacimiento</label>
                            <input type="date" class="form-control" id="fechaNacimientoEdit" name="fecha_nacimiento">
                        </div>
                        <div class="col-md-6">
                            <label for="generoEdit" class="form-label">Género</label>
                            <select class="form-control" id="generoEdit" name="genero">
                                <option value="">Seleccionar</option>
                                <option value="Masculino">Masculino</option>
                                <option value="Femenino">Femenino</option>
                                <option value="Otro">Otro</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="emailEdit" class="form-label">Correo</label>
                            <input type="email" class="form-control" id="emailEdit" name="email" required>
                        </div>
                        <div class="col-md-6">
                            <label for="telefonoEdit" class="form-label">Teléfono</label>
                            <input type="text" class="form-control" id="telefonoEdit" name="telefono">
                        </div>
                        <div class="col-md-6">
                            <label for="direccionEdit" class="form-label">Dirección</label>
                            <input type="text" class="form-control" id="direccionEdit" name="direccion">
                        </div>

                        <div class="col-md-6">
                            <label for="tituloEdit" class="form-label">Título</label>
                            <input type="text" class="form-control" id="tituloEdit" name="titulo">
                        </div>
                        <div class="col-md-6">
                            <label for="especialidadEdit" class="form-label">Especialidad</label>
                            <input type="text" class="form-control" id="especialidadEdit" name="especialidad">
                        </div>
                        <div class="col-md-6">
                            <label for="fechaIngresoEdit" class="form-label">Fecha Ingreso</label>
                            <input type="date" class="form-control" id="fechaIngresoEdit" name="fecha_ingreso">
                        </div>
                        <div class="col-md-6">
                            <label for="salarioEdit" class="form-label">Salario</label>
                            <input type="number" class="form-control" id="salarioEdit" name="salario" step="0.01" min="0">
                        </div>

                        <div class="col-md-6">
                            <label for="escuelaEdit" class="form-label">Escuela</label>
                            <select class="form-control" id="escuelaEdit" name="id_escuela" required>
                                <option value="">Seleccione una escuela</option>
                                @foreach($escuelas as $escuela)
                                    <option value="{{ $escuela->id }}">{{ $escuela->nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="gradoEdit" class="form-label">Grado</label>
                            <input type="text" class="form-control" id="gradoEdit" name="grado">    
                        </div>

                        <div class="col-md-6">
                            <label for="activoEdit" class="form-label">Activo</label>
                            <select class="form-control" id="activoEdit" name="activo">
                                <option value="1">Sí</option>
                                <option value="0">No</option>
                            </select>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Actualizar</button>
                    </div>

                </div>
            </div>
        </div>
    </form>

@endsection
