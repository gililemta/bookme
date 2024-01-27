document.addEventListener("DOMContentLoaded", function () {
  // Load the header using fetch and insert it into the designated container
  fetch("header/header.html")
    .then((response) => response.text())
    .then((html) => {
      document.getElementById("header-container").innerHTML = html;
      var link = document.createElement("link");
      link.rel = "stylesheet";
      link.href = "header/header.css";
      document.head.appendChild(link);
    });

  // Load the footer using fetch and insert it into the designated container
  fetch("footer/footer.html")
    .then((response) => response.text())
    .then((html) => {
      document.getElementById("footer-container").innerHTML = html;

      var footerStyleLink = document.createElement("link");
      footerStyleLink.rel = "stylesheet";
      footerStyleLink.href = "footer/footer.css";
      document.head.appendChild(footerStyleLink);
    });
});
