<div id="panel-resumen-cambios" class="card card-outline card-warning mt-3 shadow-lg" style="display: none; border-radius: 12px; border-top-width: 3px;">
    <div class="card-header border-0 bg-white">
        <h3 class="card-title text-warning font-weight-bold">
            <i class="fas fa-sync-alt fa-spin mr-2"></i> Resumen de la Bolsa de Cambios
        </h3>
        <div class="card-tools">
            <span class="badge badge-warning px-3 py-2 shadow-sm">
                <i class="fas fa-clock mr-1"></i> Cambios por aplicar
            </span>
        </div>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead class="bg-light text-muted" style="font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px;">
                    <tr>
                        <th class="pl-4 border-0" style="width: 35%">Elemento / Propiedad</th>
                        <th class="border-0" style="width: 25%">Dato Anterior</th>
                        <th class="text-center border-0" style="width: 10%"></th>
                        <th class="border-0" style="width: 30%">Nuevo Dato</th>
                    </tr>
                </thead>
                <tbody id="lista-cambios" style="vertical-align: middle;">
                    <tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card-footer bg-light border-top-0 d-flex justify-content-between align-items-center py-3" style="border-radius: 0 0 12px 12px;">
        <div id="contadores-cambios">
            <span class="badge badge-info mr-2 p-2 shadow-sm" id="cnt-mod" style="display:none;">
                <i class="fas fa-pen mr-1"></i> <span class="num">0</span> Modificaciones
            </span>
            <span class="badge badge-success mr-2 p-2 shadow-sm" id="cnt-add" style="display:none;">
                <i class="fas fa-plus-circle mr-1"></i> <span class="num">0</span> Nuevos
            </span>
            <span class="badge badge-danger p-2 shadow-sm" id="cnt-del" style="display:none;">
                <i class="fas fa-arrow-down mr-1"></i> <span class="num">0</span> Bajas
            </span>
        </div>
        <div class="text-right">
            <small class="text-muted italic">Revisa los cambios antes de procesar la actualización.</small>
        </div>
    </div>
</div>