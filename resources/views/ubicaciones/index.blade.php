@extends('adminlte::page')

@section('title', 'Gestión de Ubicaciones')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/ubicaciones/index.css') }}">
@stop

@section('content_header')
    @include('ubicaciones.partials._header')
@stop

@section('content')
@include('ubicaciones.partials._alerts')
    @include('ubicaciones.partials._filters')
    <div class="row">
        <div class="col-12">
            @include('ubicaciones.partials._table')
        </div>
    </div>
@stop

@section('footer')
    @include('ubicaciones.partials._footer')
@stop

@section('js')
<script>
    window.newId = "{{ session('new_id') }}";
    window.updatedId = "{{ session('actualizado_id') }}";
</script>

<script src="{{ asset('js/ubicaciones/index.js') }}"></script>
@stop