@extends('adminlte::page')

@section('title', 'Mantenimiento de Activo')


@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1><i class="fas fa-tools mr-2 text-warning"></i>Bitácora de Mantenimiento</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('vehiculos.index') }}">Inventario</a></li>
                    <li class="breadcrumb-item active">Añadir Trabajo</li>
                </ol>
            </div>
        </div>
    </div>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Tarjeta de Información de la Unidad -->
        <div class="col-md-4">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">Datos del Vehículo</h3>
                </div>
                <div class="card-body box-profile">
                    <div class="text-center mb-3">
                        <i class="fas fa-car fa-3x text-secondary"></i>
                    </div>
                    <h3 class="profile-username text-center">{{ $vehiculo->marca->nombre ?? 'Vehículo' }}</h3>
                    <p class="text-muted text-center">Placas: <strong>{{ $vehiculo->placas ?? 'S/P' }}</strong></p>

                    <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item">
                            <b>ID Interno:</b> <a class="float-right">#{{ $vehiculo->id }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Serie:</b> <a class="float-right">{{ $vehiculo->serie ?? 'N/D' }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Condición actual:</b> 
                            <span class="badge badge-success float-right">Operativo</span>
                        </li>
                    </ul>
                    <a href="{{ route('vehiculos.index') }}" class="btn btn-default btn-block"><b>Volver al Inventario</b></a>
                </div>
            </div>
        </div>

        <!-- Formulario de Registro -->
        <div class="col-md-8">
            <div class="card card-warning card-outline">
                <div class="card-header">
                    <h3 class="card-title">Registrar Nuevo Trabajo / Incidencia</h3>
                </div>
                <div class="card-body">
                    <!-- El formulario apunta a la ruta POST que acabamos de crear -->
                    <form action="{{ route('vehiculos.addwork.store', $vehiculo) }}" method="POST">
                        @csrf
                        
                        {{-- Aquí pondremos los campos del formulario en el siguiente paso --}}
                        <p class="text-muted">Formulario listo para recibir los campos de captura...</p>

                        <button type="submit" class="btn btn-warning font-weight-bold">Guardar Cambios</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@stop