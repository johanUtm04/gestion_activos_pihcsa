@extends('adminlte::page')

@section('title', 'Nuevo Departamento')

@section('content_header')
    <h1 class="font-weight-bold text-dark"><i class="fas fa-plus-circle mr-2" style="color: #FD7E14;"></i>Nuevo Departamento</h1>
@stop

@section('content')
<div class="card shadow-sm border-0" style="border-radius: 12px;">
    <form action="{{ route('departamentos.store') }}" method="POST">
        @csrf
        <div class="card-body">
            <div class="form-group">
                <label for="nombre">Nombre del Departamento</label>
                <input type="text" name="nombre" id="nombre"
                       class="form-control @error('nombre') is-invalid @enderror"
                       placeholder="Ej: SISTEMAS, VENTAS, LOGISTICA..."
                       value="{{ old('nombre') }}" required autofocus>
                @error('nombre')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="card-footer bg-white border-top-0">
            <button type="submit" class="btn px-4 shadow-sm font-weight-bold" style="background-color: #FD7E14; border-color: #FD7E14; color: #ffffff;">
                <i class="fas fa-save mr-1"></i> Guardar Departamento
            </button>
            <a href="{{ route('departamentos.index') }}" class="btn btn-outline-secondary ml-2">Cancelar</a>
        </div>
    </form>
</div>
@stop
