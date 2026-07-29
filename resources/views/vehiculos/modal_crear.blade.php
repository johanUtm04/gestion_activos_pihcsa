<div class="modal fade" id="modalCrearVehiculo" tabindex="-1" role="dialog" aria-labelledby="modalCrearVehiculoLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header bg-dark">
                <h5 class="modal-title text-white" id="modalCrearVehiculoLabel">
                    Registrar Nuevo Vehículo
                </h5>

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
                            <label for="tipo_vehiculo_id">
                                Tipo de Vehículo <span class="text-danger">*</span>
                            </label>
                            <select class="form-control" id="tipo_vehiculo_id" name="tipo_vehiculo_id" required>
                                <option value="" selected disabled>Selecciona un tipo...</option>
                            </select>
                        </div>

                        <div class="col-md-6 form-group">
                            <label for="marca_id">
                                Marca <span class="text-danger">*</span>
                            </label>
                            <select class="form-control" id="marca_id" name="marca_id" required>
                                <option value="" selected disabled>Selecciona una marca...</option>
                            </select>
                        </div>

                        <div class="col-md-6 form-group">
                            <label for="usuario_id">
                                Usuario Asignado <span class="text-danger">*</span>
                            </label>
                            <select class="form-control" id="usuario_id" name="usuario_id" required>
                                <option value="" selected disabled>Selecciona un usuario...</option>
                            </select>
                        </div>

                        <div class="col-md-6 form-group">
                            <label for="ubicacion_id">
                                Ubicación <span class="text-danger">*</span>
                            </label>
                            <select class="form-control" id="ubicacion_id" name="ubicacion_id" required>
                                <option value="" selected disabled>Selecciona una ubicación...</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <hr class="my-3">
                        </div>

                        <div class="col-md-4 form-group">
                            <label for="modelo">
                                Modelo / Versión <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   class="form-control"
                                   id="modelo"
                                   name="modelo"
                                   placeholder="Ej. Hilux"
                                   required>
                        </div>

                        <div class="col-md-4 form-group">
                            <label for="anio">
                                Año (Modelo) <span class="text-danger">*</span>
                            </label>
                            <input type="number"
                                   class="form-control"
                                   id="anio"
                                   name="anio"
                                   placeholder="Ej. 2024"
                                   required>
                        </div>

                        <div class="col-md-4 form-group">
                            <label for="placas">Placas</label>
                            <input type="text"
                                   class="form-control"
                                   id="placas"
                                   name="placas"
                                   placeholder="Ej. NKL4589">
                        </div>

                        <div class="col-md-6 form-group">
                            <label for="no_serie">Número de Serie (VIN)</label>
                            <input type="text"
                                   class="form-control"
                                   id="no_serie"
                                   name="no_serie"
                                   placeholder="VIN de 17 dígitos">
                        </div>

                        <div class="col-md-6 form-group">
                            <label for="no_motor">Número de Motor</label>
                            <input type="text"
                                   class="form-control"
                                   id="no_motor"
                                   name="no_motor"
                                   placeholder="Número grabado en motor">
                        </div>

                        <div class="col-md-4 form-group">
                            <label for="cilindros">Cilindros</label>
                            <input type="number"
                                   class="form-control"
                                   id="cilindros"
                                   name="cilindros"
                                   placeholder="Ej. 4">
                        </div>

                        <div class="col-md-4 form-group">
                            <label for="pedimento">Pedimento</label>
                            <input type="text"
                                   class="form-control"
                                   id="pedimento"
                                   name="pedimento"
                                   placeholder="Ej. 23 48 1234 5001234">
                            <small class="text-muted">
                                Solo aplica para vehículos importados o regularizados.
                            </small>
                        </div>

                        <div class="col-md-4 form-group">
                            <label for="cuenta_contable">Cuenta contable</label>
                            <input type="text"
                                   class="form-control"
                                   id="cuenta_contable"
                                   name="cuenta_contable"
                                   placeholder="Ej. 55 48 1234 5001234">
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
                            <input type="date"
                                   class="form-control"
                                   id="fecha_ultimo_mantenimiento"
                                   name="fecha_ultimo_mantenimiento">
                        </div>

                        <div class="col-12">
                            <h6 class="text-muted font-weight-bold my-2">
                                <i class="fas fa-dollar-sign mr-1"></i>
                                Control Financiero y Ciclo de Vida
                            </h6>
                            <hr class="mt-1 mb-3">
                        </div>

                        <div class="col-md-6 form-group">
                            <label for="valor_inicial">Valor Inicial (Costo)</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">$</span>
                                </div>
                                <input type="number"
                                       step="0.01"
                                       class="form-control"
                                       id="valor_inicial"
                                       name="valor_inicial"
                                       placeholder="0.00">
                            </div>
                        </div>

                        <div class="col-md-6 form-group">
                            <label for="vida_util_estimada">Vida Útil Estimada (Años)</label>
                            <input type="number"
                                   class="form-control"
                                   id="vida_util_estimada"
                                   name="vida_util_estimada"
                                   placeholder="Ej. 5">
                        </div>

                        <div class="col-md-6 form-group">
                            <label for="fecha_adquisicion">Fecha de Adquisición</label>
                            <input type="date"
                                   class="form-control"
                                   id="fecha_adquisicion"
                                   name="fecha_adquisicion">
                        </div>

                        <div class="col-md-6 form-group">
                            <label for="fecha_inicio_uso">Fecha Inicio de Uso</label>
                            <input type="date"
                                   class="form-control"
                                   id="fecha_inicio_uso"
                                   name="fecha_inicio_uso">
                        </div>

                    </div>
                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Cancelar
                    </button>

                    <button type="submit" class="btn btn-primary">
                        Guardar Registro
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>