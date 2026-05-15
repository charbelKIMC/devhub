@extends('layouts.admin')
@section('title', 'Publications – Admin DevHub')
@section('page_title', 'Modération des publications')

@section('content')

<div class="filter-bar">
    <form method="GET" style="display:contents;">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Titre, auteur..." class="filter-input" style="width:220px;">
        <select name="type" class="filter-input">
            <option value="">Tous les types</option>
            <option value="article"             {{ request('type')==='article'            ?'selected':'' }}>Article</option>
            <option value="idee"                {{ request('type')==='idee'               ?'selected':'' }}>Idée</option>
            <option value="projet_personnel"    {{ request('type')==='projet_personnel'   ?'selected':'' }}>Projet perso</option>
            <option value="projet_collaboratif" {{ request('type')==='projet_collaboratif'?'selected':'' }}>Collaboratif</option>
        </select>
        <button type="submit" class="btn-filter"><i class="fas fa-search me-1"></i> Filtrer</button>
        @if(request()->hasAny(['q','type']))
            <a href="{{ route('admin.publications') }}" style="padding:0.45rem 0.85rem;border:1px solid var(--gray-200);border-radius:8px;font-size:0.82rem;color:var(--gray-500);text-decoration:none;">
                <i class="fas fa-times me-1"></i> Réinitialiser
            </a>
        @endif
    </form>
    <span style="margin-left:auto;font-size:0.78rem;color:var(--gray-400);">
        <strong style="color:var(--gray-900);">{{ $publications->total() }}</strong> publication{{ $publications->total()>1?'s':'' }}
    </span>
</div>

<div class="table-wrap">
    <table class="admin-table">
        <thead>
            <tr>
                <th style="width:35%;">Publication</th>
                <th>Auteur</th>
                <th>Type</th>
                <th>Vues</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($publications as $pub)
                @php
                    $typeConfig = [
                        'article'             => ['Article',     '#DBEAFE','#1D4ED8'],
                        'idee'                => ['Idée',        '#FEF9C3','#854D0E'],
                        'projet_personnel'    => ['Projet',      '#EDE9FE','#7C3AED'],
                        'projet_collaboratif' => ['Collaboratif','#D1FAE5','#065F46'],
                    ];
                    $tc = $typeConfig[$pub->type] ?? [$pub->type,'#F3F4F6','#6B7280'];
                @endphp
                <tr>
                    <td>
                        <a href="{{ route('publications.show', $pub->id) }}" target="_blank" class="pub-title-link">
                            {{ Str::limit($pub->titre, 55) }}
                        </a>
                        <div style="font-size:0.72rem;color:var(--gray-400);margin-top:0.1rem;">
                            {{ $pub->nb_commentaires }} commentaire{{ $pub->nb_commentaires>1?'s':'' }} · {{ $pub->nb_likes }} like{{ $pub->nb_likes>1?'s':'' }}
                        </div>
                    </td>
                    <td>
                        <div style="font-size:0.82rem;font-weight:600;color:var(--gray-700);">{{ $pub->auteur->prenom }} {{ $pub->auteur->nom }}</div>
                        <div style="font-size:0.7rem;color:var(--gray-400);">{{ $pub->auteur->email }}</div>
                    </td>
                    <td>
                        <span class="type-badge" style="background: {{ $tc[1] }};color: {{ $tc[2] }};">{{ $tc[0] }}</span>
                    </td>
                    <td style="font-size:0.82rem;color:var(--gray-500);">{{ number_format($pub->nb_vues) }}</td>
                    <td style="font-size:0.78rem;color:var(--gray-400);">{{ $pub->date_publication->format('d/m/Y') }}</td>
                    <td>
                        <div style="display:flex;gap:0.35rem;">
                            <a href="{{ route('publications.show', $pub->id) }}" target="_blank" class="btn-voir">
                                <i class="fas fa-eye"></i>
                            </a>
                            <form action="{{ route('admin.publications.supprimer', $pub->id) }}" method="POST"
                                data-confirm="La publication '{{ Str::limit($pub->titre, 40) }}' sera définitivement supprimée."
                                data-title="Supprimer cette publication ?"
                                data-type="danger">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align:center;padding:3rem;color:var(--gray-400);">
                        <i class="fas fa-newspaper" style="font-size:2rem;display:block;margin-bottom:0.5rem;color:var(--gray-200);"></i>
                        Aucune publication trouvée
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    @if($publications->hasPages())
        <div class="pagination-zone">{{ $publications->appends(request()->query())->links() }}</div>
    @endif
</div>
@endsection