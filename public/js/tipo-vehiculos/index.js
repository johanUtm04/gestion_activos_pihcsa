// --- FUNCIONES GLOBALES ---

/**
 * Contrae o despliega el panel de búsqueda avanzada rotando el ícono
 */
function togglePanel() {
    const body = document.getElementById('searchBody');
    const icon = document.getElementById('toggle-icon');

    if (!body || !icon) return;

    if (body.style.maxHeight === "0px" || body.style.maxHeight === "") {
        body.style.maxHeight = "500px";
        body.style.opacity = "1";
        icon.classList.replace('fa-plus', 'fa-minus');
        icon.style.transform = "rotate(180deg)";
    } else {
        body.style.maxHeight = "0";
        body.style.opacity = "0";
        icon.classList.replace('fa-minus', 'fa-plus');
        icon.style.transform = "rotate(0deg)";
    }
}

/**
 * Prepara el modal para capturar el motivo de inactivación de un registro
 */
let formularioActual = null;
let inputHiddenActual = null;

function ejecutarInactivacion(elemento) {
    const btn = $(elemento);
    formularioActual = btn.closest('form');
    inputHiddenActual = btn.data('motivo-input');
    const nombre = btn.data('nombre');

    $('#nombreEquipoModal').text(nombre);
    $('#motivo_texto').val('');
    $('#errorMotivo').addClass('d-none');
    $('#modalInactivar').modal('show');
}

// --- EVENTOS DEL DOM (JQUERY & VANILLA) ---

document.addEventListener('DOMContentLoaded', function() {
    // Manejo de filas clickeables para navegación
    const rows = document.querySelectorAll('.clickable-row');
    rows.forEach(row => {
        row.addEventListener('click', function(e) {
            if (e.target.closest('button') || e.target.closest('a') || e.target.closest('.btn')) return;
            const url = this.getAttribute('data-url');
            if (url) window.location.href = url;
        });
    });
});

$(document).ready(function() {
    
    // 1. Lógica de Scroll e iluminación de registros (Módulo: Tipos de Vehículo)
    const marker = document.getElementById('scroll-target-marker');
    if (marker) {
        const targetId = marker.getAttribute('data-id');
        // Apunta al ID de la fila generado por el catálogo de tipos
        const targetRow = document.getElementById('tipo-' + targetId);

        if (targetRow) {
            targetRow.scrollIntoView({ 
                behavior: 'smooth', 
                block: 'center' 
            });

            // Color #ecfafb suave a juego con el esquema 'info'
            $(targetRow).css('background-color', '#ecfafb');
            
            // Efecto parpadeo de atención
            $(targetRow).fadeOut(400).fadeIn(400).fadeOut(400).fadeIn(400, function() {
                setTimeout(() => {
                    $(this).animate({ backgroundColor: "transparent" }, 2000);
                }, 3000);
            });
        }
    }

    // 2. Setup dinámico para selectores con opción "Otro"
    function setupSelectOtro(selectId, inputId) {
        const $select = $(`#${selectId}`);
        const $input = $(`#${inputId}`);
        
        if ($select.length && $input.length) {
            $select.on('change', function() {
                if ($(this).val() === 'OTRO_VALOR') {
                    $input.fadeIn().focus();
                } else {
                    $input.hide().val($(this).val()); 
                }
            });
        }
    }
    setupSelectOtro('tipo_vehiculo', 'tipo_input');

    // 3. Confirmación del modal de inactivación
    $('#btnConfirmarInactivacion').on('click', function() {
        const motivo = $('#motivo_texto').val().trim();
        if (motivo.length < 10) {
            $('#errorMotivo').removeClass('d-none');
            return;
        }
        
        if (inputHiddenActual && formularioActual) {
            $(inputHiddenActual).val(motivo);
            $('#modalInactivar').modal('hide');
            $(this).html('<i class="fas fa-spinner fa-spin"></i> Procesando...').prop('disabled', true);
            formularioActual[0].submit();
        }
    });
});