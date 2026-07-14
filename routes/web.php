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
    SoporteController,
    InpcController,
    TasasController,    
};

use App\Http\Controllers\VehiculoController;
use App\Http\Controllers\CatTipoVehiculoController;
use App\Http\Controllers\EmpresaSeleccionController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\SucursalController;

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

    //Seleccionar Sucursal, justo despues de login exitoso-
    Route::get('seleccionar-sucursal', [SurcursalController::class, 'seleccionar'])->name('sucursal.seleccionar')
    Route::post('seleccionar-sucursal', [SucursalController::class, 'guardarSeleccion'])->name('sucursal.guardarSeleccion')
    
    /*
    |--------------------------------------------------------------------------
    | Gestión de Sucursales
    |--------------------------------------------------------------------------
    */
    
    Route::get('/sucursales', [SucursalController::class, 'index'])
        ->name('sucursales.index');

    Route::post('/sucursales/generar', [SucursalController::class, 'generar'])
        ->name('sucursales.generar');

    Route::delete('/sucursales/{sucursal}', [SucursalController::class, 'destroy'])
    ->name('sucursales.destroy');

    Route::post('/sucursal/cambiar', [SucursalController::class, 'cambiar'])
        ->name('sucursal.cambiar');

    /* --- FLUJO OBLIGATORIO DE SELECCIÓN DE EMPRESA --- */
    // Solo se activará cuando se intente interactuar con el módulo de vehículos

    Route::get('seleccionar-empresa', [EmpresaSeleccionController::class, 'mostrarSelector'])->name('empresa.seleccionar');
    Route::post('seleccionar-empresa', [EmpresaSeleccionController::class, 'guardarSeleccion'])->name('empresa.guardar');


    /*
    |----------------------------------------------------------------------
    | MÓDULOS FILTRADOS POR EMPRESA (Únicamente Vehículos)
    |----------------------------------------------------------------------
    */
    Route::middleware(['EnsureEmpresaIsSelected'])->group(function () {
        
        /* --- GESTIÓN DE VEHÍCULOS --- */
        Route::get('vehiculos/filtros', [VehiculoController::class, 'filtros'])->name('vehiculos.filtros');
        Route::get('/vehiculos/datos-filtros', [VehiculoController::class, 'getFilterData'])->name('vehiculos.datos_filters');
        
        // NUEVO: Ruta para romper el contexto actual de la sesión y forzar re-selección de empresa
        Route::get('/vehiculos/cambiar-contexto', function() {
            session()->forget('empresa_id');
            return redirect()->route('vehiculos.index');
        })->name('vehiculos.cambiar_empresa');

        Route::resource('vehiculos', VehiculoController::class)->parameters([
            'vehiculos' => 'vehiculo'
        ]);
        
        Route::resource('tipo_vehiculos', CatTipoVehiculoController::class)->names('tipo_vehiculos');
    });


    /*
    |----------------------------------------------------------------------
    | MÓDULOS GLOBALES (No alterados, no piden seleccionar empresa)
    |----------------------------------------------------------------------
    */

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
    Route::get('/obtener-datos-fiscales', [DepreciacionController::class, 'getFiscalData']);

    /* --- CONFIGURACION FISCAL --- */
    Route::prefix('configuracion')->group(function () {
        Route::get('/tasas', [TasasController::class, 'index'])->name('tasas.index');
        Route::post('/tasas', [TasasController::class, 'store'])->name('tasas.store');
        Route::put('/tasas/{id}', [TasasController::class, 'update'])->name('tasas.update');
        Route::delete('/tasas/{id}', [TasasController::class, 'destroy'])->name('tasas.destroy');

        Route::get('/inpc', [InpcController::class, 'index'])->name('inpc.index');
        Route::post('/inpc', [InpcController::class, 'store'])->name('inpc.store');
    });

    /* --- ADMINISTRACIÓN DE CATÁLOGOS --- */
    Route::resource('gestionUsuarios', GestionUsuariosController::class)->names('users')
        ->parameters(['gestionUsuarios' => 'user']);
    
    Route::resource('gestionUbicaciones', GestionUbicacionesController::class)->names('ubicaciones')
        ->parameters(['gestionUbicaciones' => 'ubicacion']);

    Route::resource('gestionMarcas', MarcaController::class)->names('marcas')
        ->parameters(['gestionMarcas' => 'marca']);

    Route::resource('gestionTipoActivos', TipoActivoController::class)->names('tipo_activos')
        ->parameters(['gestionTipoActivos' => 'tipo_activo']);

    Route::resource('empresas', EmpresaController::class);

    /* --- CONFIGURACIÓN Y SOPORTE --- */
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('soporte/contacto', [SoporteController::class, 'contacto'])->name('soporte.contacto');
    Route::get('soporte/manual', [SoporteController::class, 'manual'])->name('soporte.manual');

    /* --- Código de Barras --- */
    Route::get('/ticket/{id}', [EquipoController::class, 'ticket'])->name('equipos.ticket');
    Route::get('/buscar-equipo', [EquipoController::class, 'vistaBusqueda'])->name('equipos.busqueda');
    Route::post('/buscar-equipo', [EquipoController::class, 'procesar'])->name('equipos.procesar');
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