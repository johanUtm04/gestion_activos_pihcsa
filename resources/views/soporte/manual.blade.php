@extends('adminlte::page')

@section('title', 'Manual de Usuario')

@section('content_header')
    <h1>Guía de Usuario - Sistema de Activos PIHCSA</h1>
@stop

@section('content')
<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-book mr-2"></i> Instrucciones Rápidas
        </h3>
    </div>

    <div class="card-body">
        <div id="accordion">
            <div class="card card-info card-outline">
                <a class="d-block w-100" data-toggle="collapse" href="#collapseOne">
                    <div class="card-header">
                        <h4 class="card-title w-100">
                            <i class="fas fa-database mr-2"></i> 1. Alimentación de Catálogos
                        </h4>
                    </div>
                </a>

                <div id="collapseOne" class="collapse show" data-parent="#accordion">
                    <div class="card-body">
                        <div class="row">
                            {{-- Columna de Texto y Código --}}
                            <div class="col-md-5">
                                <p class="text-justify">
                                    Como requisito del sistema es necesario tener al menos un valor en los catálogos de 
                                    <strong>Usuarios, Ubicaciones, Marcas y Tipo de Activos</strong>.
                                </p>
                                
                                <ul class="text-muted small">
                                    <li>Verifique que los nombres no contengan caracteres especiales.</li>
                                    <li>Asegúrese de asignar los permisos correctos a cada usuario.</li>
                                    <li>Mantenga actualizada la lista de ubicaciones físicas.</li>
                                </ul>

                                <p class="text-justify">
                                    Este se controla desde estos archivos de rutas:
                                </p>

                                {{-- Bloque de código estilo Editor --}}
                                <div class="card bg-dark shadow-sm border-0 mb-3" style="border-radius: 8px; overflow: hidden;">
                                    <div class="card-header py-2 px-3 d-flex justify-content-between align-items-center" style="background: #2d3238; border-bottom: 1px solid #3e444d;">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-file-code text-warning mr-2"></i>
                                            <span class="small text-gray-300 font-italic">routes/web.php</span>
                                        </div>
                                    </div>
                                    <div class="card-body p-0">
                                        <pre class="m-0 p-3" style="background: #1e2227; line-height: 1.5;"><code id="route-code" class="text-white" style="font-family: 'Source Code Pro', monospace; font-size: 0.85rem;">// Usuarios
Route::resource('gestionUsuarios', GestionUsuariosController::class)->names('users');

// Ubicaciones
Route::resource('gestionUbicaciones', GestionUbicacionesController::class)->names('ubicaciones');

// Marcas
Route::resource('gestionMarcas', MarcaController::class)->names('marcas');

// Tipos de Activo
Route::resource('gestionTipoActivos', TipoActivoController::class)->names('tipo_activos');</code></pre>
                                    </div>
                                </div>
                            </div>

                            {{-- Columna de Imagen/Captura --}}
                            <div class="col-md-7 text-center">
                                <div class="img-container shadow-sm border rounded p-2 bg-light">
                                    <img src="{{ asset('vendor/adminlte/dist/manual/formularios/CATALOGOS.png') }}" 
                                         alt="Captura de Catálogos" 
                                         class="img-fluid rounded img-guide">
                                    <p class="small text-muted mt-2 mb-0">
                                        <i class="fas fa-search-plus mr-1"></i> Vista previa del módulo de catálogos
                                    </p>
                                </div>
                            </div>
                        </div> {{-- /.row --}}
                    </div> {{-- /.card-body interno --}}
                </div> {{-- /.collapse --}}
            </div> {{-- /.card info --}}
            
            {{-- Aquí puedes añadir más secciones del acordeón siguiendo la misma estructura --}}
        </div> {{-- /#accordion --}}
    </div> {{-- /.card-body principal --}}
</div> {{-- /.card-outline --}}

<style>
    .img-guide {
        transition: transform 0.3s ease;
        cursor: zoom-in;
    }
    .img-guide:hover {
        transform: scale(1.02);
    }
    .card-title {
        color: #117a8b;
    }
    pre code {
        display: block;
        padding-top: 5px;
    }
</style>
@stop