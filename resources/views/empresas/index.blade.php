@extends('adminlte::page')

@section('title', 'Catálogo de Sucursales')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/empresas/index.css') }}">
@stop

@section('content_header')
    @include('empresas.partials._header')
@stop

@section('content')
    @include('empresas.partials._alerts')
    @include('empresas.partials._filters')

    <div class="row">
        <div class="col-12">
            @include('empresas.partials._table')
        </div>
    </div>
@stop

@section('footer')
    @include('users.partials._footer')
@stop

@section('js')
    <script src="{{ asset('js/empresas/index.js') }}"></script>
@stop