<div class="modal fade" id="modalInactivar" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content shadow-lg border-0">
            <div class="modal-body p-4 text-center">
                <h4 class="font-weight-bold text-dark mb-2">Justificar inactivación del equipo</h4>
                <p class="text-muted small">Por favor, indica el motivo de la baja:</p>
                
                <form id="formInactivar" method="POST">
                    @csrf
                    <div class="form-group">
                        <textarea name="motivo" id="motivoInactivacion" class="form-control" rows="3" placeholder="Ej: Equipo dañado, Renovación tecnológica..." required></textarea>
                    </div>
                    
                    <div class="d-flex justify-content-center mt-4" style="gap: 15px;">
                        <button type="submit" class="btn btn-primary px-4 shadow-sm" style="background-color: #3182ce; border: none;">Confirmar</button>
                        <button type="button" class="btn btn-secondary px-4 shadow-sm" data-dismiss="modal" style="background-color: #a0aec0; border: none;">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>