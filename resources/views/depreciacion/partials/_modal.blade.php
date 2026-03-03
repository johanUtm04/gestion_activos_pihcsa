<div class="modal fade" id="modalDepreciacion" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content shadow-lg border-0">
            <div class="modal-header bg-dark text-white p-4" style="background: linear-gradient(45deg, #1a1a1a, #343a40);">
                <h5 class="modal-title font-weight-bold">
                    <i class="fas fa-chart-line mr-2 text-info"></i>Análisis de Valoración
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body p-4 bg-white">
                <div class="text-center mb-4">
                    <h6 class="text-uppercase text-muted small font-weight-bold mb-1">Equipo Identificado</h6>
                    <h4 class="font-weight-bold text-dark" id="d-activo">---</h4>
                    <span class="badge badge-pill badge-info px-3 py-2" id="d-añosTrasncurridos"></span>
                </div>

                <div class="row no-gutters mb-4 shadow-sm rounded border">
                    <div class="col-6 border-right p-3 bg-light">
                        <small class="text-muted d-block mb-1">Costo Original</small>
                        <span class="h5 font-weight-bold text-primary">$<span id="d-valor"></span></span>
                    </div>
                    <div class="col-6 p-3 bg-light">
                        <small class="text-muted d-block mb-1">Depreciación</small>
                        <span class="h5 font-weight-bold text-danger">-$<span id="d-depreciado"></span></span>
                    </div>
                </div>

                <div class="mb-4">
                    <div class="d-flex justify-content-between mb-1">
                        <small class="font-weight-bold text-muted">Vida Útil Remanente</small>
                        <small id="d-porcentaje-text" class="font-weight-bold"></small>
                    </div>
                    <div class="progress" style="height: 12px; border-radius: 10px;">
                        <div id="d-progreso" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"></div>
                    </div>
                </div>

                <div class="py-3 px-2 rounded-lg text-center" style="background-color: #f8f9fa; border: 2px dashed #dee2e6;">
                    <p class="text-muted mb-1 small text-uppercase font-weight-bold">Valor Actual de Libros</p>
                    <h2 class="text-success font-weight-bold mb-0">
                        $<span id="d-actual"></span>
                    </h2>
                </div>
            </div>

            <div class="modal-footer border-0 p-3">
                <button type="button" class="btn btn-outline-dark btn-block font-weight-bold py-2" data-dismiss="modal">
                    Finalizar Consulta
                </button>
            </div>
        </div>
    </div>
</div>