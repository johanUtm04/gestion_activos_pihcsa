@extends('adminlte::page')

@section('title', 'Modificar Empresa')

@section('content_header')
    <div class="px-1">
        <h1 class="font-weight-bold text-dark mb-1">Modificar Datos de la Empresa</h1>
        <p class="text-muted text-sm mb-0">Actualiza la información correspondiente del registro administrativo</p>
    </div>
@stop

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger border-0 shadow-sm mb-3" style="border-radius: 8px;">
            <ul class="mb-0 list-unstyled">
                @foreach ($errors->all() as $error)
                    <li><i class="fas fa-exclamation-circle mr-2"></i> {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm border-0" style="border-radius: 12px;">
        <form action="{{ route('empresas.update', $empresa->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="card-body py-4">
                <div class="row">
                    <div class="col-md-8 form-group">
                        <label for="nombre" class="text-sm text-muted font-weight-bold">Razón Social / Nombre Oficial <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nombre" name="nombre" value="{{ old('nombre', $empresa->nombre) }}" style="border-radius: 8px;" required>
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="rfc" class="text-sm text-muted font-weight-bold">RFC / Registro Fiscal</label>
                        <input type="text" class="form-control text-uppercase" id="rfc" name="rfc" value="{{ old('rfc', $empresa->rfc) }}" maxlength="13" style="border-radius: 8px;">
                    </div>
                </div>
            </div>
            <div class="card-footer bg-light border-top-0 d-flex justify-content-end py-3" style="border-bottom-left-radius: 12px; border-bottom-right-radius: 12px;">
                <a href="{{ route('empresas.index') }}" class="btn btn-link text-muted font-weight-bold mr-2">Cancelar</a>
                <button type="submit" class="btn btn-warning text-dark font-weight-bold px-4 shadow-sm" style="border-radius: 8px;">Actualizar Cambios</button>
            </div>
        </form>
    </div>
@stop