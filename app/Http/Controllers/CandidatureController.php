<?php

namespace App\Http\Controllers;

use App\Models\Candidature;
use App\Models\ContratNda;
use App\Models\MembreProjet;
use App\Models\Notification;
use App\Models\ProjetCollaboratif;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CandidatureController extends Controller
{
    public function store(Request $request)
    {
        try {
            $request->validate([
                'projet_collaboratif_id' => 'required',
                'poste_souhaite'         => 'required|string|max:100',
                'motivation'             => 'nullable|string|max:500',
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }

        $projet = ProjetCollaboratif::where('publication_id', $request->projet_collaboratif_id)->first();
        if ($projet && $projet->phase !== 'ouvert') {
            return response()->json(['success' => false, 'message' => 'Ce projet ne recrute plus.'], 422);
        }

        $existant = Candidature::where('projet_collaboratif_id', $request->projet_collaboratif_id)
            ->where('utilisateur_id', Auth::id())
            ->first();

        if ($existant) {
            return response()->json(['success' => false, 'message' => 'Vous avez déjà postulé à ce projet.'], 422);
        }

        $dejaMembe = MembreProjet::where('projet_collaboratif_id', $request->projet_collaboratif_id)
            ->where('utilisateur_id', Auth::id())
            ->exists();

        if ($dejaMembe) {
            return response()->json(['success' => false, 'message' => 'Vous êtes déjà membre de ce projet.'], 422);
        }

        Candidature::create([
            'projet_collaboratif_id' => $request->projet_collaboratif_id,
            'utilisateur_id'         => Auth::id(),
            'poste_souhaite'         => $request->poste_souhaite,
            'motivation'             => $request->motivation,
            'statut'                 => 'en_attente',
            'date_postulation'       => now(),
        ]);

        $pub = \App\Models\Publication::find($request->projet_collaboratif_id);
        if ($pub && $pub->auteur_id !== Auth::id()) {
            $notif = Notification::create([
                'destinataire_id' => $pub->auteur_id,
                'type'            => 'nouvelle_candidature',
                'contenu'         => Auth::user()->pseudo . ' a postulé pour rejoindre votre projet "' . $pub->titre . '".',
                'lien'            => route('dashboard.collaborations'),
                'est_lue'         => false,
                'date_creation'   => now(),
            ]);
            try { broadcast(new \App\Events\NotificationCreee($notif)); } catch (\Throwable) {}

            try {
                \Illuminate\Support\Facades\Mail::to($pub->auteur->email)
                    ->send(new \App\Mail\CollabCandidatureRecue($pub->auteur, Auth::user(), $pub));
            } catch (\Throwable) {}
        }

        return response()->json(['success' => true, 'message' => 'Candidature envoyée avec succès !']);
    }

    public function accepter($id)
    {
        $candidature = Candidature::findOrFail($id);
        $pub = \App\Models\Publication::with('auteur')->find($candidature->projet_collaboratif_id);

        if ($pub->auteur_id !== Auth::id()) abort(403);

        $candidature->update([
            'statut'       => 'acceptée',
            'date_reponse' => now(),
        ]);

        // Créer le contrat NDA et envoyer le lien au candidat
        $token = Str::random(64);
        $contrat = ContratNda::create([
            'candidature_id' => $candidature->id,
            'utilisateur_id' => $candidature->utilisateur_id,
            'publication_id' => $candidature->projet_collaboratif_id,
            'token'          => $token,
            'statut'         => 'en_attente',
        ]);

        $candidat = \App\Models\Utilisateur::find($candidature->utilisateur_id);
        $lienNda  = route('nda.show', $token);

        // Notifier le candidat
        $notif = Notification::create([
            'destinataire_id' => $candidature->utilisateur_id,
            'type'            => 'candidature_acceptee',
            'contenu'         => 'Votre candidature au projet "' . $pub->titre . '" a été acceptée ! Signez le contrat de confidentialité pour rejoindre l\'équipe.',
            'lien'            => $lienNda,
            'est_lue'         => false,
            'date_creation'   => now(),
        ]);
        try { broadcast(new \App\Events\NotificationCreee($notif)); } catch (\Throwable) {}

        try {
            \Illuminate\Support\Facades\Mail::to($candidat->email)
                ->send(new \App\Mail\CollabCandidatureAccepteeNda($candidat, $pub, $lienNda));
        } catch (\Throwable) {}

        return back()->with('success', 'Candidature acceptée. Le contrat de confidentialité a été envoyé à ' . $candidat->prenom . '.');
    }

    public function refuser($id)
    {
        $candidature = Candidature::findOrFail($id);
        $pub = \App\Models\Publication::find($candidature->projet_collaboratif_id);

        if ($pub->auteur_id !== Auth::id()) abort(403);

        $candidature->update([
            'statut'       => 'refusée',
            'date_reponse' => now(),
        ]);

        ContratNda::where('candidature_id', $candidature->id)->delete();

        $notif = Notification::create([
            'destinataire_id' => $candidature->utilisateur_id,
            'type'            => 'candidature_refusee',
            'contenu'         => 'Votre candidature au projet "' . $pub->titre . '" n\'a pas été retenue.',
            'lien'            => route('dashboard.collaborations'),
            'est_lue'         => false,
            'date_creation'   => now(),
        ]);
        try { broadcast(new \App\Events\NotificationCreee($notif)); } catch (\Throwable) {}

        return back()->with('success', 'Candidature refusée.');
    }

    public function retirer($id)
    {
        $candidature = Candidature::where('id', $id)
            ->where('utilisateur_id', Auth::id())
            ->firstOrFail();

        $estMembre = MembreProjet::where('projet_collaboratif_id', $candidature->projet_collaboratif_id)
            ->where('utilisateur_id', Auth::id())
            ->exists();

        if ($estMembre) {
            MembreProjet::where('projet_collaboratif_id', $candidature->projet_collaboratif_id)
                ->where('utilisateur_id', Auth::id())
                ->delete();

            ProjetCollaboratif::where('publication_id', $candidature->projet_collaboratif_id)
                ->decrement('taille_equipe_actuelle');
        }

        ContratNda::where('candidature_id', $candidature->id)->delete();
        $candidature->delete();

        return back()->with('success', 'Vous vous êtes retiré du projet.');
    }
}
