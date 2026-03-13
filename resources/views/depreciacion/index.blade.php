@extends('adminlte::page')

@section('title', 'Depreciación de Activos')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/depreciacion/index.css') }}">
@stop

@section('content_header')
    @include('depreciacion.partials._header')
    @include('depreciacion.partials._filters')
@stop

@section('content')
    <div class="container-fluid">
        {{-- Tabla principal de depreciación --}}
        <div class="animated-box" style="animation-delay: 0.1s;">
            @include('depreciacion.partials._table')
        </div>
    </div>

    {{-- Footer y Modal --}}
    @include('depreciacion.partials._footer')
    @include('depreciacion.partials._modal')
@stop
@section('js')
    <script>
        window.baseRoute = "{{ url('obtener-datos-fiscales') }}";
    </script>
    <script src="{{ asset('js/depreciacion/index.js') }}"></script>
@stop 
