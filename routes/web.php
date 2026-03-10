<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\{
    EquipoController,
    EquipoWizardController,
    ProfileController,
    DepreciacionController,
    GestionUsuariosController,
    GestionUbicacionesController,
    HistorialController,
    MarcaController,
    TipoActivoController,
    SoporteController
};

/*
|--------------------------------------------------------------------------
| Rutas Públicas
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('auth.login');
});

/*
|--------------------------------------------------------------------------
| Rutas Protegidas (Middleware Auth)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    /* --- GESTIÓN DE EQUIPOS --- */
    Route::get('/equipos', [EquipoController::class, 'index'])->name('equipos.index');
    Route::post('/equipos', [EquipoController::class, 'store'])->name('equipos.store');
    Route::get('/equipos/{equipo}/edit', [EquipoController::class, 'edit'])->name('equipos.edit');
    Route::put('/equipos/{equipo}', [EquipoController::class, 'update'])->name('equipos.update');
    Route::delete('/equipos/{equipo}', [EquipoController::class, 'destroy'])->name('equipos.destroy');
    Route::get('/equipos/{equipo}/detalles', [EquipoController::class, 'show'])->name('equipos.show');

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

    /* --- MANTENIMIENTO Y FACTURACIÓN --- */
    Route::get('/equipos/{equipo}/addwork', [EquipoController::class, 'indexaddwork'])->name('equipos.addwork');
    Route::post('/equipos/{equipo}/addwork', [EquipoController::class, 'saveWork'])->name('equipos.addwork.store');
    
    Route::get('/equipos/{equipo}/factura', [EquipoController::class, 'indexFactura'])->name('equipos.factura.edit');
    Route::post('/equipos/{equipo}/factura', [EquipoController::class, 'saveFactura'])->name('equipos.factura.saveFactura');

    /* --- REPORTES Y DEPRECIACIÓN --- */
    Route::get('/depreciacion', [DepreciacionController::class, 'index'])->name('depreciacion.index');
    Route::get('/reporte-general-equipos', [EquipoController::class, 'exportarGeneral'])->name('equipos.reporte');
    Route::get('/historial', [HistorialController::class, 'index'])->name('historial.index');

    /* --- ADMINISTRACIÓN DE CATÁLOGOS --- */
    
    // Usuarios
    Route::resource('gestionUsuarios', GestionUsuariosController::class)->names('users')
    ->parameters(['gestionUsuarios' => 'user']);
    
    // Ubicaciones
    Route::resource('gestionUbicaciones', GestionUbicacionesController::class)->names('ubicaciones')
    ->parameters(['gestionUbicaciones' => 'ubicacion']);

    // Marcas
    Route::resource('gestionMarcas', MarcaController::class)->names('marcas')
    ->parameters(['gestionMarcas' => 'marca']);

    // Tipos de Activo
    Route::resource('gestionTipoActivos', TipoActivoController::class)->names('tipo_activos')
    ->parameters(['gestionTipoActivos' => 'tipo_activo']);

    /* --- CONFIGURACIÓN Y SOPORTE --- */
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('soporte/contacto', [SoporteController::class, 'contacto'])->name('soporte.contacto');
    Route::get('soporte/manual', [SoporteController::class, 'manual'])->name('soporte.manual');
});

/*
|--------------------------------------------------------------------------
| Sistema y Pruebas
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/auth.php';
