@extends('adminlte::page')

@section('title', 'Tipos de Activo | Gestión TI')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/tipo_activos/index.css') }}">
@stop

@section('content_header')
    @include('tipo_activos.partials._header')
@stop

@section('content')
    {{-- Sistema global de alertas --}}
    @include('users.partials._alerts')

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
    <script src="{{ asset('js/tipo_activos/index.js') }}"></script>
@stop