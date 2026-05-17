<?php

namespace App\Http\Controllers;

use App\Models\ContratNda;
use App\Models\Conversation;
use App\Models\MembreProjet;
use App\Models\Notification;
use App\Models\ProjetCollaboratif;
use App\Models\Publication;
use App\Models\Utilisateur;
use App\Jobs\AttribuerMentorJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class ContratNdaController extends Controller
{
    private const MAX_MENTORES = 3;

    public function show($token)
    {
        $contrat = ContratNda::where('token', $token)
            ->where('statut', 'en_attente')
            ->with(['utilisateur', 'publication.auteur', 'publication.projetCollaboratif', 'candidature'])
            ->firstOrFail();

        if (Auth::id() !== $contrat->utilisateur_id) {
            abort(403, 'Ce contrat ne vous est pas destiné.');
        }

        return view('contrats.nda-signer', compact('contrat'));
    }

    public function signer(Request $request, $token)
    {
        $request->validate([
            'accord' => 'required|accepted',
        ], [
            'accord.required' => 'Vous devez lire et accepter les termes du contrat.',
            'accord.accepted'  => 'Vous devez cocher la case pour confirmer votre accord.',
        ]);

        $contrat = ContratNda::where('token', $token)
            ->where('utilisateur_id', Auth::id())
            ->where('statut', 'en_attente')
            ->with(['candidature', 'publication.auteur', 'publication.projetCollaboratif'])
            ->firstOrFail();

        $pub        = $contrat->publication;
        $auteur     = $pub->auteur;
        $candidat   = Auth::user();
        $projetCol  = $pub->projetCollaboratif;
        $now        = now();

        // Générer le PDF
        $pdf = Pdf::loadView('contrats.nda-pdf', [
            'auteur'        => $auteur,
            'candidat'      => $candidat,
            'pub'           => $pub,
            'contrat'       => $contrat,
            'dateSignature' => $now,
            'ipSignature'   => $request->ip(),
        ])->setPaper('a4', 'portrait');

        $filename = 'nda_' . $contrat->id . '_' . $now->format('Ymd_His') . '.pdf';
        $chemin   = 'ndas/' . $filename;
        Storage::disk('public')->put($chemin, $pdf->output());

        // Mettre à jour le contrat
        $contrat->update([
            'statut'       => 'signé',
            'chemin_pdf'   => $chemin,
            'ip_signature' => $request->ip(),
            'signe_le'     => $now,
        ]);

        // Ajouter comme membre du projet
        MembreProjet::firstOrCreate([
            'projet_collaboratif_id' => $contrat->publication_id,
            'utilisateur_id'         => $candidat->id,
        ], [
            'role'         => $contrat->candidature->poste_souhaite ?? 'collaborateur',
            'date_rejoint' => $now,
        ]);

        // Incrémenter l'équipe
        $projetCol->increment('taille_equipe_actuelle');
        $projetCol->refresh();

        // Conversation de groupe
        $conversation = Conversation::firstOrCreate([
            'type_conversation'      => 'groupe_projet',
            'projet_collaboratif_id' => $contrat->publication_id,
        ], [
            'nom'           => 'Équipe — ' . $pub->titre,
            'date_creation' => $now,
        ]);
        $conversation->participants()->syncWithoutDetaching([$auteur->id, $candidat->id]);

        // Notifier les deux parties (contrat signé, bienvenue dans l'équipe)
        $notifCandidat = Notification::create([
            'destinataire_id' => $candidat->id,
            'type'            => 'candidature_acceptee',
            'contenu'         => 'Contrat signé ! Vous faites maintenant partie de l\'équipe "' . $pub->titre . '". La conversation de groupe est disponible.',
            'lien'            => route('messagerie.show', $conversation->id),
            'est_lue'         => false,
            'date_creation'   => $now,
        ]);
        try { broadcast(new \App\Events\NotificationCreee($notifCandidat)); } catch (\Throwable) {}

        $notifAuteur = Notification::create([
            'destinataire_id' => $auteur->id,
            'type'            => 'candidature_acceptee',
            'contenu'         => $candidat->prenom . ' ' . $candidat->nom . ' a signé le contrat de confidentialité et rejoint votre projet "' . $pub->titre . '".',
            'lien'            => route('messagerie.show', $conversation->id),
            'est_lue'         => false,
            'date_creation'   => $now,
        ]);
        try { broadcast(new \App\Events\NotificationCreee($notifAuteur)); } catch (\Throwable) {}

        // Emails avec PDF en pièce jointe
        try {
            \Illuminate\Support\Facades\Mail::to($candidat->email)
                ->send(new \App\Mail\CollabContratSigneCandidat($candidat, $pub, $chemin));
        } catch (\Throwable $e) { \Log::error('Mail contrat signe candidat: ' . $e->getMessage()); }

        try {
            \Illuminate\Support\Facades\Mail::to($auteur->email)
                ->send(new \App\Mail\CollabContratSigneAuteur($auteur, $candidat, $pub, $chemin));
        } catch (\Throwable $e) { \Log::error('Mail contrat signe auteur: ' . $e->getMessage()); }

        // Premier membre → mentor attribué immédiatement
        if ($projetCol->taille_equipe_actuelle == 1) {
            $this->attribuerMentorGroupe($pub, $projetCol, $conversation);
        }

        // Si équipe complète → phase en_cours
        if ($projetCol->taille_equipe_actuelle >= $projetCol->taille_equipe_max) {
            $projetCol->update(['phase' => 'en_cours']);
        }

        return redirect()->route('messagerie.show', $conversation->id)
            ->with('success', 'Contrat signé ! Bienvenue dans l\'équipe "' . $pub->titre . '".');
    }

    private function attribuerMentorGroupe(Publication $pub, ProjetCollaboratif $projetCol, Conversation $conversation): void
    {
        if ($projetCol->mentor_id) return;

        $mentor = AttribuerMentorJob::trouverMentorDisponible();

        if (!$mentor) {
            // Aucun mentor dispo → message système épinglé dans la conversation
            \App\Models\Message::create([
                'conversation_id' => $conversation->id,
                'expediteur_id'   => $pub->auteur_id,
                'contenu'         => '⏳ Un mentor vous sera attribué dans les meilleurs délais. Vous serez notifiés dès l\'attribution.',
                'date_envoi'      => now(),
                'est_systeme'     => true,
            ]);

            // Notifier l'admin
            $admin = Utilisateur::where('role', 'admin')->first();
            if ($admin) {
                $notif = Notification::create([
                    'destinataire_id' => $admin->id,
                    'type'            => 'mentor_attribue',
                    'contenu'         => 'Aucun mentor disponible pour "' . $pub->titre . '". Attribution requise.',
                    'lien'            => route('admin.dashboard'),
                    'est_lue'         => false,
                    'date_creation'   => now(),
                ]);
                try { broadcast(new \App\Events\NotificationCreee($notif)); } catch (\Throwable) {}
            }

            // Job différé : re-tenter dans 1 jour (au cas où un mentor se libère)
            AttribuerMentorJob::dispatch($pub->id)->delay(now()->addDay());
            return;
        }

        AttribuerMentorJob::attribuer($mentor, $projetCol, $pub, $conversation);
    }
}
