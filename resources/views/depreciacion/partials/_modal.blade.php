<!-- Modal Depreciacion -->
<div class="modal fade" id="modalDepreciacion" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg shadow-lg">
        <div class="modal-content border-0">
            <div class="modal-header bg-navy">
                <h5 class="modal-title">
                    Depreciación: <span id="span-marca" class="badge badge-warning"></span>
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body bg-light">
                <div class="row">
                    <div class="col-md-5">
                        <div class="info-box bg-white border">
                            <span class="info-box-icon bg-info"><i class="fas fa-coins"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text text-muted">Monto Original (MOI)</span>
                                <span class="info-box-number h5" id="val-moi-text">$0.00</span>
                                <input type="hidden" id="hidden-moi">
                                <input type="hidden" id="hidden-fecha">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="small">Tasa de Depreciación (%)</label>
                            <select id="select-tasa" class="form-control form-control-sm select2">
                                <option value="0.30">30% - Equipo de Cómputo</option>
                                <option value="0.10">10% - Mobiliario y Equipo</option>
                                <option value="0.25">25% - Automóviles</option>
                                <option value="0.05">5% - Edificios</option>
                            </select>
                        </div>
                        <button type="button" class="btn btn-navy btn-block shadow-sm" id="btn-recalcular">
                            <i class="fas fa-sync mr-1"></i> Procesar Cálculo
                        </button>
                    </div>

                    <div class="col-md-7">
                        <div id="calculo-animado">
                            {{-- Aquí se inyectan los resultados con JS --}}
                            <div class="text-center p-4 text-muted border rounded bg-white h-100">
                                <i class="fas fa-calculator fa-3x mb-3 opacity-25"></i>
                                <p>Presione el botón para generar la proyección fiscal.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>