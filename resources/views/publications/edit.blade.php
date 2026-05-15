<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier – {{ $publication->titre }} – DevHub</title>
    <link rel="icon" href="{{ asset('devhub.ico') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Urbanist:wght@400;500;600;700;800&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --purple:       #7C3AED;
            --purple-dark:  #6D28D9;
            --purple-light: #EDE9FE;
            --gray-50:      #F9FAFB;
            --gray-100:     #F3F4F6;
            --gray-200:     #E5E7EB;
            --gray-400:     #9CA3AF;
            --gray-500:     #6B7280;
            --gray-600:     #4B5563;
            --gray-700:     #374151;
            --gray-900:     #111827;
            --white:        #ffffff;
        }

        *, *::before, *::after { margin:0; padding:0; box-sizing:border-box; }

        html, body {
            height: 100%;
            font-family: 'DM Sans', sans-serif;
            background: var(--white);
            color: var(--gray-900);
            overflow: hidden;
        }

        .page-wrap {
            display: flex;
            flex-direction: column;
            height: 100vh;
        }

        /* ─── HEADER ─── */
        .edit-header {
            flex-shrink: 0;
            height: 60px;
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0 1.5rem;
            border-bottom: 1px solid var(--gray-200);
            background: var(--white);
        }

        .btn-back {
            display: flex; align-items: center; gap: 0.4rem;
            color: var(--gray-500); text-decoration: none;
            font-size: 0.875rem; font-weight: 500;
            padding: 0.4rem 0.75rem; border-radius: 8px;
            border: 1px solid var(--gray-200);
            transition: all 0.18s; flex-shrink: 0;
        }
        .btn-back:hover { color:var(--purple); border-color:var(--purple); background:var(--purple-light); }

        .header-badge {
            display: inline-flex; align-items: center; gap: 0.4rem;
            padding: 0.25rem 0.75rem; border-radius: 20px;
            font-size: 0.75rem; font-weight: 600;
            flex-shrink: 0;
        }
        .badge-article      { background:#DBEAFE; color:#1D4ED8; }
        .badge-idee         { background:#FEF9C3; color:#854D0E; }
        .badge-perso        { background:var(--purple-light); color:var(--purple); }
        .badge-collab       { background:#D1FAE5; color:#065F46; }

        .header-title {
            flex: 1; font-size: 0.875rem; font-weight: 600;
            color: var(--gray-600); white-space: nowrap;
            overflow: hidden; text-overflow: ellipsis; min-width: 0;
        }

        .header-sep { width:1px; height:26px; background:var(--gray-200); flex-shrink:0; }

        .header-right { display:flex; gap:0.5rem; flex-shrink:0; }

        .btn-save {
            display: inline-flex; align-items: center; gap: 0.4rem;
            padding: 0.45rem 1.25rem; background: var(--purple); color: white;
            border: none; border-radius: 8px; font-size: 0.875rem; font-weight: 600;
            cursor: pointer; transition: background 0.2s; font-family: 'DM Sans', sans-serif;
        }
        .btn-save:hover { background: var(--purple-dark); }

        .btn-cancel {
            display: inline-flex; align-items: center; gap: 0.4rem;
            padding: 0.45rem 1rem; background: transparent; color: var(--gray-500);
            border: 1px solid var(--gray-200); border-radius: 8px;
            font-size: 0.875rem; font-weight: 500; cursor: pointer;
            transition: all 0.18s; text-decoration: none; font-family: 'DM Sans', sans-serif;
        }
        .btn-cancel:hover { background: var(--gray-100); color: var(--gray-900); }

        /* ─── BODY 2 COLONNES ─── */
        .edit-body {
            flex: 1;
            display: grid;
            grid-template-columns: 1fr 340px;
            overflow: hidden;
        }

        /* ─── COLONNE GAUCHE — FORMULAIRE ─── */
        .edit-left {
            overflow-y: auto;
            padding: 2rem 2.5rem;
            border-right: 1px solid var(--gray-200);
            scrollbar-width: thin;
            scrollbar-color: var(--gray-200) transparent;
        }
        .edit-left::-webkit-scrollbar { width: 4px; }
        .edit-left::-webkit-scrollbar-thumb { background: var(--gray-200); border-radius: 2px; }

        /* Section titre formulaire */
        .form-section-label {
            font-size: 0.7rem; font-weight: 700;
            color: var(--gray-400); text-transform: uppercase;
            letter-spacing: 0.6px; margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid var(--gray-100);
            display: flex; align-items: center; gap: 0.5rem;
        }

        /* Champs */
        .field-group { margin-bottom: 1.25rem; }

        .field-label {
            display: block; font-size: 0.82rem; font-weight: 600;
            color: var(--gray-700); margin-bottom: 0.4rem;
        }

        .field-input {
            width: 100%; padding: 0.65rem 1rem;
            border: 1.5px solid var(--gray-200); border-radius: 10px;
            font-size: 0.9rem; font-family: 'DM Sans', sans-serif;
            color: var(--gray-900); background: var(--gray-50);
            outline: none; transition: all 0.2s;
        }
        .field-input:focus {
            border-color: var(--purple); background: var(--white);
            box-shadow: 0 0 0 3px rgba(124,58,237,0.08);
        }

        textarea.field-input {
            resize: vertical; min-height: 160px; line-height: 1.7;
        }

        /* Statut selector */
        .statut-row { display: flex; gap: 0.75rem; }

        .statut-opt {
            flex: 1; padding: 0.65rem 1rem;
            border: 2px solid var(--gray-200); border-radius: 10px;
            cursor: pointer; text-align: center;
            font-size: 0.82rem; font-weight: 600;
            color: var(--gray-500); transition: all 0.2s;
            display: flex; align-items: center; justify-content: center; gap: 0.4rem;
        }
        .statut-opt:hover { border-color: var(--purple); background: var(--purple-light); color: var(--purple); }
        .statut-opt.selected { border-color: var(--purple); background: var(--purple); color: white; }

        /* ─── COLONNE DROITE — APERÇU + IMAGES ─── */
        .edit-right {
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: var(--gray-200) transparent;
            background: var(--gray-50);
        }
        .edit-right::-webkit-scrollbar { width: 4px; }
        .edit-right::-webkit-scrollbar-thumb { background: var(--gray-200); border-radius: 2px; }

        .edit-right-inner { padding: 1.5rem; }

        /* Aperçu card */
        .preview-card {
            background: var(--white);
            border: 1px solid var(--gray-200);
            border-radius: 14px;
            overflow: hidden;
            margin-bottom: 1.25rem;
        }

        .preview-card-header {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--gray-100);
        }

        .preview-section-label {
            font-size: 0.7rem; font-weight: 700;
            color: var(--gray-400); text-transform: uppercase;
            letter-spacing: 0.6px; margin-bottom: 0.75rem;
        }

        .preview-title {
            font-family: 'Urbanist', sans-serif; font-size: 1rem; font-weight: 700;
            color: var(--gray-900); line-height: 1.3;
        }

        .preview-desc {
            font-size: 0.82rem; color: var(--gray-500);
            line-height: 1.6; margin-top: 0.4rem;
        }

        /* Images actuelles */
        .images-section { margin-bottom: 1.25rem; }

        .images-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 0.5rem;
            margin-top: 0.75rem;
        }

        .img-thumb {
            position: relative;
            border-radius: 10px;
            overflow: hidden;
            aspect-ratio: 1;
            border: 1px solid var(--gray-200);
        }

        .img-thumb img {
            width: 100%; height: 100%; object-fit: cover; display: block;
        }

        .img-thumb-single {
            border-radius: 10px; overflow: hidden;
            border: 1px solid var(--gray-200); margin-top: 0.75rem;
        }
        .img-thumb-single img {
            width: 100%; max-height: 200px; object-fit: cover; display: block;
        }

        /* Upload nouvelles images */
        .upload-zone {
            border: 2px dashed var(--gray-200);
            border-radius: 12px;
            padding: 1.25rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s;
            background: var(--white);
            position: relative;
        }
        .upload-zone:hover { border-color: var(--purple); background: var(--purple-light); }
        .upload-zone input { position: absolute; inset: 0; opacity: 0; cursor: pointer; width: 100%; height: 100%; }
        .upload-zone i { font-size: 1.25rem; color: var(--purple); margin-bottom: 0.35rem; display: block; }
        .upload-zone-label { font-size: 0.82rem; font-weight: 600; color: var(--gray-700); }
        .upload-zone-hint  { font-size: 0.72rem; color: var(--gray-400); margin-top: 0.2rem; }

        /* Infos modification */
        .edit-info-card {
            background: var(--white); border: 1px solid var(--gray-200);
            border-radius: 12px; padding: 1rem 1.25rem;
        }
        .edit-info-row {
            display: flex; justify-content: space-between;
            font-size: 0.8rem; padding: 0.35rem 0;
            border-bottom: 1px solid var(--gray-100);
        }
        .edit-info-row:last-child { border-bottom: none; }
        .edit-info-key   { color: var(--gray-400); font-weight: 500; }
        .edit-info-value { color: var(--gray-700); font-weight: 600; }

        /* ─── RESPONSIVE ─── */
        @media (max-width: 992px) {
            html, body { overflow: auto; }
            .page-wrap { height: auto; overflow: visible; }
            .edit-body { grid-template-columns: 1fr; overflow: visible; }
            .edit-left { overflow: visible; border-right: none; padding: 1.25rem; }
            .edit-right { max-height: none; }
        }

        @media (max-width: 768px) {
            .edit-header { padding: 0 1rem; gap: 0.65rem; }
            .header-title, .header-sep { display: none; }
        }

        @media (max-width: 576px) {
            .btn-back span { display: none; }
            .btn-back { padding: 0.4rem 0.5rem; }
            .statut-row { flex-direction: column; }
        }
    </style>
</head>
<body>
<div class="page-wrap">

    <!-- ─── HEADER ─── -->
    <header class="edit-header">
        <a href="{{ route('publications.show', $publication->id) }}" class="btn-back">
            <i class="fas fa-arrow-left"></i>
            <span>Retour</span>
        </a>

        @php
            $types = [
                'article'             => ['Article',      'badge-article', 'fa-newspaper'],
                'idee'                => ['Idée',         'badge-idee',    'fa-lightbulb'],
                'projet_personnel'    => ['Projet perso', 'badge-perso',   'fa-folder'],
                'projet_collaboratif' => ['Collaboratif', 'badge-collab',  'fa-users'],
            ];
            $t = $types[$publication->type] ?? [$publication->type, '', 'fa-file'];
        @endphp

        <span class="header-badge {{ $t[1] }}">
            <i class="fas {{ $t[2] }}"></i> {{ $t[0] }}
        </span>

        <div class="header-sep"></div>

        <div class="header-title">Modifier — {{ $publication->titre }}</div>

        <div class="header-right">
            <a href="{{ route('publications.show', $publication->id) }}" class="btn-cancel">
                Annuler
            </a>
            <button type="submit" form="editForm" class="btn-save">
                <i class="fas fa-save"></i> Enregistrer
            </button>
        </div>
    </header>

    <!-- ─── BODY ─── -->
    <div class="edit-body">

        <!-- ─── GAUCHE : formulaire ─── -->
        <div class="edit-left">
            <form method="POST"
                  action="{{ route('publications.update', $publication->id) }}"
                  enctype="multipart/form-data"
                  id="editForm">
                @csrf
                @method('PUT')

                @if(session('success'))
                    <div style="background:#D1FAE5;color:#065F46;border:1px solid #A7F3D0;border-radius:10px;padding:0.75rem 1rem;margin-bottom:1.25rem;font-size:0.875rem;">
                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div style="background:#FEE2E2;color:#991B1B;border:1px solid #FECACA;border-radius:10px;padding:0.75rem 1rem;margin-bottom:1.25rem;font-size:0.875rem;">
                        <i class="fas fa-exclamation-circle me-2"></i>{{ $errors->first() }}
                    </div>
                @endif

                <!-- Contenu principal -->
                <div class="form-section-label">
                    <i class="fas fa-edit" style="color:var(--purple);"></i>
                    Contenu de la publication
                </div>

                <div class="field-group">
                    <label class="field-label">Titre <span style="color:#EF4444;">*</span></label>
                    <input type="text" name="titre" class="field-input"
                           value="{{ old('titre', $publication->titre) }}"
                           placeholder="Titre de votre publication"
                           required>
                </div>

                <div class="field-group">
                    <label class="field-label">Description</label>
                    <textarea name="contenu" class="field-input"
                              placeholder="Décrivez votre publication...">{{ old('contenu', $publication->contenu) }}</textarea>
                </div>

                <!-- Champs projet si applicable -->
                @if($publication->projetPersonnel || $publication->projetCollaboratif)
                    @php $projet = $publication->projetPersonnel ?? $publication->projetCollaboratif; @endphp

                    <div class="form-section-label" style="margin-top:1.75rem;">
                        <i class="fas fa-code-branch" style="color:var(--purple);"></i>
                        Liens du projet
                    </div>

                    <div class="field-group">
                        <label class="field-label">Dépôt (GitHub, GitLab...)</label>
                        <input type="url" name="url_repo" class="field-input"
                               value="{{ old('url_repo', $projet->url_repo ?? '') }}"
                               placeholder="https://github.com/...">
                    </div>

                    @if($publication->projetPersonnel)
                        <div class="field-group">
                            <label class="field-label">Lien démo</label>
                            <input type="url" name="url_demo" class="field-input"
                                   value="{{ old('url_demo', $projet->url_demo ?? '') }}"
                                   placeholder="https://...">
                        </div>

                        <div class="field-group">
                            <label class="field-label">Technologies utilisées</label>
                            <input type="text" name="technologies" class="field-input"
                                   value="{{ old('technologies', is_array($projet->technologies) ? implode(', ', $projet->technologies) : ($projet->technologies ?? '')) }}"
                                   placeholder="React, Laravel, MySQL...">
                        </div>
                    @endif

                    @if($publication->projetCollaboratif)
                        <div class="field-group">
                            <label class="field-label">Phase du projet</label>
                            <select name="phase" class="field-input">
                                <option value="ouvert" {{ ($projet->phase ?? '') === 'ouvert' ? 'selected' : '' }}>
                                    Ouvert aux candidatures
                                </option>
                                <option value="en_cours" {{ ($projet->phase ?? '') === 'en_cours' ? 'selected' : '' }}>
                                    En cours
                                </option>
                                <option value="terminé" {{ ($projet->phase ?? '') === 'terminé' ? 'selected' : '' }}>
                                    Terminé
                                </option>
                            </select>
                        </div>
                    @endif
                @endif

                <!-- Statut -->
                <div class="form-section-label" style="margin-top:1.75rem;">
                    <i class="fas fa-eye" style="color:var(--purple);"></i>
                    Statut de publication
                </div>

                <input type="hidden" name="statut" id="statutInput" value="{{ $publication->statut }}">

                <div class="statut-row">
                    <div class="statut-opt {{ $publication->statut === 'publié' ? 'selected' : '' }}"
                         data-statut="publié">
                        <i class="fas fa-globe"></i> Publié
                    </div>
                    <div class="statut-opt {{ $publication->statut === 'brouillon' ? 'selected' : '' }}"
                         data-statut="brouillon">
                        <i class="fas fa-save"></i> Brouillon
                    </div>
                    <div class="statut-opt {{ $publication->statut === 'archivé' ? 'selected' : '' }}"
                         data-statut="archivé">
                        <i class="fas fa-archive"></i> Archivé
                    </div>
                </div>

            </form>
        </div>

        <!-- ─── DROITE : aperçu + images ─── -->
        <div class="edit-right">
            <div class="edit-right-inner">

                <!-- Aperçu en temps réel -->
                <div class="preview-section-label">
                    <i class="fas fa-eye me-1" style="color:var(--purple);"></i>
                    Aperçu
                </div>

                <div class="preview-card">
                    <div class="preview-card-header">
                        <div class="preview-title" id="previewTitle">
                            {{ $publication->titre }}
                        </div>
                        @if($publication->contenu)
                            <div class="preview-desc" id="previewDesc">
                                {{ Str::limit(strip_tags($publication->contenu), 120) }}
                            </div>
                        @endif
                    </div>
                    <div style="padding:0.75rem 1.25rem;display:flex;align-items:center;gap:0.75rem;font-size:0.75rem;color:var(--gray-400);">
                        <span><i class="far fa-heart me-1"></i>{{ $publication->nb_likes }}</span>
                        <span><i class="far fa-comment me-1"></i>{{ $publication->nb_commentaires }}</span>
                        <span><i class="far fa-eye me-1"></i>{{ $publication->nb_vues }}</span>
                    </div>
                </div>

                <!-- Images actuelles -->
                @if($publication->medias->isNotEmpty())
                    <div class="preview-section-label" style="margin-top:1.25rem;">
                        <i class="fas fa-images me-1" style="color:var(--purple);"></i>
                        Images actuelles ({{ $publication->medias->count() }})
                    </div>

                    @if($publication->type === 'projet_personnel' && $publication->medias->count() >= 3)
                        <div class="images-grid">
                            @foreach($publication->medias as $media)
                                <div class="img-thumb">
                                    <img src="{{ asset('storage/'.$media->chemin) }}" alt="image">
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="img-thumb-single">
                            <img src="{{ asset('storage/'.$publication->medias->first()->chemin) }}" alt="image">
                        </div>
                    @endif
                @endif

                <!-- Remplacer les images -->
                <div class="preview-section-label" style="margin-top:1.5rem;">
                    <i class="fas fa-upload me-1" style="color:var(--purple);"></i>
                    @if($publication->medias->isNotEmpty())
                        Remplacer les images
                    @else
                        Ajouter des images
                    @endif
                </div>

                @if($publication->type === 'projet_personnel')
                    <p style="font-size:0.75rem;color:var(--gray-400);margin-bottom:0.75rem;">
                        3 images requises pour un projet personnel.
                    </p>
                    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:0.5rem;" id="slotContainer">
                        @for($i = 1; $i <= 3; $i++)
                            <div class="upload-zone" id="slot{{ $i }}"
                                 onclick="document.getElementById('imgInput{{ $i }}').click()">
                                <input type="file" name="images[]" id="imgInput{{ $i }}"
                                       accept="image/*" data-slot="{{ $i }}">
                                <i class="fas fa-plus" style="font-size:1rem;"></i>
                                <div class="upload-zone-label" style="font-size:0.72rem;">Image {{ $i }}</div>
                            </div>
                        @endfor
                    </div>
                @else
                    <div class="upload-zone" id="singleUpload"
                         onclick="document.getElementById('singleInput').click()">
                        <input type="file" name="images[]" id="singleInput" accept="image/*">
                        <i class="fas fa-image"></i>
                        <div class="upload-zone-label">Cliquez pour uploader</div>
                        <div class="upload-zone-hint">JPG, PNG – Max 3MB</div>
                    </div>
                @endif

                <!-- Infos -->
                <div class="preview-section-label" style="margin-top:1.5rem;">
                    <i class="fas fa-info-circle me-1" style="color:var(--purple);"></i>
                    Informations
                </div>

                <div class="edit-info-card">
                    <div class="edit-info-row">
                        <span class="edit-info-key">Publié le</span>
                        <span class="edit-info-value">{{ $publication->date_publication->format('d M Y') }}</span>
                    </div>
                    <div class="edit-info-row">
                        <span class="edit-info-key">Type</span>
                        <span class="edit-info-value">{{ $t[0] }}</span>
                    </div>
                    <div class="edit-info-row">
                        <span class="edit-info-key">Statut actuel</span>
                        <span class="edit-info-value" id="statutDisplay">{{ ucfirst($publication->statut) }}</span>
                    </div>
                    <div class="edit-info-row">
                        <span class="edit-info-key">Vues</span>
                        <span class="edit-info-value">{{ $publication->nb_vues }}</span>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
// ─── Aperçu en temps réel ───
const titleInput  = document.querySelector('input[name="titre"]');
const descInput   = document.querySelector('textarea[name="contenu"]');
const previewTitle= document.getElementById('previewTitle');
const previewDesc = document.getElementById('previewDesc');

titleInput?.addEventListener('input', () => {
    previewTitle.textContent = titleInput.value || 'Titre de la publication';
});

descInput?.addEventListener('input', () => {
    if (previewDesc) {
        const text = descInput.value.replace(/<[^>]*>/g, '').substring(0, 120);
        previewDesc.textContent = text + (descInput.value.length > 120 ? '...' : '');
    }
});

// ─── Statut selector ───
document.querySelectorAll('.statut-opt').forEach(opt => {
    opt.addEventListener('click', () => {
        document.querySelectorAll('.statut-opt').forEach(o => o.classList.remove('selected'));
        opt.classList.add('selected');
        const val = opt.dataset.statut;
        document.getElementById('statutInput').value = val;
        const display = document.getElementById('statutDisplay');
        if (display) display.textContent = val.charAt(0).toUpperCase() + val.slice(1);
    });
});

// ─── Preview images upload ───
function setupSlot(inputId, slotId) {
    const input = document.getElementById(inputId);
    const slot  = document.getElementById(slotId);
    if (!input || !slot) return;
    input.addEventListener('change', function() {
        if (!this.files[0]) return;
        const reader = new FileReader();
        reader.onload = e => {
            const old = slot.querySelector('img');
            if (old) old.remove();
            const img = document.createElement('img');
            img.src = e.target.result;
            img.style.cssText = 'width:100%;height:100%;object-fit:cover;position:absolute;inset:0;border-radius:10px;';
            slot.style.padding = '0';
            slot.style.border  = '2px solid var(--purple)';
            slot.appendChild(img);
            slot.querySelector('i')?.remove();
            slot.querySelector('.upload-zone-label')?.remove();
        };
        reader.readAsDataURL(this.files[0]);
    });
}

setupSlot('imgInput1', 'slot1');
setupSlot('imgInput2', 'slot2');
setupSlot('imgInput3', 'slot3');

const singleInput = document.getElementById('singleInput');
const singleUpload= document.getElementById('singleUpload');
if (singleInput) {
    singleInput.addEventListener('change', function() {
        if (!this.files[0]) return;
        const reader = new FileReader();
        reader.onload = e => {
            singleUpload.innerHTML = `<img src="${e.target.result}" style="width:100%;max-height:180px;object-fit:cover;border-radius:10px;display:block;">
                <input type="file" name="images[]" id="singleInput" accept="image/*" style="position:absolute;inset:0;opacity:0;cursor:pointer;width:100%;height:100%;">`;
            singleUpload.style.padding = '0';
            singleUpload.style.border  = '2px solid var(--purple)';
            setupSingleNew();
        };
        reader.readAsDataURL(this.files[0]);
    });
}

function setupSingleNew() {
    const newInput = document.getElementById('singleInput');
    if (newInput) {
        newInput.addEventListener('change', function() {
            if (!this.files[0]) return;
            const reader = new FileReader();
            reader.onload = e => {
                const img = singleUpload.querySelector('img');
                if (img) img.src = e.target.result;
            };
            reader.readAsDataURL(this.files[0]);
        });
    }
}
</script>
</body>
</html>