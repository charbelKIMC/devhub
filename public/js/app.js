// ============================================================
//  DevHub — s-app.js  (interactions globales)
// ============================================================

// ─── DROPDOWNS ───────────────────────────────────────────────
const dropdowns = {
    manage: { btn: '#manageBtn', panel: '#managePanel' },
    user:   { btn: '#userBtn',   panel: '#userPanel'   }
};

Object.keys(dropdowns).forEach(key => {
    const btnEl   = document.querySelector(dropdowns[key].btn);
    const panelEl = document.querySelector(dropdowns[key].panel);
    if (!btnEl || !panelEl) return;

    btnEl.addEventListener('click', e => {
        e.stopPropagation();
        Object.keys(dropdowns).forEach(k => {
            if (k !== key) {
                document.querySelector(dropdowns[k].panel)?.classList.remove('active');
                document.querySelector(dropdowns[k].btn)?.classList.remove('active');
            }
        });
        panelEl.classList.toggle('active');
        btnEl.classList.toggle('active');
    });
});

document.addEventListener('click', () => {
    Object.values(dropdowns).forEach(({ btn, panel }) => {
        document.querySelector(panel)?.classList.remove('active');
        document.querySelector(btn)?.classList.remove('active');
    });
    hideSearchDropdown();
});

// ─── UTILITAIRES ────────────────────────────────────────────
function escHtml(str) {
    return String(str)
        .replace(/&/g,'&amp;').replace(/</g,'&lt;')
        .replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

// ─── RECHERCHE LIVE ──────────────────────────────────────────
(function() {
    const input    = document.getElementById('searchInput');
    const dropdown = document.getElementById('searchDropdown');
    const results  = document.getElementById('searchResults');
    const clearBtn = document.getElementById('searchClear');

    if (!input) return;

    let debounceTimer = null;

    const typeConfig = {
        article:             { label: 'Article',      icon: 'fa-newspaper',  bg: '#DBEAFE', color: '#1D4ED8' },
        idee:                { label: 'Idée',         icon: 'fa-lightbulb',  bg: '#FEF9C3', color: '#854D0E' },
        projet_personnel:    { label: 'Projet',       icon: 'fa-folder',     bg: '#EDE9FE', color: '#7C3AED' },
        projet_collaboratif: { label: 'Collaboratif', icon: 'fa-users',      bg: '#D1FAE5', color: '#065F46' },
    };

    function escRegex(str) {
        return str.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
    }

    function highlight(text, query) {
        if (!query) return escHtml(text);
        const regex = new RegExp('(' + escRegex(query) + ')', 'gi');
        return escHtml(text).replace(regex, '<span class="search-highlight">$1</span>');
    }

    function hideSearchDropdown() {
        if (dropdown) dropdown.style.display = 'none';
    }

    window.hideSearchDropdown = hideSearchDropdown;

    function showSearchDropdown() {
        if (dropdown) dropdown.style.display = 'block';
    }

    function renderResults(data, q) {
        const pubs = data.publications || [];
        const devs = data.utilisateurs || [];

        if (!pubs.length && !devs.length) {
            results.innerHTML = `<div class="search-empty"><i class="fas fa-search"></i> Aucun résultat pour "<strong>${escHtml(q)}</strong>"</div>`;
            return;
        }

        let html = '';

        if (pubs.length) {
            html += `<div class="search-section-title"><i class="fas fa-folder me-1"></i>Publications</div>`;
            pubs.slice(0,5).forEach(pub => {
                const tc = typeConfig[pub.type] || { label:pub.type, icon:'fa-file', bg:'#F3F4F6', color:'#6B7280' };
                html += `<a href="${window.APP_URL}/publications/${pub.id}" class="search-pub-item">
                    <div class="search-pub-icon" style="background:${tc.bg};color:${tc.color};"><i class="fas ${tc.icon}"></i></div>
                    <div>
                        <div class="search-pub-title">${highlight(pub.titre, q)}</div>
                        <div class="search-pub-meta">${tc.label} · ${pub.auteur ? pub.auteur.prenom+' '+pub.auteur.nom : ''}</div>
                    </div>
                </a>`;
            });
        }

        if (devs.length) {
            html += `<div class="search-section-title"><i class="fas fa-code me-1"></i>Développeurs</div>`;
            devs.slice(0,4).forEach(dev => {
                const avatar = dev.photo
                    ? `<img src="${window.APP_URL}/storage/${dev.photo}" alt="">`
                    : (dev.prenom || '?').charAt(0).toUpperCase();
                html += `<a href="${window.APP_URL}/profil/${dev.id}" class="search-dev-item">
                    <div class="search-dev-avatar">${avatar}</div>
                    <div>
                        <div class="search-dev-name">${highlight(dev.prenom+' '+dev.nom, q)}</div>
                        <div class="search-dev-spec">${escHtml(dev.specialite || dev.role || '')}</div>
                    </div>
                </a>`;
            });
        }

        html += `<a href="${window.APP_URL}/recherche?q=${encodeURIComponent(q)}" class="search-see-all">
            <i class="fas fa-arrow-right"></i> Voir tous les résultats
        </a>`;

        results.innerHTML = html;
    }

    function doSearch(q) {
        if (q.length < 2) { hideSearchDropdown(); return; }

        results.innerHTML = `<div class="search-loading"><i class="fas fa-spinner fa-spin me-2"></i>Recherche...</div>`;
        showSearchDropdown();

        fetch(window.APP_URL + `/recherche?q=${encodeURIComponent(q)}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        })
        .then(r => r.json())
        .then(data => { renderResults(data, q); showSearchDropdown(); })
        .catch(() => {
            results.innerHTML = `<div class="search-empty"><i class="fas fa-exclamation-circle"></i> Erreur de connexion</div>`;
        });
    }

    input.addEventListener('input', () => {
        const q = input.value.trim();
        clearBtn.style.display = q ? 'block' : 'none';
        clearTimeout(debounceTimer);
        if (q.length < 2) { hideSearchDropdown(); return; }
        debounceTimer = setTimeout(() => doSearch(q), 280);
    });

    clearBtn?.addEventListener('click', () => {
        input.value = '';
        clearBtn.style.display = 'none';
        hideSearchDropdown();
        input.focus();
    });

    document.addEventListener('click', e => {
        if (!e.target.closest('#searchWrapper')) hideSearchDropdown();
    });

    input.addEventListener('keydown', e => {
        if (e.key === 'Escape') { hideSearchDropdown(); input.blur(); }
        if (e.key === 'Enter' && input.value.trim().length >= 2) {
            window.location.href = window.APP_URL + `/recherche?q=${encodeURIComponent(input.value.trim())}`;
        }
    });
})();

// ─── LIKES (global) ──────────────────────────────────────────
function initLikes() {
    document.querySelectorAll('.like-btn').forEach(btn => {
        if (btn.dataset.likeInit) return;
        btn.dataset.likeInit = '1';

        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            const id = this.dataset.id;
            if (!id) return;

            fetch(window.APP_URL + '/likes/' + id, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': window.CSRF_TOKEN,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                }
            })
            .then(r => r.json())
            .then(data => {
                // Mettre à jour toutes les instances du même bouton
                document.querySelectorAll('.like-btn[data-id="' + id + '"]').forEach(b => {
                    b.classList.toggle('liked',  data.liked);
                    b.classList.toggle('active', data.liked);
                    const icon = b.querySelector('i');
                    if (icon) icon.className = data.liked ? 'fas fa-heart' : 'far fa-heart';
                    const count = b.querySelector('.like-count');
                    if (count) count.textContent = data.count;
                });
            })
            .catch(() => {});
        });
    });
}

// ─── FAVORIS (global) ────────────────────────────────────────
function initFavoris() {
    document.querySelectorAll('.favori-btn').forEach(btn => {
        if (btn.dataset.favoriInit) return;
        btn.dataset.favoriInit = '1';

        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            const id = this.dataset.id;
            if (!id) return;

            fetch(window.APP_URL + '/favoris/' + id, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': window.CSRF_TOKEN,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                }
            })
            .then(r => r.json())
            .then(data => {
                document.querySelectorAll('.favori-btn[data-id="' + id + '"]').forEach(b => {
                    b.classList.toggle('active', data.saved);
                    b.classList.toggle('saved',  data.saved);
                    const icon = b.querySelector('i');
                    if (icon) icon.className = data.saved ? 'fas fa-bookmark' : 'far fa-bookmark';
                });
            })
            .catch(() => {});
        });
    });
}

// ─── COMMENTAIRES INLINE (global) ────────────────────────────
function initCommentaires() {

    // Soumission AJAX des formulaires de commentaire inline
    document.querySelectorAll('.comment-inline-form, .comment-form-submit').forEach(form => {
        if (form.dataset.ajaxInit) return;
        form.dataset.ajaxInit = '1';

        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const pubId   = this.querySelector('[name="publication_id"]')?.value;
            const textarea= this.querySelector('textarea[name="contenu"]');
            const contenu = textarea?.value.trim();
            const btn     = this.querySelector('button[type="submit"]');

            if (!contenu || !pubId) return;

            if (btn) { btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>'; btn.disabled = true; }

            fetch(window.APP_URL + '/commentaires', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': window.CSRF_TOKEN,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ publication_id: pubId, contenu: contenu })
            })
            .then(r => r.json())
            .then(data => {
                if (!data.success) return;

                // Masquer le formulaire
                const zone = document.getElementById('commentForm' + pubId);
                if (zone) zone.style.display = 'none';

                // Vider le textarea
                if (textarea) textarea.value = '';

                // Mettre à jour le compteur
                document.querySelectorAll('.comment-toggle-btn[data-id="' + pubId + '"] span').forEach(s => {
                    s.textContent = (parseInt(s.textContent) || 0) + 1;
                });

                // Si on est sur la page de détail, ajouter le commentaire en haut
                const commentsList = document.getElementById('commentsList');
                if (commentsList) {
                    document.getElementById('commentsEmpty')?.remove();

                    const div = document.createElement('div');
                    div.className = 'comment-item comment-new';
                    div.innerHTML = `
                        <div class="comment-ava">
                            ${data.comment.avatar
                                ? `<img src="${window.APP_URL}/storage/${data.comment.avatar}" alt="">`
                                : data.comment.initiale}
                        </div>
                        <div class="comment-bubble">
                            <div class="comment-top">
                                <span class="comment-name">${escHtml(data.comment.auteur)}</span>
                                <span class="comment-time">à l'instant</span>
                            </div>
                            <div class="comment-text">${escHtml(data.comment.contenu)}</div>
                        </div>`;

                    commentsList.insertBefore(div, commentsList.firstChild);

                    // MAJ compteur sidebar
                    const cnt = document.getElementById('commentsCount');
                    const bar = document.getElementById('commentCountBar');
                    if (cnt) cnt.textContent = (parseInt(cnt.textContent) || 0) + 1;
                    if (bar) bar.textContent = (parseInt(bar.textContent) || 0) + 1;
                }
            })
            .catch(() => {})
            .finally(() => {
                if (btn) {
                    btn.innerHTML = '<i class="fas fa-paper-plane"></i>';
                    btn.disabled  = false;
                }
            });
        });
    });
}

// ─── MODAL PUBLICATION ───────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {

    // Initialiser les interactions
    initLikes();
    initFavoris();
    initCommentaires();

    // Ouvrir modal depuis le champ du feed
    document.querySelectorAll('.create-pub-input').forEach(el => {
        el.addEventListener('click', () => {
            new bootstrap.Modal(document.getElementById('modalPublication')).show();
        });
    });

    // Sélection type
    document.querySelectorAll('.modal-type-opt').forEach(opt => {
        opt.addEventListener('click', () => {
            document.querySelectorAll('.modal-type-opt').forEach(o => {
                o.style.borderColor = 'var(--gray-200)';
                o.style.background  = 'white';
            });
            opt.style.borderColor = 'var(--purple)';
            opt.style.background  = 'var(--purple-light)';

            const type = opt.dataset.type;
            document.getElementById('modalTypeInput').value = type;

            const projetFields = document.getElementById('modalProjetFields');
            const collabFields = document.getElementById('modalCollabFields');
            const uploadSingle = document.getElementById('uploadSingle');
            const uploadTriple = document.getElementById('uploadTriple');

            // Pour projet_collaboratif : champs projet + upload simple
            if (projetFields) projetFields.style.display = type === 'projet_collaboratif' ? 'block' : 'none';
            if (collabFields) collabFields.style.display = type === 'projet_collaboratif' ? 'block' : 'none';
            // Upload image disponible pour tous les types restants
            if (uploadSingle) uploadSingle.style.display = 'block';

            // Collaboratif : toujours publié, masquer brouillon
            const brouillonOpt = document.getElementById('modalBrouillonOpt');
            const statutInput  = document.getElementById('modalStatutInput');
            if (type === 'projet_collaboratif') {
                if (brouillonOpt) brouillonOpt.style.display = 'none';
                if (statutInput)  statutInput.value = 'publié';
                document.querySelectorAll('.modal-statut-opt').forEach(o => {
                    const isPublie = o.dataset.statut === 'publié';
                    o.style.borderColor = isPublie ? 'var(--purple)' : 'var(--gray-200)';
                    o.style.background  = isPublie ? 'var(--purple-light)' : 'white';
                    o.style.color       = isPublie ? 'var(--purple)' : 'var(--gray-500)';
                });
            } else {
                if (brouillonOpt) brouillonOpt.style.display = '';
            }
        });
    });

    // Sélection statut
    document.querySelectorAll('.modal-statut-opt').forEach(opt => {
        opt.addEventListener('click', () => {
            document.querySelectorAll('.modal-statut-opt').forEach(o => {
                o.style.borderColor = 'var(--gray-200)';
                o.style.background  = 'white';
                o.style.color       = 'var(--gray-500)';
            });
            opt.style.borderColor = 'var(--purple)';
            opt.style.background  = 'var(--purple-light)';
            opt.style.color       = 'var(--purple)';
            document.getElementById('modalStatutInput').value = opt.dataset.statut;
        });
    });

    // Preview images
    function previewImage(input, zoneId) {
        const zone = document.getElementById(zoneId);
        if (!zone || !input.files[0]) return;
        const reader = new FileReader();
        reader.onload = e => {
            const old = zone.querySelector('.upload-preview-img');
            if (old) old.remove();
            const img = document.createElement('img');
            img.src = e.target.result;
            img.className = 'upload-preview-img';
            zone.appendChild(img);
            zone.classList.add('has-image');
        };
        reader.readAsDataURL(input.files[0]);
    }

    document.getElementById('imgSingle')?.addEventListener('change', function() {
        previewImage(this, 'dropSingle');
    });

    // Soumission AJAX publication
    document.getElementById('btnSubmitPub')?.addEventListener('click', () => {
        const form   = document.getElementById('modalPubForm');
        const msgBox = document.getElementById('modalPubMessage');
        const btn    = document.getElementById('btnSubmitPub');

        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Publication...';
        btn.disabled  = true;

        fetch(form.action, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': window.CSRF_TOKEN },
            body: new FormData(form),
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                msgBox.style.display    = 'block';
                msgBox.style.background = '#D1FAE5';
                msgBox.style.color      = '#065F46';
                msgBox.innerHTML        = '<i class="fas fa-check-circle me-2"></i>' + data.message;
                setTimeout(() => {
                    bootstrap.Modal.getInstance(document.getElementById('modalPublication')).hide();
                    // Afficher le banner "nouvelle publication" au lieu de recharger
                    var banner = document.getElementById('newPostBanner');
                    var text   = document.getElementById('newPostBannerText');
                    if (banner && text) {
                        text.textContent = 'Votre publication a été créée avec succès !';
                        banner.style.display = 'flex';
                        banner.onclick = function() { window.location.reload(); };
                    } else {
                        window.location.reload();
                    }
                }, 1200);
            } else {
                msgBox.style.display    = 'block';
                msgBox.style.background = '#FEE2E2';
                msgBox.style.color      = '#991B1B';
                msgBox.innerHTML        = '<i class="fas fa-exclamation-circle me-2"></i>' + (data.message || 'Erreur.');
                btn.innerHTML           = '<i class="fas fa-paper-plane"></i> Publier';
                btn.disabled            = false;
            }
        })
        .catch(() => {
            msgBox.style.display    = 'block';
            msgBox.style.background = '#FEE2E2';
            msgBox.style.color      = '#991B1B';
            msgBox.innerHTML        = '<i class="fas fa-exclamation-circle me-2"></i>Erreur de connexion.';
            btn.innerHTML           = '<i class="fas fa-paper-plane"></i> Publier';
            btn.disabled            = false;
        });
    });

    // Reset modal
    document.getElementById('modalPublication')?.addEventListener('hidden.bs.modal', () => {
        document.getElementById('modalPubForm')?.reset();
        document.querySelectorAll('.upload-preview-img').forEach(i => i.remove());
        document.querySelectorAll('.upload-drop-zone').forEach(z => z.classList.remove('has-image'));
        const msgBox = document.getElementById('modalPubMessage');
        if (msgBox) msgBox.style.display = 'none';
        const errImg = document.getElementById('imagesError');
        if (errImg) errImg.style.display = 'none';
        document.querySelectorAll('.modal-type-opt').forEach((o, i) => {
            o.style.borderColor = i === 0 ? 'var(--purple)' : 'var(--gray-200)';
            o.style.background  = i === 0 ? 'var(--purple-light)' : 'white';
        });
        document.getElementById('modalTypeInput').value = 'article';
        document.getElementById('uploadSingle')?.style && (document.getElementById('uploadSingle').style.display = 'block');
        document.getElementById('uploadTriple')?.style && (document.getElementById('uploadTriple').style.display = 'none');
        document.getElementById('modalProjetFields')?.style && (document.getElementById('modalProjetFields').style.display = 'none');
        document.getElementById('modalCollabFields')?.style && (document.getElementById('modalCollabFields').style.display = 'none');
        const b = document.getElementById('btnSubmitPub');
        if (b) { b.innerHTML = '<i class="fas fa-paper-plane"></i> Publier'; b.disabled = false; }
    });
});

// ─── EXPOSITION GLOBALE des inits (utile si contenu chargé dynamiquement) ────
window.DevHub = window.DevHub || {};
window.DevHub.initLikes        = initLikes;
window.DevHub.initFavoris      = initFavoris;
window.DevHub.initCommentaires = initCommentaires;

// ─── MODAL COLLABORATION ─────────────────────────────────────
let _collabPubId = null;

function openCollabModal(id, titre) {
    _collabPubId = id;
    document.getElementById('collabModalTitre').textContent = titre || 'ce projet';
    document.getElementById('collabPosteSelected').value = '';
    document.getElementById('collabMotivation').value = '';
    document.getElementById('collabPosteError').style.display = 'none';
    document.getElementById('collabMessage').style.display = 'none';
    document.querySelectorAll('.collab-tag').forEach(t => t.classList.remove('selected'));
    const btn = document.getElementById('btnSubmitCollab');
    if (btn) { btn.innerHTML = '<i class="fas fa-paper-plane"></i> Envoyer ma candidature'; btn.disabled = false; }
    new bootstrap.Modal(document.getElementById('modalCollab')).show();
}

document.querySelectorAll('.collaboration-btn').forEach(btn => {
    btn.addEventListener('click', e => {
        e.stopPropagation();
        e.preventDefault();
        openCollabModal(btn.dataset.id, btn.dataset.titre);
    });
});

document.querySelectorAll('.collab-tag').forEach(tag => {
    tag.addEventListener('click', () => {
        document.querySelectorAll('.collab-tag').forEach(t => t.classList.remove('selected'));
        tag.classList.add('selected');
        document.getElementById('collabPosteSelected').value = tag.dataset.poste;
        document.getElementById('collabPosteError').style.display = 'none';
    });
});

document.getElementById('btnSubmitCollab')?.addEventListener('click', () => {
    const poste      = document.getElementById('collabPosteSelected').value;
    const motivation = document.getElementById('collabMotivation').value.trim();
    const msgBox     = document.getElementById('collabMessage');
    const btn        = document.getElementById('btnSubmitCollab');

    if (!poste) { document.getElementById('collabPosteError').style.display = 'block'; return; }
    if (!_collabPubId) return;

    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Envoi...';
    btn.disabled  = true;

    fetch(window.APP_URL + '/candidatures', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': window.CSRF_TOKEN,
            'Content-Type': 'application/json',
            'Accept': 'application/json',
        },
        body: JSON.stringify({
            projet_collaboratif_id: _collabPubId,
            poste_souhaite:         poste,
            motivation:             motivation,
        }),
    })
    .then(r => r.json())
    .then(data => {
        msgBox.style.display = 'block';
        if (data.success) {
            msgBox.style.background = '#D1FAE5';
            msgBox.style.color      = '#065F46';
            msgBox.innerHTML        = '<i class="fas fa-check-circle me-2"></i>' + data.message;
            setTimeout(() => bootstrap.Modal.getInstance(document.getElementById('modalCollab'))?.hide(), 2000);
        } else {
            msgBox.style.background = '#FEE2E2';
            msgBox.style.color      = '#991B1B';
            msgBox.innerHTML        = '<i class="fas fa-exclamation-circle me-2"></i>' + (data.message || 'Erreur.');
            btn.innerHTML           = '<i class="fas fa-paper-plane"></i> Envoyer ma candidature';
            btn.disabled            = false;
        }
    })
    .catch(() => {
        msgBox.style.display    = 'block';
        msgBox.style.background = '#FEE2E2';
        msgBox.style.color      = '#991B1B';
        msgBox.innerHTML        = '<i class="fas fa-exclamation-circle me-2"></i>Erreur de connexion.';
        btn.innerHTML           = '<i class="fas fa-paper-plane"></i> Envoyer ma candidature';
        btn.disabled            = false;
    });
});

