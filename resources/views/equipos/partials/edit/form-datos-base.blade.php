<div class="card card-outline card-info">
    <div class="card-header">
        <h3 class="card-title text-primary font-weight-bold">Datos editables</h3>
    </div>
    <div class="card-body">
        <fieldset class="border p-3 mb-4 rounded">
            <legend class="w-auto px-2 text-primary text-sm font-weight-bold">
                <i class="fas fa-info-circle"></i> DATOS BASE DEL ACTIVO
            </legend>

            <div class="row">
                {{-- MARCA EQUIPO --}}
                <div class="form-group col-md-6">
                    <label for="marca_id"><i class="fas fa-tag"></i> Marca del Equipo</label>
                    <select name="marca_id" id="marca_id" class="form-control"
                        data-label=" la marca del equipo"
                        data-current="{{ $equipo->marca_id }}"
                        data-motivo-input="#motivo_cambio_marca">
                        <option value="">Seleccione una marca</option>
                        @foreach($marcas as $item)
                            <option value="{{ $item->id }}" {{ $equipo->marca_id == $item->id ? 'selected' : '' }}>
                                {{ $item->nombre }}
                            </option>
                        @endforeach
                    </select>
                    <input type="hidden" id="motivo_cambio_marca">
                </div>

                {{-- MODELO DEL EQUIPO --}}
                <div class="form-group col-md-6">
                    <label for="modelo"><i class="fas fa-laptop-code"></i> Modelo Específico</label>
                    <input type="text" name="modelo" id="modelo" 
                        class="form-control" 
                        placeholder="Ej: Latitude 5420 / ThinkPad X1"
                        value="{{ old('modelo', $equipo->modelo) }}"
                        data-current="{{ $equipo->modelo }}"
                        data-label=" el modelo"
                        data-motivo-input="#motivo_cambio_modelo">
                    <input type="hidden" id="motivo_cambio_modelo">
                </div>
            </div>

            <div class="row">
                {{-- TIPO EQUIPO --}}
                <div class="form-group col-md-6">
                    <label for="tipo_activo_id"><i class="fas fa-microchip"></i> Tipo del equipo</label>
                    <select name="tipo_activo_id" id="tipo_activo_id" class="form-control"
                        data-label=" el tipo de activo"
                        data-current="{{ $equipo->tipo_activo_id }}"
                        data-motivo-input="#motivo_cambio_tipo">
                        <option value="">Seleccione un tipo de Activo</option>
                        @foreach($tiposActivo as $item)
                            <option value="{{ $item->id }}" {{ $equipo->tipo_activo_id == $item->id ? 'selected' : '' }}>
                                {{ $item->nombre }}
                            </option>
                        @endforeach
                    </select>
                    <input type="hidden" id="motivo_cambio_tipo">
                </div>

                {{-- SERIAL DEL EQUIPO --}}
                <div class="form-group col-md-6">
                    <label for="serial"><i class="fas fa-barcode"></i> No. Serial del Equipo</label>
                    <input type="text" name="serial" id="serial" 
                        class="form-control @error('serial') is-invalid @enderror" 
                        placeholder="Ingrese el serial"
                        data-current="{{ $equipo->serial }}"
                        data-label=" el numero serial"
                        value="{{ old('serial', $equipo->serial) }}" 
                        data-motivo-input="#motivo_cambio_serial"
                        required
                        style="text-transform: uppercase;"
                        onkeyup="this.value = this.value.toUpperCase();">
                    
                    @error('serial')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                    <input type="hidden" name="motivos[serial]" id="motivo_cambio_serial">
                </div>
            </div>
        </fieldset>
    </div>
</div>