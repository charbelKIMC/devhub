@extends('layouts.app')
@section('title', 'Modifier mon profil – DevHub')
@section('styles')
<link rel="stylesheet" href="{{ asset('css/profil.css') }}">
@endsection

@section('content')
<main class="main-content">
    <div class="conteneur">
        <div class="settings-container">

            <!-- Sidebar -->
            <div class="settings-sidebar">
                <ul class="settings-menu">
                    <li class="settings-menu-item active" data-setting="personal">
                        <i class="fas fa-user me-3"></i>Informations personnelles
                    </li>
                    <li class="settings-menu-item" data-setting="password">
                        <i class="fas fa-lock me-3"></i>Mot de passe
                    </li>
                </ul>
            </div>

            <!-- Contenu -->
            <div class="settings-content">

                <!-- Informations personnelles -->
                <div class="settings-panel active" id="personal">
                    <h3 class="settings-title">Informations personnelles</h3>
                    <p class="settings-desc">Modifiez vos informations de profil visibles par la communauté.</p>

                    <form method="POST" action="{{ route('profil.update') }}" enctype="multipart/form-data" class="settings-form">
                        @csrf @method('PUT')

                        <!-- Photo -->
                        <div class="form-group">
                            <label>Photo de profil professionnelle</label>
                            <div class="photo-edit-wrap">
                                <div class="photo-edit-preview">
                                    @if(Auth::user()->photo)
                                        <img src="{{ Auth::user()->photo_url }}" alt="photo" id="photoPreview">
                                    @else
                                        <div class="photo-placeholder-lg" id="photoPlaceholder">
                                            {{ strtoupper(substr(Auth::user()->prenom, 0, 1)) }}
                                        </div>
                                    @endif
                                </div>
                                <label for="photo" class="btn-upload-small">
                                    <i class="fas fa-upload me-2"></i>Changer la photo
                                </label>
                                <input type="file" id="photo" name="photo" accept="image/*" style="display:none;">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label>Prénom</label>
                                <input type="text" name="prenom" class="form-input" value="{{ Auth::user()->prenom }}">
                            </div>
                            <div class="form-group">
                                <label>Nom</label>
                                <input type="text" name="nom" class="form-input" value="{{ Auth::user()->nom }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Spécialité</label>
                            <input type="text" name="specialite" class="form-input" value="{{ Auth::user()->specialite }}" placeholder="Ex: Développeur Full-Stack">
                        </div>

                        <div class="form-group">
                            <label>Bio</label>
                            <textarea name="bio" class="form-input form-textarea" placeholder="Parlez de vous...">{{ Auth::user()->bio }}</textarea>
                        </div>

                        <div class="form-group">
                            <label>Localisation</label>
                            <input type="text" name="localisation" class="form-input" value="{{ Auth::user()->localisation }}" placeholder="Ex: Cotonou, Bénin">
                        </div>

                        <div class="form-group">
                            <label>GitHub</label>
                            <input type="url" name="github" class="form-input" value="{{ Auth::user()->github }}" placeholder="https://github.com/...">
                        </div>

                        <div class="form-group">
                            <label>LinkedIn</label>
                            <input type="url" name="linkedin" class="form-input" value="{{ Auth::user()->linkedin }}" placeholder="https://linkedin.com/in/...">
                        </div>

                        <div class="form-group">
                            <label>Portfolio</label>
                            <input type="url" name="portfolio" class="form-input" value="{{ Auth::user()->portfolio }}" placeholder="https://...">
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn-save">Enregistrer les modifications</button>
                            <a href="{{ route('profil.show', Auth::user()->id) }}" class="btn-cancel">Annuler</a>
                        </div>
                    </form>
                </div>

                <!-- Mot de passe -->
                <div class="settings-panel" id="password">
                    <h3 class="settings-title">Mot de passe</h3>
                    <p class="settings-desc">Choisissez un mot de passe fort pour sécuriser votre compte.</p>

                    <form method="POST" action="{{ route('password.update') }}" class="settings-form">
                        @csrf @method('POST')

                        <div class="form-group">
                            <label>Mot de passe actuel</label>
                            <input type="password" name="current_password" class="form-input" placeholder="••••••••">
                        </div>
                        <div class="form-group">
                            <label>Nouveau mot de passe</label>
                            <input type="password" name="mot_de_passe" class="form-input" placeholder="Minimum 8 caractères">
                        </div>
                        <div class="form-group">
                            <label>Confirmer le nouveau mot de passe</label>
                            <input type="password" name="mot_de_passe_confirmation" class="form-input" placeholder="••••••••">
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn-save">Changer le mot de passe</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</main>
@endsection

@section('scripts')
<script src="{{ asset('js/profil.js') }}"></script>
@endsection