<div class="card card-outline card-info">
<div class="card-header d-flex align-items-center">
    {{-- Título a la izquierda --}}
    <div class="flex-grow-1">
        <h3 class="card-title text-primary font-weight-bold mb-0">Información Base del Activo</h3>
    </div>

    {{-- Contenedor del Logo a la derecha --}}
    <div class="d-flex align-items-center" style="opacity: 0.9;">
        <div class="mx-3 d-none d-md-block" style="border-left: 1px solid #e0e0e0; height: 35px;"></div>
        <div class="text-right mr-2 d-none d-lg-block">
            <small class="text-muted d-block" style="font-size: 0.55rem; line-height: 1; letter-spacing: 0.5px;">SISTEMA DE GESTIÓN</small>
            <span class="font-weight-bold text-secondary" style="font-size: 0.75rem;">ACTIVOS TI</span>
        </div>
        <img src="{{ asset('vendor/adminlte/dist/img/logohd.png') }}" 
             alt="Logo PIHCSA" 
             style="height: 35px; width: auto; filter: drop-shadow(0px 2px 2px rgba(0,0,0,0.1));">
    </div>
</div>
    <div class="card-body">
        <fieldset class="border p-3 mb-4 rounded bg-light">
            <legend class="w-auto px-2 text-primary text-sm font-weight-bold">
                <i class="fas fa-lock"></i> DATOS NO MODIFICABLES
            </legend>

            <div class="row">
                {{-- MARCA EQUIPO --}}
                <div class="form-group col-md-6">
                    <label><i class="fas fa-tag"></i> Marca del Equipo</label>
                    <input type="text" class="form-control" value="{{ $equipo->marca->nombre ?? 'N/A' }}" readonly>
                    {{-- Este es el que salva el error: --}}
                    <input type="hidden" name="marca_id" value="{{ $equipo->marca_id }}">
                </div>

                {{-- MODELO DEL EQUIPO --}}
                <div class="form-group col-md-6">
                    <label><i class="fas fa-laptop-code"></i> Modelo Específico</label>
                    <input type="text" name="modelo" class="form-control" value="{{ $equipo->modelo }}" >
                </div>
            </div>

            <div class="row">
                {{-- TIPO EQUIPO --}}
                <div class="form-group col-md-6">
                    <label><i class="fas fa-microchip"></i> Tipo del equipo</label>
                    <input type="text" class="form-control" value="{{ $equipo->tipoActivo->nombre ?? 'N/A' }}" readonly>
                    {{-- Este es el que salva el error: --}}
                    <input type="hidden" name="tipo_activo_id" value="{{ $equipo->tipo_activo_id }}">
                </div>

                {{-- SERIAL DEL EQUIPO --}}
                <div class="form-group col-md-6">
                    <label><i class="fas fa-barcode"></i> No. Serial del Equipo</label>
                    <input type="text" name="serial" class="form-control" value="{{ $equipo->serial }}" readonly>
                </div>
            </div>
        </fieldset>
    </div>
</div>