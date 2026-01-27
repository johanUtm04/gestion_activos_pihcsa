@extends('adminlte::page')

@section('title', 'Registrar Nuevo Usuario')

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
        color: #198754;
    }

    .fieldset-group i.fa-3x {
        opacity: 0.25;
    }

    .form-group label {
        font-weight: 500;
    }
</style>
@stop

@section('content_header')
<div class="mb-3">
    <h1 class="font-weight-bold mb-1">
        <i class="fas fa-user-plus text-success"></i> Registrar Nuevo Usuario
    </h1>

    <a href="{{ route('users.index') }}" class="btn btn-secondary btn-sm">
        <i class="fas fa-arrow-left"></i> Volver a gestion de usuarios
    </a>
</div>
{{-- WIZARD SIMPLE --}}
<div class="card mb-3">
    <div class="card-body p-3">
        <div class="d-flex justify-content-around text-center">
            <div class="wizard-step active">
                <i class="fas fa-user"></i>
                <div>Usuario</div>
            </div>
            <div class="wizard-step">
                <i class="fas fa-check-circle"></i>
                <div>Confirmar</div>
            </div>
        </div>
    </div>
</div>

@stop

@section('content')

{{-- ERRORES --}}
@if ($errors->any())
<div class="alert alert-danger alert-dismissible fade show">
    <strong><i class="fas fa-exclamation-triangle"></i> Revisa los datos</strong>
    <button type="button" class="close" data-dismiss="alert">
        <span>&times;</span>
    </button>
    <ul class="mt-2 mb-0">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form action="{{ route('users.store')}}" method="POST">
@csrf

<div class="card card-outline card-success">
    <div class="card-body">

        <div class="row">

            {{-- COLUMNA IZQUIERDA --}}
            <div class="col-md-6">
                <fieldset class="fieldset-group">
                    <legend><i class="fas fa-user"></i> Información del Usuario</legend>

                    <div class="text-center mb-3 text-muted">
                        <i class="fas fa-user-circle fa-3x"></i>
                        <div class="small mt-1">Datos personales</div>
                    </div>

                    <div class="form-group">
                        <label>Nombre completo </label>
                        <input type="text"
                               name="name"
                               class="form-control"
                               value="{{ old('name') }}"
                               placeholder="Juan Pérez"
                               required>
                    </div>

                    <div class="form-group">
                        <label>Correo electrónico </label>
                        <input type="email"
                               name="email"
                               class="form-control"
                               value="{{ old('email') }}"
                               placeholder="correo@empresa.com"
                               required>
                    </div>

                    <div class="form-group">
                        <label>Contraseña </label>
                        <input type="password"
                               name="password"
                               class="form-control"
                               placeholder="Ingresa Tu contraseña"
                               required>
                    </div>


                    <div class="form-group">
                        <label>Confirmar contraseña </label>
                        <input type="password"
                            name="password_confirmation"
                            class="form-control"
                            placeholder="Repite la contraseña"
                            required>
                    </div>
                </fieldset>
            </div>

            {{-- COLUMNA DERECHA --}}
            <div class="col-md-6">
                <fieldset class="fieldset-group">
                    <legend><i class="fas fa-user-shield"></i> Rol y Estado</legend>

                    <div class="text-center mb-3 text-muted">
                        <i class="fas fa-users-cog fa-3x"></i>
                        <div class="small mt-1">Configuración del usuario</div>
                    </div>

                    <div class="form-group">
                        <label>Rol </label>
                        <select name="rol" class="form-control" required>
                            <option value="">Seleccione un rol</option>
                            <option value="ADMIN" {{ old('rol') == 'ADMIN' ? 'selected' : '' }}>Administrador</option>
                            <option value="SISTEMAS" {{ old('rol') == 'SISTEMAS' ? 'selected' : '' }}>Sistemas</option>
                            <option value="INVITADO" {{ old('rol') == 'INVITADO' ? 'selected' : '' }}>Invitado/Usuario Comun</option>
                        </select>
                    </div>

                <div class="form-group">
                    <label>Departamento </label>
                    <select name="departamento" class="form-control" required>
                        <option value="" disabled {{ old('departamento') == '' ? 'selected' : '' }}>-- Seleccione --</option>
                        
                        <option value="ALMACEN" {{ old('departamento') == 'ALMACEN' ? 'selected' : '' }}>
                            ALMACEN
                        </option>

                        <option value="ASIST_PAGOS" {{ old('departamento') == 'ASIST_PAGOS' ? 'selected' : '' }}>
                            ASIST. PAGOS
                        </option>
                        
                        <option value="COBRANZA" {{ old('departamento') == 'COBRANZA' ? 'selected' : '' }}>
                            COBRANZA
                        </option>

                        <option value="AUDITORIA" {{ old('departamento') == 'AUDITORIA' ? 'selected' : '' }}>
                            AUDITORIA
                        </option>

                        <option value="AUXILIAR_ADMINISTRATIVO" {{ old('departamento') == 'AUXILIAR_ADMINISTRATIVO' ? 'selected' : '' }}>
                            AUXILIAR ADMINSTRATIVO
                        </option>

                        <option value="AUXILIAR_LOGISTICA" {{ old('departamento') == 'AUXILIAR_LOGISTICA' ? 'selected' : '' }}>
                            AUXILIAR LOGISTICA
                        </option>

                        <option value="CALIDAD" {{ old('departamento') == 'CALIDAD' ? 'selected' : '' }}>
                            CALIDAD
                        </option>

                        <option value="COBRANZA_GOB" {{ old('departamento') == 'COBRANZA_GOB' ? 'selected' : '' }}>
                            COBRANZA GOB
                        </option>

                        <option value="COMPRAS" {{ old('departamento') == 'COMPRAS' ? 'selected' : '' }}>
                            COMPRAS
                        </option>

                        <option value="CONTABILIDAD" {{ old('departamento') == 'CONTABILIDAD' ? 'selected' : '' }}>
                            CONTABILIDAD
                        </option>

                        <option value="COPIADORA" {{ old('departamento') == 'COPIADORA' ? 'selected' : '' }}>
                            COPIADORA
                        </option>

                        <option value="COSTOS" {{ old('departamento') == 'COSTOS' ? 'selected' : '' }}>
                            COSTOS
                        </option>

                        <option value="CREDITO" {{ old('departamento') == 'CREDITO' ? 'selected' : '' }}>
                            CREDITO
                        </option>

                        <option value="CULTURA_TALENTO" {{ old('departamento') == 'CULTURA_TALENTO' ? 'selected' : '' }}>
                            CULTURA Y TALENTO
                        </option>

                        <option value="EMBARQUES" {{ old('departamento') == 'EMBARQUES' ? 'selected' : '' }}>
                            EMBARQUES
                        </option>

                        <option value="ETIQUETAS" {{ old('departamento') == 'ETIQUETAS' ? 'selected' : '' }}>
                            ETIQUETAS
                        </option>

                        <option value="FACTURACION" {{ old('departamento') == 'FACTURACION' ? 'selected' : '' }}>
                            FACTURACION
                        </option>

                        <option value="JURIDICO" {{ old('departamento') == 'JURIDICO' ? 'selected' : '' }}>
                            JURIDICO
                        </option>

                        <option value="LOGISTICA" {{ old('departamento') == 'LOGISTICA' ? 'selected' : '' }}>
                            LOGISTICA
                        </option>

                        <option value="NOMINAS" {{ old('departamento') == 'NOMINAS' ? 'selected' : '' }}>
                            NOMINAS
                        </option>

                        <option value="OPERACIONES" {{ old('departamento') == 'OPERACIONES' ? 'selected' : '' }}>
                            OPERACIONES
                        </option>
                        
                        <option value="RECEPCION" {{ old('departamento') == 'RECEPCION' ? 'selected' : '' }}>
                            RECEPCION
                        </option>

                        <option value="RECEPCION_COMPRAS" {{ old('departamento') == 'RECEPCION_COMPRAS' ? 'selected' : '' }}>
                            RECEPCION DE COMPRAS
                        </option> 

                        <option value="RECEPCION_MATERIAL" {{ old('departamento') == 'RECEPCION_MATERIAL' ? 'selected' : '' }}>
                            RECEPCION DE MATERIAL
                        </option> 

                        <option value="RESPONSABLE_SANITARIO" {{ old('departamento') == 'RESPONSABLE_SANITARIO' ? 'selected' : '' }}>
                            RESPONSABLE SANITARIO
                        </option> 

                        <option value="SISTEMAS" {{ old('departamento') == 'SISTEMAS' ? 'selected' : '' }}>
                            SISTEMAS
                        </option>

                        <option value="SITE" {{ old('departamento') == 'SITE' ? 'selected' : '' }}>
                            SITE
                        </option> 

                        <option value="VENTAS_GOB" {{ old('departamento') == 'VENTAS_GOB' ? 'selected' : '' }}>
                            VENTAS GOB
                        </option> 

                        <option value="VENTAS_PRIV" {{ old('departamento') == 'VENTAS_PRIV' ? 'selected' : '' }}>
                            VENTAS PRIV
                        </option> 

                        <option value="VIGILANCIA" {{ old('departamento') == 'VIGILANCIA' ? 'selected' : '' }}>
                            VIGILANCIA
                        </option> 

                    </select>
                </div>

                    <div class="form-group">
                        <label>Estatus </label>
                        <select name="estatus" class="form-control" required>
                            <option value="ACTIVO" {{ old('estatus') == 'ACTIVO' ? 'selected' : '' }}>
                                Activo
                            </option>
                            <option value="INACTIVO" {{ old('estatus') == 'INACTIVO' ? 'selected' : '' }}>
                                Inactivo
                            </option>
                            <option value="SUSPENDIDO" {{ old('estatus') == 'SUSPENDIDO' ? 'selected' : '' }}>
                                Suspendido
                            </option>
                        </select>
                    </div>

                </fieldset>
            </div>

        </div>
    </div>

    {{-- FOOTER --}}
    <div class="card-footer text-right">
        <button type="submit" class="btn btn-success btn-lg">
            <i class="fas fa-save"></i> Guardar Usuario
        </button>

        <a href="{{ route('users.index') }}" class="btn btn-secondary btn-lg">
            Cancelar
        </a>
    </div>
</div>
</form>

@stop
