<div class="modal fade" id="modalDepreciacion" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content shadow-lg border-0">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title font-weight-bold">
                    <i class="fas fa-chart-bar mr-2 text-info depreciacion-icon-anim"></i>Depreciación en Tiempo Real
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body p-4">
                <div class="d-flex justify-content-between mb-3">
                    <span class="text-muted">Activo:</span>
                    <span class="font-weight-bold text-dark" id="d-activo"></span> {{-- ID VITAL --}}
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Valor Inicial:</span>
                    <span class="font-weight-bold text-primary">$<span id="d-valor"></span></span> {{-- ID VITAL --}}
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Años Transcurridos:</span>
                    <span class="badge badge-secondary px-3" id="d-añosTrasncurridos"></span> {{-- ID VITAL --}}
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <span class="text-muted">Depreciación Acumulada:</span>
                    <span class="font-weight-bold text-danger">-$<span id="d-depreciado"></span></span> {{-- ID VITAL --}}
                </div>
                <hr>
                <div class="bg-light p-3 rounded text-center">
                    <p class="text-muted mb-1 small uppercase font-weight-bold">Valor Actual de Mercado</p>
                    <h3 class="text-success font-weight-bold mb-0">$<span id="d-actual"></span></h3> {{-- ID VITAL --}}
                </div>
            </div>
            <div class="modal-footer bg-light border-0">
                <button type="button" class="btn btn-secondary btn-block" data-dismiss="modal">Cerrar Análisis</button>
            </div>
        </div>
    </div>
</div>