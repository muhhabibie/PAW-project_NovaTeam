document.getElementById("gameForm").addEventListener("submit", function(e) {
    e.preventDefault();

    const formData = new FormData(this);

    fetch("reqGame.php", {
        method: "POST",
        body: formData
    })
    .then(res => res.json())
    .then(response => {
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
    });
});
