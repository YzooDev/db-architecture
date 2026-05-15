const input = document.getElementById('images');
const previewGrid = document.getElementById('preview-grid');
const countEl = document.getElementById('preview-count');

input.addEventListener('change', function () {
    previewGrid.innerHTML = '';
    const files = Array.from(this.files);
    if (!files.length) { countEl.textContent = ''; return; }

    files.forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = e => {
            const item = document.createElement('div');
            item.className = 'preview-item';
            const img = document.createElement('img');
            img.src = e.target.result;
            img.alt = file.name;
            item.appendChild(img);
            if (index === 0) {
                const badge = document.createElement('span');
                badge.className = 'preview-item__badge';
                badge.textContent = 'Couv.';
                item.appendChild(badge);
            }
            previewGrid.appendChild(item);
        };
        reader.readAsDataURL(file);
    });

    const n = files.length;
    countEl.textContent = n + ' image' + (n > 1 ? 's' : '') + ' sélectionnée' + (n > 1 ? 's' : '');
});