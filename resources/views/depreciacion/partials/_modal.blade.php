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
                                    <div class="input-group input-group-sm">
                                        <input type="date" id="in_fecha_uso" class="form-control border-success">
                                        <div class="input-group-append" id="lock-icon" style="display: none;">
                                            <span class="input-group-text bg-white border-success text-warning">
                                                <i class="fas fa-lock" title="Fecha bloqueada (ya guardada en BD)"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <small class="text-muted" style="font-size: 0.7rem;">
                                        * Se utiliza para determinar los meses de uso en el año.
                                    </small>
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
</div><style>
    /* Suavizado de bordes y sombras para AdminLTE */
    .modal-fluid .modal-content { border-radius: 12px; border: none; }
    .modal-fluid .modal-header { border-bottom: 1px solid #eee; }
    
    /* Efecto de enfoque en la tarjeta de inputs */
    .card-input-section { background-color: #fcfcfc; border: 1px solid #e9ecef; }
    
    /* Animación para que la sección de resultados no aparezca de golpe */
    #seccion-actualizacion {
        transition: all 0.6s ease-in-out;
    }

    /* Estilo para los inputs para que se sientan más "limpios" */
    .form-control-minimal {
        border-radius: 0;
        border-top: 0;
        border-left: 0;
        border-right: 0;
        border-bottom: 2px solid #ddd;
        padding-left: 0;
        background: transparent !important;
    }
    .form-control-minimal:focus {
        box-shadow: none;
        border-bottom-color: #17a2b8;
    }

    /* Resaltado de valores finales */
    .text-value { font-family: 'Monaco', 'Consolas', monospace; letter-spacing: -0.5px; }
</style>

<div class="modal fade modal-fluid" id="modalDepreciacion" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content shadow-lg">
            
            <div class="modal-header bg-white">
                <h5 class="modal-title font-weight-bold">
                    <i class="fas fa-calculator text-info mr-2"></i>
                    Análisis de Depreciación Fiscal: <span id="nombreActivo" class="text-primary text-uppercase"></span>
                </h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body p-4">
                <input type="hidden" id="hidden_fecha_adquisicion">
                
                <div class="row">
                    <div class="col-lg-4 border-right">
                        <p class="text-muted mb-4 small font-weight-bold text-uppercase">1. Configuración del Activo</p>
                        
                        <div class="form-group mb-4">
                            <label class="small text-muted">Monto de Factura</label>
                            <input type="text" id="in_valor_inicial" class="form-control form-control-minimal font-weight-bold" readonly>
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <div class="form-group mb-4">
                                    <label class="small">Gastos Extras</label>
                                    <input type="number" id="in_gastos_extras" class="form-control form-control-minimal" value="0">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group mb-4">
                                    <label class="small">Impuestos</label>
                                    <input type="number" id="in_impuestos" class="form-control form-control-minimal" value="0">
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <label class="small font-weight-bold">Fecha de Inicio de Uso</label>
                            <input type="date" id="in_fecha_uso" class="form-control border-info shadow-sm">
                        </div>

                        <div class="form-group mb-4">
                            <label class="small font-weight-bold">Tipo de Activo (Tasa LISR)</label>
                            <select id="in_tasa" class="form-control shadow-sm border-info">
                                <option value="" disabled selected>-- Seleccione --</option>
                                @foreach($tasas as $tasa)
                                    <option value="{{ $tasa->porcentaje / 100 }}">
                                        {{ number_format($tasa->porcentaje, 0) }}% - {{ $tasa->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <button type="button" id="btnProcesarCalculo" class="btn btn-primary btn-block btn-lg shadow mt-4">
                            <i class="fas fa-play-circle mr-2"></i> Calcular Ahora
                        </button>
                    </div>

                    <div class="col-lg-8 bg-light py-3 rounded">
                        <div class="px-3">
                            <p class="text-muted mb-4 small font-weight-bold text-uppercase">2. Resultados del Análisis</p>
                            
                            <div class="card card-body border-0 shadow-sm mb-4">
                                <div class="row">
                                    <div class="col-sm-6 border-right">
                                        <label class="small text-muted d-block">MOI DETERMINADO</label>
                                        <span class="h4 font-weight-bold text-value" id="out_moi">$0.00</span>
                                    </div>
                                    <div class="col-sm-6 pl-md-4">
                                        <label class="small text-muted d-block">DEDUCCIÓN SIN ACTUALIZAR</label>
                                        <span class="h4 font-weight-bold text-info text-value" id="out_total_sin_act">$0.00</span>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <small class="text-muted">
                                        Basado en una tasa de <span id="out_tasa_text" class="font-weight-bold">0%</span> y <span id="out_meses_uso" class="font-weight-bold">0</span> meses de uso.
                                    </small>
                                </div>
                            </div>

                            <div id="seccion-actualizacion" class="opacity-50">
                                <div class="card card-body border-0 shadow-sm bg-dark text-white">
                                    <div class="row align-items-center">
                                        <div class="col-sm-7">
                                            <label class="small opacity-75 d-block">DEDUCCIÓN TOTAL ACTUALIZADA</label>
                                            <h2 class="font-weight-bold text-success mb-0 text-value" id="out_total_actualizado">$0.00</h2>
                                            <p class="small mb-0 mt-2 text-muted">Reconocimiento del efecto inflacionario (LISR).</p>
                                        </div>
                                        <div class="col-sm-5 text-right border-left border-secondary">
                                            <div class="mb-2">
                                                <small class="d-block opacity-50">FACTOR DE AJUSTE</small>
                                                <span class="h5 font-weight-bold text-warning" id="out_factor">0.0000</span>
                                            </div>
                                            <div class="small">
                                                <span id="out_inpc_mitad" class="opacity-75">0.0000</span> / <span id="out_inpc_adq" class="opacity-75">0.0000</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-outline-info btn-sm" onclick="window.print()">
                    <i class="fas fa-print"></i>
                </button>
            </div>
        </div>
    </div>
</div>