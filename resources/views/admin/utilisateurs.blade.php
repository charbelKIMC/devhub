@extends('layouts.admin')
@section('title', 'Utilisateurs – Admin DevHub')
@section('page_title', 'Gestion des utilisateurs')

@section('content')

<!-- Filtres -->
<form method="GET" class="filter-bar">
    <input type="text" name="q" value="{{ request('q') }}" placeholder="Nom, email, pseudo..." class="filter-input" style="width:220px;">
    <select name="role" class="filter-input">
        <option value="">Tous les rôles</option>
        <option value="développeur" {{ request('role')==='développeur'?'selected':'' }}>Développeurs</option>
        <option value="mentor"      {{ request('role')==='mentor'?'selected':'' }}>Mentors</option>
    </select>
    <select name="statut" class="filter-input">
        <option value="">Tous les statuts</option>
        <option value="actif"    {{ request('statut')==='actif'   ?'selected':'' }}>Actif</option>
        <option value="suspendu" {{ request('statut')==='suspendu'?'selected':'' }}>Suspendu</option>
    </select>
    <button type="submit" class="btn-filter"><i class="fas fa-search me-1"></i> Filtrer</button>
    @if(request()->hasAny(['q','role','statut']))
        <a href="{{ route('admin.utilisateurs') }}" style="padding:0.45rem 0.85rem;border:1px solid var(--gray-200);border-radius:8px;font-size:0.82rem;color:var(--gray-500);text-decoration:none;">
            <i class="fas fa-times me-1"></i> Réinitialiser
        </a>
    @endif
    <span style="margin-left:auto;font-size:0.78rem;color:var(--gray-400);">
        <strong style="color:var(--gray-900);">{{ $utilisateurs->total() }}</strong> utilisateur{{ $utilisateurs->total()>1?'s':'' }}
    </span>
</form>

<!-- Tableau -->
<div class="table-wrap">
    <table class="admin-table">
        <thead>
            <tr>
                <th>Utilisateur</th>
                <th>Rôle</th>
                <th>Statut</th>
                <th>Spécialité</th>
                <th>Inscription</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($utilisateurs as $u)
                <tr>
                    <td>
                        <div class="user-cell">
                            <div class="user-ava">
                                @if($u->photo)<img src="{{ asset('storage/'.$u->photo) }}" alt="">
                                @else {{ strtoupper(substr($u->prenom,0,1)) }} @endif
                            </div>
                            <div>
                                <div class="user-name">{{ $u->prenom }} {{ $u->nom }}</div>
                                <div class="user-email">{{ $u->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="badge {{ $u->role==='mentor'?'badge-mentor':'badge-dev' }}">
                            {{ ucfirst($u->role) }}
                        </span>
                    </td>
                    <td>
                        <span class="badge {{ $u->statut==='actif'?'badge-actif':'badge-suspendu' }}">
                            <span style="width:5px;height:5px;border-radius:50%;background:currentColor;display:inline-block;"></span>
                            {{ ucfirst($u->statut) }}
                        </span>
                    </td>
                    <td style="font-size:0.82rem;color:var(--gray-500);">{{ $u->specialite ?? '—' }}</td>
                    <td style="font-size:0.78rem;color:var(--gray-400);">{{ $u->date_inscription->format('d/m/Y') }}</td>
                    <td>
                        <div class="actions-cell">
                            @if($u->statut === 'actif')
                                <form action="{{ route('admin.utilisateurs.suspendre', $u->id) }}" method="POST"
                                    data-confirm="Le compte de {{ $u->prenom }} {{ $u->nom }} sera suspendu et l'utilisateur ne pourra plus se connecter."
                                    data-title="Suspendre ce compte ?"
                                    data-type="warning">
                                    @csrf @method('PUT')
                                    <button type="submit" class="act-btn act-suspend">
                                        <i class="fas fa-ban me-1"></i>Suspendre
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('admin.utilisateurs.activer', $u->id) }}" method="POST"
                                    data-confirm="Le compte de {{ $u->prenom }} {{ $u->nom }} sera réactivé."
                                    data-title="Activer ce compte ?"
                                    data-type="success">
                                    @csrf @method('PUT')
                                    <button type="submit" class="act-btn act-activer">
                                        <i class="fas fa-check me-1"></i>Activer
                                    </button>
                                </form>
                            @endif
                            <form action="{{ route('admin.utilisateurs.supprimer', $u->id) }}" method="POST"
                                  data-confirm="Le compte de {{ $u->prenom }} {{ $u->nom }} sera définitivement supprimé."
                                  data-title="Supprimer ce compte ?"
                                  data-type="danger">
                                @csrf @method('DELETE')
                                <button type="submit" class="act-btn act-delete" title="Supprimer">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            <a href="{{ route('admin.utilisateurs.detail', $u->id) }}" style="padding:0.3rem 0.65rem;background:var(--purple-light);border:1px solid var(--purple-border);color:var(--purple);border-radius:6px;font-size:0.75rem;font-weight:600;text-decoration:none;">
                                <i class="fas fa-eye"></i>
                            </a>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align:center;padding:3rem;color:var(--gray-400);">
                        <i class="fas fa-users" style="font-size:2rem;display:block;margin-bottom:0.5rem;color:var(--gray-200);"></i>
                        Aucun utilisateur trouvé
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    @if($utilisateurs->hasPages())
        <div class="pagination-zone">{{ $utilisateurs->appends(request()->query())->links() }}</div>
    @endif
</div>
@endsection