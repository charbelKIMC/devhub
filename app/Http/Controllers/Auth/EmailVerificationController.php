<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Utilisateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EmailVerificationController extends Controller
{
    public function showSent()
    {
        return view('auth.email-verification-sent');
    }

    public function verify(Request $request, string $token)
    {
        $inscription = DB::table('inscriptions_en_attente')->where('token', $token)->first();

        if (!$inscription) {
            return redirect()->route('register')->withErrors([
                'email' => 'Ce lien de vérification est invalide.',
            ]);
        }

        if (now()->gt(\Carbon\Carbon::parse($inscription->expire_a))) {
            DB::table('inscriptions_en_attente')->where('token', $token)->delete();
            return redirect()->route('register')->withErrors([
                'email' => 'Ce lien de vérification a expiré. Veuillez vous inscrire à nouveau.',
            ]);
        }

        $donnees = json_decode(decrypt($inscription->donnees), true);

        // Créer le compte
        $utilisateur = Utilisateur::create([
            'prenom'       => $donnees['prenom'],
            'nom'          => $donnees['nom'],
            'pseudo'       => $donnees['pseudo'],
            'email'        => $donnees['email'],
            'telephone'    => $donnees['telephone'],
            'mot_de_passe' => $donnees['mot_de_passe'],
            'role'         => 'développeur',
            'statut'       => 'actif',
        ]);

        // Supprimer l'inscription en attente
        DB::table('inscriptions_en_attente')->where('token', $token)->delete();

        // Connexion automatique + régénération de session (sécurité)
        Auth::login($utilisateur);
        $request->session()->regenerate();

        return redirect()->route('profil.setup');
    }
}
