document.addEventListener("DOMContentLoaded", function () {
    const notifBtn = document.getElementById('notifBtn');
    const notifDropdown = document.getElementById('notifDropdown');
    const notifCount = document.getElementById('notifCount');
    const notifList = document.getElementById('notifList');
    const sound = document.getElementById('notifSound');

    // 1. Toggle Dropdown Notifikasi
    if (notifBtn) {
        notifBtn.addEventListener('click', () => {
            notifDropdown.classList.toggle('hidden');
            notifCount.classList.add('hidden'); // Sembunyikan badge setelah dibuka
        });
    }

    // 2. Simulasi Pesanan Masuk (Realtime)
    setInterval(() => {
        const adaPesananBaru = Math.random() < 0.3; // 30% peluang ada order
        
        if(adaPesananBaru && notifList) {
            // Mainkan Suara
            if(sound) sound.play().catch(e => console.log('Audio perlu interaksi user'));
            
            // Tampilkan Notif
            const item = document.createElement('div');
            item.className = 'notif-item';
            item.innerHTML = '<b>Pesanan Baru!</b><br>Pisang Goreng Keju (2x) - Rp 30.000';
            
            const empty = notifList.querySelector('.empty-notif');
            if(empty) empty.remove();

            notifList.prepend(item);
            
            if(notifCount) {
                notifCount.classList.remove('hidden');
                notifCount.innerText = parseInt(notifCount.innerText) + 1;
            }
        }
    }, 10000); 

    // ============================================
    // BAGIAN BARU YANG DITAMBAHKAN (SWEETALERT)
    // ============================================
    const flashData = document.getElementById('flash-data');
    
    if (flashData) {
        const icon = flashData.getAttribute('data-icon');
        const title = flashData.getAttribute('data-title');
        const text = flashData.getAttribute('data-text');

        Swal.fire({
            icon: icon,
            title: title,
            text: text,
            confirmButtonColor: '#89CFF0', // Warna biru tema
            confirmButtonText: 'Oke Siap!'
        });
    }
});