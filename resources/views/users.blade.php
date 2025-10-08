@extends('layouts.app')
@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3>Lista de Usuarios</h3>

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

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $u)
                        <tr>
                            <td>{{ $u->name }}</td>
                            <td>{{ $u->email }}</td>
                            <td>
                                {{-- Si tu ruta para eliminar es GET (como la tenías), queda así: --}}
                                <a href="#" class="text-danger me-2">Eliminar</a>

                                {{-- Botón editar: abre modal y carga valores en los inputs mediante JS --}}
                                <a href="#">
                                    Editar
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="card mt-5">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3>Lista de Tokens</h3>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#crearToken">
                Crear Token de registro
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

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Token</th>
                        <th>Link</th>
                        <th>Status</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tokens as $t)
                        <tr>
                            <td>{{ $t->nombre }}</td>
                            <td>{{ $t->email }}</td>
                            <td>{{ $t->token }}</td>
                            <td><a
                                    href="{{ env('APP_URL') }}/register?token={{ $t->token }}">{{ env('APP_URL') }}/register?token={{ $t->token }}</a>
                            <td>
                                @if ($t->status)
                                    <span class="badge badge-success bg-success">Usado</span>
                                @else
                                    <span class="badge badge-warning bg-warning">Pendiente</span>
                                @endif
                            </td>
                            <td>
                                {{-- Si tu ruta para eliminar es GET (como la tenías), queda así: --}}
                                <a href="#" class="text-danger me-2">Eliminar</a>

                                {{-- Botón editar: abre modal y carga valores en los inputs mediante JS --}}
                                <a href="#">
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
    <form method="post" action="{{ route('tokens.create') }}" enctype="multipart/form-data">
        @csrf
        <div class="modal fade" id="crearToken" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5">Crear Token de registro</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body row g-3">
                        <div class="form-group">
                            <label>Nombre de la persona</label>
                            <input type="text" class="form-control" name="nombre" required />
                        </div>
                        <div class="form-group">
                            <label>Correo de la persona</label>
                            <input type="text" class="form-control" name="email" required />
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
