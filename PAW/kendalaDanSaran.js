const pengalamanInput = document.getElementById("pengalaman");
const counter = document.getElementById("char-counter");
const form = document.getElementById("feedback-form");

function updateCounter() {
  const length = pengalamanInput.value.length;
  counter.textContent = `${length}/50`;

    if (length === 50) {
    counter.style.color = "red";
  } else {
    counter.style.color = "black";
  }

}
pengalamanInput.addEventListener("input", updateCounter);

form.addEventListener("submit", function (e) {
  e.preventDefault();

  const pengalaman = pengalamanInput.value.trim();
  if (pengalaman === "") {
    Swal.fire("Peringatan", "Kolom pengalaman tidak boleh kosong!", "warning");
    return;
  }

  if (pengalaman.length > 50) {
    Swal.fire("Peringatan", "Maksimal 50 karakter untuk pengalaman.", "warning");
    return;
  }

  const formData = new FormData(form);

  fetch("kendalaDanSaran.php", {
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
      } else if (data.status === "success") {
        form.reset();
        updateCounter();
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
