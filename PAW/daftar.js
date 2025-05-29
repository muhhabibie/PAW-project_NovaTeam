document.addEventListener('DOMContentLoaded', () => {
    const scheduleForm = document.getElementById('scheduleForm');
    const timeSlotsContainer = document.getElementById('timeSlotsContainer');
    const timeButtons = timeSlotsContainer.querySelectorAll('.time-slot');
    const unitGameContainer = document.getElementById('unitGameContainer');
    const unitButtons = unitGameContainer.querySelectorAll('.unit-game');
    const tanggalSelect = document.getElementById('tanggal');

    let selectedUnit = null;

    const hariMapping = {
        today: 'Hari ini',
        tomorrow: 'Besok',
        'day-after-tomorrow': 'Lusa'
    };

    function getFormattedDate(hariKey) {
        const today = new Date();
        if (hariKey === 'today') return today.toISOString().split('T')[0];
        if (hariKey === 'tomorrow') {
            today.setDate(today.getDate() + 1);
            return today.toISOString().split('T')[0];
        }
        if (hariKey === 'day-after-tomorrow') {
            today.setDate(today.getDate() + 2);
            return today.toISOString().split('T')[0];
        }
        return hariKey;
    }

    function updateBookedTimes(bookedTimes) {
        timeButtons.forEach(btn => {
            const time = btn.dataset.time;
            if (bookedTimes.includes(time)) {
                btn.classList.add('booked');
                btn.classList.remove('selected');
            } else {
                btn.classList.remove('booked');
            }
        });
    }

    function fetchBookedTimes(hariKey) {
        const hari = getFormattedDate(hariKey);
        if (!selectedUnit) return;

        fetch('daftar_cek.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ hari, unit: selectedUnit })
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                updateBookedTimes(data.bookedTimes);
            } else {
                console.error('Server error:', data.message);
            }
        })
        .catch(err => console.error('Error fetch:', err));
    }

    tanggalSelect.addEventListener('change', () => {
        fetchBookedTimes(tanggalSelect.value);
    });

    unitButtons.forEach(button => {
        button.addEventListener('click', () => {
            unitButtons.forEach(btn => btn.classList.remove('selected'));
            button.classList.add('selected');
            selectedUnit = button.dataset.unit;
            fetchBookedTimes(tanggalSelect.value);
        });
    });

    timeButtons.forEach(button => {
        button.addEventListener('click', () => {
            if (button.classList.contains('booked')) {
                Swal.fire({
                    icon: 'info',
                    title: 'Sudah dibooking',
                    text: 'Waktu ini sudah dibooking oleh orang lain.'
                });
                return;
            }

            timeButtons.forEach(btn => btn.classList.remove('selected'));
            button.classList.add('selected');
        });
    });

    scheduleForm.addEventListener('submit', (e) => {
        e.preventDefault();

        const hariKey = tanggalSelect.value;
        const hari = getFormattedDate(hariKey);
        const pemain = document.getElementById('pemain').value;
        const selectedTimeSlot = timeSlotsContainer.querySelector('.time-slot.selected');

        if (!selectedTimeSlot || !selectedUnit) {
            Swal.fire({
                icon: 'warning',
                title: 'Data belum lengkap',
                text: 'Pilih unit dan waktu terlebih dahulu.',
            });
            return;
        }

        const waktu = selectedTimeSlot.dataset.time;

        fetch('daftar.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ hari, pemain, waktu, unit: selectedUnit })
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    html: `Unit <b>${selectedUnit}</b> telah dibooking untuk <b>${hariMapping[hariKey]}</b> pukul <b>${waktu}</b>.`,
                }).then(() => {
                    scheduleForm.reset();
                    timeButtons.forEach(btn => btn.classList.remove('selected'));
                    unitButtons.forEach(btn => btn.classList.remove('selected'));
                    selectedUnit = null;
                    fetchBookedTimes(tanggalSelect.value);
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: data.message
                });
            }
        })
        .catch(() => {
            Swal.fire({
                icon: 'error',
                title: 'Kesalahan',
                text: 'Terjadi kesalahan saat menyimpan jadwal.'
            });
        });
    });
});
