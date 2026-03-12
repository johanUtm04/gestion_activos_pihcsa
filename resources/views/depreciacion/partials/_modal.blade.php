<div class="modal fade" id="modalDepreciacion" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content shadow-lg border-0" style="border-radius: 15px;">
            <div class="modal-header bg-gradient-dark p-3">
                <h5 class="modal-title text-white font-weight-light">
                    <i class="fas fa-file-invoice-dollar mr-2 text-success"></i>
                    Análisis Fiscal: <span id="nombreActivo" class="font-weight-bold text-uppercase"></span>
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body bg-light p-4">
                <input type="hidden" id="hidden_fecha_adquisicion">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="card card-outline card-info shadow-sm mb-3">
                            <div class="card-header"><h3 class="card-title font-weight-bold">1. Datos de Entrada</h3></div>
                            <div class="card-body">
                                <div class="callout callout-info py-2 mb-3">
                                    <small class="text-muted"><i class="fas fa-info-circle mr-1"></i> Ingrese los costos adicionales para determinar el <strong>MOI Real</strong>.</small>
                                </div>
                                <div class="form-group">
                                    <label class="small">Valor de Factura</label>
                                    <input type="text" id="in_valor_inicial" class="form-control form-control-sm bg-light" readonly>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label class="small text-primary">Fletes/Seguros</label>
                                            <input type="number" id="in_gastos_extras" class="form-control form-control-sm border-info" value="0">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label class="small text-primary">Impuestos Imp.</label>
                                            <input type="number" id="in_impuestos" class="form-control form-control-sm border-info" value="0">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="small font-weight-bold">Fecha Inicio Uso</label>
                                    <input type="date" id="in_fecha_uso" class="form-control form-control-sm border-success">
                                </div>
                                <div class="form-group">
                                    <label class="small font-weight-bold">Tipo de Activo (Tasa LISR)</label>
                                    <select id="in_tasa" class="form-control form-control-sm border-success shadow-sm">
                                        <option value="" disabled selected>-- Seleccione una opción --</option>
                                        @foreach($tasas as $tasa)
                                            <option value="{{ $tasa->porcentaje / 100 }}">
                                                {{ number_format($tasa->porcentaje, 0) }}% - {{ $tasa->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="button" id="btnProcesarCalculo" class="btn btn-info btn-block shadow-sm font-weight-bold">
                                    <i class="fas fa-calculator mr-2"></i>EJECUTAR ANÁLISIS
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-8">
                        <div class="card shadow-sm border-0">
                            <div class="card-body p-4">
                                <div class="mb-4">
                                    <h6 class="font-weight-bold text-dark border-bottom pb-2">
                                        <span class="badge badge-dark mr-2">I</span> CÁLCULO DE LA DEDUCCIÓN LINEAL
                                    </h6>
                                    <table class="table table-sm table-borderless">
                                        <tr>
                                            <td><i class="fas fa-arrow-right text-xs mr-2"></i>Monto Original de Inversión (MOI)</td>
                                            <td class="text-right font-weight-bold" id="out_moi">$0.00</td>
                                        </tr>
                                        <tr>
                                            <td><i class="fas fa-percentage text-xs mr-2"></i>% Tasa de Deducción Aplicada</td>
                                            <td class="text-right" id="out_tasa_text">0%</td>
                                        </tr>
                                        <tr class="table-secondary">
                                            <td class="font-weight-bold">(=) Deducción Máxima Anual</td>
                                            <td class="text-right font-weight-bold" id="out_max_anual">$0.00</td>
                                        </tr>
                                        <tr>
                                            <td><i class="fas fa-calendar-day text-xs mr-2"></i>Meses de uso en el presente ejercicio</td>
                                            <td class="text-right font-weight-bold text-info" id="out_meses_uso">0</td>
                                        </tr>
                                        <tr class="bg-primary text-white">
                                            <td class="font-weight-bold text-uppercase">(=) Deducción del Ejercicio S/ Actualizar</td>
                                            <td class="text-right font-weight-bold" id="out_total_sin_act">$0.00</td>
                                        </tr>
                                    </table>
                                    <p class="small text-muted mt-2 mb-0">
                                        <i class="fas fa-lightbulb mr-1 text-warning"></i> 
                                        <strong>Nota:</strong> Este monto representa el desgaste contable del equipo basado únicamente en el tiempo transcurrido desde su inicio de uso.
                                    </p>
                                </div>

                                <div id="seccion-actualizacion" style="opacity: 0.3;">
                                    <h6 class="font-weight-bold text-dark border-bottom pb-2">
                                        <span class="badge badge-dark mr-2">II</span> AJUSTE POR INFLACIÓN (EFECTO FISCAL)
                                    </h6>
                                    <table class="table table-sm table-borderless">
                                        <tr>
                                            <td>INPC Último mes 1ra mitad del periodo de uso</td>
                                            <td class="text-right" id="out_inpc_mitad">0.0000</td>
                                        </tr>
                                        <tr>
                                            <td>(/) INPC del mes de adquisición del bien</td>
                                            <td class="text-right" id="out_inpc_adq">0.0000</td>
                                        </tr>
                                        <tr class="table-warning font-weight-bold text-dark">
                                            <td>(=) Factor de Actualización</td>
                                            <td class="text-right" id="out_factor">0.0000</td>
                                        </tr>
                                        <tr class="h4 bg-success text-white">
                                            <td class="font-weight-bold">DEDUCCIÓN TOTAL ACTUALIZADA</td>
                                            <td class="text-right font-weight-bold" id="out_total_actualizado">$0.00</td>
                                        </tr>
                                    </table>
                                    <p class="small text-muted mt-2">
                                        <i class="fas fa-shield-alt mr-1 text-success"></i> 
                                        <strong>Explicación:</strong> Se aplica el factor de actualización para reconocer el valor del dinero en el tiempo, multiplicando la deducción base por el factor inflacionario derivado del INPC.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-white border-top">
                <button type="button" class="btn btn-outline-secondary btn-sm" data-dismiss="modal">Cerrar Ventana</button>
            </div>
        </div>
    </div>
</div>