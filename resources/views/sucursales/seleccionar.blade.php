@extends('adminlte::page')

@section('title', 'Seleccionar Base | PIHCSA')

@section('content_header')
@stop

@section('content')
<div class="row justify-content-center align-items-center" style="min-height: 75vh;">
    <div class="col-md-8 col-lg-6">

        <div class="card shadow-lg border-0" style="border-radius: 18px; overflow: hidden;">
            <div class="card-header bg-dark text-white text-center py-4">
                <img src="{{ asset('vendor/adminlte/dist/img/logohd.png') }}"
                     alt="PIHCSA"
                     style="height: 70px; width: auto;"
                     class="mb-3">

                <h3 class="font-weight-bold mb-1">
                    Selección de Base de Datos
                </h3>

                <p class="mb-0 text-muted">
                    Antes de continuar, selecciona el entorno operativo donde trabajarás.
                </p>
            </div>

            <form action="{{ route('sucursal.guardarSeleccion') }}" method="POST">
                @csrf

                <div class="card-body p-4">

                    @if($errors->any())
                        <div class="alert alert-danger">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <div class="form-group">
                        <label class="font-weight-bold text-uppercase text-muted">
                            <i class="fas fa-database mr-1"></i> Base / Sucursal
                        </label>

                        <select name="sucursal"
                                class="form-control form-control-lg @error('sucursal') is-invalid @enderror"
                                required>
                            <option value="">Selecciona una base...</option>

                            @foreach($sucursales as $sucursal)
                                <option value="{{ $sucursal->clave }}"
                                    {{ old('sucursal') === $sucursal->clave ? 'selected' : '' }}>
                                    {{ $sucursal->nombre }} — {{ $sucursal->database_name }}
                                </option>
                            @endforeach
                        </select>

                        @error('sucursal')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror

                        <small class="text-muted d-block mt-2">
                            Esta selección definirá la información visible dentro del sistema.
                        </small>
                    </div>

                    <div class="alert alert-info mt-4 mb-0">
                        <i class="fas fa-info-circle mr-1"></i>
                        Cada base contiene información independiente de inventario, vehículos, catálogos y reportes.
                    </div>
                </div>

                <div class="card-footer bg-white p-4">
                    <button type="submit" class="btn btn-info btn-lg btn-block font-weight-bold">
                        <i class="fas fa-sign-in-alt mr-1"></i> Entrar al Sistema
                    </button>
                </div>
            </form>
        </div>

        <div class="text-center mt-3 text-muted small">
            PIHCSA · Sistema de Gestión de Activos
        </div>

    </div>
</div>
@stop