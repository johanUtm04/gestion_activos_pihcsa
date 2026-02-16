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

function togglePanel() {
    const body = document.getElementById('searchBody');
    const icon = document.getElementById('toggle-icon');

    if (body.style.maxHeight === "0px" || body.style.maxHeight === "") {
        body.style.maxHeight = "500px";
        body.style.opacity = "1";
        
        icon.classList.remove('fa-plus');
        icon.classList.add('fa-minus');
        
        icon.style.transform = "rotate(180deg)";
    } else {

        body.style.maxHeight = "0";
        body.style.opacity = "0";
        
        icon.classList.remove('fa-minus');
        icon.classList.add('fa-plus');
        
        icon.style.transform = "rotate(0deg)";
    }
}