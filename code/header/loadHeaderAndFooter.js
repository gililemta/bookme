document.addEventListener("DOMContentLoaded", function () {
  fetch("../header/header.php")
  .then((response) => response.text())
  .then((html) => {
      document.getElementById("header-container").innerHTML = html;
      var link = document.createElement("link");
      link.rel = "stylesheet";
      link.href = "../header/header.css";
      document.head.appendChild(link);

      // Re-bind menu toggle functionality after header is loaded
      bindMenuToggle();
  });

  fetch("../footer/footer.html")
  .then((response) => response.text())
  .then((html) => {
      document.getElementById("footer-container").innerHTML = html;

      var footerStyleLink = document.createElement("link");
      footerStyleLink.rel = "stylesheet";
      footerStyleLink.href = "../footer/footer.css";
      document.head.appendChild(footerStyleLink);
  });
});

function bindMenuToggle() {
  const menuToggle = document.querySelector('.menu-toggle');
  const nav = document.querySelector('nav');

  if (menuToggle && nav) {
      menuToggle.addEventListener('click', function() {
          nav.classList.toggle('nav-active');
          
          if (nav.classList.contains('nav-active')) {
              menuToggle.textContent = 'X'; // Change to 'X' or close icon
          } else {
              menuToggle.textContent = '☰'; // Change back to menu icon
          }
      });
  }
}
