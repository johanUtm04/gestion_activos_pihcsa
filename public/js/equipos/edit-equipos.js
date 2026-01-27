/**
 * Agrega un nuevo bloque de componente clonando el template
 */
function agregarComponente(tipo) {
    const container = document.getElementById(`${tipo}-container`);
    const templateElement = document.getElementById(`template-${tipo}`);

    if (!container || !templateElement) return;

    // 1. Obtenemos el índice actual desde el data-count del contenedor
    let currentIndex = parseInt(container.dataset.count) || 0;
    
    // 2. Obtenemos el HTML del template y reemplazamos __INDEX__ por el número actual
    let html = templateElement.innerHTML;
    const nuevoHtmlStr = html.replace(/__INDEX__/g, currentIndex);
    
    // 3. Convertimos el string a un elemento del DOM
    const tempDiv = document.createElement('div');
    tempDiv.innerHTML = nuevoHtmlStr.trim();
    const nuevoNodo = tempDiv.firstElementChild;

    // 4. Actualizamos el número visual si existe el span .numero-index
    const spanNumero = nuevoNodo.querySelector('.numero-index');
    
    if (spanNumero) {
        spanNumero.textContent = currentIndex + 1;
    }
    
    // 5. Lo inyectamos al final del contenedor
    container.appendChild(nuevoNodo);
    
    // 6. Aumentamos el contador para la próxima vez
    container.dataset.count = currentIndex + 1;
}

/**
 * Gestiona la eliminación visual y lógica del componente
 */
function eliminarComponente(btn, clasePadre) {
    if (!confirm('¿Estás seguro de eliminar este componente?')) return;

    const item = btn.closest('.' + clasePadre);
    const deleteInput = item.querySelector('input[name*="[_delete]"]');

    if (deleteInput) {
        // Si el ítem ya existe en la BD (tiene un campo _delete), lo ocultamos y marcamos
        deleteInput.value = "1";
        item.style.display = 'none';
        
        // Quitamos el 'required' de los campos hijos para que no bloqueen el envío del form
        item.querySelectorAll('select, input').forEach(el => el.removeAttribute('required'));
    } else {
        // Si es un ítem nuevo que no se ha guardado, lo borramos del mapa
        item.remove();
    }
}

//Campo de Vida Util estimada
document.getElementById('vida_util_unidad').addEventListener('change', function() {
    const inputPrecio = document.getElementById('vida_util_input');
    if (this.value !== "") {
        inputPrecio.disabled = false;
    }
});