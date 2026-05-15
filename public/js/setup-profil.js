// ─── Navigation étapes ───
const step1 = document.getElementById('step-1');
const step2 = document.getElementById('step-2');
const ind1  = document.getElementById('step-indicator-1');
const ind2  = document.getElementById('step-indicator-2');
const fill  = document.getElementById('progressFill');

function goToStep2() {
    step1.classList.remove('active');
    step2.classList.add('active');
    ind1.classList.remove('active');
    ind1.classList.add('done');
    ind2.classList.add('active');
    fill.style.width = '100%';
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function goToStep1() {
    step2.classList.remove('active');
    step1.classList.add('active');
    ind2.classList.remove('active');
    ind1.classList.remove('done');
    ind1.classList.add('active');
    fill.style.width = '50%';
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

document.getElementById('goToStep2').addEventListener('click', goToStep2);
document.getElementById('goToStep1').addEventListener('click', goToStep1);

// Si la soumission a échoué avec des erreurs sur l'étape 2 → ouvrir l'étape 2 directement
if (window.SETUP_STEP2_ERRORS) {
    goToStep2();
}

// ─── Spécialité (sélection unique) ───
document.querySelectorAll('#specialiteTags .tag-option').forEach(tag => {
    tag.addEventListener('click', () => {
        document.querySelectorAll('#specialiteTags .tag-option').forEach(t => t.classList.remove('selected'));
        tag.classList.add('selected');
        document.getElementById('specialiteInput').value = tag.dataset.value;
    });
});

// Restaurer l'ancienne sélection de spécialité après échec de validation
const oldSpecialite = window.SETUP_OLD_SPECIALITE;
if (oldSpecialite) {
    const tag = document.querySelector(`#specialiteTags .tag-option[data-value="${oldSpecialite}"]`);
    if (tag) tag.classList.add('selected');
}

// ─── Compétences (sélection multiple) ───
// e.preventDefault() évite le double-toggle natif du label
document.querySelectorAll('.competences-wrapper .tag-option').forEach(tag => {
    tag.addEventListener('click', (e) => {
        e.preventDefault();
        const checkbox = tag.querySelector('input[type="checkbox"]');
        checkbox.checked = !checkbox.checked;
        tag.classList.toggle('selected', checkbox.checked);
    });
});

// ─── Preview photo ───
document.getElementById('photo').addEventListener('change', function () {
    const file = this.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = e => {
        const img = document.getElementById('photoImg');
        const ph  = document.getElementById('photoPlaceholder');
        img.src = e.target.result;
        img.style.display = 'block';
        ph.style.display  = 'none';
    };
    reader.readAsDataURL(file);
});

// ─── Compteur bio ───
const bioInput = document.getElementById('bioInput');
const bioCount = document.getElementById('bioCount');
if (bioInput) {
    // Initialiser le compteur avec la valeur déjà présente (old input)
    bioCount.textContent = bioInput.value.length;
    bioInput.addEventListener('input', () => {
        bioCount.textContent = bioInput.value.length;
    });
}
