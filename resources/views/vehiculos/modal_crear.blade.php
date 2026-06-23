<div class="modal fade" id="modalCrearVehiculo" tabindex="-1" role="dialog" aria-labelledby="modalCrearVehiculoLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-dark">
                <h5 class="modal-title text-white" id="modalCrearVehiculoLabel">Registrar Nuevo Vehículo</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('vehiculos.store') }}" method="POST">
                @csrf
                
                <input type="hidden" name="empresa_id" value="{{ session('empresa_id') }}">

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="tipo_vehiculo_id">Tipo de Vehículo <span class="text-danger">*</span></label>
                            <select class="form-control" id="tipo_vehiculo_id" name="tipo_vehiculo_id" required>
                                <option value="" selected disabled>Selecciona un tipo...</option>
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="marca_id">Marca <span class="text-danger">*</span></label>
                            <select class="form-control" id="marca_id" name="marca_id" required>
                                <option value="" selected disabled>Selecciona una marca...</option>
                            </select>
                        </div>

                        <div class="col-md-6 form-group">
                            <label for="usuario_id">Usuario Asignado <span class="text-danger">*</span></label>
                            <select class="form-control" id="usuario_id" name="usuario_id" required>
                                <option value="" selected disabled>Selecciona un usuario...</option>
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="ubicacion_id">Ubicación <span class="text-danger">*</span></label>
                            <select class="form-control" id="ubicacion_id" name="ubicacion_id" required>
                                <option value="" selected disabled>Selecciona una ubicación...</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <hr class="my-3">
                        </div>

                        <div class="col-md-4 form-group">
                            <label for="modelo">Modelo / Versión <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="modelo" name="modelo" placeholder="Ej. Hilux" required>
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="anio">Año (Modelo) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="anio" name="anio" placeholder="Ej. 2024" required>
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="placas">Placas</label>
                            <input type="text" class="form-control" id="placas" name="placas" placeholder="Ej. NKL4589">
                        </div>

                        <div class="col-md-6 form-group">
                            <label for="no_serie">Número de Serie (VIN)</label>
                            <input type="text" class="form-control" id="no_serie" name="no_serie" placeholder="VIN de 17 dígitos">
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="no_motor">Número de Motor</label>
                            <input type="text" class="form-control" id="no_motor" name="no_motor" placeholder="Número grabado en motor">
                        </div>

                        <div class="col-md-4 form-group">
                            <label for="cilindros">Cilindros</label>
                            <input type="number" class="form-control" id="cilindros" name="cilindros" placeholder="Ej. 4">
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="tipo_combustible">Combustible</label>
                            <select class="form-control" id="tipo_combustible" name="tipo_combustible">
                                <option value="" selected disabled>Selecciona...</option>
                                <option value="Gasolina">Gasolina</option>
                                <option value="Diesel">Diesel</option>
                                <option value="Hibrido">Híbrido</option>
                                <option value="Eléctrico">Eléctrico</option>
                            </select>
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="fecha_ultimo_mantenimiento">Último Mantenimiento</label>
                            <input type="date" class="form-control" id="fecha_ultimo_mantenimiento" name="fecha_ultimo_mantenimiento">
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Registro</button>
                </div>
            </form>
        </div>
    </div>
</div>