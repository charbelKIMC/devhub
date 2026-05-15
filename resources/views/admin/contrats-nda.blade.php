@extends('layouts.app')
@section('title', 'Contrats NDA – Admin DevHub')

@section('styles')
<style>
    .admin-nda-wrap { max-width: 1100px; margin: 0 auto; }
    .admin-nda-title {
        font-family: 'Urbanist', sans-serif; font-size: 1.2rem;
        font-weight: 800; color: var(--gray-900); margin-bottom: 1.5rem;
        display: flex; align-items: center; gap: 0.5rem;
    }
    .nda-table {
        width: 100%; border-collapse: collapse;
        background: white; border-radius: 14px; overflow: hidden;
        border: 1.5px solid var(--gray-200);
    }
    .nda-table th {
        background: var(--gray-50); font-size: 0.72rem; font-weight: 700;
        color: var(--gray-500); text-transform: uppercase; letter-spacing: 0.4px;
        padding: 0.75rem 1rem; text-align: left; border-bottom: 1.5px solid var(--gray-200);
    }
    .nda-table td {
        padding: 0.85rem 1rem; border-bottom: 1px solid var(--gray-100);
        font-size: 0.83rem; color: var(--gray-700); vertical-align: middle;
    }
    .nda-table tr:last-child td { border-bottom: none; }
    .nda-table tr:hover td { background: var(--gray-50); }
    .nda-statut {
        font-size: 0.7rem; font-weight: 700; padding: 0.2rem 0.55rem;
        border-radius: 6px; text-transform: uppercase; letter-spacing: 0.3px;
        display: inline-block;
    }
    .nda-statut.signe    { background: #D1FAE5; color: #065F46; }
    .nda-statut.en_attente { background: #FEF9C3; color: #92400E; }
    .btn-dl {
        padding: 0.3rem 0.75rem; background: var(--purple); color: white;
        border-radius: 7px; font-size: 0.72rem; font-weight: 600;
        text-decoration: none; display: inline-flex; align-items: center; gap: 0.3rem;
    }
    .btn-dl:hover { opacity: 0.85; }
</style>
@endsection

@section('content')
<main class="main-content">
<div class="admin-nda-wrap">

    <h1 class="admin-nda-title">
        <i class="fas fa-file-contract" style="color:var(--purple);"></i>
        Contrats de confidentialité NDA
    </h1>

    @if(session('error'))
        <div style="background:#FEE2E2;border:1px solid #FECACA;border-radius:8px;padding:0.75rem 1rem;margin-bottom:1rem;font-size:0.85rem;color:#DC2626;">
            {{ session('error') }}
        </div>
    @endif

    @if($contrats->isEmpty())
        <div style="text-align:center;padding:4rem;background:white;border-radius:14px;border:1.5px solid var(--gray-200);">
            <i class="fas fa-file-contract" style="font-size:2.5rem;color:var(--gray-200);display:block;margin-bottom:1rem;"></i>
            <div style="font-family:'Urbanist',sans-serif;font-weight:700;color:var(--gray-500);">Aucun contrat NDA pour le moment</div>
        </div>
    @else
        <table class="nda-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Collaborateur</th>
                    <th>Projet</th>
                    <th>Responsable</th>
                    <th>Statut</th>
                    <th>Signé le</th>
                    <th>Adresse IP</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($contrats as $nda)
                <tr>
                    <td style="color:var(--gray-400);">{{ $nda->id }}</td>
                    <td>
                        <div style="font-weight:600;color:var(--gray-900);">{{ $nda->utilisateur?->prenom }} {{ $nda->utilisateur?->nom }}</div>
                        <div style="font-size:0.72rem;color:var(--gray-400);">{{ $nda->utilisateur?->email }}</div>
                    </td>
                    <td>
                        <div style="font-weight:600;">{{ $nda->publication?->titre }}</div>
                    </td>
                    <td>
                        <div>{{ $nda->publication?->auteur?->prenom }} {{ $nda->publication?->auteur?->nom }}</div>
                        <div style="font-size:0.72rem;color:var(--gray-400);">{{ $nda->publication?->auteur?->email }}</div>
                    </td>
                    <td>
                        <span class="nda-statut {{ str_replace('é', 'e', $nda->statut) }}">
                            {{ $nda->statut === 'signé' ? 'Signé' : 'En attente' }}
                        </span>
                    </td>
                    <td>
                        @if($nda->signe_le)
                            {{ \Carbon\Carbon::parse($nda->signe_le)->format('d/m/Y H:i') }}
                        @else
                            <span style="color:var(--gray-300);">—</span>
                        @endif
                    </td>
                    <td style="font-size:0.75rem;color:var(--gray-500);">{{ $nda->ip_signature ?? '—' }}</td>
                    <td>
                        @if($nda->chemin_pdf)
                            <a href="{{ route('admin.contrats.telecharger', $nda->id) }}" class="btn-dl">
                                <i class="fas fa-download"></i> PDF
                            </a>
                        @else
                            <span style="color:var(--gray-300);font-size:0.75rem;">Non disponible</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div style="margin-top:1rem;">
            {{ $contrats->links() }}
        </div>
    @endif

</div>
</main>
@endsection
