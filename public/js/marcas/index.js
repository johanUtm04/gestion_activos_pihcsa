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


$(document).ready(function() {
    const marker = document.getElementById('scroll-target-marker');

    if (marker) {
        const equipoId = marker.getAttribute('data-id');
        const targetRow = document.getElementById('marca-' + equipoId);

        if (targetRow) {
            targetRow.scrollIntoView({ 
                behavior: 'smooth', 
                block: 'center' 
            });

            $(targetRow).css('background-color', '#fdecea');
            
            $(targetRow).fadeOut(400).fadeIn(400).fadeOut(400).fadeIn(400, function() {
                setTimeout(() => {
                    $(this).animate({ backgroundColor: "transparent" }, 2000);
                }, 3000);
            });
        }
    }
});

