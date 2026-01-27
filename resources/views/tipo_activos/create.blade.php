@extends('adminlte::page')

@section('title', 'Nuevo Tipo de Activo')

@section('content_header')
    <h1 class="font-weight-bold text-dark"><i class="fas fa-plus-circle text-danger mr-2"></i>Nuevo Tipo de Activo</h1>
@stop

@section('content')
<div class="card shadow-sm border-0" style="border-radius: 12px;">
    <form action="{{ route('tipo_activos.store') }}" method="POST">
        @csrf
        <div class="card-body">
            <div class="form-group">
                <label for="nombre">Descripción del Tipo de Activo</label>
                <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror" 
                       placeholder="Ej: Laptop, Monitor, Impresora..." value="{{ old('nombre') }}" required autofocus>
                @error('nombre')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="card-footer bg-white border-top-0">
            <button type="submit" class="btn btn-danger px-4 shadow-sm font-weight-bold">
                <i class="fas fa-save mr-1"></i> Guardar Tipo de Activo
            </button>
            <a href="{{ route('tipo_activos.index') }}" class="btn btn-outline-secondary ml-2">Cancelar</a>
        </div>
    </form>
</div>
@stop