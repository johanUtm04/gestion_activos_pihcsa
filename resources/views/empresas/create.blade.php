@extends('adminlte::page')

@section('title', 'Registrar Empresa')

@section('content_header')
    <div class="px-1">
        <h1 class="font-weight-bold text-dark mb-1">Registrar Nueva Empresa</h1>
        <p class="text-muted text-sm mb-0">Agrega una nueva organización al sistema central</p>
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
        <form action="{{ route('empresas.store') }}" method="POST">
            @csrf
            <div class="card-body py-4">
                <div class="row">
                    <div class="col-md-8 form-group">
                        <label for="nombre" class="text-sm text-muted font-weight-bold">Razón Social / Nombre Oficial <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nombre" name="nombre" value="{{ old('nombre') }}" placeholder="Ej. Corporación Azul" style="border-radius: 8px;" required>
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="rfc" class="text-sm text-muted font-weight-bold">RFC / Registro Fiscal</label>
                        <input type="text" class="form-control text-uppercase" id="rfc" name="rfc" value="{{ old('rfc') }}" placeholder="Ej. CAZ120345XYZ" maxlength="13" style="border-radius: 8px;">
                    </div>
                </div>
            </div>
            <div class="card-footer bg-light border-top-0 d-flex justify-content-end py-3" style="border-bottom-left-radius: 12px; border-bottom-right-radius: 12px;">
                <a href="{{ route('empresas.index') }}" class="btn btn-link text-muted font-weight-bold mr-2">Cancelar</a>
                <button type="submit" class="btn btn-primary px-4 font-weight-bold shadow-sm" style="border-radius: 8px;">Guardar Registro</button>
            </div>
        </form>
    </div>
@stop