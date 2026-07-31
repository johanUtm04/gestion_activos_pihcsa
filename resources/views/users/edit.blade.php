@extends('adminlte::page')

@section('title', 'Editar Equipo | Activos TI')

@section('css')
<style>
    .section-title {
        border-bottom: 2px solid #007bff; 
        padding-bottom: 5px;
        margin-bottom: 15px;
        color: #17a2b8; 
        font-weight: 600;
    }

    .data-item {
        margin-bottom: 10px;
        padding-bottom: 5px;
    }

    .data-item:last-child {
        border-bottom: none;
    }

    .data-label {
        font-weight: 600;
        color: #495057;
    }

    .component-group {
        border: 1px solid #dee2e6;
        border-radius: .25rem;
        padding: 15px;
        margin-bottom: 20px;
        background-color: #f8f9fa;
    }
</style>
@stop

@section('content_header')
    <h1 class="font-weight-bold text-center">
        <i class="fas fa-desktop text-primary"></i> 
        Edición del Usuario: {{ strtoupper($user->name) }}
    </h1>
    <a href="{{ route('users.index') }}" class="btn btn-secondary btn-sm">
        <i class="fas fa-arrow-left"></i> Volver a gestion de usuarios
    </a>
@stop

@section('content')
    <div class="container-fluid">
        <div class="row">

            <!-- columna Izquierda -->
            <div class="col-md-5">
                <div class="card card-outline card-info">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-clipboard-list"></i> Detalle y Estado Actual
                        </h3>
                    </div>
                    <!-- Comienzo a escribir la informacion (Lado Derecho) -->
                <fieldset class="border p-3 mb-4">

                    <legend class="w-auto px-2 text-primary"><i class="fas fa-info-circle"></i> Especificaciones Generales</legend>

                        {{-- Datos Principales --}}
                        <div class="data-item">
                            <span class="data-label">
                                <i class="fas fa-id-badge"></i> ID del Usuario:
                            </span> 
                            <span class="float-right">{{ $user->id }}</span>
                        </div>

                        <div class="data-item">
                            <span class="data-label">
                                <i class="fas fa-user"></i> Nombre:
                            </span> 
                            <span class="float-right">{{ $user->name }}</span>
                        </div>

                        <div class="data-item">
                            <span class="data-label">
                                <i class="fas fa-envelope"></i> Correo Electrónico:
                            </span> 
                            <span class="float-right text-bold">{{ $user->email }}</span>
                        </div>

                        <div class="data-item">
                            <span class="data-label">
                                <i class="fas fa-building"></i> Departamento:
                            </span> 
                            <span class="float-right">{{ $user->departamento }}</span>
                        </div>

                        <div class="data-item">
                            <span class="data-label">
                                <i class="fas fa-toggle-on"></i> Estatus:
                            </span> 
                            <span class="float-right">{{ $user->estatus }}</span>
                        </div>

                        <div class="data-item">
                            <span class="data-label">
                            <i class="fas fa-user-shield"></i> Rol del Sistema:
                            </span> 
                            <span class="float-right badge badge-primary">{{ $user->rol ?? 'SIN ROL' }}</span>
                        </div>

                    </div> {{-- /card-body --}}
                </fieldset>
            </div> {{-- /col-md-5 --}}


        <!-- Inicio columna Derecha -->
            <div class="col-md-6">
                <div class="card card-outline card-primary">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-pen-square"></i> Modificación de Datos
                        </h3>
                    </div>

                    <!-- Formulario -->
                     <form action="{{ route('users.update', $user) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <fieldset class="border p-3 mb-4">
                                <legend class="w-auto px-2 text-primary"><i class="fas fa-info-circle"></i> Datos Base</legend>

                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="name"><i class="fas fa-laptop"></i> Nombre: </label>
                                        <input type="text" name="name" id="name" class="form-control"
                                            value="{{ old('name', $user->name) }}">
                                    </div>

                                <div class="form-group col-md-6">
                                    <label for="estatus"><i class="fas fa-toggle-on"></i> Estatus:</label>
                                    <select name="estatus" id="estatus" class="form-control @error('estatus') is-invalid @enderror">
                                        <option value="ACTIVO" {{ old('estatus', $user->estatus) == 'ACTIVO' ? 'selected' : '' }}>
                                            ACTIVO
                                        </option>
                                        <option value="INACTIVO" {{ old('estatus', $user->estatus) == 'INACTIVO' ? 'selected' : '' }}>
                                            INACTIVO
                                        </option>
                                        <option value="SUSPENDIDO" {{ old('estatus', $user->estatus) == 'SUSPENDIDO' ? 'selected' : '' }}>
                                            SUSPENDIDO
                                        </option>
                                    </select>
                                    
                                    @error('estatus')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="rol"><i class="fas fa-user-tag"></i> Asignar Rol:</label>
                                    <select name="rol" id="rol" class="form-control @error('rol') is-invalid @enderror">
                                    <option value="admin" {{ old('rol', $user->rol) == 'admin' ? 'selected' : '' }}>
                                    admin
                                    </option>
                                    <option value="sistemas" {{ old('rol', $user->rol) == 'sistemas' ? 'selected' : '' }}>
                                    sistemas
                                    </option>
                                    <option value="invitado" {{ old('rol', $user->rol) == 'invitado' ? 'selected' : '' }}>
                                    invitado
                                    </option>
                                    </select>

                                    @error('rol')
                                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                </div>

                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="email"><i class="fas fa-envelope text-green-pure"></i> Correo Electrónico:</label>
                                        <input type="text" name="email" id="email" class="form-control"
                                        value="{{ old('email', $user->email) }}">
                                    </div>

                                <div class="form-group col-md-6">
                                    <label for="departamento"><i class="fas fa-building text-green-pure"></i> Departamento: </label>
                                    <select name="departamento" id="departamento" class="form-control @error('departamento') is-invalid @enderror">
                                        <option value="" disabled {{ old('departamento', $user->departamento) == '' ? 'selected' : '' }}>-- Seleccione --</option>
                                        
                                        @php
                                            $deps = [
                                                'ADMINISTRACION', 'ALMACEN', 'CALIDAD', 'COBRANZA', 
                                                'COMPRAS', 'CONTABILIDAD', 'CREDITO', 'CULTURA Y TALENTO', 
                                                'DIRECCION', 'EMBARQUES', 'INVENTARIOS', 'JURIDICO', 
                                                'LOGISTICA', 'SISTEMAS', 'VENTAS'
                                            ];
                                        @endphp

                                        @foreach($deps as $dep)
                                            <option value="{{ $dep }}" {{ old('departamento', $user->departamento) == $dep ? 'selected' : '' }}>
                                                {{ $dep }}
                                            </option>
                                        @endforeach
                                    </select>

                                    @error('departamento')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <small class="text-muted">Departamento oficial al que el usuario reporta actividades.</small>
                                </div>
                                </div>

                            </fieldset>
                            {{-- BOTÓN FINAL --}}
                            <div class="mt-4">
                                <button type="submit" class="btn btn-green-pure btn-lg btn-block">
                                    <i class="fas fa-database"></i> Aplicar Cambios y Registrar Historial
                                </button>
                            </div>
                        </form>
                    </div> {{-- /card-body --}}
                </div> {{-- /card --}}
            </div> {{-- /col-md-7 --}}




        </div> {{-- row --}}
    </div> {{-- container --}}

@stop