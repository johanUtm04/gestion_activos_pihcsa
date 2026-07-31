@extends('adminlte::page')

@section('title', 'Gestión de Usuarios')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/users/index.css') }}">
@stop

@section('content_header')
    @include('users.partials._header')
@stop

@section('content')
    @include('users.partials._alerts')
    @include('users.partials._filters')

    <div class="row">
        <div class="col-12">
            @include('users.partials._table')
        </div>
    </div>
@stop

@section('footer')
    @include('users.partials._footer')
@stop

@section('js')
    <script src="{{ asset('js/users/index.js') }}"></script>
@stop
