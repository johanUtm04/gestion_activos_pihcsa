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

        <div class="{{ request('filter') == 'inactivos' ? 'col-xl-12' : 'col-xl-8' }}">
            @include('equipos.partials.index._table')
        </div>


        @if(request('filter') !== 'inactivos')
            <div class="col-xl-4 d-none d-xl-block">
                @include('equipos.partials.index._preview_panel')
            </div>
        @endif
    </div>

    @include('equipos.partials.index._footer')
    @include('equipos.partials.index._modal')

@stop
@section('js')
    <script src="{{ asset('js/equipos/index.js') }}"></script>
@stop