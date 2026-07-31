@extends('adminlte::page')

@section('title', 'Editar Usuario | Activos TI')

@section('css')
<style>
    :root {
        --green-primary: #198754;
        --green-dark: #146c43;
        --green-soft: #d1e7dd;
        --green-focus: rgba(25, 135, 84, 0.18);
    }

    .section-title {
        border-bottom: 2px solid var(--green-primary);
        padding-bottom: 5px;
        margin-bottom: 15px;
        color: var(--green-primary);
        font-weight: 600;
    }

    .data-item {
        margin-bottom: 10px;
        padding-bottom: 8px;
        border-bottom: 1px solid #eeeeee;
    }

    .data-item:last-child {
        border-bottom: none;
    }

    .data-label {
        font-weight: 600;
        color: #495057;
    }

    fieldset.border {
        border: 1px solid #dee2e6 !important;
        border-radius: 8px;
    }

    /* Texto verde */
    .text-green-pure {
        color: var(--green-primary) !important;
    }

    /* Tarjetas verdes */
    .card-green-pure {
        border-top: 3px solid var(--green-primary) !important;
    }

    /* Botón verde */
    .btn-green-pure {
        background-color: var(--green-primary) !important;
        border-color: var(--green-primary) !important;
        color: #ffffff !important;
        font-weight: 600;
    }

    .btn-green-pure:hover,
    .btn-green-pure:focus,
    .btn-green-pure:active {
        background-color: var(--green-dark) !important;
        border-color: var(--green-dark) !important;
        color: #ffffff !important;
    }

    /* Badge verde */
    .badge-green-pure {
        background-color: var(--green-primary);
        color: #ffffff;
        font-weight: 600;
    }

    /* Focus verde en inputs */
    .form-control:focus {
        border-color: var(--green-primary);
        box-shadow: 0 0 0 0.2rem var(--green-focus);
    }

    /* Checkbox o select con color verde */
    select.form-control:focus {
        border-color: var(--green-primary);
    }
</style>
@stop

@section('content_header')
    <h1 class="font-weight-bold text-center">
        <i class="fas fa-user-edit text-green-pure"></i>
        Edición del Usuario: {{ strtoupper($user->name) }}
    </h1>

    <a href="{{ route('users.index') }}"
       class="btn btn-secondary btn-sm shadow-sm">
        <i class="fas fa-arrow-left"></i>
        Volver a gestión de usuarios
    </a>
@stop

@section('content')
    <div class="container-fluid">
        <div class="row">

            {{-- Columna izquierda --}}
            <div class="col-md-5">
                <div class="card card-outline card-green-pure shadow-sm">

                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-clipboard-list text-green-pure"></i>
                            Detalle y Estado Actual
                        </h3>
                    </div>

                    <div class="card-body">
                        <fieldset class="border p-3 mb-0">

                            <legend class="w-auto px-2 text-green-pure font-weight-bold">
                                <i class="fas fa-info-circle"></i>
                                Especificaciones Generales
                            </legend>

                            <div class="data-item">
                                <span class="data-label">
                                    <i class="fas fa-id-badge text-green-pure"></i>
                                    ID del Usuario:
                                </span>

                                <span class="float-right font-weight-bold">
                                    {{ $user->id }}
                                </span>
                            </div>

                            <div class="data-item">
                                <span class="data-label">
                                    <i class="fas fa-user text-green-pure"></i>
                                    Nombre:
                                </span>

                                <span class="float-right">
                                    {{ $user->name }}
                                </span>
                            </div>

                            <div class="data-item">
                                <span class="data-label">
                                    <i class="fas fa-envelope text-green-pure"></i>
                                    Correo Electrónico:
                                </span>

                                <span class="float-right font-weight-bold">
                                    {{ $user->email }}
                                </span>
                            </div>

                            <div class="data-item">
                                <span class="data-label">
                                    <i class="fas fa-building text-green-pure"></i>
                                    Departamento:
                                </span>

                                <span class="float-right">
                                    {{ $user->departamento ?? 'SIN DEPARTAMENTO' }}
                                </span>
                            </div>

                            <div class="data-item">
                                <span class="data-label">
                                    <i class="fas fa-toggle-on text-green-pure"></i>
                                    Estatus:
                                </span>

                                <span class="float-right">
                                    @if($user->estatus === 'ACTIVO')
                                        <span class="badge badge-success">
                                            {{ $user->estatus }}
                                        </span>
                                    @elseif($user->estatus === 'SUSPENDIDO')
                                        <span class="badge badge-warning">
                                            {{ $user->estatus }}
                                        </span>
                                    @else
                                        <span class="badge badge-secondary">
                                            {{ $user->estatus }}
                                        </span>
                                    @endif
                                </span>
                            </div>

                            <div class="data-item">
                                <span class="data-label">
                                    <i class="fas fa-user-shield text-green-pure"></i>
                                    Rol del Sistema:
                                </span>

                                <span class="float-right badge badge-green-pure">
                                    {{ strtoupper($user->rol ?? 'SIN ROL') }}
                                </span>
                            </div>

                        </fieldset>
                    </div>
                </div>
            </div>

            {{-- Columna derecha --}}
            <div class="col-md-7">
                <div class="card card-outline card-green-pure shadow-sm">

                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-pen-square text-green-pure"></i>
                            Modificación de Datos
                        </h3>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('users.update', $user) }}"
                              method="POST">

                            @csrf
                            @method('PUT')

                            <fieldset class="border p-4 mb-4">

                                <legend class="w-auto px-2 text-green-pure font-weight-bold">
                                    <i class="fas fa-database"></i>
                                    Datos Base
                                </legend>

                                <div class="row">

                                    {{-- Nombre --}}
                                    <div class="form-group col-md-6">
                                        <label for="name">
                                            <i class="fas fa-user text-green-pure"></i>
                                            Nombre:
                                        </label>

                                        <input
                                            type="text"
                                            name="name"
                                            id="name"
                                            class="form-control @error('name') is-invalid @enderror"
                                            value="{{ old('name', $user->name) }}"
                                            required
                                        >

                                        @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    {{-- Estatus --}}
                                    <div class="form-group col-md-6">
                                        <label for="estatus">
                                            <i class="fas fa-toggle-on text-green-pure"></i>
                                            Estatus:
                                        </label>

                                        <select
                                            name="estatus"
                                            id="estatus"
                                            class="form-control @error('estatus') is-invalid @enderror"
                                            required
                                        >
                                            <option
                                                value="ACTIVO"
                                                {{ old('estatus', $user->estatus) === 'ACTIVO' ? 'selected' : '' }}
                                            >
                                                ACTIVO
                                            </option>

                                            <option
                                                value="INACTIVO"
                                                {{ old('estatus', $user->estatus) === 'INACTIVO' ? 'selected' : '' }}
                                            >
                                                INACTIVO
                                            </option>

                                            <option
                                                value="SUSPENDIDO"
                                                {{ old('estatus', $user->estatus) === 'SUSPENDIDO' ? 'selected' : '' }}
                                            >
                                                SUSPENDIDO
                                            </option>
                                        </select>

                                        @error('estatus')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    {{-- Rol --}}
                                    <div class="form-group col-md-6">
                                        <label for="rol">
                                            <i class="fas fa-user-tag text-green-pure"></i>
                                            Asignar Rol:
                                        </label>

                                        <select
                                            name="rol"
                                            id="rol"
                                            class="form-control @error('rol') is-invalid @enderror"
                                            required
                                        >
                                            <option
                                                value="admin"
                                                {{ old('rol', $user->rol) === 'admin' ? 'selected' : '' }}
                                            >
                                                Administrador
                                            </option>

                                            <option
                                                value="sistemas"
                                                {{ old('rol', $user->rol) === 'sistemas' ? 'selected' : '' }}
                                            >
                                                Sistemas
                                            </option>

                                            <option
                                                value="invitado"
                                                {{ old('rol', $user->rol) === 'invitado' ? 'selected' : '' }}
                                            >
                                                Invitado
                                            </option>
                                        </select>

                                        @error('rol')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    {{-- Correo --}}
                                    <div class="form-group col-md-6">
                                        <label for="email">
                                            <i class="fas fa-envelope text-green-pure"></i>
                                            Correo Electrónico:
                                        </label>

                                        <input
                                            type="email"
                                            name="email"
                                            id="email"
                                            class="form-control @error('email') is-invalid @enderror"
                                            value="{{ old('email', $user->email) }}"
                                            required
                                        >

                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    {{-- Departamento --}}
                                    <div class="form-group col-md-12">
                                        <label for="departamento">
                                            <i class="fas fa-building text-green-pure"></i>
                                            Departamento:
                                        </label>

                                        <select
                                            name="departamento"
                                            id="departamento"
                                            class="form-control @error('departamento') is-invalid @enderror"
                                            required
                                        >
                                            <option value="" disabled>
                                                -- Seleccione un departamento --
                                            </option>

                                            @php
                                                $deps = [
                                                    'ADMINISTRACION',
                                                    'ALMACEN',
                                                    'CALIDAD',
                                                    'COBRANZA',
                                                    'COMPRAS',
                                                    'CONTABILIDAD',
                                                    'CREDITO',
                                                    'CULTURA Y TALENTO',
                                                    'DIRECCION',
                                                    'EMBARQUES',
                                                    'INVENTARIOS',
                                                    'JURIDICO',
                                                    'LOGISTICA',
                                                    'SISTEMAS',
                                                    'VENTAS'
                                                ];
                                            @endphp

                                            @foreach($deps as $dep)
                                                <option
                                                    value="{{ $dep }}"
                                                    {{ old('departamento', $user->departamento) === $dep ? 'selected' : '' }}
                                                >
                                                    {{ $dep }}
                                                </option>
                                            @endforeach
                                        </select>

                                        @error('departamento')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror

                                        <small class="text-muted">
                                            Departamento oficial al que el usuario reporta actividades.
                                        </small>
                                    </div>

                                </div>
                            </fieldset>

                            <div class="mt-4">
                                <button
                                    type="submit"
                                    class="btn btn-green-pure btn-lg btn-block shadow"
                                >
                                    <i class="fas fa-save"></i>
                                    Aplicar Cambios y Registrar Historial
                                </button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
@stop