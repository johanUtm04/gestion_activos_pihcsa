<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="text-dark font-weight-bold">
            <i class="fas fa-dollar-sign text-info mr-2 depreciacion-icon-anim"></i>
            Depreciación de Activos
        </h1>
        <p class="text-muted mb-0">Análisis financiero y valor actual de activos médicos y TI</p>
    </div>

    <div class="btn-group shadow-sm">
        <a href="{{ route('depreciacion.reporte.anual', request()->query()) }}"
           class="btn btn-sm btn-outline-info font-weight-bold">
            <i class="fas fa-calendar-alt mr-1"></i> Reporte por año
        </a>

        <a href="{{ route('depreciacion.reporte.concentrado', request()->query()) }}"
           class="btn btn-sm btn-outline-success font-weight-bold">
            <i class="fas fa-file-excel mr-1"></i> Concentrado
        </a>
    </div>
</div>