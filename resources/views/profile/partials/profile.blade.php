@extends('adminlte::page')

@section('title', 'Perfil de Usuario')

@section('css')
<style>
    .fieldset-group {
        border: 1px solid #ced4da;
        padding: 20px;
        margin-bottom: 20px;
        border-radius: .25rem;
        background-color: #ffffff;
    }

    .fieldset-group legend {
        width: inherit;
        padding: 0 10px;
        border-bottom: none;
        font-size: 1.1em;
        font-weight: 600;
        color: #007bff;
    }
</style>
@stop

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title font-weight-bold">
                        <i class="fas fa-user-cog"></i>
                        Perfil de Usuario
                    </h3>
                </div>

                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    @method('PATCH')

                    <div class="card-body">

                        {{-- Información personal --}}
                        <fieldset class="fieldset-group">
                            <legend><i class="fas fa-id-card"></i> Información personal</legend>

                            <div class="form-group">
                                <label>Nombre</label>
                                <input type="text" class="form-control"
                                    name="name" value="{{ old('name', auth()->user()->name) }}" required>
                            </div>

                            <div class="form-group">
                                <label>Correo electrónico</label>
                                <input type="email" class="form-control"
                                    name="email" value="{{ old('email', auth()->user()->email) }}" required>
                            </div>
                        </fieldset>

                        {{-- Seguridad --}}
                        <fieldset class="fieldset-group">
                            <legend><i class="fas fa-lock"></i> Seguridad</legend>

                            <div class="form-group">
                                <label>Contraseña actual <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" name="current_password" required>
                            </div>

                            <div class="form-group">
                                <label>Nueva contraseña</label>
                                <input type="password" class="form-control" name="password">
                            </div>

                            <div class="form-group">
                                <label>Confirmar contraseña</label>
                                <input type="password" class="form-control" name="password_confirmation">
                            </div>

                        </fieldset>

                        {{-- Información del sistema --}}
                        <fieldset class="fieldset-group">
                            <legend><i class="fas fa-info-circle"></i> Información del sistema</legend>

                            <div class="form-group">
                                <label>Rol</label>
                                <input class="form-control" value="{{ auth()->user()->rol }}" disabled>
                            </div>

                            <div class="form-group">
                                <label>Departamento</label>
                                <input class="form-control" value="{{ auth()->user()->departamento }}" disabled>
                            </div>

                            <div class="form-group">
                                <label>Estatus</label>
                                <input class="form-control" value="{{ auth()->user()->estatus }}" disabled>
                            </div>
                        </fieldset>

                    </div>

                    <div class="card-footer text-right">
                        <button class="btn btn-primary">
                            <i class="fas fa-save"></i> Guardar cambios
                        </button>
                    </div>

                </form>

            </div>

        </div>
    </div>
</div>
@stop


