@extends('adminlte::page')

@section('title', 'Nuevo Tipo de Vehículo')

@section('content_header')
    <h1 class="font-weight-bold text-dark"><i class="fas fa-plus-circle text-info mr-2"></i>Nuevo Tipo de Vehículo</h1>
@stop

@section('content')
<div class="card shadow-sm border-0" style="border-radius: 12px;">
    <form action="{{ route('tipo_vehiculos.store') }}" method="POST">
        @csrf
        <div class="card-body">
            <div class="form-group">
                <label for="nombre">Descripción del Tipo de Vehículo</label>
                <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror" 
                       placeholder="Ej: Sedán, Camioneta, Montacargas, Motocicleta..." value="{{ old('nombre') }}" required autofocus>
                @error('nombre')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group mt-4">
                <label for="frecuencia_meses">
                    <i class="fas fa-tools text-muted mr-1"></i> Frecuencia de Mantenimiento Preventivo (Meses)
                </label>
                <input type="number" name="frecuencia_meses" id="frecuencia_meses" min="0" max="48"
                       class="form-control @error('frecuencia_meses') is-invalid @enderror" 
                       placeholder="Ej: 6 (Para servicios de afinación/revisión semestral)" value="{{ old('frecuencia_meses', 0) }}">
                <small class="form-text text-muted">Indica cada cuántos meses el sistema debe alertar sobre el mantenimiento preventivo de la unidad. Usa "0" si no aplica.</small>
                @error('frecuencia_meses')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="card-footer bg-white border-top-0">
            <button type="submit" class="btn btn-info px-4 shadow-sm font-weight-bold">
                <i class="fas fa-save mr-1"></i> Guardar Tipo de Vehículo
            </button>
            <a href="{{ route('tipo_vehiculos.index') }}" class="btn btn-outline-secondary ml-2">Cancelar</a>
        </div>
    </form>
</div>
@stop