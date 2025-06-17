document.addEventListener("DOMContentLoaded", () => {
    const template = document.getElementById('ticket-template');
    const daftarTiket = document.getElementById('daftarTiket');

    if (!template) {
        console.error('Template tidak ditemukan!');
        return;
    }

    fetch('riwayat.php')
        .then(response => response.json())
        .then(data => {
            daftarTiket.innerHTML = '';

            if (data.status !== 'success') {
                console.error('Data gagal dimuat:', data.message || 'Unknown error');
                daftarTiket.innerHTML = '<p style="text-align:center; color:red;">Gagal memuat data. User belum login</p>';
                return;
            }

            if (!data.data || data.data.length === 0) {
                daftarTiket.innerHTML = `
                    <div style="text-align:center; color:gray; font-size:16px; margin-top:20px;">
                        <i class="fas fa-folder-open" style="font-size:40px; margin-bottom:10px;"></i><br>
                        Tidak ada riwayat booking
                    </div>`;
                return;
            }

            data.data.forEach(item => {
                const clone = template.content.cloneNode(true);

                clone.querySelector('.unit-text').textContent = item.unit || '-';
                clone.querySelector('.waktu-text').textContent = item.waktu || '-';
                clone.querySelector('.unit-number-text').textContent = item.unit || '-';
                clone.querySelector('.pemain-text').textContent = item.pemain + ' orang' || '-';
                clone.querySelector('.booking-id-text').textContent = item.booking_id || '-';
                clone.querySelector('.bulan-text').textContent = item.month || '-';
                clone.querySelector('.hari-text').textContent = item.day || '-';
                clone.querySelector('.tahun-text').textContent = item.year || '-';

                daftarTiket.appendChild(clone);
            });
        })
        .catch(err => {
            console.error('Gagal mengambil data:', err);
            daftarTiket.innerHTML = `
                <p style="text-align:center; color:red;">Gagal terhubung ke server.</p>`;
        });
});
