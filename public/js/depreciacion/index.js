$(document).ready(function() {
    $(document).on('click', '.btn-depreciar', function() {
        
        const marca = $(this).data('marca');
        const valor = parseFloat($(this).data('valor')) || 0;
        const fechaAdquisicion = new Date($(this).data('fecha'));
        const vidaUtil = parseInt($(this).data('vida')) || 1;
        
        const hoy = new Date();
        
        let años = hoy.getFullYear() - fechaAdquisicion.getFullYear();
        const m = hoy.getMonth() - fechaAdquisicion.getMonth();
        if (m < 0 || (m === 0 && hoy.getDate() < fechaAdquisicion.getDate())) {
            años--;
        }
        años = Math.max(0, años);

        const depreciacionAnual = valor / vidaUtil;
        const totalDepreciado = Math.min(depreciacionAnual * años, valor);
        const valorActual = valor - totalDepreciado;

        $('#d-activo').text(marca);
        $('#d-valor').text(valor.toLocaleString('en-US', {minimumFractionDigits: 2}));
        $('#d-añosTrasncurridos').text(años + (años === 1 ? ' año' : ' años'));
        $('#d-depreciado').text(totalDepreciado.toLocaleString('en-US', {minimumFractionDigits: 2}));
        $('#d-actual').text(valorActual.toLocaleString('en-US', {minimumFractionDigits: 2}));

        $('#modalDepreciacion').modal('show');
    });
});