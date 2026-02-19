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
    document.querySelectorAll('.periferico-item, .ram-item').forEach(item => {
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
        sw.checked = true;
        sw.disabled = true;
        sw.parentElement.style.cursor = 'not-allowed';
        sw.parentElement.title = 'Un componente nuevo debe estar activo al registrarse';
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
}