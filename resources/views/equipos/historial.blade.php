@extends('adminlte::page')

@section('title', 'Editar Equipo | Activos TI')

{{-- HEADER PRINCIPAL --}}
@section('content_header')
    <h1 class="font-weight-bold text-center">
        <i class="fas fa-desktop text-primary"></i> 
        historial de  Acciones de los Activos
    </h1>
    <a href="{{ route('equipos.index') }}" class="btn btn-outline-secondary btn-sm mt-2">
        <i class="fas fa-arrow-circle-left"></i> Volver a Inventario
    </a>
@stop

{{-- CONTENIDO PRINCIPAL --}}
@section('content')

En proceso...

@stop