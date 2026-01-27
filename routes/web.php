<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EquipoController; //🤙
use App\Http\Controllers\EquipoWizardController;  //🤙
use App\Http\Controllers\ProfileController;  //🤙
use App\Http\Controllers\DepreciacionController;  //🤙    
use App\Http\Controllers\GestionUsuariosController;  //🤙    
use App\Http\Controllers\GestionUbicacionesController;  //🤙    
use App\Http\Controllers\HistorialController;  //🤙    
use App\Http\Controllers\MarcaController;
use App\Http\Controllers\TipoActivoController;

/*
|--------------------------------------------------------------------------
| Rutas Públicas e Inicio
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('auth.login');
});

/*
|--------------------------------------------------------------------------
| Aplicación Web Protegida (Middleware Auth)
|--------------------------------------------------------------------------
| Todas estas rutas requieren que el usuario esté autenticado mediante Breeze.
*/
Route::middleware(['auth'])->group(function () {

    /* --- GESTIÓN PRINCIPAL DE EQUIPOS (CRUD) --- */
    Route::get('/equipos', [EquipoController::class, 'index'])->name('equipos.index');
    Route::post('/equipos', [EquipoController::class, 'store'])->name('equipos.store');

   // Paso 1: Inicio del Wizard
    Route::get('/equipos/wizard/create', [EquipoWizardController::class, 'create'])->name('equipos.wizard.create');

    Route::get('/equipos/{equipo}/edit', [EquipoController::class, 'edit'])->name('equipos.edit');
    Route::put('/equipos/{equipo}', [EquipoController::class, 'update'])->name('equipos.update');
    Route::delete('/equipos/{equipo}', [EquipoController::class, 'destroy'])->name('equipos.destroy');
    Route::get('/equipos/{uuid}/detalles', [EquipoController::class, 'show'])->name('equipos.show');

    /* --- FLUJO WIZARD (CREACIÓN POR PASOS) --- */
 

    // Paso 2: Ubicación
    Route::get('/equipos/{uuid}/ubicacion', [EquipoWizardController::class, 'ubicacionForm'])->name('equipos.wizard-ubicacion');
    Route::post('/equipos/{uuid}/ubicacion', [EquipoWizardController::class, 'saveUbicacion'])->name('equipos.wizard.saveUbicacion');

    // Paso 3: Monitores
    Route::get('/equipos/{uuid}/monitores', [EquipoWizardController::class, 'monitoresForm'])->name('equipos.wizard-monitores');
    Route::post('/equipos/{uuid}/monitores', [EquipoWizardController::class, 'saveMonitor'])->name('equipos.wizard.saveMonitor');

    // Paso 4: Discos Duros
    Route::get('/equipos/{uuid}/discoduro', [EquipoWizardController::class, 'discoduroForm'])->name('equipos.wizard-discos_duros');
    Route::post('/equipos/{uuid}/discoduro', [EquipoWizardController::class, 'saveDiscoduro'])->name('equipos.wizard.saveDiscoduro');

    // Paso 5: Memoria RAM
    Route::get('/equipos/{uuid}/ram', [EquipoWizardController::class, 'ramForm'])->name('equipos.wizard-ram');
    Route::post('/equipos/{uuid}/ram', [EquipoWizardController::class, 'saveRam'])->name('equipos.wizard.saveRam');

    // Paso 6: Periféricos
    Route::get('/equipos/{uuid}/periferico', [EquipoWizardController::class, 'perifericoForm'])->name('equipos.wizard-periferico');
    Route::post('/equipos/{uuid}/periferico', [EquipoWizardController::class, 'savePeriferico'])->name('equipos.wizard.savePeriferico');

    // Paso 7: Procesador (Finalización)
    Route::get('/equipos/{uuid}/procesador', [EquipoWizardController::class, 'procesadorForm'])->name('equipos.wizard-procesador');
    Route::post('/equipos/{uuid}/procesador', [EquipoWizardController::class, 'saveProcesador'])->name('equipos.wizard.saveProcesador');

    /* --- MANTENIMIENTOS (ADD WORK) --- */
    Route::get('/equipos/{equipo}/addwork', [EquipoController::class, 'indexaddwork'])->name('equipos.addwork');
    Route::post('/equipos/{equipo}/addwork', [EquipoController::class, 'saveWork'])->name('equipos.addwork.store');

    /* --- DEPRECIACIÓN Y REPORTES --- */
    Route::get('/depreciacion', [DepreciacionController::class, 'index'])->name('depreciacion.index');
    Route::get('/depreciacion/reporte/pdf', [DepreciacionController::class, 'exportPdf'])->name('depreciacion.pdf');
    Route::get('/depreciacion/{equipo}', [DepreciacionController::class, 'show'])->name('depreciacion.show');

    /* --- GESTIÓN DE USUARIOS --- */
    Route::get('/gestionUsuarios', [GestionUsuariosController::class, 'index'])->name('users.index');
    Route::get('/gestionUsuarios/create', [GestionUsuariosController::class, 'create'])->name('users.create');
    Route::post('/gestionUsuarios', [GestionUsuariosController::class, 'store'])->name('users.store');
    Route::get('/gestionUsuarios/{user}/edit', [GestionUsuariosController::class, 'edit'])->name('users.edit');
    Route::put('/gestionUsuarios/{user}', [GestionUsuariosController::class, 'update'])->name('users.update');
    Route::delete('/gestionUsuarios/{user}', [GestionUsuariosController::class, 'destroy'])->name('users.destroy');

    /* --- GESTIÓN DE UBICACIONES --- */
    Route::get('/gestionUbicaciones', [GestionUbicacionesController::class, 'index'])->name('ubicaciones.index');
    Route::get('/gestionUbicaciones/create', [GestionUbicacionesController::class, 'create'])->name('ubicaciones.create');
    Route::post('/gestionUbicaciones', [GestionUbicacionesController::class, 'store'])->name('ubicaciones.store');
    Route::get('/gestionUbicaciones/{ubicacion}/edit', [GestionUbicacionesController::class, 'edit'])->name('ubicaciones.edit');
    Route::put('/gestionUbicaciones/{ubicacion}', [GestionUbicacionesController::class, 'update'])->name('ubicaciones.update');
    Route::delete('/gestionUbicaciones/{ubicacion}', [GestionUbicacionesController::class, 'destroy'])->name('ubicaciones.destroy');

/* --- GESTIÓN DE MARCAS --- */
    Route::get('/gestionMarcas', [MarcaController::class, 'index'])->name('marcas.index');
    Route::get('/gestionMarcas/create', [MarcaController::class, 'create'])->name('marcas.create');
    Route::post('/gestionMarcas', [MarcaController::class, 'store'])->name('marcas.store');
    Route::get('/gestionMarcas/{marca}/edit', [MarcaController::class, 'edit'])->name('marcas.edit');
    Route::put('/gestionMarcas/{marca}', [MarcaController::class, 'update'])->name('marcas.update');
    Route::delete('/gestionMarcas/{marca}', [MarcaController::class, 'destroy'])->name('marcas.destroy');

/* --- GESTIÓN DE TIPOS DE ACTIVO --- */
    Route::get('/gestionTipoActivos', [TipoActivoController::class, 'index'])->name('tipo_activos.index');
    Route::get('/gestionTipoActivos/create', [TipoActivoController::class, 'create'])->name('tipo_activos.create');
    Route::post('/gestionTipoActivos', [TipoActivoController::class, 'store'])->name('tipo_activos.store');
    Route::get('/gestionTipoActivos/{tipo_activo}/edit', [TipoActivoController::class, 'edit'])->name('tipo_activos.edit');
    Route::put('/gestionTipoActivos/{tipo_activo}', [TipoActivoController::class, 'update'])->name('tipo_activos.update');
    Route::delete('/gestionTipoActivos/{tipo_activo}', [TipoActivoController::class, 'destroy'])->name('tipo_activos.destroy');

    /* --- HISTORIAL Y PERFIL --- */
    Route::get('/historial', [HistorialController::class, 'index'])->name('historial.index');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('reporte-general-equipos', [EquipoController::class, 'exportarGeneral'])->name('equipos.reporte');
});

/*
|--------------------------------------------------------------------------
| Rutas Complementarias y Dashboard
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/auth.php';

/* --- RUTAS DE PRUEBA Y MIDDLEWARE PERSONALIZADO --- */
Route::get('/mayorDeEdad', function () {
    return "Unico correo autorizado Correcto";
})->middleware('age');

Route::get('no-autorizado', function () {
    return "Acceso denegado: El correo no cumple con los requisitos.";
});