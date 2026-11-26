const box = document.getElementById('lightbox');
const img = document.getElementById('lightbox-img');

function openLightbox(src, alt = '') {
    img.src = src;
    img.alt = alt;
    box.classList.remove('hidden');
    box.classList.add('flex');
    document.body.classList.add('overflow-hidden');

    // dua frame supaya transisi CSS jalan
    requestAnimationFrame(() => {
        requestAnimationFrame(() => {
            box.classList.remove('opacity-0');
            img.classList.remove('scale-90', 'opacity-0');
        });
    });
}

function closeLightbox() {
    box.classList.add('opacity-0');
    img.classList.add('scale-90', 'opacity-0');
    document.body.classList.remove('overflow-hidden');

    setTimeout(() => {
        box.classList.add('hidden');
        box.classList.remove('flex');
        img.src = '';
    }, 200);
}

if (box && img) {
    document.addEventListener('click', (e) => {
        const trigger = e.target.closest('[data-lightbox]');

        if (trigger) {
            e.preventDefault();
            openLightbox(trigger.dataset.lightbox || trigger.src, trigger.alt);
            return;
        }

        // klik area gelap atau tombol X menutup, klik gambar sendiri tidak
        if (e.target === box || e.target.closest('#lightbox-close')) {
            closeLightbox();
        }
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && !box.classList.contains('hidden')) {
            closeLightbox();
        }
    });
}