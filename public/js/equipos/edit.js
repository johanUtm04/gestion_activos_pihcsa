/**
 * 1. FUNCIONES GLOBALES 
 * (Deben estar fuera de cualquier bloque para que el onclick del HTML las vea)
 */
function confirmarAgregar(tipo, nombreLegible) {
    Swal.fire({
        title: `¿Agregar ${nombreLegible}?`,
        text: `Se habilitará un nuevo formulario para este componente.`,
        type: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, agregar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.value) {
            agregarComponente(tipo);
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000
            });

            Toast.fire({
                icon: 'success',
                title: `${nombreLegible} preparado`
            });
        }
    });
}

function agregarComponente(tipo) {
    const container = document.getElementById(`${tipo}-container`);
    const templateElement = document.getElementById(`template-${tipo}`);

    if (!container || !templateElement) return;

    let currentIndex = parseInt(container.dataset.count) || 0;
    let html = templateElement.innerHTML;
    const nuevoHtmlStr = html.replace(/__INDEX__/g, currentIndex);
    
    const tempDiv = document.createElement('div');
    tempDiv.innerHTML = nuevoHtmlStr.trim();
    const nuevoNodo = tempDiv.firstElementChild;

    const spanNumero = nuevoNodo.querySelector('.numero-index');
    if (spanNumero) spanNumero.textContent = currentIndex + 1;
    
    container.appendChild(nuevoNodo);
    container.dataset.count = currentIndex + 1;
}


function eliminarComponente(btn, clasePadre) {
    if (!confirm('¿Estás seguro de eliminar este componente?')) return;

    const item = btn.closest('.' + clasePadre);
    const deleteInput = item.querySelector('input[name*="[_delete]"]');

    if (deleteInput) {
        deleteInput.value = "1";
        item.style.display = 'none';
        item.querySelectorAll('select, input').forEach(el => el.removeAttribute('required'));
    } else {
        item.remove();
    }
}

/**
 * 2. LÓGICA DE INICIALIZACIÓN
 */
$(document).ready(function() {
    // Función genérica para solicitar motivo
function solicitarMotivoCambio(selector) {
    $(selector).on('change', function() {
        const select = $(this);
        const nuevoValor = select.val();
        const valorOriginal = String(select.attr('data-current'));
        const targetInput = select.attr('data-motivo-input');
        const nombreCampo = select.attr('data-label');

        console.log(`Cambiando ${nombreCampo}: Anterior: ${valorOriginal}, Nuevo: ${nuevoValor}`);

        if (nuevoValor != valorOriginal && nuevoValor !== "") {
            Swal.fire({
                title: `Justificar cambio en ${nombreCampo}`,
                input: 'text',
                // Cambiamos 'icon' por 'type' por si tu versión es antigua
                type: 'warning', 
                showCancelButton: true,
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar',
                inputValidator: (value) => {
                    if (!value) return '¡Es obligatorio!';
                }
            }).then((result) => {
                // En versiones muy viejas, se usa result.value directamente. 
                // En las nuevas es result.isConfirmed. Probemos con esta lógica:
                if (result && (result.value || result.isConfirmed)) {
                    const motivo = result.value;
                    console.log("Confirmado. Motivo:", motivo);
                    
                    // 1. Guardamos motivo
                    $(targetInput).val(motivo);
                    
                    // 2. Actualizamos 'data-current' para que ya no pregunte por este mismo valor
                    select.attr('data-current', nuevoValor);
                    
                    // 3. Forzamos que el select mantenga el valor nuevo
                    select.val(nuevoValor);
                    
                    // Si usas Select2
                    if (select.hasClass('select2-hidden-accessible')) {
                        select.trigger('change.select2');
                    }
                } else {
                    // Si el usuario cancela o cierra el modal
                    console.log("Cancelado o cerrado. Revirtiendo a:", valorOriginal);
                    select.val(valorOriginal);
                    if (select.hasClass('select2-hidden-accessible')) {
                        select.trigger('change.select2');
                    }
                }
            });
        }
    });
}

    // Activar -- (id)
    solicitarMotivoCambio('#marca_id');
    solicitarMotivoCambio('#tipo_activo_id');
    solicitarMotivoCambio('#usuario_id');
    solicitarMotivoCambio('#ubicacion_id');
    solicitarMotivoCambio('#valor_inicial');
    solicitarMotivoCambio('#fecha_adquisicion');
    solicitarMotivoCambio('#vida_util_input');
    solicitarMotivoCambio('#serial');

    // Select2
    if (typeof $.fn.select2 !== 'undefined') {
        $('.select2').select2({ theme: 'bootstrap4', width: '100%' });
    }

    // Vida útil
    $('#vida_util_unidad').on('change', function() {
        $('#vida_util_input').prop('disabled', !$(this).val());
    }).trigger('change');

});

/**
 * 3. EVENTOS DINÁMICOS (Delegación de eventos)
 */
$(document).on('change', '.switch-estado-componente', function() {
    const isChecked = $(this).is(':checked');
    const container = $(this).closest('.item-componente');
    const divMotivo = container.find('.div-motivo');
    const inputMotivo = container.find('.input-motivo');
    const label = $(this).siblings('.custom-control-label');

    if (isChecked) {
        divMotivo.fadeOut(200);
        inputMotivo.prop('required', false).val('');
        label.text('Activo').removeClass('text-danger').addClass('text-success');
    } else {
        divMotivo.fadeIn(200);
        inputMotivo.prop('required', true).focus();
        label.text('Inactivo').removeClass('text-success').addClass('text-danger');
    }
});