@extends('adminlte::page')

@section('title', 'Nueva Marca')

@section('content_header')
    <h1 class="font-weight-bold text-dark"><i class="fas fa-plus-circle mr-2" style="color: #FFC107;"></i>Nueva Marca</h1>
@stop

@section('content')
<div class="card shadow-sm border-0" style="border-radius: 12px;">
    <form action="{{ route('marcas.store') }}" method="POST">
        @csrf

        <div class="card-body">
            <div class="form-group">
                <label for="nombre">Nombre del Fabricante / Marca</label>
                <input type="text"
                       name="nombre"
                       id="nombre"
                       class="form-control @error('nombre') is-invalid @enderror"
                       placeholder="Ej: Lenovo, Dell, HP, Chevrolet..."
                       value="{{ old('nombre') }}"
                       required>

                @error('nombre')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="tipo">Tipo de Marca</label>
                <select name="tipo"
                        id="tipo"
                        class="form-control @error('tipo') is-invalid @enderror"
                        required>
                    <option value="" selected disabled>Selecciona el tipo...</option>
                    <option value="TI" {{ old('tipo') == 'TI' ? 'selected' : '' }}>
                        TI / Equipo de cómputo
                    </option>
                    <option value="AUTO" {{ old('tipo') == 'AUTO' ? 'selected' : '' }}>
                        AUTO / Vehículos
                    </option>
                </select>

                <small class="text-muted">
                    Selecciona si esta marca se usará para equipos de cómputo o para vehículos.
                </small>

                @error('tipo')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="card-footer bg-white border-top-0">
            <button type="submit" class="btn px-4 shadow-sm font-weight-bold" style="background-color: #FFC107; border-color: #FFC107; color: #212529;">
                <i class="fas fa-save mr-1"></i> Guardar Marca
            </button>

            <a href="{{ route('marcas.index') }}" class="btn btn-outline-secondary ml-2">
                Cancelar
            </a>
        </div>
    </form>
</div>
@stop