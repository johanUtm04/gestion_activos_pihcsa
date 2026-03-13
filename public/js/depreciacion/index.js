/**
 * JS MAESTRO: CÁLCULO DE DEPRECIACIÓN FISCAL (LISR)
 * Conexión dinámica a Catálogos de Tasas e INPC
 */

// 1. EVENTO: ABRIR MODAL Y PREPARAR DATOS DEL ACTIVO
$(document).on('click', '.btn-depreciar', function() {
    const btn = $(this);
    
    // 1. DECLARACIÓN (Esto evita el ReferenceError)
    const inputFechaUso = $('#in_fecha_uso'); 
    const lockIcon = $('#lock-icon'); // Si agregaste el icono del candado
    
    // 2. CAPTURA DE DATOS
    const marca = btn.data('marca');
    const valor = btn.data('valor');
    const fechaAdq = btn.data('fecha');
    const fechaUsoBD = btn.data('fecha-uso'); // El dato que viene de tu base de datos

    // 3. ASIGNACIÓN BÁSICA
    $('#nombreActivo').text(marca);
    $('#in_valor_inicial').val(valor);
    $('#hidden_fecha_adquisicion').val(fechaAdq);

    // 4. LÓGICA DE BLOQUEO (Mitigación del error)
    if (fechaUsoBD && fechaUsoBD !== "" && fechaUsoBD !== "null") {
        // Si ya existe en BD: Cargamos y bloqueamos
        inputFechaUso.val(fechaUsoBD)
                     .prop('readonly', true)
                     .addClass('bg-light'); // Estilo gris de "solo lectura"
        
        if(lockIcon.length) lockIcon.show(); // Mostramos candado si existe
    } else {
        // Si es nuevo: Sugerimos fecha de adquisición y permitimos editar
        inputFechaUso.val(fechaAdq)
                     .prop('readonly', false)
                     .removeClass('bg-light');
        
        if(lockIcon.length) lockIcon.hide(); // Ocultamos candado
    }

    $('#modalDepreciacion').modal('show');
});

// 2. EVENTO: PROCESAR CÁLCULO FISCAL (AJAX)
$('#btnProcesarCalculo').on('click', function() {
    const btn = $(this);
    const f = (n) => new Intl.NumberFormat('es-MX', {style:'currency', currency:'MXN'}).format(n);
    
    const dataEnvio = {
        valor_base: parseFloat($('#in_valor_inicial').val()) || 0,
        gastos: parseFloat($('#in_gastos_extras').val()) || 0,
        imptos: parseFloat($('#in_impuestos').val()) || 0,
        tasa_id: $('#in_tasa').val(),
        fecha_uso: $('#in_fecha_uso').val(),
        fecha_adq: $('#hidden_fecha_adquisicion').val()
    };

    // Validaciones básicas
    if (!dataEnvio.tasa_id) {
        Swal.fire('Atención', 'Por favor seleccione un Tipo de Activo para aplicar la tasa LISR.', 'warning');
        return;
    }
    if (!dataEnvio.fecha_uso) {
        Swal.fire('Error', 'La fecha de inicio de vida útil es obligatoria.', 'error');
        return;
    }

    // Feedback visual de carga
    btn.html('<i class="fas fa-sync fa-spin"></i> CONSULTANDO CATÁLOGOS...').prop('disabled', true);

    // LLAMADA AL SERVIDOR PARA OBTENER TASAS E INPC REALES
    $.get(window.baseRoute, dataEnvio, function(response) {
        
        // --- BLOQUE I: DEDUCCIÓN LINEAL ---
        const moi = dataEnvio.valor_base + dataEnvio.gastos + dataEnvio.imptos;
        const anual = moi * response.tasa;
        const mensual = anual / 12;
        
        // Cálculo de meses de uso (del mes de inicio a diciembre)
        const fUso = new Date(dataEnvio.fecha_uso + "T00:00:00");
        const mesesUso = (11 - fUso.getMonth()) + 1; // Enero es 0, Diciembre es 11
        const sinAct = mensual * mesesUso;

        // --- BLOQUE II: AJUSTE POR INFLACIÓN ---
        // El factor se redondea a 4 decimales según norma fiscal
        let factor = Math.floor((response.inpc_mitad / response.inpc_adq) * 10000) / 10000;
        
        // Por ley, si el factor es menor a 1, se utiliza 1 (no se desactualiza)
        if (factor < 1) factor = 1.0000;
        
        const actualizada = sinAct * factor;

        // --- RENDERIZADO DE RESULTADOS ---
        // Sección I
        $('#out_moi').text(f(moi)).addClass('animated fadeIn');
        $('#out_tasa_text').text((response.tasa * 100).toFixed(2) + '%');
        $('#out_max_anual').text(f(anual));
        $('#out_meses_uso').text(mesesUso);
        $('#out_total_sin_act').text(f(sinAct));

        // Sección II (Inflación)
        $('#seccion-actualizacion').animate({opacity: 1}, 500);
        $('#out_inpc_mitad').text(response.inpc_mitad.toFixed(4));
        $('#out_inpc_adq').text(response.inpc_adq.toFixed(4));
        $('#out_factor').text(factor.toFixed(4));
        $('#out_total_actualizado').text(f(actualizada)).addClass('animated tada');

        // Restaurar Botón
        btn.html('<i class="fas fa-check-double mr-2"></i> ANÁLISIS COMPLETO').removeClass('btn-info').addClass('btn-success').prop('disabled', false);  
    })
    .fail(function(xhr) {
        // Manejo de error específico (Mes faltante en catálogo)
        let msg = "No se encontraron los datos del INPC o la Tasa en el sistema.";
        if (xhr.responseJSON && xhr.responseJSON.error) {
            msg = xhr.responseJSON.error;
        }

        Swal.fire({
            icon: 'error',
            title: 'Error de Catálogo',
            text: msg,
            footer: '<small>Verifique que el año y mes correspondientes estén registrados en el catálogo de INPC.</small>'
        });
        
        btn.html('<i class="fas fa-calculator mr-2"></i> REINTENTAR').prop('disabled', false);
    });
});

// 3. UI: TOGGLE PARA PANELES DE BÚSQUEDA O FILTROS
function togglePanel() {
    const body = document.getElementById('searchBody');
    const icon = document.getElementById('toggle-icon');

    if (body.style.maxHeight === "0px" || body.style.maxHeight === "") {
        body.style.maxHeight = "500px"; 
        body.style.opacity = "1";
        body.style.paddingTop = "15px";
        icon.classList.replace('fa-plus', 'fa-minus');
        icon.style.transform = "rotate(180deg)";
    } else {
        body.style.maxHeight = "0";
        body.style.opacity = "0";
        body.style.paddingTop = "0";
        icon.classList.replace('fa-minus', 'fa-plus');
        icon.style.transform = "rotate(0deg)";
    }
}