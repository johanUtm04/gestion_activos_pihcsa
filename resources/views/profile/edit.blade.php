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

                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

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

                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>Error: <strong>{{ $error }}</strong></li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div class="form-group">
                                <label>Contraseña actual <span class="text-danger">*</span></label>
                                <input type="password" class="form-control @error('current_password') is-invalid @enderror" 
                                name="current_password" >
                            </div>

                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <hr class="my-4">

                            <h5 class="mt-3">¿Necesitas cambiar tu contraseña?</h5>
                            <p class="text-muted">
                                Solo completa los siguientes campos si deseas cambiar tu contraseña.
                            </p>
                            <div class="form-group">
                                <label>Nueva contraseña</label>
                                <input type="password" class="form-control" name="password">
                            </div>

                            <div class="form-group">
                                <label>Confirmar Nueva contraseña</label>
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

                @if(session('saved'))
                <div class="modal fade show" id="successModal" tabindex="-1" style="display:block;">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">

                            <div class="modal-header bg-success">
                                <h5 class="modal-title">
                                    <i class="fas fa-check-circle"></i> Cambios guardados
                                </h5>
                                <button type="button" class="close" data-dismiss="modal">
                                    <span>&times;</span>
                                </button>
                            </div>

                            <div class="modal-body text-center">
                                <p>La información del perfil se actualizó correctamente.</p>
                            </div>

                            <div class="modal-footer justify-content-center">
                                <button type="button" class="btn btn-success" data-dismiss="modal">
                                    Aceptar
                                </button>
                            </div>

                        </div>
                    </div>
                </div>

                @endif


            </div>

        </div>
    </div>
</div>
@stop

@section('js')

<script>
    $(document).ready(function () {
        $('#successModal').modal('show');
    });
</script>

@stop