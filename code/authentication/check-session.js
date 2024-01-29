fetch("authentication/check-session.php")
  .then((response) => response.json())
  .then((data) => {
    if (data.user_email) {
      const userEmail = data.user_email;
      sessionStorage.setItem("user_email", userEmail);
    } else {
      console.warn("unauthorized");
      window.location.href = "unauthorizedPage.html";
    }
  })
  .catch((error) => {
    window.location.href = "unauthorizedPage.html";
    console.error("Error:", error);
  });
