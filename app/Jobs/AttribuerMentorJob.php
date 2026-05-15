<?php

namespace App\Jobs;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\Notification;
use App\Models\ProjetCollaboratif;
use App\Models\Publication;
use App\Models\Utilisateur;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;

class AttribuerMentorJob implements ShouldQueue
{
    use Queueable;

    public function __construct(public int $publicationId) {}

    public function handle(): void
    {
        $projetCol = ProjetCollaboratif::where('publication_id', $this->publicationId)
            ->whereNull('mentor_id')
            ->first();

        if (!$projetCol) return; // mentor déjà attribué entre temps

        $pub = Publication::find($this->publicationId);
        if (!$pub) return;

        $mentor = $this->trouverMentorDisponible();
        if (!$mentor) return; // toujours personne de dispo, on abandonne (un autre job sera lancé plus tard)

        $conversation = Conversation::where('type_conversation', 'groupe_projet')
            ->where('projet_collaboratif_id', $this->publicationId)
            ->first();

        $this->attribuer($mentor, $projetCol, $pub, $conversation);
    }

    public static function trouverMentorDisponible(): ?Utilisateur
    {
        return Utilisateur::where('role', 'mentor')
            ->where('statut', 'actif')
            ->withCount(['projetsEnMentorat as nb_actifs' => fn($q) =>
                $q->whereIn('phase', ['ouvert', 'en_cours'])
            ])
            ->having('nb_actifs', '<', 3)
            ->orderBy('nb_actifs')
            ->get()
            ->sortBy('nb_actifs')
            ->groupBy('nb_actifs')
            ->first()           // groupe avec le minimum
            ?->random();        // aléatoire parmi ce groupe
    }

    public static function attribuer(Utilisateur $mentor, ProjetCollaboratif $projetCol, Publication $pub, ?Conversation $conversation): void
    {
        $projetCol->update(['mentor_id' => $mentor->id]);

        if ($conversation) {
            // Supprimer le message d'attente système s'il existe
            Message::where('conversation_id', $conversation->id)
                ->where('est_systeme', true)
                ->delete();

            // Message système d'annonce mentor
            Message::create([
                'conversation_id' => $conversation->id,
                'expediteur_id'   => $mentor->id,
                'contenu'         => '👋 Je suis ' . $mentor->prenom . ' ' . $mentor->nom . ', votre mentor pour ce projet. Ravi de vous accompagner !',
                'date_envoi'      => now(),
                'est_systeme'     => false,
            ]);

            $conversation->participants()->syncWithoutDetaching([$mentor->id]);

            // Notifier le mentor
            $notif = Notification::create([
                'destinataire_id' => $mentor->id,
                'type'            => 'mentor_attribue',
                'contenu'         => 'Vous avez été attribué comme mentor du projet "' . $pub->titre . '".',
                'lien'            => route('messagerie.show', $conversation->id),
                'est_lue'         => false,
                'date_creation'   => now(),
            ]);
            try { broadcast(new \App\Events\NotificationCreee($notif)); } catch (\Throwable) {}

            // Notifier les membres
            $membres = DB::table('participants_conversation')
                ->where('conversation_id', $conversation->id)
                ->where('utilisateur_id', '!=', $mentor->id)
                ->pluck('utilisateur_id');

            foreach ($membres as $membreId) {
                $n = Notification::create([
                    'destinataire_id' => $membreId,
                    'type'            => 'mentor_attribue',
                    'contenu'         => $mentor->prenom . ' ' . $mentor->nom . ' a été attribué comme mentor de votre équipe !',
                    'lien'            => route('messagerie.show', $conversation->id),
                    'est_lue'         => false,
                    'date_creation'   => now(),
                ]);
                try { broadcast(new \App\Events\NotificationCreee($n)); } catch (\Throwable) {}
            }
        }

        try {
            \Illuminate\Support\Facades\Mail::to($mentor->email)
                ->send(new \App\Mail\CollabMentorAttribueGroupe($mentor, $pub, $conversation));
        } catch (\Throwable) {}
    }
}
