@extends('adminlte::page')

@section('title', 'Tipos de Activo | Gestión TI')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/tipo_activos/index.css') }}">
@stop

@section('content_header')
    @include('tipo_activos.partials._header')
@stop

@section('content')
    @include('marcas.partials._alerts')
    @include('tipo_activos.partials._filters')

    <div class="row">
        <div class="col-12">
            @include('tipo_activos.partials._table')
        </div>
    </div>
@stop

@section('footer')
    @include('users.partials._footer')
@stop

@section('js')
    <script src="{{ asset('js/tipo-activos/index.js') }}"></script>
@stop