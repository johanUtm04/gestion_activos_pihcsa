@extends('adminlte::page')

@section('title', 'Departamentos | Gestión TI')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/departamentos/index.css') }}">
@stop

@section('content_header')
    @include('departamentos.partials._header')
@stop

@section('content')
    @include('marcas.partials._alerts')
    @include('departamentos.partials._filters')

    <div class="row">
        <div class="col-12">
            @include('departamentos.partials._table')
        </div>
    </div>
@stop

@section('footer')
    @include('users.partials._footer')
@stop

@section('js')
    <script src="{{ asset('js/departamentos/index.js') }}"></script>
@stop