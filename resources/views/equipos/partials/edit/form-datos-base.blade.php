<div class="card card-outline card-info">
    <div class="card-header">
        <h3 class="card-title text-primary font-weight-bold">Información Base del Activo</h3>
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