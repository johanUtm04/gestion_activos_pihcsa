/**
 * CALCULO DE DEPRECIACIÓN DE ACTIVOS (Línea Recta Mensual)
 * * NOTA PARA EL SIGUIENTE INGENIERO:
 * Este script calcula la pérdida de valor por cada mes que el equipo ha estado en operación.
 */
$(document).ready(function() {
    $(document).on('click', '.btn-depreciar', function() {
        
        // 1. Extracción y Saneamiento de Datos
        const marca            = $(this).data('marca');
        const valorInicial     = parseFloat($(this).data('valor')) || 0;
        const fechaAdquisicion = new Date($(this).data('fecha'));
        const vidaUtilAnios    = parseInt($(this).data('vida')) || 1; // Siempre llega en años desde el Blade
        
        const hoy = new Date();
        
        // 2. Cálculo de Tiempo Transcurrido en Meses (Más preciso para contabilidad médica)
        // Calculamos la diferencia total de meses entre hoy y la compra
        let mesesTranscurridos = (hoy.getFullYear() - fechaAdquisicion.getFullYear()) * 12;
        mesesTranscurridos += hoy.getMonth() - fechaAdquisicion.getMonth();
        
        // Si el día de hoy es menor al día de compra, no se ha cumplido el mes completo
        if (hoy.getDate() < fechaAdquisicion.getDate()) {
            mesesTranscurridos--;
        }

        // Aseguramos que no sea negativo (por si la fecha de compra es futura por error)
        mesesTranscurridos = Math.max(0, mesesTranscurridos);

        // 3. Lógica de Depreciación Lineal Mensual
        const vidaUtilMeses = vidaUtilAnios * 12;
        const depreciacionMensual = valorInicial / vidaUtilMeses;
        
        // El total depreciado no puede exceder el valor inicial del equipo
        const totalDepreciado = Math.min(depreciacionMensual * mesesTranscurridos, valorInicial);
        const valorActual = valorInicial - totalDepreciado;

        // 4. Formateo para la Interfaz (Cálculo de años/meses para mostrar al usuario)
        const aniosFinales = Math.floor(mesesTranscurridos / 12);
        const mesesRestantes = mesesTranscurridos % 12;
        
        let textoTiempo = `${aniosFinales} ${aniosFinales === 1 ? 'año' : 'años'}`;
        if (mesesRestantes > 0) {
            textoTiempo += ` y ${mesesRestantes} ${mesesRestantes === 1 ? 'mes' : 'meses'}`;
        }

        // 5. Inyección en el DOM (Modal)
        $('#d-activo').text(marca);
        $('#d-valor').text(valorInicial.toLocaleString('en-US', {minimumFractionDigits: 2}));
        $('#d-añosTrasncurridos').text(textoTiempo);
        $('#d-depreciado').text(totalDepreciado.toLocaleString('en-US', {minimumFractionDigits: 2}));
        $('#d-actual').text(valorActual.toLocaleString('en-US', {minimumFractionDigits: 2}));

        // 6. Alerta visual si el equipo está totalmente depreciado
        if (valorActual <= 0) {
            $('#d-actual').addClass('text-danger').append(' (Vida útil agotada)');
        } else {
            $('#d-actual').removeClass('text-danger');
        }

        $('#modalDepreciacion').modal('show');
    });
});


// Cálculo del porcentaje de vida restante
const porcentajeRestante = Math.max(0, ((valorActual / valor) * 100).toFixed(0));
const progressBar = $('#d-progreso');

// Actualizar la barra visualmente
progressBar.css('width', porcentajeRestante + '%');
$('#d-porcentaje-text').text(porcentajeRestante + '%');

// Cambiar color de la barra según el estado
if (porcentajeRestante > 50) progressBar.addClass('bg-success').removeClass('bg-warning bg-danger');
else if (porcentajeRestante > 20) progressBar.addClass('bg-warning').removeClass('bg-success bg-danger');
else progressBar.addClass('bg-danger').removeClass('bg-success bg-warning');