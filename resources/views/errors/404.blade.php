@extends('adminlte::page')

@section('title', '¡Ups! Página no encontrada.')

@section('content_header')
    <h1></h1>
@stop

@section('content')
<div class="container text-center" style="margin-top: 10%; margin-bottom: 10%;">
    <div class="error-content">
        <h1 class="display-1 font-weight-bold text-info">404</h1>
        <h2 class="h3 font-weight-bold">
            <i class="fas fa-exclamation-triangle text-warning"></i> ¡Ups! Página no encontrada.
        </h2>
        <p class="text-muted mt-3">
            No pudimos encontrar la página que estabas buscando. 
            Mientras tanto, puedes <a href="{{ route('equipos.index') }}">volver al tablero principal</a>.
        </p>
    </div>
</div>
@endsection