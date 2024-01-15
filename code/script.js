document.addEventListener("DOMContentLoaded", function () {
    const searchBar = document.getElementById("searchBar");
    const navLinks = document.querySelectorAll("nav a");

    searchBar.addEventListener("input", function () {
        const searchTerm = searchBar.value.toLowerCase();

        navLinks.forEach(function (link) {
            const linkText = link.textContent.toLowerCase();

            if (linkText.includes(searchTerm)) {
                link.style.display = "inline-block";
            } else {
                link.style.display = "none";
            }
        });
    });
});

//jjjjjjjj