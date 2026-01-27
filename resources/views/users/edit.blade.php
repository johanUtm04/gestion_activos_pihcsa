@extends('adminlte::page')

@section('title', 'Editar Equipo | Activos TI')

@section('css')
<style>
    .section-title {
        border-bottom: 2px solid #007bff; 
        padding-bottom: 5px;
        margin-bottom: 15px;
        color: #17a2b8; 
        font-weight: 600;
    }

    .data-item {
        margin-bottom: 10px;
        padding-bottom: 5px;
        border-bottom: 1px dashed #ced4da;
    }

    .data-item:last-child {
        border-bottom: none;
    }

    .data-label {
        font-weight: 600;
        color: #495057;
    }

    .component-group {
        border: 1px solid #dee2e6;
        border-radius: .25rem;
        padding: 15px;
        margin-bottom: 20px;
        background-color: #f8f9fa;
    }
</style>
@stop

@section('content_header')
    <h1 class="font-weight-bold text-center">
        <i class="fas fa-desktop text-primary"></i> 
        Edición del Usuario: {{ strtoupper($user->name) }}
    </h1>
    <a href="{{ route('users.index') }}" class="btn btn-secondary btn-sm">
        <i class="fas fa-arrow-left"></i> Volver a gestion de usuarios
    </a>
@stop

@section('content')
    <div class="container-fluid">
        <div class="row">

            <!-- columna Izquierda -->
            <div class="col-md-5">
                <div class="card card-outline card-info">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-clipboard-list"></i> Detalle y Estado Actual
                        </h3>
                    </div>
                    <!-- Comienzo a escribir la informacion (Lado Derecho) -->
                    <div class="card-body">

                        <h5 class="section-title">
                            <i class="fas fa-cogs"></i> Especificaciones Generales
                        </h5>

                        {{-- Datos Principales --}}
                        <div class="data-item">
                            <span class="data-label">
                                <i class="fas fa-id-badge"></i> ID del Usuario:
                            </span> 
                            <span class="float-right">{{ $user->id }}</span>
                        </div>

                        <div class="data-item">
                            <span class="data-label">
                                <i class="fas fa-user"></i> Nombre:
                            </span> 
                            <span class="float-right">{{ $user->name }}</span>
                        </div>

                        <div class="data-item">
                            <span class="data-label">
                                <i class="fas fa-envelope"></i> Correo Electrónico:
                            </span> 
                            <span class="float-right text-bold">{{ $user->email }}</span>
                        </div>

                        <div class="data-item">
                            <span class="data-label">
                                <i class="fas fa-building"></i> Departamento:
                            </span> 
                            <span class="float-right">{{ $user->departamento }}</span>
                        </div>

                        <div class="data-item">
                            <span class="data-label">
                                <i class="fas fa-toggle-on"></i> Estatus:
                            </span> 
                            <span class="float-right">{{ $user->estatus }}</span>
                        </div>

                    </div> {{-- /card-body --}}
                </div> {{-- /card --}}
            </div> {{-- /col-md-5 --}}


        <!-- Inicio columna Derecha -->
            <div class="col-md-6">
                <div class="card card-outline card-primary">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-pen-square"></i> Modificación de Datos
                        </h3>
                    </div>

                    <!-- Formulario -->
                     <form action="{{ route('users.update', $user) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <fieldset class="border p-3 mb-4">
                                <legend class="w-auto px-2 text-primary"><i class="fas fa-info-circle"></i> Datos Base</legend>

                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="name"><i class="fas fa-laptop"></i> Nombre: </label>
                                        <input type="text" name="name" id="name" class="form-control"
                                            value="{{ old('name', $user->name) }}">
                                    </div>

                                <div class="form-group col-md-6">
                                    <label for="estatus"><i class="fas fa-toggle-on"></i> Estatus:</label>
                                    <select name="estatus" id="estatus" class="form-control @error('estatus') is-invalid @enderror">
                                        <option value="ACTIVO" {{ old('estatus', $user->estatus) == 'ACTIVO' ? 'selected' : '' }}>
                                            ACTIVO
                                        </option>
                                        <option value="INACTIVO" {{ old('estatus', $user->estatus) == 'INACTIVO' ? 'selected' : '' }}>
                                            INACTIVO
                                        </option>
                                        <option value="SUSPENDIDO" {{ old('estatus', $user->estatus) == 'SUSPENDIDO' ? 'selected' : '' }}>
                                            SUSPENDIDO
                                        </option>
                                    </select>
                                    
                                    @error('estatus')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                </div>

                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="email"><i class="fas fa-barcode"></i> Correo Electronico:</label>
                                        <input type="text" name="email" id="email" class="form-control"
                                        value="{{ old('email', $user->email) }}">
                                    </div>

                                <div class="form-group col-md-6">
                                    <label for="departamento"><i class="fas fa-building"></i> Departamento: </label>
                                    <select name="departamento" id="departamento" class="form-control @error('departamento') is-invalid @enderror">
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

                                    @error('departamento')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                </div>

                            </fieldset>
                            {{-- BOTÓN FINAL --}}
                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary btn-lg btn-block">
                                    <i class="fas fa-database"></i> Aplicar Cambios y Registrar Historial
                                </button>
                            </div>
                        </form>
                    </div> {{-- /card-body --}}
                </div> {{-- /card --}}
            </div> {{-- /col-md-7 --}}




        </div> {{-- row --}}
    </div> {{-- container --}}

@stop