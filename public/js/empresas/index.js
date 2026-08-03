// --- FUNCIONES GLOBALES ---

/**
 * Contrae o despliega el panel de búsqueda rotando el ícono.
 */
function togglePanel() {
    const body = document.getElementById('searchBody');
    const icon = document.getElementById('toggle-icon');

    if (!body || !icon) return;

    if (body.style.maxHeight === '0px' || body.style.maxHeight === '') {
        body.style.maxHeight = '500px';
        body.style.opacity = '1';

        icon.classList.replace('fa-plus', 'fa-minus');
        icon.style.transform = 'rotate(180deg)';
    } else {
        body.style.maxHeight = '0';
        body.style.opacity = '0';

        icon.classList.replace('fa-minus', 'fa-plus');
        icon.style.transform = 'rotate(0deg)';
    }
}

// --- EVENTOS DEL DOM ---

document.addEventListener('DOMContentLoaded', function () {
    /**
     * Permite navegar al hacer clic sobre una fila.
     * No se ejecuta cuando el usuario pulsa botones, enlaces o formularios.
     */
    const rows = document.querySelectorAll('.clickable-row');

    rows.forEach(function (row) {
        row.addEventListener('click', function (event) {
            const interactiveElement = event.target.closest(
                'button, a, .btn, form, input, select, textarea'
            );

            if (interactiveElement) return;

            const url = this.getAttribute('data-url');

            if (url) {
                window.location.href = url;
            }
        });
    });
});

$(document).ready(function () {
    /**
     * Scroll y resaltado del registro creado o actualizado.
     * La fila debe tener el ID: empresa-{id}
     */
    const marker = document.getElementById('scroll-target-marker');

    if (marker) {
        const targetId = marker.getAttribute('data-id');
        const targetRow = document.getElementById('empresa-' + targetId);

        if (targetRow) {
            targetRow.scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });

            // Rosa suave acorde con el módulo de Empresas.
            $(targetRow).css('background-color', '#fce4ef');

            // Efecto de atención.
            $(targetRow)
                .fadeOut(400)
                .fadeIn(400)
                .fadeOut(400)
                .fadeIn(400, function () {
                    setTimeout(() => {
                        $(this).animate(
                            {
                                backgroundColor: 'transparent'
                            },
                            2000
                        );
                    }, 3000);
                });
        }
    }
});