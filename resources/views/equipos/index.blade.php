@extends('adminlte::page')

@section('title', 'Inventario de Activos TI')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/equipos/index.css') }}">
@stop

@section('content_header')
    @include('equipos.partials.index._header')
@stop

@section('content')

    {{-- Filtros --}}
    @include('equipos.partials.index._filters')

    <div class="row">
        {{-- TABLA (LADO IZQUIERDO) --}}
        <div class="col-xl-8">
            @include('equipos.partials.index._table')
        </div>

        {{-- PANEL DE DETALLE (LADO DERECHO) --}}
        <div class="col-xl-4 d-none d-xl-block">
            @include('equipos.partials.index._preview_panel')
        </div>
    </div>
    @include('equipos.partials.index._footer')
    @include('equipos.partials.index._modal')
@stop

@section('js')
    <script src="{{ asset('js/equipos/index.js') }}"></script>
@stop