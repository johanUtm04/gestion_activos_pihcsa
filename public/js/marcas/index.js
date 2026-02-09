$(document).ready(function() {
    const highlightRow = $('tr').filter(function() {
        return $(this).find('.badge-status').length > 0;
    });

    if (highlightRow.length) {
        highlightRow[0].scrollIntoView({behavior: 'smooth', block: 'center'});
        highlightRow.css('background-color', '#fff5f5');
        setTimeout(() => {
            highlightRow.animate({ backgroundColor: "transparent" }, 2000);
        }, 1000);
    }
});