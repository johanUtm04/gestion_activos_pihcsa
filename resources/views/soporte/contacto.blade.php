@extends('adminlte::page')

@section('title', 'Contacto')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6 mt-5">
        <div class="card card-primary card-outline">
            <div class="card-body box-profile">
                <div class="text-center">
                    <i class="fas fa-user-tie fa-4x text-primary"></i>
                </div>
                <h3 class="profile-username text-center">Desarrollador de Software</h3>
                <p class="text-muted text-center">Estadías TI - PIHCSA</p>
                <ul class="list-group list-group-unbordered mb-3">
                    <li class="list-group-item">
                        <b>Nombre:</b> <a class="float-right">Johan Jael López Reyes</a>
                    </li>
                    <li class="list-group-item">
                        <b>Email:</b> <a class="float-right">johanlopezrey1@gmail.com</a>
                    </li>
                </ul>
                <a href="mailto:tu-correo@ejemplo.com" class="btn btn-primary btn-block"><b>Enviar Correo de Soporte</b></a>
            </div>
        </div>
    </div>
</div>
@stop