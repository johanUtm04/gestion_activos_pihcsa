<div class="card card-outline card-info">
    <div class="card-header">
        <h3 class="card-title text-primary font-weight-bold">Datos del Activo</h3>
    </div>
    <div class="card-body">
        <fieldset class="border p-3 mb-4 rounded">
            <legend class="w-auto px-2 text-primary text-sm font-weight-bold">
                <i class="fas fa-info-circle"></i> DATOS BASE DEL ACTIVO
            </legend>

            <div class="row">
                {{-- MARCA EQUIPO (Ahora como Input) --}}
                <div class="form-group col-md-6">
                    <label><i class="fas fa-tag"></i> Marca del Equipo</label>
                    <input type="text" class="form-control" 
                        value="{{ $equipo->marca->nombre ?? 'N/A' }}" readonly>
                </div>

                {{-- MODELO DEL EQUIPO --}}
                <div class="form-group col-md-6">
                    <label for="modelo"><i class="fas fa-laptop-code"></i> Modelo Específico</label>
                    <input type="text" id="modelo" class="form-control" 
                        value="{{ $equipo->modelo }}" readonly>
                </div>
            </div>

            <div class="row">
                {{-- TIPO EQUIPO (Ahora como Input) --}}
                <div class="form-group col-md-6">
                    <label><i class="fas fa-microchip"></i> Tipo del equipo</label>
                    <input type="text" class="form-control" 
                        value="{{ $equipo->tipoActivo->nombre ?? 'N/A' }}" readonly>
                </div>

                {{-- SERIAL DEL EQUIPO --}}
                <div class="form-group col-md-6">
                    <label for="serial"><i class="fas fa-barcode"></i> No. Serial del Equipo</label>
                    <input type="text" id="serial" class="form-control" 
                        value="{{ $equipo->serial }}" readonly 
                        style="text-transform: uppercase;">
                </div>
            </div>
        </fieldset>
    </div>
</div>