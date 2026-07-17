<div class="card card-outline card-secondary shadow-sm mb-3" id="cardConcentradoAnual">
    <div class="card-header d-flex justify-content-between align-items-center"
         style="cursor: pointer;"
         id="toggleConcentradoAnual">

        <div>
            <h3 class="card-title font-weight-bold mb-0">
                <i class="fas fa-calendar-alt mr-2"></i>
                Concentrado Anual de Depreciación
            </h3>
            <small class="text-muted">
                Resumen estimado por año con base en valor inicial, fecha de adquisición y vida útil.
            </small>
        </div>

        <button type="button"
                class="btn btn-sm btn-outline-secondary"
                id="btnToggleConcentrado">
            <i class="fas fa-chevron-down mr-1"></i>
            Mostrar
        </button>
    </div>

    <div class="card-body p-0 concentrado-collapsible" id="bodyConcentradoAnual">
        <div class="concentrado-inner">
            @if(!empty($concentradoAnual) && count($concentradoAnual) > 0)
                <div class="table-responsive concentrado-wrapper">
                    <table class="table table-sm table-striped table-hover mb-0 tabla-concentrado" id="tablaConcentradoAnual">
                        <thead class="thead-light">
                            <tr>
                                <th class="text-center">Año</th>
                                <th class="text-center">Activos considerados</th>
                                <th class="text-right">Valor inicial total</th>
                                <th class="text-right">Depreciación del año</th>
                                <th class="text-right">Depreciación acumulada</th>
                                <th class="text-right">Valor en libros</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($concentradoAnual as $fila)
                                <tr class="fila-concentrado" data-anio="{{ $fila['anio'] }}">
                                    <td class="text-center font-weight-bold">
                                        {{ $fila['anio'] }}
                                    </td>

                                    <td class="text-center">
                                        <span class="badge badge-secondary">
                                            {{ $fila['activos'] }}
                                        </span>
                                    </td>

                                    <td class="text-right font-weight-bold">
                                        ${{ number_format($fila['valor_inicial_total'], 2) }}
                                    </td>

                                    <td class="text-right text-danger font-weight-bold">
                                        ${{ number_format($fila['depreciacion_del_anio'], 2) }}
                                    </td>

                                    <td class="text-right text-warning font-weight-bold">
                                        ${{ number_format($fila['depreciacion_acumulada'], 2) }}
                                    </td>

                                    <td class="text-right text-success font-weight-bold">
                                        ${{ number_format($fila['valor_en_libros'], 2) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                        <tfoot class="bg-light">
                            <tr>
                                <th colspan="2" class="text-right">Totales:</th>

                                <th class="text-right">
                                    ${{ number_format(collect($concentradoAnual)->sum('valor_inicial_total'), 2) }}
                                </th>

                                <th class="text-right">
                                    ${{ number_format(collect($concentradoAnual)->sum('depreciacion_del_anio'), 2) }}
                                </th>

                                <th class="text-right">
                                    ${{ number_format(collect($concentradoAnual)->max('depreciacion_acumulada'), 2) }}
                                </th>

                                <th class="text-right">
                                    ${{ number_format(collect($concentradoAnual)->last()['valor_en_libros'] ?? 0, 2) }}
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @else
                <div class="p-4 text-center text-muted">
                    <i class="fas fa-secondary-circle fa-2x mb-2"></i>
                    <p class="mb-0">
                        No hay información suficiente para calcular el concentrado anual.
                    </p>
                    <small>
                        Verifica que los activos tengan valor inicial, fecha de adquisición y vida útil estimada.
                    </small>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    .concentrado-wrapper {
        overflow-x: hidden !important;
    }

    .tabla-concentrado {
        width: 100% !important;
        table-layout: fixed;
    }

    .tabla-concentrado th,
    .tabla-concentrado td {
        white-space: normal !important;
        font-size: 0.82rem;
        padding: 0.45rem 0.5rem;
    }

    .tabla-concentrado th:nth-child(1),
    .tabla-concentrado td:nth-child(1) {
        width: 8%;
    }

    .tabla-concentrado th:nth-child(2),
    .tabla-concentrado td:nth-child(2) {
        width: 13%;
    }

    .tabla-concentrado th:nth-child(3),
    .tabla-concentrado td:nth-child(3),
    .tabla-concentrado th:nth-child(4),
    .tabla-concentrado td:nth-child(4),
    .tabla-concentrado th:nth-child(5),
    .tabla-concentrado td:nth-child(5),
    .tabla-concentrado th:nth-child(6),
    .tabla-concentrado td:nth-child(6) {
        width: 19.75%;
    }

    .concentrado-collapsible {
        max-height: 0;
        overflow: hidden;
        opacity: 0;
        transform: translateY(-8px);
        transition:
            max-height 0.42s ease,
            opacity 0.28s ease,
            transform 0.28s ease;
    }

    .concentrado-collapsible.concentrado-abierto {
        max-height: 650px;
        opacity: 1;
        transform: translateY(0);
    }

    .concentrado-inner {
        transform: scale(0.985);
        transition: transform 0.28s ease;
    }

    .concentrado-collapsible.concentrado-abierto .concentrado-inner {
        transform: scale(1);
    }

    #btnToggleConcentrado {
        transition: all 0.22s ease-in-out;
    }

    #btnToggleConcentrado:hover {
        transform: translateY(-1px);
        box-shadow: 0 3px 8px rgba(23, 162, 184, 0.25);
    }

    #btnToggleConcentrado i {
        transition: transform 0.25s ease;
    }

    #btnToggleConcentrado.btn-abierto i {
        transform: rotate(180deg);
    }

    #tablaConcentradoAnual .fila-concentrado {
        cursor: pointer;
        transition: all 0.18s ease-in-out;
    }

    #tablaConcentradoAnual .fila-concentrado:hover {
        background-color: #e8f8fb !important;
        transform: scale(1.002);
    }

    #tablaConcentradoAnual .fila-concentrado.fila-activa {
        background: linear-gradient(90deg, #d9f7fb 0%, #ffffff 100%) !important;
        box-shadow: inset 5px 0 0 #17a2b8;
        font-size: 1.02rem;
    }

    #tablaConcentradoAnual .fila-concentrado.fila-activa td {
        border-top: 2px solid #17a2b8;
        border-bottom: 2px solid #17a2b8;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggleHeader = document.getElementById('toggleConcentradoAnual');
        const body = document.getElementById('bodyConcentradoAnual');
        const btn = document.getElementById('btnToggleConcentrado');
        const filas = document.querySelectorAll('#tablaConcentradoAnual .fila-concentrado');

        if (toggleHeader && body && btn) {
            toggleHeader.addEventListener('click', function (event) {
                event.preventDefault();

                const estaAbierto = body.classList.contains('concentrado-abierto');

                if (estaAbierto) {
                    body.classList.remove('concentrado-abierto');
                    btn.classList.remove('btn-abierto');
                    btn.innerHTML = '<i class="fas fa-chevron-down mr-1"></i> Mostrar';
                } else {
                    body.classList.add('concentrado-abierto');
                    btn.classList.add('btn-abierto');
                    btn.innerHTML = '<i class="fas fa-chevron-up mr-1"></i> Ocultar';

                    setTimeout(function () {
                        body.scrollIntoView({
                            behavior: 'smooth',
                            block: 'nearest'
                        });
                    }, 180);
                }
            });
        }

        filas.forEach(function (fila) {
            fila.addEventListener('click', function () {
                filas.forEach(function (item) {
                    item.classList.remove('fila-activa');
                });

                fila.classList.add('fila-activa');

                fila.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
            });
        });
    });
</script>