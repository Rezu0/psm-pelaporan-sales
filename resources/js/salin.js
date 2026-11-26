document.addEventListener('click', async (e) => {
    const tombol = e.target.closest('.salin-laporan');
    if (!tombol || tombol.dataset.sibuk) return;

    const teks = tombol.dataset.text;

    try {
        await navigator.clipboard.writeText(teks);
    } catch {
        const ta = document.createElement('textarea');
        ta.value = teks;
        ta.style.cssText = 'position:fixed;opacity:0';
        document.body.appendChild(ta);
        ta.select();
        document.execCommand('copy');
        ta.remove();
    }

    const asli = tombol.innerHTML;
    tombol.dataset.sibuk = '1';
    tombol.innerHTML = '<i class="fa-solid fa-check"></i> Tersalin';

    setTimeout(() => {
        tombol.innerHTML = asli;
        delete tombol.dataset.sibuk;
    }, 1500);
});