@extends('adminlte::page')

@section('title', 'Mantenimiento de Vehículo')

@section('css')
<style>
    .fieldset-group {
        border: 1px solid #ced4da;
        padding: 20px;
        margin-bottom: 20px;
        border-radius: .25rem;
        background-color: #ffffff;
    }

    .fieldset-group legend {
        width: inherit;
        padding: 0 10px;
        border-bottom: none;
        font-size: 1.1em;
        font-weight: 600;
        color: #007bff;
    }

    .form-group label {
        font-weight: 600;
    }

    .custom-input { display: none; margin-top: 10px; }

    .file-hint {
        font-size: 0.8rem;
        color: #6c757d;
    }
</style>
@stop

@section('content_header')
<div class="mb-3">
    <div class="d-flex justify-content-between align-items-center">
        <a href="{{ route('vehiculos.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Volver al inventario
        </a>
    </div>
</div>
@stop

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title font-weight-bold">
                        <i class="fas fa-tools"></i>
                        Mantenimiento de Vehículo: {{ $vehiculo->marca->nombre ?? '[Vehículo]' }} | Placas: {{ $vehiculo->placas ?? 'S/P' }}
                    </h3>
                </div>

                {{-- enctype es obligatorio para poder subir los archivos --}}
                <form method="POST" action="{{ route('vehiculos.addwork.store', $vehiculo) }}" enctype="multipart/form-data">
                    @csrf

                    <div class="card-body">
                        <fieldset class="fieldset-group">
                            <legend>
                                <i class="fas fa-clipboard-list"></i>
                                Detalle del evento operativo
                            </legend>

                            <div class="form-group">
                                <label>Tipo de evento</label>
                                <select class="form-control" id="tipo_evento" name="tipo_evento" required>
                                    <option value="">Seleccione una opción</option>
                                    <option value="Mantenimiento preventivo">Mantenimiento preventivo (Afinación, Aceite)</option>
                                    <option value="Mantenimiento correctivo">Mantenimiento correctivo (Reparación)</option>
                                    <option value="Carga de combustible">Carga de combustible</option>
                                    <option value="Verificación / Seguro">Trámite (Verificación / Seguro)</option>
                                    <option value="OTRO_VALOR">-- Otro evento (Escribir) --</option>
                                </select>
                                <input type="text" name="tipo_evento_input" id="tipo_evento_input" 
                                       class="form-control custom-input" 
                                       placeholder="Ej. Cambio de neumáticos, Ajuste de frenos..."
                                       value="{{ old('tipo_evento_input') }}">
                            </div>

                            <div class="form-group">
                                <label for="proveedor">Nombre del proveedor / taller</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-store"></i></span>
                                    </div>
                                    <input type="text" class="form-control" name="proveedor" id="proveedor"
                                           placeholder="Ej. Refaccionaria Morelia, Taller Hnos. Ramírez..."
                                           value="{{ old('proveedor') }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Usuario que realiza la acción</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-user-check"></i></span>
                                    </div>
                                    <input type="text" class="form-control" value="{{ auth()->user()->name }}" disabled>
                                    <input type="hidden" name="usuario_id" value="{{ auth()->user()->id }}">
                                </div>
                                <small class="text-muted">El registro quedará vinculado a tu sesión actual.</small>
                            </div>

                            <div class="form-group">
                                <label for="kilometraje">Kilometraje Actual</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-tachometer-alt"></i></span>
                                    </div>
                                    <input type="number" min="0" class="form-control" name="kilometraje" placeholder="Ej. 85400" required>
                                    <div class="input-group-append">
                                        <span class="input-group-text">KM</span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Fecha del evento</label>
                                <input type="date" class="form-control" name="fecha_evento" value="{{ date('Y-m-d') }}" required>
                            </div>

                            <div class="form-group">
                                <label>Contexto del evento / Notas detalladas</label>
                                <textarea class="form-control" rows="4" name="contexto"
                                          placeholder="Descripción clara de los servicios aplicados al coche o motivo de la incidencia..." required></textarea>
                            </div>

                            <div class="form-group">
                                <label>Costo (opcional)</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">$</span>
                                    </div>
                                    <input type="number" step="0.01" min="0" class="form-control" name="costo" placeholder="0.00">
                                </div>
                            </div>
                        </fieldset>

                        <fieldset class="fieldset-group">
                            <legend>
                                <i class="fas fa-paperclip"></i>
                                Documentos del servicio (opcional)
                            </legend>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="orden_servicio">Orden de servicio</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="orden_servicio"
                                               name="orden_servicio" accept=".pdf,.jpg,.jpeg,.png">
                                        <label class="custom-file-label" for="orden_servicio">Seleccionar archivo...</label>
                                    </div>
                                    <span class="file-hint">PDF o imagen, máx. 5MB. Solo si la tienes.</span>
                                    @error('orden_servicio')
                                        <span class="text-danger d-block">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="factura">Factura</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="factura"
                                               name="factura" accept=".pdf,.jpg,.jpeg,.png">
                                        <label class="custom-file-label" for="factura">Seleccionar archivo...</label>
                                    </div>
                                    <span class="file-hint">PDF o imagen, máx. 5MB. Solo si la tienes.</span>
                                    @error('factura')
                                        <span class="text-danger d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </fieldset>
                    </div>

                    <div class="card-footer text-right">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Registrar mantenimiento
                        </button>
                        <a href="{{ route('vehiculos.index') }}" class="btn btn-secondary">
                            Cancelar
                        </a>
                    </div>
                </form>

            </div>

        </div>
    </div>
</div>
@stop

@section('js')
<script>
    $(document).ready(function() {

        function setupSelectOtro(selectId, inputId) {
            const $select = $(`#${selectId}`);
            const $input = $(`#${inputId}`);

            $select.on('change', function() {
                if ($(this).val() === 'OTRO_VALOR') {
                    $input.fadeIn().focus().prop('required', true);
                } else {
                    $input.hide().val($(this).val()).prop('required', false); 
                }
            });

            let initialVal = $input.val();
            if(initialVal && !$select.find(`option[value='${initialVal}']`).length && initialVal !== '') {
                $select.val('OTRO_VALOR');
                $input.show().prop('required', true);
            }
        }

        setupSelectOtro('tipo_evento', 'tipo_evento_input');

        $('.custom-file-input').on('change', function () {
            const fileName = this.files[0] ? this.files[0].name : 'Seleccionar archivo...';
            $(this).next('.custom-file-label').html(fileName);
        });
    });
</script>
@stop