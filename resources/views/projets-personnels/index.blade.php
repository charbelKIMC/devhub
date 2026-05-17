@extends('layouts.app')
@section('title', 'Mes projets personnels – DevHub')
@section('secondary_nav', true)

@section('styles')
<style>
    .pp-header {
        display: flex; align-items: center; justify-content: space-between;
        margin-bottom: 1.5rem; flex-wrap: wrap; gap: 0.75rem;
    }
    .pp-title {
        font-family: 'Urbanist', sans-serif;
        font-size: 1.1rem; font-weight: 800; color: var(--gray-900);
    }
    .pp-sub { font-size: 0.82rem; color: var(--gray-400); margin-top: 0.15rem; }

    /* Bannière mentor */
    .mentor-banner {
        border-radius: 14px; padding: 1.1rem 1.25rem;
        display: flex; align-items: center; gap: 1rem;
        margin-bottom: 1.5rem;
    }
    .mentor-banner.active  { background: #D1FAE5; border: 1px solid #6EE7B7; }
    .mentor-banner.waiting { background: #FEF9C3; border: 1px solid #FDE68A; }
    .mentor-banner-icon {
        width: 42px; height: 42px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1rem; flex-shrink: 0;
    }

    /* Grille projets */
    .pp-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 1rem; margin-bottom: 1.5rem;
    }

    .pp-card {
        background: white; border: 1.5px solid var(--gray-200);
        border-radius: 14px; overflow: hidden;
        transition: box-shadow 0.2s, transform 0.2s;
    }
    .pp-card:hover { box-shadow: 0 4px 20px rgba(0,0,0,0.08); transform: translateY(-2px); }

    .pp-card-img {
        width: 100%; height: 160px; object-fit: cover;
        background: var(--gray-100);
        display: flex; align-items: center; justify-content: center;
        color: var(--gray-300); font-size: 2rem;
    }
    .pp-card-img img { width: 100%; height: 100%; object-fit: cover; }

    .pp-card-body { padding: 1rem 1.1rem; }
    .pp-card-title {
        font-family: 'Urbanist', sans-serif;
        font-size: 0.95rem; font-weight: 700; color: var(--gray-900);
        margin-bottom: 0.4rem;
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }
    .pp-card-desc { font-size: 0.8rem; color: var(--gray-500); line-height: 1.5; margin-bottom: 0.75rem; }

    .pp-tags { display: flex; flex-wrap: wrap; gap: 0.35rem; margin-bottom: 0.75rem; }
    .pp-tag {
        font-size: 0.7rem; background: var(--purple-light); color: var(--purple);
        padding: 0.2rem 0.55rem; border-radius: 6px; font-weight: 600;
    }

    .pp-card-footer {
        display: flex; align-items: center; justify-content: space-between;
        padding: 0.75rem 1.1rem;
        border-top: 1px solid var(--gray-100);
        font-size: 0.75rem; color: var(--gray-400);
    }
    .pp-card-links { display: flex; gap: 0.5rem; }
    .pp-card-link {
        padding: 0.3rem 0.65rem; border-radius: 7px; font-size: 0.72rem;
        font-weight: 600; text-decoration: none; transition: opacity 0.15s;
    }
    .pp-card-link:hover { opacity: 0.8; }
    .pp-card-link.repo { background: #1F2937; color: white; }
    .pp-card-link.demo { background: var(--purple); color: white; }

    .pp-statut {
        font-size: 0.68rem; font-weight: 700; padding: 0.2rem 0.5rem;
        border-radius: 6px; text-transform: uppercase; letter-spacing: 0.3px;
    }
    .pp-statut.en_cours   { background: #D1FAE5; color: #065F46; }
    .pp-statut.terminé    { background: #DBEAFE; color: #1D4ED8; }
    .pp-statut.abandonné  { background: #FEE2E2; color: #DC2626; }

    /* Bouton terminer */
    .btn-terminer {
        display: inline-flex; align-items: center; gap: 0.35rem;
        padding: 0.3rem 0.75rem; border-radius: 7px; font-size: 0.72rem;
        font-weight: 700; cursor: pointer; border: none;
        background: #065F46; color: white; transition: background 0.15s;
        font-family: 'DM Sans', sans-serif;
    }
    .btn-terminer:hover { background: #064E3B; }

    /* Modal confirmation */
    .confirm-overlay {
        display: none; position: fixed; inset: 0;
        background: rgba(0,0,0,0.45); z-index: 9999;
        align-items: center; justify-content: center;
    }
    .confirm-overlay.open { display: flex; }
    .confirm-box {
        background: white; border-radius: 16px; padding: 2rem;
        max-width: 400px; width: 90%; box-shadow: 0 20px 60px rgba(0,0,0,0.2);
        text-align: center;
    }
    .confirm-icon {
        width: 56px; height: 56px; border-radius: 16px;
        background: #D1FAE5; color: #065F46;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.4rem; margin: 0 auto 1rem;
    }
    .confirm-title {
        font-family: 'Urbanist', sans-serif; font-size: 1rem;
        font-weight: 800; color: var(--gray-900); margin-bottom: 0.5rem;
    }
    .confirm-sub { font-size: 0.82rem; color: var(--gray-500); margin-bottom: 1.5rem; line-height: 1.5; }
    .confirm-actions { display: flex; gap: 0.65rem; justify-content: center; }
    .confirm-btn-cancel {
        padding: 0.6rem 1.25rem; border-radius: 10px; font-size: 0.85rem;
        font-weight: 700; cursor: pointer; border: 1.5px solid var(--gray-200);
        background: white; color: var(--gray-600); font-family: 'DM Sans', sans-serif;
        transition: background 0.15s;
    }
    .confirm-btn-cancel:hover { background: var(--gray-100); }
    .confirm-btn-ok {
        padding: 0.6rem 1.5rem; border-radius: 10px; font-size: 0.85rem;
        font-weight: 700; cursor: pointer; border: none;
        background: #065F46; color: white; font-family: 'DM Sans', sans-serif;
        transition: background 0.15s;
    }
    .confirm-btn-ok:hover { background: #064E3B; }

    /* Empty */
    .pp-empty {
        text-align: center; padding: 4rem 2rem;
        background: white; border: 1px solid var(--gray-200); border-radius: 14px;
    }
    .pp-empty i { font-size: 3rem; color: var(--gray-200); display: block; margin-bottom: 1rem; }
    .pp-empty-title { font-family: 'Urbanist',sans-serif; font-size: 1rem; font-weight: 700; color: var(--gray-600); margin-bottom: 0.5rem; }
    .pp-empty-sub { font-size: 0.85rem; color: var(--gray-400); margin-bottom: 1.5rem; }
</style>
@endsection

@section('content')
<main class="main-content" >

    <div class="pp-header">
        <div>
            <p class="pp-sub">{{ $projets->count() }} projet{{ $projets->count() > 1 ? 's' : '' }}</p>
        </div>
        <a href="{{ route('projets-personnels.create') }}"
           style="padding:0.6rem 1.25rem;background:var(--purple);color:white;border-radius:10px;font-size:0.875rem;font-weight:700;text-decoration:none;display:flex;align-items:center;gap:0.5rem;">
            <i class="fas fa-plus"></i> Nouveau projet
        </a>
    </div>

    {{-- Bannière mentor actif --}}
    @if($relationActive)
        <div class="mentor-banner active">
            <div class="mentor-banner-icon" style="background:#065F46;color:white;">
                <i class="fas fa-chalkboard-teacher"></i>
            </div>
            <div style="flex:1;">
                <div style="font-family:'Urbanist',sans-serif;font-weight:700;font-size:0.9rem;color:#065F46;">
                    Votre mentor : {{ $relationActive->mentor?->prenom }} {{ $relationActive->mentor?->nom }}
                </div>
                <div style="font-size:0.78rem;color:#047857;margin-top:0.1rem;">
                    Relation active · Jusqu'au {{ $relationActive->date_fin ? \Carbon\Carbon::parse($relationActive->date_fin)->format('d/m/Y') : '—' }}
                </div>
            </div>
            <a href="{{ route('messagerie.index') }}"
               style="padding:0.4rem 0.9rem;background:#065F46;color:white;border-radius:8px;font-size:0.78rem;font-weight:600;text-decoration:none;flex-shrink:0;">
                <i class="fas fa-comment me-1"></i> Contacter
            </a>
        </div>

    @elseif($enAttente)
        <div class="mentor-banner waiting">
            <div class="mentor-banner-icon" style="background:#F59E0B;color:white;">
                <i class="fas fa-clock"></i>
            </div>
            <div>
                <div style="font-family:'Urbanist',sans-serif;font-weight:700;font-size:0.9rem;color:#92400E;">
                    Attribution de mentor en cours
                </div>
                <div style="font-size:0.78rem;color:#B45309;margin-top:0.1rem;">
                    Aucun mentor disponible pour le moment. L'administrateur vous attribuera un mentor dès que possible.
                </div>
            </div>
        </div>
    @endif

    {{-- Liste des projets --}}
    @if($projets->isNotEmpty())
        <div class="pp-grid">
            @foreach($projets as $pub)
                @php $projet = $pub->projetPersonnel; @endphp
                <div class="pp-card">

                    {{-- Image --}}
                    <div class="pp-card-img">
                        @if($pub->medias->isNotEmpty())
                            <img src="{{ $pub->medias->first()->url }}" alt="">
                        @else
                            <i class="fas fa-folder"></i>
                        @endif
                    </div>

                    <div class="pp-card-body">
                        <div class="pp-card-title">{{ $pub->titre }}</div>

                        @if($pub->contenu)
                            <div class="pp-card-desc">{{ Str::limit($pub->contenu, 100) }}</div>
                        @endif

                        @if($projet && !empty($projet->technologies))
                            <div class="pp-tags">
                                @foreach(array_slice($projet->technologies, 0, 4) as $tech)
                                    <span class="pp-tag">{{ $tech }}</span>
                                @endforeach
                                @if(count($projet->technologies) > 4)
                                    <span class="pp-tag" style="background:var(--gray-100);color:var(--gray-500);">
                                        +{{ count($projet->technologies) - 4 }}
                                    </span>
                                @endif
                            </div>
                        @endif
                    </div>

                    <div class="pp-card-footer">
                        <div class="pp-card-links">
                            @if($projet?->url_repo)
                                <a href="{{ $projet->url_repo }}" target="_blank" class="pp-card-link repo">
                                    <i class="fab fa-github me-1"></i>Repo
                                </a>
                            @endif
                            @if($projet?->url_demo)
                                <a href="{{ $projet->url_demo }}" target="_blank" class="pp-card-link demo">
                                    <i class="fas fa-external-link-alt me-1"></i>Demo
                                </a>
                            @endif
                        </div>
                        <div style="display:flex;align-items:center;gap:0.5rem;flex-wrap:wrap;justify-content:flex-end;">
                            @if($projet && $projet->statut_projet === 'en_cours')
                                <button type="button" class="btn-terminer"
                                        onclick="ouvrirConfirmTerminer({{ $pub->id }}, '{{ addslashes($pub->titre) }}')">
                                    <i class="fas fa-check-circle"></i> Terminer et publier
                                </button>
                            @endif
                            @if($projet)
                                <span class="pp-statut {{ $projet->statut_projet }}">
                                    {{ str_replace('_', ' ', $projet->statut_projet) }}
                                </span>
                            @endif
                            <span>{{ $pub->date_publication->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="pp-empty">
            <i class="fas fa-folder-open"></i>
            <div class="pp-empty-title">Aucun projet personnel</div>
            <p class="pp-empty-sub">
                Créez votre premier projet pour bénéficier d'un accompagnement mentor automatique.
            </p>
            <a href="{{ route('projets-personnels.create') }}"
               style="padding:0.65rem 1.5rem;background:var(--purple);color:white;border-radius:10px;font-size:0.875rem;font-weight:700;text-decoration:none;display:inline-flex;align-items:center;gap:0.5rem;">
                <i class="fas fa-plus"></i> Créer mon premier projet
            </a>
        </div>
    @endif

    {{-- Modal confirmation terminer --}}
    <div class="confirm-overlay" id="confirmOverlay">
        <div class="confirm-box">
            <div class="confirm-icon"><i class="fas fa-check-circle"></i></div>
            <div class="confirm-title">Terminer et publier ?</div>
            <p class="confirm-sub" id="confirmSubtitle">
                Le projet sera marqué comme <strong>terminé</strong> et publié dans le feed DevHub pour que la communauté puisse le voir.
            </p>
            <div class="confirm-actions">
                <button class="confirm-btn-cancel" onclick="fermerConfirm()">Annuler</button>
                <button class="confirm-btn-ok" onclick="soumettreTerminer()">
                    <i class="fas fa-rocket me-1"></i> Publier
                </button>
            </div>
        </div>
    </div>

    {{-- Formulaire caché pour PUT terminer --}}
    <form id="formTerminer" method="POST" style="display:none;">
        @csrf
        @method('PUT')
    </form>

</main>
@endsection

@section('scripts')
<script>
    var terminerAction = '';

    function ouvrirConfirmTerminer(id, titre) {
        terminerAction = '{{ url('/projets-personnels') }}/' + id + '/terminer';
        document.getElementById('confirmSubtitle').innerHTML =
            'Le projet <strong>' + titre + '</strong> sera marqué comme <strong>terminé</strong> et publié dans le feed DevHub pour que la communauté puisse le voir.';
        document.getElementById('confirmOverlay').classList.add('open');
    }

    function fermerConfirm() {
        document.getElementById('confirmOverlay').classList.remove('open');
    }

    function soumettreTerminer() {
        var form = document.getElementById('formTerminer');
        form.action = terminerAction;
        form.submit();
    }

    document.getElementById('confirmOverlay').addEventListener('click', function(e) {
        if (e.target === this) fermerConfirm();
    });
</script>
@endsection
