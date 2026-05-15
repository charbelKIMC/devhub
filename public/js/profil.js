document.addEventListener('DOMContentLoaded', () => {

    // ─── Onglets profil ───
    document.querySelectorAll('.profile-tab').forEach(tab => {
        tab.addEventListener('click', () => {
            document.querySelectorAll('.profile-tab').forEach(t => t.classList.remove('active'));
            document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
            tab.classList.add('active');
            const panel = document.getElementById(tab.dataset.tab);
            if (panel) panel.classList.add('active');
        });
    });

    // ─── Onglets paramètres ───
    document.querySelectorAll('.settings-menu-item').forEach(item => {
        item.addEventListener('click', () => {
            document.querySelectorAll('.settings-menu-item').forEach(i => i.classList.remove('active'));
            document.querySelectorAll('.settings-panel').forEach(p => p.classList.remove('active'));
            item.classList.add('active');
            const panel = document.getElementById(item.dataset.setting);
            if (panel) panel.classList.add('active');
        });
    });

    // ─── Preview photo ───
    const photoInput = document.getElementById('photo');
    if (photoInput) {
        photoInput.addEventListener('change', function () {
            const file = this.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = e => {
                const preview = document.getElementById('photoPreview');
                const placeholder = document.getElementById('photoPlaceholder');
                if (preview) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                } else {
                    const img = document.createElement('img');
                    img.id = 'photoPreview';
                    img.src = e.target.result;
                    img.style.cssText = 'width:100%;height:100%;object-fit:cover;';
                    if (placeholder) placeholder.replaceWith(img);
                }
                if (placeholder) placeholder.style.display = 'none';
            };
            reader.readAsDataURL(file);
        });
    }

});