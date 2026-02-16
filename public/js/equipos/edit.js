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
