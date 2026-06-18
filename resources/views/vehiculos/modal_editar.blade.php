<div class="modal fade" id="modalEditarVehiculo" tabindex="-1" role="dialog" aria-labelledby="modalEditarVehiculoLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title" id="modalEditarVehiculoLabel"><strong>Modificar Datos del Vehículo</strong></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formEditarVehiculo" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="edit_tipo_vehiculo_id">Tipo de Vehículo <span class="text-danger">*</span></label>
                            <select class="form-control" id="edit_tipo_vehiculo_id" name="tipo_vehiculo_id" required></select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="edit_marca_id">Marca <span class="text-danger">*</span></label>
                            <select class="form-control" id="edit_marca_id" name="marca_id" required></select>
                        </div>

                        <div class="col-md-6 form-group">
                            <label for="edit_usuario_id">Usuario Asignado <span class="text-danger">*</span></label>
                            <select class="form-control" id="edit_usuario_id" name="usuario_id" required></select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="edit_ubicacion_id">Ubicación <span class="text-danger">*</span></label>
                            <select class="form-control" id="edit_ubicacion_id" name="ubicacion_id" required></select>
                        </div>

                        <div class="col-12"><hr class="my-2"></div>

                        <div class="col-md-4 form-group">
                            <label for="edit_modelo">Modelo / Versión <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_modelo" name="modelo" required>
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="edit_anio">Año (Modelo) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="edit_anio" name="anio" required>
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="edit_placas">Placas</label>
                            <input type="text" class="form-control" id="edit_placas" name="placas">
                        </div>

                        <div class="col-md-6 form-group">
                            <label for="edit_no_serie">Número de Serie (VIN)</label>
                            <input type="text" class="form-control" id="edit_no_serie" name="no_serie">
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="edit_no_motor">Número de Motor</label>
                            <input type="text" class="form-control" id="edit_no_motor" name="no_motor">
                        </div>

                        <div class="col-12"><hr class="my-2"></div>

                        <div class="col-md-4 form-group">
                            <label for="edit_is_active">Estado Operativo</label>
                            <select class="form-control" id="edit_is_active" name="is_active">
                                <option value="1">Activo / Disponible</option>
                                <option value="0">Inactivo / Fuera de Servicio</option>
                            </select>
                        </div>
                        <div class="col-md-8 form-group" id="contenedor_motivo" style="display: none;">
                            <label for="edit_motivo_inactivacion">Motivo de Inactivación <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_motivo_inactivacion" name="motivo_inactivacion" placeholder="Ej. Taller mecánico / Baja por siniestro">
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-warning text-dark"><strong>Actualizar Datos</strong></button>
                </div>
            </form>
        </div>
    </div>
</div>