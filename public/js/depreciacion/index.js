//Extrae datos de la tabla y los coloca en el Modal
$(document).on('click', '.btn-depreciar', function() {
    // 1. Extraer datos del botón
    const marca = $(this).data('marca');
    const valor = $(this).data('valor');
    const fecha = $(this).data('fecha');

    // 2. Colocar datos base en el modal
    $('#span-marca').text(marca);
    $('#val-moi-text').text(new Intl.NumberFormat('es-MX', {style:'currency', currency:'MXN'}).format(valor));
    $('#hidden-moi').val(valor);
    $('#hidden-fecha').val(fecha);

    // 3. Limpiar resultados anteriores y abrir modal
    $('#calculo-animado').html('<div class="text-center p-4 text-muted border rounded bg-white h-100"><i class="fas fa-calculator fa-3x mb-3"></i><p>Listo para calcular...</p></div>');
    $('#modalDepreciacion').modal('show');
});


//Logica de Calculo + animacion
$('#btn-recalcular').on('click', function() {
    const moi = parseFloat($('#hidden-moi').val());
    const tasa = parseFloat($('#select-tasa').val());
    const fechaAdq = new Date($('#hidden-fecha').val());
    const finAnio = new Date(fechaAdq.getFullYear(), 11, 31);

    // Cálculo de meses deducibles (LISR México)
    let mesesDeducibles = (finAnio.getMonth() - fechaAdq.getMonth()) + 1;

    // Animación de carga
    $('#calculo-animado').html(`
        <div class="text-center py-5">
            <div class="spinner-grow text-info" role="status"></div>
            <p class="mt-3 font-italic">Aplicando tasas de depreciación...</p>
        </div>
    `);

    setTimeout(() => {
        const anual = moi * tasa;
        const mensual = anual / 12;
        const totalEjercicio = mensual * mesesDeducibles;

        const f = (n) => new Intl.NumberFormat('es-MX', {style:'currency', currency:'MXN'}).format(n);

        // Inyectar HTML con los resultados finales
        $('#calculo-animado').hide().html(`
            <div class="card card-outline card-success shadow-none mb-0">
                <div class="card-body p-2">
                    <table class="table table-sm mb-0">
                        <tr><td>Depreciación Anual (${tasa*100}%):</td><td class="text-right">${f(anual)}</td></tr>
                        <tr><td>Depreciación Mensual:</td><td class="text-right">${f(mensual)}</td></tr>
                        <tr class="bg-light"><td><strong>Meses de Uso:</strong></td><td class="text-right text-primary font-weight-bold">${mesesDeducibles}</td></tr>
                        <tr class="h5"><td><strong>Deducción Total:</strong></td><td class="text-right text-success font-weight-bold">${f(totalEjercicio)}</td></tr>
                    </table>
                </div>
            </div>
            <p class="small text-muted mt-2"><i class="fas fa-info-circle mr-1"></i> Basado en adquisición del ${fechaAdq.toLocaleDateString()}</p>
        `).fadeIn(400);
    }, 700);
});

function togglePanel() {
    const body = document.getElementById('searchBody');
    const icon = document.getElementById('toggle-icon');

    if (body.style.maxHeight === "0px" || body.style.maxHeight === "") {
        // Abrir: Usamos un valor lo suficientemente grande (ej. 500px) para el contenido
        body.style.maxHeight = "500px"; 
        body.style.opacity = "1";
        body.style.paddingTop = "15px"; // Opcional, para que no pegue con el borde
        icon.classList.replace('fa-plus', 'fa-minus');
        icon.style.transform = "rotate(180deg)";
    } else {
        // Cerrar
        body.style.maxHeight = "0";
        body.style.opacity = "0";
        body.style.paddingTop = "0";
        icon.classList.replace('fa-minus', 'fa-plus');
        icon.style.transform = "rotate(0deg)";
    }
}