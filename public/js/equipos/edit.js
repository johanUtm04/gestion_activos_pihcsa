/**
 * EDITAR EQUIPO - Bolsa de Cambios 2.0
 * Frontend solamente.
 * La auditoría real se reforzará después en Laravel.
 */

const LABELS_GENERALES = {
    modelo: 'Modelo',
    serial: 'Serial',
    pedimento: 'Pedimento',
    cuenta_contable: 'Cuenta contable',
    sistema_operativo: 'Sistema Operativo',

    usuario_id: 'Usuario Responsable',
    ubicacion_id: 'Ubicación',
    empresa_id: 'Área',
    departamento_perteneciente: 'Departamento',

    valor_inicial: 'Valor Inicial',
    fecha_adquisicion: 'Fecha de Adquisición',
    fecha_inicio_uso: 'Fecha Inicio de Uso',
    vida_util_estimada: 'Vida Útil Estimada'
};


/* ============================================================
   UTILIDADES
============================================================ */

function escapeHtml(valor) {
    return String(valor ?? '')
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}


function valorNormalizado(valor) {
    return String(valor ?? '').trim();
}


function obtenerNombreCampo($input) {

    const name = $input.attr('name') || '';
    const id = $input.attr('id') || '';
    const dataLabel = valorNormalizado(
        $input.attr('data-label')
    );

    if (LABELS_GENERALES[name]) {
        return LABELS_GENERALES[name];
    }

    if (LABELS_GENERALES[id]) {
        return LABELS_GENERALES[id];
    }

    if (dataLabel) {
        return dataLabel;
    }

    const $label = $input
        .closest(
            '.form-group, .col-md-3, .col-md-4, .col-md-6, .col-md-8'
        )
        .find('label')
        .first();

    if ($label.length) {
        return valorNormalizado(
            $label.text()
        );
    }

    const ultimaParte =
        name.match(/\[([^\]]+)\]$/)?.[1]
        || name
        || id
        || 'Campo';


    const labelsComponente = {

        capacidad_gb: 'Capacidad',
        clock_mhz: 'Clock',
        tipo_chz: 'Tipo',

        tipo_hdd_ssd: 'Tipo',
        capacidad: 'Capacidad',

        interface: 'Interfaz / Conexión',
        serial: 'Serial',
        marca: 'Marca',

        escala_pulgadas: 'Escala en pulgadas',

        descripcion_tipo: 'Descripción / Tipo',
        clock_ghz: 'Frecuencia',

        tipo: 'Tipo de periférico',

        motivo_inactivo: 'Motivo de baja'
    };

    return labelsComponente[ultimaParte]
        || ultimaParte.replace(/_/g, ' ');
}


function obtenerTextoSelectPorValor($select, valor) {

    const buscado = valorNormalizado(valor);

    if (buscado === '') {
        return 'Sin asignar';
    }

    let texto = '';

    $select.find('option').each(function () {

        if (
            valorNormalizado($(this).val())
            === buscado
        ) {

            texto = valorNormalizado(
                $(this).text()
            );

            return false;
        }

    });

    return texto || buscado;
}


/* ============================================================
   FORMATO DE VALORES
============================================================ */

function formatearValorGeneral($input, valor) {

    const name = $input.attr('name') || '';
    const limpio = valorNormalizado(valor);

    if (limpio === '') {
        return 'Sin dato';
    }


    if ($input.is('select')) {

        return obtenerTextoSelectPorValor(
            $input,
            limpio
        );
    }


    if (name === 'valor_inicial') {

        const numero = Number(limpio);

        if (!Number.isNaN(numero)) {

            return '$' + numero.toLocaleString(
                'es-MX',
                {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }
            );
        }

    }


    if (name === 'vida_util_estimada') {

        return `${limpio} año(s)`;
    }


    if (
        name === 'fecha_adquisicion'
        || name === 'fecha_inicio_uso'
    ) {

        const partes = limpio.split('-');

        if (partes.length === 3) {

            return `${partes[2]}/${partes[1]}/${partes[0]}`;
        }

    }


    return limpio;
}


function formatearValorComponente($input, valor) {

    const limpio = valorNormalizado(valor);

    const name = $input.attr('name') || '';

    if (limpio === '') {
        return 'Sin dato';
    }


    if ($input.is('select')) {

        return obtenerTextoSelectPorValor(
            $input,
            limpio
        );
    }


    if (name.includes('[clock_ghz]')) {

        return `${limpio} GHz`;
    }


    return limpio;
}


/* ============================================================
   VALORES ORIGINALES
============================================================ */

function obtenerValorOriginal($input) {

    const dataCurrent =
        $input.attr('data-current');


    if (
        typeof dataCurrent !== 'undefined'
    ) {

        return valorNormalizado(
            dataCurrent
        );
    }


    return valorNormalizado(
        $input.attr('data-original-runtime')
    );
}


/**
 * Los campos viejos como modelo, serial, pedimento, etc.
 * todavía no tienen data-current.
 *
 * Guardamos su valor original al cargar la página.
 */
function capturarEstadoInicialGeneral() {

    $('#formEditarEquipo')

        .find(
            'input[name], select[name], textarea[name]'
        )

        .not('.item-componente *')

        .each(function () {

            const $input = $(this);

            const tipo =
                ($input.attr('type') || '')
                    .toLowerCase();

            const name =
                $input.attr('name') || '';


            if (
                tipo === 'hidden'
                || tipo === 'submit'
                || tipo === 'button'
            ) {
                return;
            }


            if (
                name.startsWith(
                    'motivo_cambio_'
                )
            ) {
                return;
            }


            if (
                typeof $input.attr(
                    'data-current'
                ) === 'undefined'
            ) {

                $input.attr(
                    'data-original-runtime',
                    valorNormalizado(
                        $input.val()
                    )
                );
            }

        });
}


/* ============================================================
   COMPONENTES
============================================================ */

function obtenerNombreComponente($item) {

    let tipo = 'Componente';


    if ($item.hasClass('ram-item')) {

        tipo = 'RAM';

    } else if (
        $item.hasClass('procesador-item')
    ) {

        tipo = 'Procesador';

    } else if (
        $item.hasClass('discoDuro-item')
    ) {

        tipo = 'Disco Duro';

    } else if (
        $item.hasClass('monitor-item')
    ) {

        tipo = 'Monitor';

    } else if (
        $item.hasClass('periferico-item')
    ) {

        tipo = 'Periférico';
    }


    const numero = valorNormalizado(

        $item
            .find('.numero-index')
            .first()
            .text()

    );


    return numero
        ? `${tipo} #${numero}`
        : tipo;
}


/**
 * Crea un resumen bonito cuando el componente
 * todavía es nuevo.
 */
function obtenerResumenComponente($item) {

    const partes = [];


    $item
        .find(
            'input[name], select[name]'
        )
        .each(function () {

            const $input = $(this);

            const type =
                ($input.attr('type') || '')
                    .toLowerCase();

            const name =
                $input.attr('name') || '';


            if (type === 'hidden') {
                return;
            }


            if (
                $input.hasClass(
                    'switch-estado-componente'
                )
            ) {
                return;
            }


            if (
                $input.hasClass(
                    'input-motivo'
                )
            ) {
                return;
            }


            if (
                name.includes('[_delete]')
                || name.includes('[id]')
            ) {
                return;
            }


            const valor =
                valorNormalizado(
                    $input.val()
                );


            if (valor === '') {
                return;
            }


            const label =
                obtenerNombreCampo($input);


            const mostrado =
                formatearValorComponente(
                    $input,
                    valor
                );


            partes.push(
                `${label}: ${mostrado}`
            );

        });


    return partes.length

        ? partes.join(' | ')

        : 'Nuevo componente agregado';
}


/* ============================================================
   FILAS DE LA BOLSA
============================================================ */

function generarFilaCambio(
    titulo,
    anterior,
    nuevo,
    categoria,
    colorClase
) {

    const ant =
        valorNormalizado(anterior)
        || 'Sin dato';


    const nue =
        valorNormalizado(nuevo)
        || 'Sin dato';


    return `

        <tr>

            <td class="pl-4 py-3">

                <span
                    class="small d-block ${colorClase}"
                    style="
                        font-weight:700;
                        text-transform:uppercase;
                        font-size:.7rem;
                    "
                >

                    ${escapeHtml(categoria)}

                </span>

                <strong class="text-dark">

                    ${escapeHtml(titulo)}

                </strong>

            </td>


            <td>

                <span
                    class="badge badge-secondary p-2"
                    style="
                        background:#f1f3f5;
                        color:#495057;
                        white-space:normal;
                    "
                >

                    ${escapeHtml(ant)}

                </span>

            </td>


            <td class="text-center text-muted">

                <i class="fas fa-arrow-right"></i>

            </td>


            <td>

                <span
                    class="badge badge-success p-2 shadow-sm"
                    style="white-space:normal;"
                >

                    <i class="fas fa-edit mr-1"></i>

                    ${escapeHtml(nue)}

                </span>

            </td>

        </tr>

    `;
}


/* ============================================================
   CONTADORES
============================================================ */

function actualizarContadores(
    mod,
    add,
    del
) {

    if (mod > 0) {

        $('#cnt-mod')
            .show()
            .find('.num')
            .text(mod);

    } else {

        $('#cnt-mod')
            .hide()
            .find('.num')
            .text(0);
    }


    if (add > 0) {

        $('#cnt-add')
            .show()
            .find('.num')
            .text(add);

    } else {

        $('#cnt-add')
            .hide()
            .find('.num')
            .text(0);
    }


    if (del > 0) {

        $('#cnt-del')
            .show()
            .find('.num')
            .text(del);

    } else {

        $('#cnt-del')
            .hide()
            .find('.num')
            .text(0);
    }
}


/* ============================================================
   MOTOR PRINCIPAL DE LA BOLSA
============================================================ */

function actualizarBolsaCambios() {

    const cambios = [];


    let countMod = 0;
    let countAdd = 0;
    let countDel = 0;


    /* ========================================================
       1. DATOS GENERALES DEL EQUIPO
    ======================================================== */

    $('#formEditarEquipo')

        .find(
            'input[name], select[name], textarea[name]'
        )

        .not('.item-componente *')

        .each(function () {

            const $input = $(this);


            const type =
                ($input.attr('type') || '')
                    .toLowerCase();


            const name =
                $input.attr('name') || '';


            if (
                type === 'hidden'
                || type === 'submit'
                || type === 'button'
            ) {
                return;
            }


            if (
                name.startsWith(
                    'motivo_cambio_'
                )
            ) {
                return;
            }


            if (
                $input.prop('disabled')
            ) {
                return;
            }


            const original =
                obtenerValorOriginal(
                    $input
                );


            const actual =
                valorNormalizado(
                    $input.val()
                );


            if (actual === original) {
                return;
            }


            cambios.push(

                generarFilaCambio(

                    obtenerNombreCampo(
                        $input
                    ),

                    formatearValorGeneral(
                        $input,
                        original
                    ),

                    formatearValorGeneral(
                        $input,
                        actual
                    ),

                    'Equipo / Asignación',

                    'text-info'
                )

            );


            countMod++;

        });



    /* ========================================================
       2. COMPONENTES
    ======================================================== */

    $('.item-componente').each(function () {

        const $item = $(this);


        const nombreBase =
            obtenerNombreComponente(
                $item
            );


        const esNuevo =
            $item.attr(
                'data-nuevo'
            ) === 'true';


        const $deleteInput =
            $item
                .find(
                    'input[name*="[_delete]"]'
                )
                .first();


        const marcadoEliminar =
            valorNormalizado(
                $deleteInput.val()
            ) === '1';



        /* ----------------------------------------------------
           COMPONENTE EXISTENTE ELIMINADO
        ---------------------------------------------------- */

        if (
            marcadoEliminar
            && !esNuevo
        ) {

            cambios.push(

                generarFilaCambio(

                    nombreBase,

                    'Registrado en el activo',

                    'ELIMINAR COMPONENTE',

                    'Eliminación',

                    'text-danger'
                )

            );


            countDel++;

            return;
        }



        /* ----------------------------------------------------
           COMPONENTE NUEVO
        ---------------------------------------------------- */

        if (esNuevo) {

            cambios.push(

                generarFilaCambio(

                    nombreBase,

                    'N/A',

                    obtenerResumenComponente(
                        $item
                    ),

                    'Alta',

                    'text-success'
                )

            );


            countAdd++;

            return;
        }



        /* ----------------------------------------------------
           MODIFICACIONES DEL COMPONENTE
        ---------------------------------------------------- */

        $item
            .find(
                'input[data-current][name], select[data-current][name]'
            )
            .each(function () {

                const $input = $(this);


                const type =
                    ($input.attr('type') || '')
                        .toLowerCase();


                const name =
                    $input.attr('name') || '';


                if (type === 'hidden') {
                    return;
                }


                if (
                    $input.hasClass(
                        'switch-estado-componente'
                    )
                ) {
                    return;
                }


                if (
                    $input.hasClass(
                        'input-motivo'
                    )
                ) {
                    return;
                }


                if (
                    name.includes('[_delete]')
                    || name.includes('[id]')
                ) {
                    return;
                }


                const original =
                    obtenerValorOriginal(
                        $input
                    );


                const actual =
                    valorNormalizado(
                        $input.val()
                    );


                if (actual === original) {
                    return;
                }


                cambios.push(

                    generarFilaCambio(

                        `${nombreBase}: ${obtenerNombreCampo($input)}`,

                        formatearValorComponente(
                            $input,
                            original
                        ),

                        formatearValorComponente(
                            $input,
                            actual
                        ),

                        'Modificación',

                        'text-primary'
                    )

                );


                countMod++;

            });



        /* ----------------------------------------------------
           ACTIVO / INACTIVO / REACTIVACIÓN
        ---------------------------------------------------- */

        const $switch =
            $item
                .find(
                    '.switch-estado-componente'
                )
                .first();


        if ($switch.length) {

            const estabaActivo =

                valorNormalizado(
                    $switch.attr(
                        'data-current'
                    )
                ) === '1';


            const estaActivo =
                $switch.prop(
                    'checked'
                );


            const $motivo =
                $item
                    .find(
                        '.input-motivo'
                    )
                    .first();


            const motivoActual =
                valorNormalizado(
                    $motivo.val()
                );


            const motivoOriginal =
                $motivo.length

                    ? obtenerValorOriginal(
                        $motivo
                    )

                    : '';



            /* BAJA */

            if (
                estaActivo
                !== estabaActivo
            ) {

                if (!estaActivo) {

                    const nuevoEstado =

                        motivoActual

                            ? `INACTIVO — Motivo: ${motivoActual}`

                            : 'INACTIVO — Sin motivo capturado';


                    cambios.push(

                        generarFilaCambio(

                            `Estado ${nombreBase}`,

                            'ACTIVO',

                            nuevoEstado,

                            'Baja',

                            'text-danger'
                        )

                    );


                    countDel++;

                }


                /* REACTIVACIÓN */

                else {

                    cambios.push(

                        generarFilaCambio(

                            `Estado ${nombreBase}`,

                            'INACTIVO',

                            'ACTIVO',

                            'Reactivación',

                            'text-success'
                        )

                    );


                    countMod++;

                }

            }


            /* MODIFICACIÓN DE MOTIVO */

            else if (
                !estaActivo
                && $motivo.length
                && motivoActual
                    !== motivoOriginal
            ) {

                cambios.push(

                    generarFilaCambio(

                        `${nombreBase}: Motivo de baja`,

                        motivoOriginal
                            || 'Sin motivo',

                        motivoActual
                            || 'Sin motivo',

                        'Modificación',

                        'text-primary'
                    )

                );


                countMod++;
            }

        }

    });



    /* ========================================================
       RENDER FINAL
    ======================================================== */

    const $lista =
        $('#lista-cambios');


    const $panel =
        $('#panel-resumen-cambios');


    if (cambios.length > 0) {

        $lista.html(
            cambios.join('')
        );


        actualizarContadores(

            countMod,
            countAdd,
            countDel

        );


        $panel
            .stop(true, true)
            .fadeIn(150);

    } else {

        $lista.empty();


        actualizarContadores(
            0,
            0,
            0
        );


        $panel
            .stop(true, true)
            .fadeOut(150);

    }
}


/* ============================================================
   BLOQUEO DE COMPONENTES INACTIVOS
============================================================ */

function gestionarBloqueoCampos(
    contenedor
) {

    const sw =
        contenedor.querySelector(
            '.switch-estado-componente'
        );


    if (!sw) {
        return;
    }


    const btnOjo =
        contenedor.querySelector(
            '[data-toggle="collapse"]'
        );


    if (btnOjo) {

        btnOjo.innerHTML =

            sw.checked

                ? '<i class="fas fa-eye"></i> Contraer'

                : '<i class="fas fa-eye"></i> Ver detalles';
    }


    const campos =
        contenedor.querySelectorAll(

            'input:not(.switch-estado-componente):not(.input-motivo):not([type="hidden"]), select'

        );


    campos.forEach(campo => {

        if (!sw.checked) {

            campo.readOnly = true;

            campo.classList.add(
                'bg-light'
            );


            if (
                campo.tagName === 'SELECT'
            ) {

                campo.style.pointerEvents =
                    'none';

                campo.tabIndex = -1;
            }

        } else {

            campo.readOnly = false;

            campo.classList.remove(
                'bg-light'
            );


            if (
                campo.tagName === 'SELECT'
            ) {

                campo.style.pointerEvents =
                    'auto';

                campo.removeAttribute(
                    'tabindex'
                );
            }

        }

    });
}


/* ============================================================
   COMPONENTES NUEVOS
============================================================ */

function bloquearSwitchNuevo(
    contenedor
) {

    const esNuevo =

        contenedor.getAttribute(
            'data-nuevo'
        ) === 'true';


    const sw =

        contenedor.querySelector(
            '.switch-estado-componente'
        );


    if (
        !esNuevo
        || !sw
    ) {
        return;
    }


    /*
     * IMPORTANTE:
     *
     * NO usamos:
     *
     * sw.disabled = true;
     *
     * porque los inputs disabled
     * NO se envían al backend.
     */

    sw.checked = true;

    sw.tabIndex = -1;

    sw.setAttribute(
        'aria-disabled',
        'true'
    );

    sw.dataset.lockedNew =
        'true';


    if (
        !sw.dataset.lockListener
    ) {

        sw.addEventListener(

            'click',

            function (e) {

                if (
                    this.dataset.lockedNew
                    === 'true'
                ) {

                    e.preventDefault();

                    this.checked = true;
                }

            }

        );


        sw.dataset.lockListener =
            'true';
    }


    const label =
        contenedor.querySelector(

            `label[for="${sw.id}"]`

        );


    if (label) {

        label.style.cursor =
            'not-allowed';

        label.title =
            'Un componente nuevo debe registrarse como activo';
    }
}


function inicializarComponente(
    contenedor
) {

    bloquearSwitchNuevo(
        contenedor
    );

    gestionarBloqueoCampos(
        contenedor
    );
}


function asegurarNuevosActivos() {

    $(
        '.item-componente[data-nuevo="true"] .switch-estado-componente'
    )
        .prop(
            'checked',
            true
        );
}


/* ============================================================
   AGREGAR COMPONENTES
============================================================ */

function agregarComponente(tipo) {

    const container =
        document.getElementById(
            `${tipo}-container`
        );


    const templateElement =
        document.getElementById(
            `template-${tipo}`
        );


    if (
        !container
        || !templateElement
    ) {
        return;
    }


    const currentIndex =

        parseInt(
            container.dataset.count,
            10
        ) || 0;


    const nuevoHtmlStr =

        templateElement
            .innerHTML
            .replace(
                /__INDEX__/g,
                currentIndex
            );


    const tempDiv =
        document.createElement(
            'div'
        );


    tempDiv.innerHTML =
        nuevoHtmlStr.trim();


    const nuevoNodo =
        tempDiv.firstElementChild;


    if (!nuevoNodo) {
        return;
    }


    nuevoNodo.setAttribute(
        'data-nuevo',
        'true'
    );


    const spanNumero =

        nuevoNodo.querySelector(
            '.numero-index'
        );


    if (spanNumero) {

        spanNumero.textContent =
            currentIndex + 1;
    }


    container.appendChild(
        nuevoNodo
    );


    container.dataset.count =
        currentIndex + 1;


    inicializarComponente(
        nuevoNodo
    );


    actualizarBolsaCambios();
}


/* ============================================================
   CONFIRMAR ALTA DE COMPONENTE
============================================================ */

function confirmarAgregar(
    tipo,
    nombreLegible
) {

    Swal.fire({

        title:
            `¿Agregar ${nombreLegible}?`,

        text:
            'Se habilitará un nuevo formulario para este componente.',

        type:
            'question',

        showCancelButton:
            true,

        confirmButtonColor:
            '#3085d6',

        cancelButtonColor:
            '#d33',

        confirmButtonText:
            'Sí, agregar',

        cancelButtonText:
            'Cancelar'

    }).then((result) => {

        if (!result.value) {
            return;
        }


        agregarComponente(
            tipo
        );


        const Toast =
            Swal.mixin({

                toast: true,

                position:
                    'top-end',

                showConfirmButton:
                    false,

                timer:
                    3500,

                timerProgressBar:
                    true

            });


        Toast.fire({

            type:
                'success',

            title:
                `${nombreLegible} preparado correctamente`

        });

    });
}


/* ============================================================
   ELIMINAR COMPONENTE
============================================================ */

function eliminarComponente(
    btn,
    clasePadre
) {

    const item =
        btn.closest(
            '.' + clasePadre
        );


    if (!item) {
        return;
    }


    const esNuevo =

        item.getAttribute(
            'data-nuevo'
        ) === 'true';


    Swal.fire({

        title: esNuevo

            ? '¿Quitar componente nuevo?'

            : '¿Eliminar componente?',


        text: esNuevo

            ? 'Se quitará del formulario y no se guardará.'

            : 'La eliminación quedará incluida en la Bolsa de Cambios.',


        type:
            'warning',


        showCancelButton:
            true,


        confirmButtonColor:
            '#d33',


        cancelButtonColor:
            '#6c757d',


        confirmButtonText: esNuevo

            ? 'Sí, quitar'

            : 'Sí, eliminar',


        cancelButtonText:
            'Cancelar'


    }).then((result) => {

        if (!result.value) {
            return;
        }


        /*
         * Si todavía no existe en BD,
         * simplemente desaparece.
         */

        if (esNuevo) {

            item.remove();

        }


        /*
         * Si ya existe:
         * Laravel recibirá _delete = 1
         */

        else {

            const deleteInput =

                item.querySelector(
                    'input[name*="[_delete]"]'
                );


            if (deleteInput) {

                deleteInput.value =
                    '1';


                item.style.display =
                    'none';


                item
                    .querySelectorAll(
                        'select, input, textarea'
                    )
                    .forEach(el => {

                        el.removeAttribute(
                            'required'
                        );

                    });
            }

        }


        actualizarBolsaCambios();

    });
}


/* ============================================================
   PROCESADOR - FRECUENCIA "OTRO"
============================================================ */

function checkOtroFrec(
    select,
    index
) {

    const input =
        document.getElementById(
            `input_frec_${index}`
        );


    if (!input) {
        return;
    }


    /*
     * OTRO VALOR
     */

    if (
        select.value === 'otro'
    ) {

        input.classList.remove(
            'd-none'
        );

        input.focus();

    }


    /*
     * VACÍO
     */

    else if (
        select.value === ''
    ) {

        input.value =
            '';

        input.classList.add(
            'd-none'
        );

        $(input).trigger(
            'input'
        );

    }


    /*
     * VALOR DEL CATÁLOGO
     */

    else {

        input.value =
            select.value;

        input.classList.add(
            'd-none'
        );

        $(input).trigger(
            'input'
        );

    }
}


/* ============================================================
   INICIALIZACIÓN
============================================================ */

$(document).ready(function () {


    /*
     * 1. Capturar valores originales
     */

    capturarEstadoInicialGeneral();


    /*
     * 2. Preparar componentes existentes
     */

    document
        .querySelectorAll(
            '.periferico-item, .ram-item, .procesador-item, .monitor-item, .discoDuro-item'
        )
        .forEach(
            inicializarComponente
        );


    /*
     * 3. Estado inicial de Bolsa
     */

    actualizarBolsaCambios();



    /* ========================================================
       ESCUCHAR TODOS LOS CAMBIOS
    ======================================================== */

    $('#formEditarEquipo').on(

        'change input',

        'input, select, textarea',

        function () {

            actualizarBolsaCambios();

        }

    );



    /* ========================================================
       SWITCH ACTIVO / INACTIVO
    ======================================================== */

    $('#formEditarEquipo').on(

        'change',

        '.switch-estado-componente',

        function () {

            const $switch =
                $(this);


            const $item =
                $switch.closest(
                    '.item-componente'
                );


            /*
             * Un componente nuevo
             * siempre debe iniciar activo.
             */

            if (
                $switch.attr(
                    'data-locked-new'
                ) === 'true'
            ) {

                $switch.prop(
                    'checked',
                    true
                );

                return;
            }


            const estaActivo =
                $switch.is(
                    ':checked'
                );


            const $divMotivo =
                $item.find(
                    '.div-motivo'
                );


            const $inputMotivo =
                $item.find(
                    '.input-motivo'
                );


            const $label =
                $item.find(
                    `label[for="${this.id}"]`
                );


            /*
             * REACTIVAR
             */

            if (estaActivo) {

                $divMotivo.fadeOut(
                    150
                );


                $inputMotivo
                    .prop(
                        'required',
                        false
                    )
                    .val('');


                $label

                    .text(
                        'COMPONENTE ACTIVO'
                    )

                    .removeClass(
                        'text-danger'
                    )

                    .addClass(
                        'text-success'
                    );

            }


            /*
             * DAR DE BAJA
             */

            else {

                $divMotivo.fadeIn(
                    150
                );


                $inputMotivo
                    .prop(
                        'required',
                        true
                    )
                    .focus();


                $label

                    .text(
                        'COMPONENTE INACTIVO'
                    )

                    .removeClass(
                        'text-success'
                    )

                    .addClass(
                        'text-danger'
                    );

            }


            gestionarBloqueoCampos(
                $item.get(0)
            );


            actualizarBolsaCambios();

        }

    );



    /* ========================================================
       CONFIRMACIÓN FINAL
    ======================================================== */

    $('#formEditarEquipo').on(

        'submit',

        function (e) {

            e.preventDefault();


            asegurarNuevosActivos();


            actualizarBolsaCambios();


            const contenidoCambios =

                $('#lista-cambios')
                    .html();


            /*
             * SIN CAMBIOS
             */

            if (
                !contenidoCambios
                || !contenidoCambios.trim()
            ) {

                Swal.fire({

                    type:
                        'info',

                    title:
                        'Sin cambios',

                    text:
                        'No se han detectado modificaciones para guardar.',

                    confirmButtonColor:
                        '#3085d6'

                });


                return;
            }



            /*
             * TABLA DE CONFIRMACIÓN
             */

            const tablaHtml = `

                <div class="text-left">

                    <p class="text-muted small">

                        Revisa los cambios antes de confirmar:

                    </p>


                    <div
                        style="
                            max-height:420px;
                            overflow:auto;
                        "
                    >

                        <table
                            class="
                                table
                                table-sm
                                table-striped
                                border
                            "
                        >

                            <thead class="bg-light">

                                <tr>

                                    <th>
                                        Campo
                                    </th>

                                    <th>
                                        Anterior
                                    </th>

                                    <th></th>

                                    <th>
                                        Nuevo
                                    </th>

                                </tr>

                            </thead>


                            <tbody
                                style="
                                    font-size:.85rem;
                                "
                            >

                                ${contenidoCambios}

                            </tbody>

                        </table>

                    </div>

                </div>

            `;


            const form =
                this;


            Swal.fire({

                title:
                    '¿Confirmar cambios?',


                html:
                    tablaHtml,


                type:
                    'warning',


                showCancelButton:
                    true,


                confirmButtonColor:
                    '#28a745',


                cancelButtonColor:
                    '#d33',


                confirmButtonText:
                    '<i class="fas fa-save"></i> Sí, guardar todo',


                cancelButtonText:
                    'Cancelar',


                width:
                    '850px'


            }).then((result) => {


                if (!result.value) {
                    return;
                }


                Swal.fire({

                    title:
                        'Guardando...',


                    text:
                        'Actualizando información del activo',


                    allowOutsideClick:
                        false,


                    onBeforeOpen:
                        () => {

                            Swal.showLoading();

                        }

                });


                /*
                 * SUBMIT NATIVO.
                 *
                 * Esto evita disparar nuevamente
                 * nuestro listener de submit.
                 */

                form.submit();

            });

        }

    );

});