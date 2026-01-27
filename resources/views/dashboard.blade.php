@extends('adminlte::page')

@section('title', '[Pruebas]Dashboard - Activos Fijos')

@section('content_header')
    <h1>Activos de la Empresa</h1>
@stop

@section('content')
    <p>Aqui Ira el panel de Activos, Binevenido!</p>
    <div class="card">
        <h3 class="card-title">Cargando Modulo de los Activos</h3>
        <div class="card-body">
            Este es un Componente de AdminLTE. Si lo ves, estamos funcionando :)
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop