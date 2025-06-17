document.getElementById("gameForm").addEventListener("submit", function(e) {
  e.preventDefault();

  const formData = new FormData(this);

  fetch("reqGame.php", {
    method: "POST",
    body: formData
  })
    .then(res => res.json())
    .then(response => {
  
      if (
        response.status === "error" &&
        response.message === "Anda harus login terlebih dahulu."
      ) {
        Swal.fire({
          icon: "warning",
          title: "Belum Login",
          text: "Anda harus login terlebih dahulu untuk mengajukan game.",
          confirmButtonText: "Login"
        }).then(() => {
          window.location.href = "login.html";
        });
        return;
      }
      Swal.fire({
        icon: response.status,
        title: response.title,
        text: response.message
      }).then(() => {
        if (response.redirect) {
          window.location.href = response.redirect;
        }
      });
    })
    .catch(error => {
      Swal.fire("Error", "Terjadi kesalahan pada server.", "error");
      console.error(error);
    });
});
