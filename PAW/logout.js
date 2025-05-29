document.addEventListener("DOMContentLoaded", () => {
    const logoutBtn = document.getElementById("logoutBtn");

    if (logoutBtn) {
        logoutBtn.addEventListener("click", function (e) {
            e.preventDefault();

            Swal.fire({
                title: "Yakin ingin logout?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Ya, Logout",
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch("logout.php")
                        .then((res) => res.json())
                        .then((data) => {
                            if (data.status === "success") {
                                Swal.fire({
                                    title: "Berhasil!",
                                    text: "Anda telah logout.",
                                    icon: "success",
                                    timer: 2000,
                                    showConfirmButton: false
                                }).then(() => {
                                    window.location.href = "login.html"; 
                                });
                            } else {
                                Swal.fire("Gagal", data.message || "Logout gagal", "error");
                            }
                        })
                        .catch((err) => {
                            console.error("Gagal logout:", err);
                            Swal.fire("Error", "Terjadi kesalahan saat logout.", "error");
                        });
                }
            });
        });
    }
});
