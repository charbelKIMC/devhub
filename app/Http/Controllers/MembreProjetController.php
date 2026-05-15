<?php

namespace App\Http\Controllers;

use App\Models\MembreProjet;
use App\Models\Candidature;
use App\Models\ProjetCollaboratif;
use Illuminate\Support\Facades\Auth;

class MembreProjetController extends Controller
{
    public function retirer($id)
    {
        $candidature = Candidature::where('id', $id)
                                  ->where('utilisateur_id', Auth::id())
                                  ->firstOrFail();

        MembreProjet::where('projet_collaboratif_id', $candidature->projet_collaboratif_id)
                    ->where('utilisateur_id', Auth::id())
                    ->delete();

        ProjetCollaboratif::where('publication_id', $candidature->projet_collaboratif_id)
                          ->decrement('taille_equipe_actuelle');

        $candidature->delete();

        return back()->with('success', 'Vous vous êtes retiré du projet.');
    }
}