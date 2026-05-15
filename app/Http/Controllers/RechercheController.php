<?php

namespace App\Http\Controllers;

use App\Models\Publication;
use App\Models\Utilisateur;
use Illuminate\Http\Request;

class RechercheController extends Controller
{
    public function index(Request $request)
    {
        $q    = $request->get('q', '');
        $type = $request->get('type', 'all');

        $publications = collect();
        $utilisateurs = collect();

        if (strlen($q) >= 2) {
            $publications = Publication::with('auteur')
                ->where('statut', 'publié')
                ->where(function ($query) use ($q) {
                    $query->where('titre',   'like', "%{$q}%")
                        ->orWhere('contenu','like', "%{$q}%");
                })
                ->latest('date_publication')
                ->take(20)->get();

            $utilisateurs = Utilisateur::where('statut', 'actif')
                ->where(function ($query) use ($q) {
                    $query->where('pseudo',     'like', "%{$q}%")
                        ->orWhere('prenom',   'like', "%{$q}%")
                        ->orWhere('nom',      'like', "%{$q}%")
                        ->orWhere('specialite','like', "%{$q}%");
                })
                ->take(10)->get();
        }

        // AJAX → JSON
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'publications' => $publications,
                'utilisateurs' => $utilisateurs,
            ]);
        }

        return view('recherche.index', compact('publications', 'utilisateurs', 'q', 'type'));
    }
}