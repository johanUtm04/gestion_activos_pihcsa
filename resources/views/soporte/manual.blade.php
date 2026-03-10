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

    <div class="card-body p-0"> {{-- p-0 para que el acordeón toque los bordes --}}
        <div id="accordion" class="custom-accordion">
            {{-- SECCIÓN 1 --}}
            <div class="card card-info card-outline mb-0 shadow-none border-bottom">
                <a class="d-block w-100 collapsed" data-toggle="collapse" href="#collapseOne">
                    <div class="card-header">
                        <h4 class="card-title w-100 font-weight-bold">
                            <i class="fas fa-database mr-2 text-info"></i> 1. Alimentación de Catálogos
                        </h4>
                    </div>
                </a>

                <div id="collapseOne" class="collapse" data-parent="#accordion">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-5">
                                <p class="text-justify">
                                    Como requisito del sistema es necesario tener al menos un valor en los catálogos de 
                                    <strong>Usuarios, Ubicaciones, Marcas y Tipo de Activos</strong>.
                                </p>
                                
                                <ul class="text-muted small">
                                    <li>Accesibles como usuario de Tipo <strong>Admin</strong></li>
                                    <li>Verifique que los nombres no contengan caracteres especiales.</li>
                                    <li>Asegúrese de asignar los permisos correctos a cada usuario.</li>
                                    <li>Mantenga actualizada la lista de ubicaciones físicas.</li>
                                </ul>

                                <p class="text-justify font-italic small">
                                    Este se controla desde estos archivos de rutas:
                                </p>

                                <div class="card bg-dark shadow-sm border-0 mb-3" style="border-radius: 8px; overflow: hidden;">
                                    <div class="card-header py-2 px-3 d-flex justify-content-between align-items-center" style="background: #2d3238; border-bottom: 1px solid #3e444d;">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-file-code text-warning mr-2"></i>
                                            <span class="small text-gray-300 font-italic text-uppercase" style="font-size: 10px">routes/web.php</span>
                                        </div>
                                    </div>
                                    <div class="card-body p-0">
                                        <pre class="m-0 p-3" style="background: #1e2227; line-height: 1.4;"><code class="text-white" style="font-family: 'Source Code Pro', monospace; font-size: 0.8rem;">// Usuarios
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

                            <div class="col-md-7 text-center">
                                <div class="img-container shadow-sm border rounded p-2 bg-light">
                                    <img src="{{ asset('vendor/adminlte/dist/manual/formularios/CATALOGOS.png') }}" 
                                         alt="Captura de Catálogos" 
                                         class="img-fluid rounded img-guide">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- SECCIÓN 2 (EJEMPLO PARA COPIAR) --}}
            <div class="card card-info card-outline mb-0 shadow-none border-bottom">
                <a class="d-block w-100 collapsed" data-toggle="collapse" href="#collapseTwo">
                    <div class="card-header">
                        <h4 class="card-title w-100 font-weight-bold">
                            <i class="fas fa-laptop mr-2 text-info"></i> 2. Registro de Activos (Wizard Formulario)
                        </h4>
                    </div>
                </a>
                <div id="collapseTwo" class="collapse" data-parent="#accordion">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-5">
                                <p class="text-justify">
                                    Una ves tengamos almenos un formulario (paso 1) podremos comenzar con la inserccion en el formulario principal 
                                    <strong>Este Sera la base del Activo</strong>.
                                </p>
                                
                                <ul class="text-muted small">
                                    <li>Accesibles como usuario de Tipo <strong>Admin | Sistemas</strong></li>
                                    <li>Para el armado de la maqueta, se podra seleccionar un componente extra de c/u o omitir.</li>
                                    <li>Datos Guardados mediante seciones del navegador, se toca DB hasta paso final.</li>
                                </ul>

                                <p class="text-justify font-italic small">
                                    Este se controla desde estos archivos de rutas:
                                </p>

                                <div class="card bg-dark shadow-sm border-0 mb-3" style="border-radius: 8px; overflow: hidden;">
                                    <div class="card-header py-2 px-3 d-flex justify-content-between align-items-center" style="background: #2d3238; border-bottom: 1px solid #3e444d;">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-file-code text-warning mr-2"></i>
                                            <span class="small text-gray-300 font-italic text-uppercase" style="font-size: 10px">routes/web.php</span>
                                        </div>
                                    </div>
                                    <div class="card-body p-0">
                                        <pre class="m-0 p-3" style="background: #1e2227; line-height: 1.4;"><code class="text-white" style="font-family: 'Source Code Pro', monospace; font-size: 0.8rem;">// Usuarios
/* --- FLUJO DE REGISTRO (WIZARD) --- */
Route::get('/equipos/wizard/create', [EquipoWizardController::class, 'create'])->name('equipos.wizard.create');
Route::get('/validar-serial-activo', [EquipoWizardController::class, 'validarSerial'])->name('equipos.validar_serial');
// El UUID vincula los componentes al equipo en creación
Route::prefix('equipos/{uuid}')->group(function () {
    Route::get('/ubicacion', [EquipoWizardController::class, 'ubicacionForm'])->name('equipos.wizard.ubicacion');
    Route::post('/ubicacion', [EquipoWizardController::class, 'saveUbicacion'])->name('equipos.wizard.saveUbicacion');

    Route::get('/monitor', [EquipoWizardController::class, 'monitoresForm'])->name('equipos.wizard.monitor');
    Route::post('/monitor', [EquipoWizardController::class, 'saveMonitor'])->name('equipos.wizard.saveMonitor');

    Route::get('/discoduro', [EquipoWizardController::class, 'discoDuroForm'])->name('equipos.wizard.discoDuro');
    Route::post('/discoduro', [EquipoWizardController::class, 'savediscoDuro'])->name('equipos.wizard.savediscoDuro');

    Route::get('/ram', [EquipoWizardController::class, 'ramForm'])->name('equipos.wizard.ram');
    Route::post('/ram', [EquipoWizardController::class, 'saveRam'])->name('equipos.wizard.saveRam');

    Route::get('/procesador', [EquipoWizardController::class, 'procesadorForm'])->name('equipos.wizard.procesador');
    Route::post('/procesador', [EquipoWizardController::class, 'saveProcesador'])->name('equipos.wizard.saveProcesador');

    Route::get('/periferico', [EquipoWizardController::class, 'perifericoForm'])->name('equipos.wizard.periferico');
    Route::post('/periferico', [EquipoWizardController::class, 'savePeriferico'])->name('equipos.wizard.savePeriferico');

});
</code></pre>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-7 text-center">
                                <div class="img-container shadow-sm border rounded p-2 bg-light">
                                    <img src="{{ asset('vendor/adminlte/dist/manual/wizard/wizard.png') }}" 
                                         alt="Captura de Catálogos" 
                                         class="img-fluid rounded img-guide">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>







{{-- SECCIÓN 3: EDICIÓN DE ACTIVOS --}}
<div class="card card-info card-outline mb-0 shadow-none border-bottom">
    <a class="d-block w-100 collapsed" data-toggle="collapse" href="#collapseThree">
        <div class="card-header">
            <h4 class="card-title w-100 font-weight-bold">
                <i class="fas fa-edit mr-2 text-info"></i> 3. Edición de Activos
            </h4>
        </div>
    </a>
    <div id="collapseThree" class="collapse" data-parent="#accordion">
        <div class="card-body">
            <div class="row">
                <div class="col-md-5">
                    <p class="text-justify">
                        Una vez registrado un nuevo Activo, será posible editar su información técnica y administrativa. 
                        <strong>Los cambios se verán reflejados de manera inmediata</strong> en la base de datos tras procesar el formulario.
                    </p>
                    
                    <ul class="text-muted small">
                        <li>Accesible para usuarios con rol de <strong>Admin | Sistemas</strong>.</li>
                        <li><strong>Interfaz Dual:</strong> En el lado izquierdo se visualizan los datos actuales y en el derecho los campos editables.</li>
                        <li><strong>Auditoría:</strong> Cada modificación en cualquier apartado genera automáticamente un registro en el historial (Log).</li>
                    </ul>

                    <p class="text-justify font-italic small">
                        Este módulo se controla mediante la siguiente ruta:
                    </p>

                    <div class="card bg-dark shadow-sm border-0 mb-3" style="border-radius: 8px; overflow: hidden;">
                        <div class="card-header py-2 px-3 d-flex justify-content-between align-items-center" style="background: #2d3238; border-bottom: 1px solid #3e444d;">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-file-code text-warning mr-2"></i>
                                <span class="small text-gray-300 font-italic text-uppercase" style="font-size: 10px">routes/web.php</span>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <pre class="m-0 p-3" style="background: #1e2227; line-height: 1.4;"><code class="text-white" style="font-family: 'Source Code Pro', monospace; font-size: 0.8rem;">// Ruta de Edición de Equipos
Route::get('/equipos/{equipo}/edit', [EquipoController::class, 'edit'])->name('equipos.edit');</code></pre>
                        </div>
                    </div>
                </div>

                <div class="col-md-7 text-center">
                    <div class="img-container shadow-sm border rounded p-2 bg-light">
                        <img src="{{ asset('vendor/adminlte/dist/manual/edit/edit.png') }}" 
                             alt="Captura de Edición de Activo" 
                             class="img-fluid rounded img-guide">
                        <p class="small text-muted mt-2 mb-0">
                            <i class="fas fa-info-circle mr-1"></i> Panel de edición de hardware y asignación
                        </p>
                    </div>
                </div>
            </div> {{-- /.row --}}
        </div>
    </div>
</div>






            
        </div> {{-- /#accordion --}}
    </div> {{-- /.card-body principal --}}
</div>
@stop