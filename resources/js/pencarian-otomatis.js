document.addEventListener('DOMContentLoaded', () => {
    const form  = document.getElementById('form-cari');
    const input = document.getElementById('input-cari');
    const tanda = document.getElementById('tanda-memuat');
    const hasil = document.getElementById('hasil-laporan');

    if (!form || !input || !hasil) return;

    let urutan = 0;
    let timer;

    async function cari(url) {
        const saya = ++urutan;
        if (tanda) tanda.style.display = '';
        url = url || form.action + '?cari=' + encodeURIComponent(input.value);

        try {
            const res  = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
            const html = await res.text();

            // abaikan jawaban lama kalau sudah ada ketikan yang lebih baru
            if (saya !== urutan) return;

            const doc = new DOMParser().parseFromString(html, 'text/html');
            ['jumlah-laporan', 'hasil-laporan'].forEach((id) => {
                const baru   = doc.getElementById(id);
                const lama   = document.getElementById(id);
                if (baru && lama) lama.innerHTML = baru.innerHTML;
            });
            history.replaceState(null, '', url);
        } catch (e) {
            console.error('Pencarian gagal:', e);
        } finally {
            if (tanda && saya === urutan) tanda.style.display = 'none';
        }
    }

    // otomatis cari 0,3 detik setelah berhenti mengetik
    input.addEventListener('input', () => {
        clearTimeout(timer);
        timer = setTimeout(() => cari(), 300);
    });

    // Enter / tombol Cari tetap jalan tanpa reload
    form.addEventListener('submit', (e) => {
        e.preventDefault();
        clearTimeout(timer);
        cari();
    });

    // pagination tanpa reload (tombol edit/hapus tidak tersentuh)
    hasil.addEventListener('click', (e) => {
        const a = e.target.closest('a[href]');
        if (a && a.href.includes('page=')) {
            e.preventDefault();
            cari(a.href);
        }
    });
});