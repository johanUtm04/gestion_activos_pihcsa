<div class="modal fade" id="modalInactivar" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-danger">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="fas fa-exclamation-triangle mr-2"></i> Confirmar Inactivación</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Estás a punto de inactivar el equipo: <strong id="nombreEquipoModal"></strong></p>
                <div class="form-group">
                    <label for="motivo_texto">Motivo de la baja:</label>
                    <textarea class="form-control" id="motivo_texto" rows="3" placeholder="Ej. Daño irreparable, obsolescencia..."></textarea>
                    <small class="text-danger d-none" id="errorMotivo">El motivo es obligatorio (mín. 10 caracteres).</small>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="btnConfirmarInactivacion">Inactivar Activo</button>
            </div>
        </div>
    </div>
</div>