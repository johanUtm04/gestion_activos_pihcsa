@extends('adminlte::page')

@section('title', 'Gestión de Marcas')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/marcas/index.css') }}">
@stop

@section('content_header')
    @include('marcas.partials._header')
@stop

@section('content')
    {{-- Reutilizamos el sistema dinámico de alertas --}}
    @include('users.partials._alerts')

    <div class="row">
        <div class="col-12">
            @include('marcas.partials._table')
        </div>
    </div>
@stop

@section('footer')
    @include('users.partials._footer')
@stop

@section('js')
    <script src="{{ asset('js/marcas/index.js') }}"></script>
@stop