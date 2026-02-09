$(document).ready(function() {
    const newUserRow = $('tr[id^="user-"]').filter(function() {
        return $(this).find('.badge-status-pill').length > 0;
    });

    if (newUserRow.length) {
        newUserRow[0].scrollIntoView({behavior: 'smooth', block: 'center'});
        newUserRow.css('background-color', '#f8fff9');
        setTimeout(() => {
            newUserRow.animate({ backgroundColor: "transparent" }, 2000);
        }, 1000);
    }
});