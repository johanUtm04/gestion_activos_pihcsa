$(document).ready(function() {
    const targetRow = $('tr').filter(function() {
        return $(this).find('.badge-status').length > 0;
    });

    if (targetRow.length) {
        targetRow[0].scrollIntoView({behavior: 'smooth', block: 'center'});
        targetRow.css('background-color', '#fff5f5');
        setTimeout(() => {
            targetRow.animate({ backgroundColor: "transparent" }, 2000);
        }, 1000);
    }
});