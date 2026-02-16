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