<!-- Vista no usada -->
@extends('adminlte::page')

@section('content')
@php
    $alertTypes = ['success', 'danger', 'warning', 'info', 'primary'];
@endphp

@foreach ($alertTypes as $msg)
    @if(Session::has($msg))
        <div class="alert alert-{{ $msg }} alert-dismissible fade show" role="alert">
            {{ Session::get($msg) }}
        </div>
    @endif
@endforeach
    <div class="container">

        <h2>Equipo creado exitosamente</h2>
        <p>Marca: {{ $equipo->marca_equipo }}</p>
        <p>Tipo: {{ $equipo->tipo_equipo }}</p>

        <hr>

        <h3>Agregar información opcional</h3>

        <div class="list-group">

            <a href="{{ route('equipos.wizard-ubicacion', $equipo) }}" class="list-group-item">
                Registrar Ubicación 
            </a>

            <a href="{{ route('equipos.wizard-monitores', $equipo) }}" class="list-group-item">
                Registrar Monitor 
            </a>

            <a href="{{ route('equipos.wizard-discos_duros', $equipo) }}" class="list-group-item">
                Registrar Disco Duro 
            </a>

            <a href="{{ route('equipos.wizard-ram', $equipo) }}" class="list-group-item">
            Registrar una RAM
            </a>

            <a href="{{ route('equipos.wizard-periferico', $equipo) }}" class="list-group-item">
            Registrar un periferico
            </a>
        
            <a href="{{ route('equipos.wizard-procesador', $equipo) }}" class="list-group-item">
            Registrar un Procesador
            </a>
        </div>

        <br>


        
    </div>
@endsection
