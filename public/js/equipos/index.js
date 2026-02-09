$(document).ready(function() {
    $('.equipo-row').on('mouseenter', function() {
        const d = $(this).data();
        let contador = 1;
        // Estructura Profesional Dinamica
        const html = `
            <div class="animate-details">
                <div class="detail-header-premium">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <span class="badge badge-light text-info mb-2">${d.tipo}</span>
                            <h3 class="font-weight-bold mb-0">${d.marca}</h3>
                            <p class="mb-0 opacity-8 text-sm"><i class="fas fa-hashtag mr-1"></i>ID: ${d.id} | <i class="fas fa-barcode mr-1"></i>${d.serial}</p>
                        </div>
                        <i class="fas fa-laptop-code fa-3x opacity-2"></i>
                    </div>
                </div>

                <div class="section-divider">Especificaciones Base</div>
                <div class="info-box-custom">
                    <span class="info-label">Sistema Operativo</span>
                    <span class="info-value text-info"><i class="fab fa-windows mr-1"></i>${d.so}</span>
                </div>
                <div class="info-box-custom">
                    <span class="info-label">Ubicación Actual</span>
                    <span class="info-value">${d.ubicacion}</span>
                </div>

                <div class="section-divider">Responsable del Activo</div>
                <div class="p-3 d-flex align-items-center">
                    <div class="bg-light rounded-circle p-3 mr-3">
                        <i class="fas fa-user-tie fa-2x text-secondary"></i>
                    </div>
                    <div>
                        <div class="font-weight-bold text-dark">${d.usuario}</div>
                        <div class="small text-muted">${d.email}</div>
                    </div>
                </div>

                <div class="section-divider">Arquitectura de Hardware</div>
                <div class="info-box-custom">
                    <span class="info-label"><i class="fas fa-microchip mr-1"></i> Procesadores</span>
                    <span class="info-value badge badge-pill badge-secondary">${d.procesadores} unidades</span>
                </div>
                <div class="info-box-custom">
                    <span class="info-label"><i class="fas fa-memory mr-1"></i> Memoria RAM</span>
                    <span class="info-value">${d.ram}</span>
                </div>
                <div class="info-box-custom">
                    <span class="info-label"><i class="fas fa-hdd mr-1"></i> Almacenamiento</span>
                    <span class="info-value">${d.discos} Disco(s) Instalado(s)</span>
                </div>
                <div class="info-box-custom">
                    <span class="info-label"><i class="fas fa-desktop mr-1"></i> Monitores</span>
                    <span class="info-value">${d.monitores} Asignados</span>
                </div>
                <div class="p-3 border-bottom">
                    <span class="info-label d-block mb-2">Otros Periféricos</span>
                    <p class="small text-dark mb-0 font-italic"> ${d.perifericos || 'Sin periféricos adicionales registrados'}</p>
                </div>

                <div class="section-divider">Datos Económicos</div>
                <div class="p-3 bg-light">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="small font-weight-bold text-muted">Valor de Adquisición</span>
                        <span class="text-success font-weight-bold">$${d.valor}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="small font-weight-bold text-muted">Fecha Compra</span>
                        <span class="small">${d.fecha}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="small font-weight-bold text-muted">Vida Útil Restante</span>
                        <span class="badge badge-warning text-dark">${d.vida} años</span>
                    </div>
                </div>
            </div>
        `;
        contador++;
        $('#detail-content').html(html);
    });

    // LÓGICA DE MENSAJES Y SCROLL PARA EL USUARIO
    const scrollId = "{{ session('new_id') ?? session('actualizado_id') }}";    
    if (scrollId) {
        const targetRow = document.getElementById('equipo-' + scrollId);
        if (targetRow) {
            targetRow.scrollIntoView({ behavior: 'smooth', block: 'center' });
            $(targetRow).css('background-color', '#fff3cd');
            setTimeout(() => {
                $(targetRow).animate({ backgroundColor: 'transparent' }, 2000);
            }, 1000);
        }
    }
});


$(document).ready(function() {
    function setupSelectOtro(selectId, inputId) {
        const $select = $(`#${selectId}`);
        const $input = $(`#${inputId}`);

        //Si se nota un cambio en la etiqueta <select>
        $select.on('change', function() {
            if ($(this).val() === 'OTRO_VALOR') {
                $input.fadeIn().focus();
            } else {
                $input.hide().val($(this).val()); 
            }
        });
    }
    setupSelectOtro('tipo_activo', 'tipo_input');
});

function togglePanel() {
    const body = document.getElementById('searchBody');
    const icon = document.getElementById('panelIcon');

    if (body.style.maxHeight === "0px" || body.style.maxHeight === "") {
        body.style.maxHeight = "500px"; 
        body.style.opacity = "1";
        icon.style.transform = "rotate(180deg)";
    } else {
        body.style.maxHeight = "0px";
        body.style.opacity = "0";
        icon.style.transform = "rotate(0deg)";
    }
}


document.addEventListener('DOMContentLoaded', function() {
    const rows = document.querySelectorAll('.clickable-row');

    rows.forEach(row => {
        row.addEventListener('click', function(e) {
            if (e.target.closest('button') || e.target.closest('a') || e.target.closest('.btn')) {
                return;
            }

            const url = this.getAttribute('data-url');
            if (url) {
                window.location.href = url;
            }
        });
    });
});


    // Función genérica para solicitar motivo
function ejecutarInactivacion(elemento) {
    const btn = $(elemento);
    const nombreCampo = btn.data('label');
    const targetInput = btn.data('motivo-input');
    const form = btn.closest('form');

    Swal.fire({
        title: `Justificar ${nombreCampo}`,
        text: `Por favor, indica el motivo:`,
        input: 'text',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Confirmar',
        cancelButtonText: 'Cancelar',
        inputValidator: (value) => {
            if (!value) return '¡El motivo es obligatorio!';
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // Llenamos el hidden input y enviamos el form
            $(targetInput).val(result.value);
            form.submit();
        }
    });
}
    // Activar motivos
    solicitarMotivoCambio('#motivo_inactivacion');