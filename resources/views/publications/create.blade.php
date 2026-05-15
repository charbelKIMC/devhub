@extends('layouts.app')
@section('title', 'Nouvelle publication – DevHub')
@section('styles')
<style>
    .create-container { max-width: 800px; margin: 2rem auto; padding: 0 1.5rem; }
    .create-card { background: white; border-radius: 16px; padding: 2rem; border: 1px solid var(--gray-200); }
    .create-title { font-family: 'Urbanist', sans-serif; font-size: 1.5rem; font-weight: 700; color: var(--gray-900); margin-bottom: 1.5rem; }
    .type-selector { display: flex; gap: 0.75rem; flex-wrap: wrap; margin-bottom: 1.5rem; }
    .type-option { flex: 1; min-width: 140px; padding: 1rem; border: 2px solid var(--gray-200); border-radius: 12px; cursor: pointer; text-align: center; transition: all 0.2s; }
    .type-option:hover { border-color: var(--purple); background: var(--purple-light); }
    .type-option.selected { border-color: var(--purple); background: var(--purple-light); }
    .type-option i { font-size: 1.5rem; color: var(--purple); display: block; margin-bottom: 0.5rem; }
    .type-option span { font-size: 0.85rem; font-weight: 600; color: var(--gray-700); }
    .form-group { margin-bottom: 1.25rem; }
    label { display: block; font-size: 0.875rem; font-weight: 600; color: var(--gray-700); margin-bottom: 0.4rem; }
    .form-control-devhub { width: 100%; padding: 0.7rem 1rem; border: 1.5px solid var(--gray-200); border-radius: 10px; font-size: 0.9rem; font-family: 'DM Sans', sans-serif; transition: all 0.2s; outline: none; background: #fafafa; }
    .form-control-devhub:focus { border-color: var(--purple); background: white; box-shadow: 0 0 0 3px rgba(124,58,237,0.08); }
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
    .status-options { display: flex; gap: 0.75rem; }
    .status-option { flex: 1; padding: 0.65rem; border: 2px solid var(--gray-200); border-radius: 10px; cursor: pointer; text-align: center; font-size: 0.85rem; font-weight: 600; color: var(--gray-600); transition: all 0.2s; }
    .status-option.selected { border-color: var(--purple); background: var(--purple-light); color: var(--purple); }
    .btn-submit { background: var(--purple); color: white; border: none; padding: 0.75rem 2rem; border-radius: 10px; font-size: 0.95rem; font-weight: 600; cursor: pointer; transition: background 0.2s; }
    .btn-submit:hover { background: var(--purple-dark); }
    .extra-fields { display: none; }
    .extra-fields.visible { display: block; }
</style>
@endsection

@section('content')
<div class="create-container">
    <div class="create-card">
        <div class="create-title">Nouvelle publication</div>

        @if($errors->any())
            <div style="background:#FEE2E2;color:#991B1B;border:1px solid #FECACA;border-radius:8px;padding:10px 14px;font-size:0.88rem;margin-bottom:1rem;">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('publications.store') }}" id="createForm">
            @csrf

            <!-- Type de publication -->
            <div class="form-group">
                <label>Type de publication</label>
                <input type="hidden" name="type" id="typeInput" value="{{ request('type', 'article') }}">
                <div class="type-selector">
                    <div class="type-option {{ request('type') === 'article' || !request('type') ? 'selected' : '' }}" data-type="article">
                        <i class="fas fa-newspaper"></i>
                        <span>Article</span>
                    </div>
                    <div class="type-option {{ request('type') === 'idee' ? 'selected' : '' }}" data-type="idee">
                        <i class="fas fa-lightbulb"></i>
                        <span>Idée</span>
                    </div>
                    <div class="type-option {{ request('type') === 'projet_personnel' ? 'selected' : '' }}" data-type="projet_personnel">
                        <i class="fas fa-folder"></i>
                        <span>Projet perso</span>
                    </div>
                    <div class="type-option {{ request('type') === 'projet_collaboratif' ? 'selected' : '' }}" data-type="projet_collaboratif">
                        <i class="fas fa-users"></i>
                        <span>Collaboratif</span>
                    </div>
                </div>
            </div>

            <!-- Titre -->
            <div class="form-group">
                <label>Titre</label>
                <input type="text" name="titre" class="form-control-devhub" placeholder="Donnez un titre à votre publication" value="{{ old('titre') }}" required>
            </div>

            <!-- Contenu -->
            <div class="form-group">
                <label>Description</label>
                <textarea name="contenu" class="form-control-devhub" rows="6" placeholder="Décrivez votre publication...">{{ old('contenu') }}</textarea>
            </div>

            <!-- Champs projet (personnel et collaboratif) -->
            <div class="extra-fields" id="projetFields">
                <div class="form-row">
                    <div class="form-group">
                        <label>Lien GitHub / Dépôt</label>
                        <input type="url" name="url_repo" class="form-control-devhub" placeholder="https://github.com/...">
                    </div>
                    <div class="form-group">
                        <label>Lien démo</label>
                        <input type="url" name="url_demo" class="form-control-devhub" placeholder="https://...">
                    </div>
                </div>
                <div class="form-group">
                    <label>Technologies utilisées</label>
                    <input type="text" name="technologies" class="form-control-devhub" placeholder="React, Node.js, MySQL...">
                </div>
            </div>

            <!-- Champs projet collaboratif uniquement -->
            <div class="extra-fields" id="collabFields">
                <div class="form-row">
                    <div class="form-group">
                        <label>Taille équipe maximale</label>
                        <input type="number" name="taille_equipe_max" class="form-control-devhub" value="5" min="2" max="20">
                    </div>
                    <div class="form-group">
                        <label>Phase</label>
                        <select name="phase" class="form-control-devhub">
                            <option value="ouvert">Ouvert aux candidatures</option>
                            <option value="en_cours">En cours</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Statut -->
            <div class="form-group">
                <label>Statut de publication</label>
                <input type="hidden" name="statut" id="statutInput" value="publié">
                <div class="status-options">
                    <div class="status-option selected" data-statut="publié">
                        <i class="fas fa-globe me-2"></i>Publier maintenant
                    </div>
                    <div class="status-option" data-statut="brouillon">
                        <i class="fas fa-save me-2"></i>Sauvegarder en brouillon
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2 mt-2">
                <button type="submit" class="btn-submit">
                    <i class="fas fa-paper-plane me-2"></i>Publier
                </button>
                <a href="{{ route('dashboard') }}" style="padding:0.75rem 1.5rem;border:1.5px solid var(--gray-200);border-radius:10px;color:var(--gray-500);text-decoration:none;font-weight:500;">
                    Annuler
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Type selector
document.querySelectorAll('.type-option').forEach(opt => {
    opt.addEventListener('click', () => {
        document.querySelectorAll('.type-option').forEach(o => o.classList.remove('selected'));
        opt.classList.add('selected');
        const type = opt.dataset.type;
        document.getElementById('typeInput').value = type;

        const projetFields = document.getElementById('projetFields');
        const collabFields = document.getElementById('collabFields');

        projetFields.classList.toggle('visible', ['projet_personnel','projet_collaboratif'].includes(type));
        collabFields.classList.toggle('visible', type === 'projet_collaboratif');

        document.querySelector('.btn-submit').innerHTML =
            type === 'brouillon' ? '<i class="fas fa-save me-2"></i>Sauvegarder' :
            '<i class="fas fa-paper-plane me-2"></i>Publier';
    });
});

// Statut selector
document.querySelectorAll('.status-option').forEach(opt => {
    opt.addEventListener('click', () => {
        document.querySelectorAll('.status-option').forEach(o => o.classList.remove('selected'));
        opt.classList.add('selected');
        document.getElementById('statutInput').value = opt.dataset.statut;
    });
});

// Init
const initialType = document.getElementById('typeInput').value;
const projetFields = document.getElementById('projetFields');
const collabFields = document.getElementById('collabFields');
if (['projet_personnel','projet_collaboratif'].includes(initialType)) projetFields.classList.add('visible');
if (initialType === 'projet_collaboratif') collabFields.classList.add('visible');
</script>
@endsection