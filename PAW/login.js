function togglePassword() {
  const passwordField = document.getElementById("password");
  passwordField.type = passwordField.type === "password" ? "text" : "password";
}

document.getElementById("loginForm").addEventListener("submit", function(e) {
  e.preventDefault(); 

  const formData = new FormData(this);

  fetch("login.php", {
    method: "POST",
    body: formData,
  })
  .then(res => res.json())
  .then(data => {
    Swal.fire({
      icon: data.status,
      title: data.title,
      text: data.message,
      confirmButtonText: "OK"
    }).then(() => {
      if (data.status === "success" && data.redirect) {
        window.location.href = data.redirect;
      }
    });
  })
  .catch(err => {
    Swal.fire({
      icon: "error",
      title: "Error",
      text: "Terjadi kesalahan, silakan coba lagi.",
      confirmButtonText: "OK"
    });
    console.error(err);
  });
});