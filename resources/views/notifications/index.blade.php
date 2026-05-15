@extends('layouts.app')
@section('title', 'Notifications – DevHub')
@section('secondary_nav', true)

@section('styles')
<style>
.notif-page { max-width: 720px; margin: 0 auto; }

.notif-page-header {
    display: flex; align-items: center; justify-content: space-between;
    margin-bottom: 1.5rem; flex-wrap: wrap; gap: 0.75rem;
}

.notif-page-title {
    font-family: 'Urbanist', sans-serif;
    font-size: 1.1rem; font-weight: 800; color: var(--gray-900);
    display: flex; align-items: center; gap: 0.5rem;
}

.notif-count-badge {
    background: var(--purple-light); color: var(--purple);
    font-size: 0.72rem; font-weight: 700;
    padding: 0.15rem 0.6rem; border-radius: 20px;
}

.btn-tout-lire {
    display: inline-flex; align-items: center; gap: 0.4rem;
    padding: 0.45rem 1rem;
    background: transparent; color: var(--purple);
    border: 1.5px solid var(--purple);
    border-radius: 8px; font-size: 0.82rem; font-weight: 600;
    cursor: pointer; text-decoration: none;
    transition: all 0.2s; font-family: 'DM Sans', sans-serif;
}
.btn-tout-lire:hover { background: var(--purple-light); }

/* Groupe de notifications */
.notif-group { margin-bottom: 1.5rem; }

.notif-group-label {
    font-size: 0.7rem; font-weight: 700;
    color: var(--gray-400); text-transform: uppercase;
    letter-spacing: 0.5px; margin-bottom: 0.5rem;
    padding: 0 0.25rem;
}

/* Item notification */
.notif-item {
    display: flex; align-items: flex-start; gap: 0.85rem;
    padding: 0.9rem 1.1rem;
    background: white;
    border: 1px solid var(--gray-200);
    border-radius: 12px;
    margin-bottom: 0.5rem;
    transition: all 0.18s;
    position: relative;
    text-decoration: none;
}

.notif-item:hover {
    border-color: var(--purple);
    box-shadow: 0 2px 12px rgba(124,58,237,0.08);
    transform: translateY(-1px);
}

.notif-item.unread {
    background: #F5F3FF;
    border-color: #DDD6FE;
}

.notif-item.unread::before {
    content: '';
    position: absolute;
    left: -1px; top: 50%;
    transform: translateY(-50%);
    width: 3px; height: 60%;
    background: var(--purple);
    border-radius: 0 2px 2px 0;
}

/* Icône */
.notif-icon {
    width: 40px; height: 40px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 0.9rem; flex-shrink: 0;
}

/* Corps */
.notif-body { flex: 1; min-width: 0; }

.notif-content {
    font-size: 0.875rem; line-height: 1.5;
    color: var(--gray-700);
}

.notif-item.unread .notif-content {
    font-weight: 600; color: var(--gray-900);
}

.notif-time {
    font-size: 0.72rem; color: var(--gray-400);
    margin-top: 0.2rem;
    display: flex; align-items: center; gap: 0.3rem;
}

/* Action */
.notif-action {
    display: inline-flex; align-items: center; gap: 0.3rem;
    padding: 0.3rem 0.75rem;
    background: var(--purple-light); color: var(--purple);
    border-radius: 6px; font-size: 0.75rem; font-weight: 600;
    text-decoration: none; flex-shrink: 0;
    transition: all 0.18s;
}
.notif-action:hover { background: var(--purple); color: white; }

/* Point non lu */
.unread-dot {
    width: 8px; height: 8px; border-radius: 50%;
    background: var(--purple); flex-shrink: 0;
    margin-top: 4px;
}

/* Empty */
.notif-empty {
    text-align: center; padding: 4rem 2rem;
    color: var(--gray-400);
}
.notif-empty i {
    font-size: 3rem; color: var(--gray-200);
    display: block; margin-bottom: 1rem;
}
.notif-empty h3 {
    font-family: 'Urbanist', sans-serif;
    font-size: 1rem; font-weight: 700;
    color: var(--gray-600); margin-bottom: 0.4rem;
}

/* Pagination */
.notif-pagination {
    margin-top: 1.5rem;
    display: flex; justify-content: center;
}
</style>
@endsection

@section('content')
<main class="main-content">
<div class="notif-page">

    <!-- Header -->
    <div class="notif-page-header">
        <div class="notif-page-title">
            <i class="fas fa-bell" style="color:var(--purple);"></i>
            Notifications
            @php $nonLues = $notifications->where('est_lue', false)->count(); @endphp
            @if($nonLues > 0)
                <span class="notif-count-badge">{{ $nonLues }} nouvelle{{ $nonLues > 1 ? 's' : '' }}</span>
            @endif
        </div>

        @if($nonLues > 0)
            <form action="{{ route('notifications.toutLire') }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit" class="btn-tout-lire">
                    <i class="fas fa-check-double"></i> Tout marquer comme lu
                </button>
            </form>
        @endif
    </div>

    @if($notifications->isNotEmpty())

        @php
            $grouped = $notifications->groupBy(function($n) {
                if (!$n->date_creation) return 'Anciennes';
                if ($n->date_creation->isToday()) return "Aujourd'hui";
                if ($n->date_creation->isYesterday()) return 'Hier';
                if ($n->date_creation->isCurrentWeek()) return 'Cette semaine';
                return 'Plus anciennes';
            });

            $typeConfig = [
                'nouveau_like'         => ['fa-heart',            '#FCE7F3', '#BE185D'],
                'nouveau_commentaire'  => ['fa-comment-dots',     '#EDE9FE', '#7C3AED'],
                'nouveau_message'      => ['fa-envelope',         '#DBEAFE', '#1D4ED8'],
                'nouvelle_candidature' => ['fa-user-plus',        '#D1FAE5', '#065F46'],
                'candidature_acceptee' => ['fa-check-circle',     '#D1FAE5', '#065F46'],
                'candidature_refusee'  => ['fa-times-circle',     '#FEE2E2', '#DC2626'],
                'nouvel_avis'          => ['fa-star',             '#FEF9C3', '#854D0E'],
                'bienvenue'            => ['fa-hand-sparkles',    '#EDE9FE', '#7C3AED'],
                'connexion'            => ['fa-sign-in-alt',      '#F3F4F6', '#6B7280'],
            ];
        @endphp

        @foreach($grouped as $label => $group)
            <div class="notif-group">
                <div class="notif-group-label">{{ $label }}</div>

                @foreach($group as $notif)
                    @php
                        $cfg = $typeConfig[$notif->type] ?? ['fa-bell', '#F3F4F6', '#6B7280'];
                    @endphp

                    <div class="notif-item {{ !$notif->est_lue ? 'unread' : '' }}">

                        <!-- Icône -->
                        <div class="notif-icon"
                             style="background:{{ $cfg[1] }};color:{{ $cfg[2] }};">
                            <i class="fas {{ $cfg[0] }}"></i>
                        </div>

                        <!-- Corps -->
                        <div class="notif-body">
                            <div class="notif-content">{{ $notif->contenu }}</div>
                            <div class="notif-time">
                                <i class="far fa-clock"></i>
                                {{ $notif->date_creation ? $notif->date_creation->diffForHumans() : 'récemment' }}
                            </div>
                        </div>

                        <!-- Action -->
                        @if($notif->lien)
                            <a href="{{ url($notif->lien) }}" class="notif-action">
                                Voir <i class="fas fa-arrow-right"></i>
                            </a>
                        @endif

                        <!-- Point non lu -->
                        @if(!$notif->est_lue)
                            <div class="unread-dot"></div>
                        @endif

                    </div>
                @endforeach
            </div>
        @endforeach

        <!-- Pagination -->
        <div class="notif-pagination">
            {{ $notifications->links() }}
        </div>

    @else
        <div class="notif-empty">
            <i class="fas fa-bell-slash"></i>
            <h3>Aucune notification</h3>
            <p style="font-size:0.85rem;">Vous n'avez pas encore de notifications.</p>
        </div>
    @endif

</div>
</main>
@endsection