$(document).ready(function() {
    
    // El contador debe estar AFUERA para que incremente globalmente
    let contadorVistas = 1; 

$('.equipo-row').on('mouseenter', function() {
    const d = $(this).data();

    // Función para marcar texto si algo está inactivo (Ubicación, Usuario, etc.)
    const formatInactivo = (valor, estaInactivo) => {
        return estaInactivo ? `${valor} <span class="text-danger small font-italic">(Inactivado)</span>` : valor;
    };

    // Estructura Profesional Dinámica
    const html = `
        <div class="animate-details">
            <div class="detail-header-premium">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span class="badge ${d.equipo_inactivo ? 'badge-danger' : 'badge-light text-info'} mb-2">
                            ${d.tipo} ${d.equipo_inactivo ? '- INACTIVO' : ''}
                        </span>
                        <h3 class="font-weight-bold mb-0">${d.marca}</h3>
                        <p class="mb-0 opacity-8 text-sm">
                            <i class="fas fa-hashtag mr-1"></i>ID: ${d.id} | 
                            <i class="fas fa-barcode mr-1"></i>${d.serial} |
                            <i class="fas fa-layer-group mr-1"></i>Vista #${contadorVistas}
                        </p>
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
                <span class="info-value">${formatInactivo(d.ubicacion, d.ubicacion_inactiva)}</span>
            </div>

            <div class="section-divider">Responsable del Activo</div>
            <div class="p-3 d-flex align-items-center">
                <div class="bg-light rounded-circle p-3 mr-3">
                    <i class="fas fa-user-tie fa-2x text-secondary"></i>
                </div>
                <div>
                    <div class="font-weight-bold text-dark">${formatInactivo(d.usuario, d.usuario_inactivo)}</div>
                    <div class="small text-muted">${d.email}</div>
                </div>
            </div>

            <div class="section-divider">Arquitectura de Hardware</div>
            
            <div class="info-box-custom">
                <span class="info-label"><i class="fas fa-microchip mr-1"></i> Procesadores</span>
                <span class="info-value">
                    ${d.procesadores} unidad(es) 
                    ${d.procesadores_inactivos > 0 ? `<span class="text-danger small font-italic">(${d.procesadores_inactivos} inactivos)</span>` : ''}
                </span>
            </div>

            <div class="info-box-custom">
                <span class="info-label"><i class="fas fa-memory mr-1"></i> Memoria RAM</span>
                <span class="info-value">
                    ${d.ram || 'Sin RAM activa'} 
                    ${d.ram_inactiva ? `<br><small class="text-danger font-italic">Inactiva: ${d.ram_inactiva}GB</small>` : ''}
                </span>
            </div>

            <div class="info-box-custom">
                <span class="info-label"><i class="fas fa-hdd mr-1"></i> Almacenamiento</span>
                <span class="info-value">
                    ${d.discos} Disco(s) Activo(s) 
                    ${d.discos_inactivos > 0 ? `<span class="text-danger small font-italic">(${d.discos_inactivos} inactivos)</span>` : ''}
                </span>
            </div>
            
            <div class="info-box-custom">
                <span class="info-label"><i class="fas fa-desktop mr-1"></i> Monitores</span>
                <span class="info-value">
                    ${d.monitores} Activo(s) 
                    ${d.monitores_inactivos > 0 ? `<span class="text-danger small font-italic">(${d.monitores_inactivos} inactivados)</span>` : ''}
                </span>
            </div>

            <div class="p-3 border-bottom">
                <span class="info-label d-block mb-2">Otros Periféricos</span>
                <p class="small text-dark mb-1">
                    <i class="fas fa-keyboard mr-1 text-muted"></i>
                    ${d.perifericos || '<span class="text-muted">Ninguno activo</span>'}
                </p>
                ${d.perifericos_inactivos ? `
                    <p class="small text-danger mb-0 font-italic" style="opacity: 0.8;">
                        <i class="fas fa-times-circle mr-1"></i> Inactivos: 
                        <span style="text-decoration: line-through;">${d.perifericos_inactivos}</span>
                    </p>
                ` : ''}
            </div>
            <div class="section-divider">Datos Económicos</div>
            <div class="p-3 bg-light">
                <div class="d-flex justify-content-between mb-2">
                    <span class="small font-weight-bold text-muted">Número Factura</span>
                    <span class="small badge badge-secondary">
                        <i class="fas fa-file-invoice-dollar mr-1"></i>
                        ${d.numero_factura || 'No asignada'}
                    </span>
                </div>
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
    
    contadorVistas++;
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

    // Setup para select de "Otro"
    function setupSelectOtro(selectId, inputId) {
        const $select = $(`#${selectId}`);
        const $input = $(`#${inputId}`);
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

// --- Funciones Globales ---

function togglePanel() {
    const body = document.getElementById('searchBody');
    const icon = document.getElementById('toggle-icon');
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

document.addEventListener('DOMContentLoaded', function() {
    const rows = document.querySelectorAll('.clickable-row');
    rows.forEach(row => {
        row.addEventListener('click', function(e) {
            if (e.target.closest('button') || e.target.closest('a') || e.target.closest('.btn')) return;
            const url = this.getAttribute('data-url');
            if (url) window.location.href = url;
        });
    });
});

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

$('#btnConfirmarInactivacion').on('click', function() {
    const motivo = $('#motivo_texto').val().trim();
    if (motivo.length < 10) {
        $('#errorMotivo').removeClass('d-none');
        return;
    }
    $(inputHiddenActual).val(motivo);
    $('#modalInactivar').modal('hide');
    $(this).html('<i class="fas fa-spinner fa-spin"></i> Procesando...').prop('disabled', true);
    formularioActual[0].submit();
});