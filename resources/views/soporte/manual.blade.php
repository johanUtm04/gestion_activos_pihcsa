@extends('adminlte::page')

@section('title', 'Manual de Usuario')

@section('content_header')
    <h1>Guía de Usuario - Sistema de Activos PIHCSA</h1>
@stop

@section('content')
    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-book mr-2"></i> Instrucciones Rápidas</h3>
        </div>
        <div class="card-body">
            <div id="accordion">
                <div class="card card-info card-outline">
                    <a class="d-block w-100" data-toggle="collapse" href="#collapseOne">
                        <div class="card-header">
                            <h4 class="card-title w-100">1. Gestión de Activos</h4>
                        </div>
                    </a>
                    <div id="collapseOne" class="collapse show" data-parent="#accordion">
                        <div class="card-body">
                            Para editar un activo, es <b>obligatorio</b> indicar el motivo del cambio. El sistema guardará quién realizó la modificación.
                        </div>
                    </div>
                </div>
                {{-- Añade más secciones aquí --}}
            </div>
        </div>
    </div>
@stop