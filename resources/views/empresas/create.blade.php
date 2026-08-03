@extends('adminlte::page')

@section('title', 'Registrar Área')

@section('content_header')
    <h1 class="font-weight-bold text-dark">
        <i class="fas fa-plus-circle mr-2" style="color: #E83E8C;"></i>
        Registrar Nueva Área
    </h1>
@stop

@section('content')
<div class="card shadow-sm border-0" style="border-radius: 12px;">
    <form action="{{ route('empresas.store') }}" method="POST">
        @csrf

        <div class="card-body">
            <div class="form-group">
                <label for="nombre">Nombre del Área</label>

                <input type="text"
                       name="nombre"
                       id="nombre"
                       class="form-control @error('nombre') is-invalid @enderror"
                       placeholder="Ej: Recursos Humanos, Sistemas, Contabilidad..."
                       value="{{ old('nombre') }}"
                       required
                       autofocus>

                @error('nombre')
                    <span class="invalid-feedback">
                        {{ $message }}
                    </span>
                @enderror
            </div>

            <div class="form-group">
                <label for="rfc">Descripción</label>

                <input type="text"
                       name="rfc"
                       id="rfc"
                       class="form-control @error('rfc') is-invalid @enderror"
                       placeholder="Ej: Área encargada de la gestión administrativa..."
                       value="{{ old('rfc') }}"
                       maxlength="13">

                <small class="text-muted">
                    Agrega una descripción breve que permita identificar el área.
                </small>

                @error('rfc')
                    <span class="invalid-feedback">
                        {{ $message }}
                    </span>
                @enderror
            </div>
        </div>

        <div class="card-footer bg-white border-top-0">
            <button type="submit"
                    class="btn px-4 shadow-sm font-weight-bold"
                    style="background-color: #E83E8C;
                           border-color: #E83E8C;
                           color: #ffffff;">

                <i class="fas fa-save mr-1"></i>
                Guardar Área
            </button>

            <a href="{{ route('empresas.index') }}"
               class="btn btn-outline-secondary ml-2">

                Cancelar
            </a>
        </div>
    </form>
</div>
@stop