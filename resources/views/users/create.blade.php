@extends('adminlte::page')

@section('title', 'Registrar Nuevo Usuario')

@section('css')
<style>
    .fieldset-group {
        border: 1px solid #dee2e6;
        border-top: 3px solid #198754;
        padding: 25px 20px 20px 20px;
        margin-bottom: 20px;
        border-radius: 8px;
        background-color: #ffffff;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }

    .fieldset-group legend {
        width: inherit;
        padding: 0 12px;
        border-bottom: none;
        font-size: 1.05em;
        font-weight: 700;
        color: #198754;
        background-color: #ffffff;
        border-radius: 4px;
        margin-top: -15px;
    }

    .fieldset-group i.fa-3x {
        opacity: 0.15;
    }

    .form-group label {
        font-weight: 600;
        color: #495057;
        font-size: 0.9rem;
    }

    .form-control {
        border-radius: 6px;
        font-size: 0.95rem;
    }

    .btn-green-pure {
        background-color: #198754 !important;
        border-color: #198754 !important;
        color: #ffffff !important;
        font-weight: 600;
    }

    .btn-green-pure:hover {
        background-color: #146c43 !important;
        border-color: #146c43 !important;
    }
</style>
@stop



@section('content_header')
<div class="mb-3">
    <h1 class="font-weight-bold mb-1">
        <i class="fas fa-user-plus text-green-pure"></i> Registrar Nuevo Usuario
    </h1>

    <a href="{{ route('users.index') }}" class="btn btn-secondary btn-sm">
        <i class="fas fa-arrow-left"></i> Volver a gestion de usuarios
    </a>
</div>
@stop

@section('content')

@if ($errors->any())
<div class="alert alert-danger alert-dismissible fade show">
    <strong><i class="fas fa-exclamation-triangle"></i> Revisa los datos</strong>
    <button type="button" class="close" data-dismiss="alert">
        <span>&times;</span>
    </button>
    <ul class="mt-2 mb-0">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form action="{{ route('users.store')}}" method="POST">
@csrf

<div class="card card-outline card-green-pure">
    <div class="card-body">
        <div class="row">

            <div class="col-md-6">
                <fieldset class="fieldset-group">
                    <legend><i class="fas fa-user text-green-pure"></i> Información del Usuario</legend>

                    <div class="text-center mb-3 text-muted">
                        <i class="fas fa-user-circle fa-3x"></i>
                        <div class="small mt-1">Datos personales</div>
                    </div>

                    <div class="form-group">
                        <label>Nombre completo </label>
                        <input type="text"
                               name="name"
                               class="form-control"
                               value="{{ old('name') }}"
                               placeholder="Juan Pérez"
                               required>
                    </div>

                    <div class="form-group">
                        <label>Correo electrónico </label>
                        <input type="email"
                               name="email"
                               class="form-control"
                               value="{{ old('email') }}"
                               placeholder="correo@empresa.com"
                               required>
                    </div>

                    <div class="form-group">
                        <label>Contraseña </label>
                        <input type="password"
                               name="password"
                               class="form-control"
                               placeholder="Ingresa Tu contraseña"
                               required>
                    </div>

                    <div class="form-group">
                        <label>Confirmar contraseña </label>
                        <input type="password"
                            name="password_confirmation"
                            class="form-control"
                            placeholder="Repite la contraseña"
                            required>
                    </div>
                </fieldset>
            </div>

            <div class="col-md-6">
                <fieldset class="fieldset-group">
                    <legend><i class="fas fa-user-shield text-green-pure"></i> Rol y Estado</legend>

                    <div class="text-center mb-3 text-muted">
                        <i class="fas fa-users-cog fa-3x"></i>
                        <div class="small mt-1">Configuración del usuario</div>
                    </div>

                    <div class="form-group">
                        <label>Rol </label>
                        <select name="rol" class="form-control" required>
                            <option value="">Seleccione un rol</option>
                            <option value="ADMIN" {{ old('rol') == 'ADMIN' ? 'selected' : '' }}>Administrador</option>
                            <option value="SISTEMAS" {{ old('rol') == 'SISTEMAS' ? 'selected' : '' }}>Sistemas</option>
                            <option value="INVITADO" {{ old('rol') == 'INVITADO' ? 'selected' : '' }}>Invitado/Usuario Comun</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="departamento"><i class="fas fa-building text-green-pure"></i> Departamento</label>
                        <select name="departamento" id="departamento" class="form-control select2 @error('departamento') is-invalid @enderror" required>
                            <option value="" disabled {{ old('departamento', $equipo->departamento ?? '') == '' ? 'selected' : '' }}>
                                -- Seleccione un departamento --
                            </option>

                            @php
                                $deps = [
                                    'ADMINISTRACION', 'ALMACEN', 'CALIDAD', 'COBRANZA', 
                                    'COMPRAS', 'CONTABILIDAD', 'CREDITO', 'CULTURA Y TALENTO', 
                                    'DIRECCION', 'EMBARQUES', 'INVENTARIOS', 'JURIDICO', 
                                    'LOGISTICA', 'SISTEMAS', 'VENTAS'
                                ];
                            @endphp

                            @foreach($deps as $dep)
                                <option value="{{ $dep }}" {{ old('departamento', $equipo->departamento ?? '') == $dep ? 'selected' : '' }}>
                                    {{ $dep }}
                                </option>
                            @endforeach
                        </select>
                        
                        @error('departamento')
                            <span class="invalid-feedback" role="alert">
                                <strong>El departamento es obligatorio.</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Estatus </label>
                        <select name="estatus" class="form-control" required>
                            <option value="ACTIVO" {{ old('estatus') == 'ACTIVO' ? 'selected' : '' }}>
                                Activo
                            </option>
                            <option value="INACTIVO" {{ old('estatus') == 'INACTIVO' ? 'selected' : '' }}>
                                Inactivo
                            </option>
                            <option value="SUSPENDIDO" {{ old('estatus') == 'SUSPENDIDO' ? 'selected' : '' }}>
                                Suspendido
                            </option>
                        </select>
                    </div>
                </fieldset>
            </div>

        </div>
    </div>

    <div class="card-footer text-right">
        <button type="submit" class="btn btn-green-pure btn-lg">
            <i class="fas fa-save"></i> Guardar Usuario
        </button>

        <a href="{{ route('users.index') }}" class="btn btn-secondary btn-lg">
            Cancelar
        </a>
    </div>
</div>
</form>
@stop
