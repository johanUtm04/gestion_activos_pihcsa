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
    document.querySelectorAll('.periferico-item').forEach(item => {
        bloquearSwitchNuevo(item);
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
    // 1. Verificamos si el atributo data-nuevo es "true"
    const esNuevo = contenedor.getAttribute('data-nuevo') === 'true';
    
    // 2. Buscamos el switch dentro de este contenedor
    const sw = contenedor.querySelector('.switch-estado-componente');

    if (esNuevo && sw) {
        // Forzamos que esté marcado (Activo)
        sw.checked = true;
        
        // Lo deshabilitamos para que el usuario no pueda apagarlo
        sw.disabled = true;

        // Opcional: Cambiamos el cursor para indicar que no es clicable
        sw.parentElement.style.cursor = 'not-allowed';
        sw.parentElement.title = 'Un componente nuevo debe estar activo al registrarse';
    }
}