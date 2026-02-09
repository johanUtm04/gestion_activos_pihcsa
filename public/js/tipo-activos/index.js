$(document).ready(function() {
    const alertRow = $('tr').filter(function() {
        return $(this).find('.badge-status').length > 0;
    });

    if (alertRow.length) {
        alertRow[0].scrollIntoView({behavior: 'smooth', block: 'center'});
        alertRow.css('background-color', '#fff5f5');
        setTimeout(() => {
            alertRow.animate({ backgroundColor: "transparent" }, 2000);
        }, 1000);
    }
});