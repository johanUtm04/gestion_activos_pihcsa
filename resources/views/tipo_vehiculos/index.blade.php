@extends('adminlte::page')

@section('title', 'Tipos de Vehículo | Control de Flotillas')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/tipo_vehiculos/index.css') }}">
@stop

@section('content_header')
    @include('tipo_vehiculos.partials._header')
@stop

@section('content')
    @include('tipo_vehiculos.partials._alerts')
    @include('tipo_vehiculos.partials._filters')

    <div class="row">
        <div class="col-12">
            @include('tipo_vehiculos.partials._table')
        </div>
    </div>
@stop

@section('footer')
    @include('users.partials._footer')
@stop

@section('js')
    <script src="{{ asset('js/tipo-vehiculos/index.js') }}"></script>
@stop