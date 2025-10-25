// dropdown on profile button click
document.addEventListener('DOMContentLoaded', function() {
    const dropdownButton = document.querySelector(".dropdown button");
    const dropdownMenu = document.querySelector(".dropdown-menu");
    
    if (dropdownButton && dropdownMenu) {
        dropdownButton.addEventListener("click", function() {
            dropdownMenu.classList.toggle("show");
        });
        
        // Close dropdown if clicked outside
        window.addEventListener("click", function(e) {
            if (!e.target.closest(".dropdown")) {
                dropdownMenu.classList.remove("show");
            }
        });
    }
});
