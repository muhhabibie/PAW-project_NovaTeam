async function loadJadwalSaya() {
    const container = document.getElementById('jadwalSayaContainer');
    const template = document.getElementById('jadwal-template');

    container.innerHTML = 'Memuat jadwal...';

    try {
        const res = await fetch('homepage.php');
        if (!res.ok) throw new Error(`HTTP error: ${res.status}`);
        const result = await res.json();

        if (result.status !== 'success') {
            container.innerHTML = `<p style="color:red;">Gagal memuat jadwal: ${result.message || 'Unknown error'}</p>`;
            return;
        }

        const jadwalList = result.data;
        if (jadwalList.length === 0) {
            container.innerHTML = '<p>Belum ada jadwal yang dibuat.</p>';
            return;
        }

        container.innerHTML = ''; 

        jadwalList.forEach(jadwal => {
            const clone = template.content.cloneNode(true);

            const dateObj = new Date(jadwal.hari);
            const month = dateObj.toLocaleDateString('id-ID', { month: 'short' });
            const day = dateObj.toLocaleDateString('id-ID', { day: '2-digit' });
            const year = dateObj.toLocaleDateString('id-ID', { year: 'numeric' });

           
            clone.querySelector('.unit-text').textContent = jadwal.unit;
            clone.querySelectorAll('.unit-text')[1].textContent = jadwal.unit; 
            clone.querySelector('.waktu-text').textContent = jadwal.waktu;
            clone.querySelector('.pemain-text').textContent = `${jadwal.pemain} orang`;
            clone.querySelector('.booking-id-text').textContent = jadwal.booking_id || '-';

            clone.querySelector('.bulan-text').textContent = month;
            clone.querySelector('.hari-text').textContent = day;
            clone.querySelector('.tahun-text').textContent = year;

            container.appendChild(clone);
        });

    } catch (error) {
        container.innerHTML = `<p style="color:red;">Terjadi kesalahan: ${error.message}</p>`;
    }
}

window.addEventListener('DOMContentLoaded', loadJadwalSaya);
