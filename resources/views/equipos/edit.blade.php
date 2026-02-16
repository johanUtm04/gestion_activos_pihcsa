@extends('adminlte::page')

@section('title', 'Editar Equipo')

@section('content_header')
<div class="mb-3">
    <h1 class="font-weight-bold mb-1">
        <i class="fas fa-pen-square text-warning"></i> Editar Activo
    </h1>
    <a href="{{ url()->previous() }}" class="btn btn-secondary btn-sm">
        <i class="fas fa-arrow-left"></i> Volver al inventario
    </a>
</div>
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('css/equipos/edit.css') }}">
@stop

@section('content')
    <div class="container-fluid">
        <div class="row">
            {{-- COLUMNA IZQUIERDA: Solo lectura --}}
            <div class="col-md-5">
                @include('equipos.partials.edit.info-actual')
            </div>

            {{-- COLUMNA DERECHA: Formulario --}}
            <div class="col-md-7">
                <form action="{{ route('equipos.update', $equipo) }}" method="POST" id="formEditarEquipo">
                    @csrf
                    @method('PUT')
                    
                    @include('equipos.partials.edit.form-datos-base')
                    @include('equipos.partials.edit.form-asignacion')
                    @include('equipos.partials.edit.form-componentes')

                    <div class="card-footer text-center bg-transparent">
                        <button type="submit" id="btnPrevisualizar" class="btn btn-success">
                            <i class="fas fa-save mr-1"></i> Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('js')

    <!-- // console.log("Prueba rápida: El JS en el Blade sí funciona"); -->
    <script src="{{ asset('js/equipos/edit.js') }}"></script>

@stop