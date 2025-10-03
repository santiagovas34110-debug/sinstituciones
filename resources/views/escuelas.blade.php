@extends("layouts.app")
@section("content")
    <div class ="card">
        <div class ="card-header d-flex justify-content-between aling-items-center">
            <h3> Lista de escuela </h3>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#agregarEscuela">
                Agregar Escuela (s)
            </button>
        </div>
        <div class="card-body table-responsive">

            <table class="table table-striped">
                <theand>
                    <tr>
                        <th>Nombre</th>
                        <th>Ubicacion </th>
                        <th>Contacto </th>
                        <th>Cargo del contacto </th>  
                        <th>Tipo de  documento </th>
                        <th>Documento del contacto </th>
                        <th>TLF. del contacto </th>
                        <th>E-Mail del contacto </th>
                        <th>Codigo DANE </th>
                        <th>NIT </th>
                        <th>Acciones</th>
                    <tr>
                    </thead>
                    <tbody>
                        @foreach($escuelas as $escuela)
                        <tr>
                            <td>{{ $escuela->nombre }}</td>
                            <td>{{ $escuela->ubicacion }}</td>
                            <td>{{ $escuela->contacto_nombre }}</td>
                            <td>{{ $escuela->contacto_rol }}</td>
                            <td>{{ $escuela->contacto_tipo_documento}}</td>
                            <td>{{ $escuela->contacto_documento }}</td>
                            <td>{{ $escuela->contacto_telefono }}</td>
                            <td>{{ $escuela->contacto_email }}</td>
                            <td>{{ $escuela->codigo_dane }}</td>
                            <td>{{ $escuela->nit }}</td>
                            <td>
                                <a href="{{ route("escuelas.delete",$escuela->id) }}">Eliminar</a> &nbsp;
                                <a href="#" data-bs-toggle="modal" data-bs-target="#updateEscuela" 
                                   onclick="document.getElementById('idEdit').value='{{ $escuela->id }}';
                                            document.getElementById('nombreEdit').value='{{ $escuela->nombre }}';
                                            document.getElementById('ubicacionEdit').value='{{ $escuela->ubicacion }}';
                                            document.getElementById('contacto_nombreEdit').value='{{ $escuela->contacto_nombre }}';
                                            document.getElementById('contacto_rolEdit').value='{{ $escuela->contacto_rol }}';
                                            document.getElementById('contacto_telefonoEdit').value='{{ $escuela->contacto_telefono }}';
                                            document.getElementById('contacto_emailEdit').value='{{ $escuela->contacto_email }}';
                                            document.getElementById('contacto_documentoEdit').value='{{ $escuela->contacto_documento }}';
                                            document.getElementById('contacto_tipo_documentoEdit').value='{{ $escuela->contacto_tipo_documento }}';
                                            document.getElementById('codigo_daneEdit').value='{{ $escuela->codigo_dane }}';
                                            document.getElementById('nitEdit').value='{{ $escuela->nit }}';"

                                   >Editar Escuela
                                </a>
                            </td>
                        </tr>
                        @endforeach
                                                
                        
                    <tbody>
            </table>
        </div>
    </div>
    <form method="post" action="{{route("escuelas.create")}}" enctype="multipart/form-data">
        @csrf
        <div class="modal fade" id="agregarEscuela" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Crear Escuelas(s)</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Nombre -->
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Ingrese el nombre">
                        </div>

                        <!-- Ubicación -->
                        <div class="mb-3">
                            <label for="ubicacion" class="form-label">Ubicación</label>
                            <input type="text" class="form-control" id="ubicacion" name="ubicacion" placeholder="Ingrese la ubicación">
                        </div>

                        <hr class="my-4">

                        <h6 class="mb-3">Datos de Contacto</h6>

                        <!-- Contacto Nombre -->
                        <div class="mb-3">
                            <label for="contacto_nombre" class="form-label">Nombre del Contacto</label>
                            <input type="text" class="form-control" id="contacto_nombre" name="contacto_nombre" placeholder="Ingrese el nombre del contacto">
                        </div>

                        <!-- Contacto Rol -->
                        <div class="mb-3">
                            <label for="contacto_rol" class="form-label">Rol del Contacto</label>
                            <input type="text" class="form-control" id="contacto_rol" name="contacto_rol" placeholder="Ingrese el rol del contacto">
                        </div>

                        <!-- Contacto Teléfono -->
                        <div class="mb-3">
                            <label for="contacto_telefono" class="form-label">Teléfono del Contacto</label>
                            <input type="text" class="form-control" id="contacto_telefono" name="contacto_telefono" placeholder="Ingrese el teléfono del contacto">
                        </div>

                        <!-- Contacto Email -->
                        <div class="mb-3">
                            <label for="contacto_email" class="form-label">Email del Contacto</label>
                            <input type="email" class="form-control" id="contacto_email" name="contacto_email" placeholder="Ingrese el email del contacto">
                        </div>

                        <!-- Contacto Documento -->
                        <div class="mb-3">
                            <label for="contacto_documento" class="form-label">Documento del Contacto</label>
                            <input type="text" class="form-control" id="contacto_documento" name="contacto_documento" placeholder="Ingrese el documento del contacto">
                        </div>

                        <!-- Tipo de Documento -->
                        <div class="mb-3">
                            <label for="contacto_tipo_documento" class="form-label">Tipo de Documento</label>
                            <input type="text" class="form-control" id="contacto_tipo_documento" name="contacto_tipo_documento" placeholder="Ingrese el tipo de documento">
                        </div>
                        <!-- Codigo DANE -->
                        <div class="mb-3">
                            <label for="codigo_dane" class="form-label">Codigo DANE</label>
                            <input type="text" class="form-control" id="codigo_dane" name="codigo_dane" placeholder="Ingrese el codigo DANE">  
                        </div>
                        <!-- NIT -->
                        <div class="mb-3">
                            <label for="nit" class="form-label">NIT</label>
                            <input type="text" class="form-control" id="nit" name="nit" placeholder="Ingrese el NIT">
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

    <form method="post" action="{{route("escuelas.update")}}" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="id" id="idEdit"/>
        <div class="modal fade" id="updateEscuela" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Crear Escuelas(s)</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Nombre -->
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="nombreEdit" name="nombre" placeholder="Ingrese el nombre">
                        </div>

                        <!-- Ubicación -->
                        <div class="mb-3">
                            <label for="ubicacion" class="form-label">Ubicación</label>
                            <input type="text" class="form-control" id="ubicacionEdit" name="ubicacion" placeholder="Ingrese la ubicación">
                        </div>

                        <hr class="my-4">

                        <h6 class="mb-3">Datos de Contacto</h6>

                        <!-- Contacto Nombre -->
                        <div class="mb-3">
                            <label for="contacto_nombre" class="form-label">Nombre del Contacto</label>
                            <input type="text" class="form-control" id="contacto_nombreEdit" name="contacto_nombre" placeholder="Ingrese el nombre del contacto">
                        </div>

                        <!-- Contacto Rol -->
                        <div class="mb-3">
                            <label for="contacto_rol" class="form-label">Rol del Contacto</label>
                            <input type="text" class="form-control" id="contacto_rolEdit" name="contacto_rol" placeholder="Ingrese el rol del contacto">
                        </div>

                        <!-- Contacto Teléfono -->
                        <div class="mb-3">
                            <label for="contacto_telefono" class="form-label">Teléfono del Contacto</label>
                            <input type="text" class="form-control" id="contacto_telefonoEdit" name="contacto_telefono" placeholder="Ingrese el teléfono del contacto">
                        </div>

                        <!-- Contacto Email -->
                        <div class="mb-3">
                            <label for="contacto_email" class="form-label">Email del Contacto</label>
                            <input type="email" class="form-control" id="contacto_emailEdit" name="contacto_email" placeholder="Ingrese el email del contacto">
                        </div>

                        <!-- Contacto Documento -->
                        <div class="mb-3">
                            <label for="contacto_documento" class="form-label">Documento del Contacto</label>
                            <input type="text" class="form-control" id="contacto_documentoEdit" name="contacto_documento" placeholder="Ingrese el documento del contacto">
                        </div>

                        <!-- Tipo de Documento -->
                        <div class="mb-3">
                            <label for="contacto_tipo_documento" class="form-label">Tipo de Documento</label>
                            <input type="text" class="form-control" id="contacto_tipo_documentoEdit" name="contacto_tipo_documento" placeholder="Ingrese el tipo de documento">
                        </div>
                        <!-- Codigo DANE -->
                        <div class="mb-3">
                            <label for="codigo_dane" class="form-label">Codigo DANE</label>
                            <input type="text" class="form-control" id="codigo_dane" name="codigo_dane" placeholder="Ingrese el codigo DANE">  
                        </div>
                        <!-- NIT -->
                        <div class="mb-3">
                            <label for="nit" class="form-label">NIT</label>
                            <input type="text" class="form-control" id="nit" name="nit" placeholder="Ingrese el NIT">
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




