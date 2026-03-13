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
            timer: 5000,
            timerProgressBar: true,
            showClass: { popup: 'animate__animated animate__fadeInRight' },
            hideClass: { popup: 'animate__animated animate__fadeOutRight' }
        });

        Toast.fire({
            icon: 'success',
            html: `
                <div class="d-flex align-items-center">
                    <div class="mr-3" style="font-size: 1.5rem; color: #28a745;">
                        <i class="fas fa-microchip"></i> 
                    </div>
                    <div class="text-left">
                        <b class="d-block" style="font-size: 1rem;">${nombreLegible}</b>
                        <small class="text-muted">Componente preparado con éxito</small>
                    </div>
                </div>
            `,
            background: '#ffffff',
            customClass: {
                popup: 'shadow-lg border-left border-success' 
            }
        });
        }
    });
} 



$(document).ready(function() {

    $('#btnGuardarCambios').on('click', function(e) {
        e.preventDefault();

        const contenidoCambios = $('#lista-cambios').html();

        if (!contenidoCambios || contenidoCambios.trim() === "") {
            Swal.fire({
                icon: 'info',
                title: 'Sin cambios',
                text: 'No se han detectado modificaciones para guardar.',
                confirmButtonColor: '#3085d6',
            });
            return;
        }
        const tablaHtml = `
            <div class="text-left">
                <p class="text-muted small">Revisa los cambios antes de confirmar:</p>
                <table class="table table-sm table-striped border">
                    <thead class="bg-light">
                        <tr>
                            <th>Campo</th>
                            <th>Anterior</th>
                            <th>Nuevo</th>
                        </tr>
                    </thead>
                    <tbody style="font-size: 0.85rem;">
                        ${contenidoCambios}
                    </tbody>
                </table>
            </div>
        `;

        Swal.fire({
            title: '¿Confirmar cambios?',
            html: tablaHtml, 
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#28a745', 
            cancelButtonColor: '#d33',
            confirmButtonText: '<i class="fas fa-save"></i> Sí, guardar todo',
            cancelButtonText: 'Cancelar',
            width: '600px', 
        }).then((result) => {
            if (result.value) {

                Swal.fire({
                    title: 'Guardando...',
                    text: 'Actualizando información del activo',
                    allowOutsideClick: false,
                    onBeforeOpen: () => {
                        Swal.showLoading();
                    }
                });

                $('#formEditarEquipo').submit();
            }
        });
    });
});

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

    nuevoNodo.setAttribute('data-nuevo', 'true');
    
    container.appendChild(nuevoNodo);
    container.dataset.count = currentIndex + 1;

    bloquearSwitchNuevo(nuevoNodo);
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
    document.querySelectorAll('.periferico-item, .ram-item, .procesador-item, .monitor-item, .discoDuro-item').forEach(item => {
        // 1. Aplicamos la regla del Caso 1 (No nacen muertos)
        bloquearSwitchNuevo(item);
        
        // 2. Aplicamos la regla del Caso 2 (Lo inactivo se bloquea)
        gestionarBloqueoCampos(item);

        // 3. Escuchamos el cambio del switch para este item
        const sw = item.querySelector('.switch-estado-componente');
        sw.addEventListener('change', function() {
            gestionarBloqueoCampos(item);
        });
    });
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


/**
 * Protege los componentes nuevos para que no nazcan inactivos.
 */
function bloquearSwitchNuevo(contenedor) {
    const esNuevo = contenedor.getAttribute('data-nuevo') === 'true';
    const sw = contenedor.querySelector('.switch-estado-componente');

    if (esNuevo && sw) {
        sw.checked = true; // Forzamos que esté activo
        sw.disabled = true; // <-- ESTO es lo que impide que se "desactive"
        
        // El estilo visual para que el usuario entienda por qué no puede moverlo
        const label = sw.parentElement;
        if (label) {
            label.style.cursor = 'not-allowed';
            label.title = 'Un componente nuevo debe estar activo al registrarse';
            
            // Si usas clases de CSS (como Bootstrap o Tailwind), podrías añadir:
            // label.classList.add('opacity-50');
        }
    }
}


/**
 * Bloquea o desbloquea los inputs de un periférico según su estado de actividad.
 */
function gestionarBloqueoCampos(contenedor) {
    const sw = contenedor.querySelector('.switch-estado-componente');

    const btnOjo = contenedor.querySelector('[data-toggle="collapse"]');

    if (sw.checked) {
        if(btnOjo) btnOjo.innerHTML = '<i class="fas fa-eye"></i> Contraer';
    } else {

        if(btnOjo) btnOjo.innerHTML = '<i class="fas fa-eye"></i> Ver detalles';
    
    }

    const campos = contenedor.querySelectorAll('input:not(.switch-estado-componente):not(.input-motivo), select');
    
    if (!sw.checked) {
        campos.forEach(input => {
            input.readOnly = true; 
            if (input.tagName === 'SELECT') {
                input.style.pointerEvents = 'none';
            }
            input.classList.add('bg-light'); 
        });
    } else {
        campos.forEach(input => {
            input.readOnly = false;
            if (input.tagName === 'SELECT') {
                input.style.pointerEvents = 'auto';
            }
            input.classList.remove('bg-light');
        });
    }

/**
 * Mostrar Bolsa de Cambios
 */
$(document).ready(function() {
    // 1. Diccionario para nombres amigables
    const labels = {
        'usuario_id': 'Usuario Responsable',
        'ubicacion_id': 'Ubicación',
        'departamento_perteneciente': 'Departamento',
        'valor_inicial': 'Valor Inicial',
        'fecha_adquisicion': 'Fecha de Adquisición',
        'fecha_inicio_uso': 'Fecha Inicio de Uso',
        'vida_util_input': 'Vida Útil Estimada'
    };

 // 2. Función principal de monitoreo
function actualizarBolsaCambios() {
    let cambios = [];
    const $lista = $('#lista-cambios');
    const $panel = $('#panel-resumen-cambios');

    let countMod = 0;
    let countAdd = 0;
    let countDel = 0;

    // 1. CAMPOS GENERALES DEL EQUIPO (Evita duplicados con componentes)
    $('input[data-current], select[data-current]').not('.item-componente *').each(function() {
        const $input = $(this);
        const id = $input.attr('id');
        const original = String($input.data('current') || '').trim();
        const actual = String($input.val() || '').trim();

        if (actual !== original) {
            let txtAnt = original;
            let txtNue = actual;

            if ($input.is('select')) {
                txtNue = $input.find('option:selected').text().trim();
                const $optAnt = $input.find(`option[value="${original}"]`);
                txtAnt = $optAnt.length > 0 ? $optAnt.text().trim() : (original || 'Sin asignar');
            }

            const nombreCampo = labels[id] || id || 'Campo General';
            cambios.push(generarFilaCambio(nombreCampo, txtAnt, txtNue, 'Equipo Principal', 'text-info'));
            countMod++;
        }
    });

    // 2. LÓGICA UNIVERSAL PARA COMPONENTES (RAM, Procesadores, Discos, Monitores, Periféricos)
    $('.item-componente').each(function() {
        const $item = $(this);
        
        // Detectar automáticamente el tipo de componente (ej. "RAM #1", "Disco Duro #2")
        const nombreBase = $item.find('h6').text().split('Ver')[0].trim(); 
        const esNuevo = $item.find('.numero-index').text().includes('Nuevo');

        if (esNuevo) {
            cambios.push(generarFilaCambio(nombreBase, 'N/A', 'Nuevo componente agregado', 'Alta', 'text-success'));
            countAdd++;
        } else {
            // Rastrear inputs y selects internos
            $item.find('input[data-current], select[data-current]').each(function() {
                const $input = $(this);

                // Evitar duplicidad en selects de frecuencia/otros que usan input alterno
                if ($input.val() === 'otro' && $input.is('select')) return;

                const original = String($input.data('current') || '').trim();
                const actual = String($input.val() || '').trim();

                if (actual !== original) {
                    const nombreProp = $input.closest('.form-group').find('label').text().trim() || 'Dato';
                    
                    let txtAnt = original;
                    let txtNue = actual;

                    if ($input.is('select')) {
                        txtNue = $input.find('option:selected').text().trim();
                        const $optAnt = $input.find(`option[value="${original}"]`);
                        txtAnt = $optAnt.length > 0 ? $optAnt.text().trim() : original;
                    }

                    // Formateo de unidades
                    if (nombreProp.toLowerCase().includes('capacidad')) { txtAnt += " GB"; txtNue += " GB"; }
                    if (nombreProp.toLowerCase().includes('frecuencia')) { txtAnt += " GHz"; txtNue += " GHz"; }
                    if (nombreProp.toLowerCase().includes('clock')) { txtAnt += " MHz"; txtNue += " MHz"; }

                    cambios.push(generarFilaCambio(`${nombreBase}: ${nombreProp}`, txtAnt, txtNue, 'Modificación', 'text-primary'));
                    countMod++;
                }
            });

            // Rastrear Switch de Estado (Activo/Inactivo)
            const $switch = $item.find('.switch-estado-componente');
            if ($switch.length) {
                const estOriginal = String($switch.data('current')) === "1";
                const estActual = $switch.prop('checked');

                if (estActual !== estOriginal) {
                    cambios.push(generarFilaCambio(
                        `Estado ${nombreBase}`, 
                        estOriginal ? 'ACTIVO' : 'INACTIVO', 
                        estActual ? 'ACTIVO' : 'INACTIVO', 
                        'Estatus', 
                        'text-danger'
                    ));
                    countDel++;
                }
            }
        }
    });

    // Renderizado Final
    if (cambios.length > 0) {
        $lista.html(cambios.join(''));
        actualizarContadores(countMod, countAdd, countDel);
        $panel.fadeIn();
    } else {
        $panel.fadeOut();
    }
}

function actualizarContadores(mod, add, del) {
    if(mod > 0) $('#cnt-mod').show().find('.num').text(mod); else $('#cnt-mod').hide();
    if(add > 0) $('#cnt-add').show().find('.num').text(add); else $('#cnt-add').hide();
    if(del > 0) $('#cnt-del').show().find('.num').text(del); else $('#cnt-del').hide();
}

function generarFilaCambio(titulo, anterior, nuevo, categoria, colorClase) {
    const antLimpios = (anterior === "" || anterior === "Seleccione...") ? "Sin dato" : anterior;
    return `
        <tr>
            <td class="pl-4 py-3">
                <span class="small d-block ${colorClase}" style="font-weight: 700; text-transform: uppercase; font-size: 0.7rem;">${categoria}</span>
                <strong class="text-dark">${titulo}</strong>
            </td>
            <td><span class="badge badge-secondary p-2" style="background:#f1f3f5; color:#495057;">${antLimpios}</span></td>
            <td class="text-center text-muted"><i class="fas fa-arrow-right"></i></td>
            <td><span class="badge badge-success p-2 shadow-sm"><i class="fas fa-edit mr-1"></i> ${nuevo}</span></td>
        </tr>`;
}
    // 4. Escuchar cualquier cambio en el formulario
    $('#formEditarEquipo').on('change input', 'input, select', function() {
        actualizarBolsaCambios();
    });
});



}