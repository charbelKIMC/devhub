<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Utilisateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Notification;

class LoginController extends Controller
{
    // Affiche le formulaire de connexion
    public function showForm()
    {
        return view('auth.login');
    }

    // Traite la connexion
    public function login(Request $request)
    {
        $request->validate([
            'email'        => 'required|email',
            'mot_de_passe' => 'required|string',
        ], [
            'email.required'        => 'L\'adresse email est obligatoire.',
            'email.email'           => 'L\'adresse email n\'est pas valide.',
            'mot_de_passe.required' => 'Le mot de passe est obligatoire.',
        ]);

        // Cherche l'utilisateur
        $utilisateur = Utilisateur::where('email', $request->email)->first();

        // Vérifie le mot de passe
        if (!$utilisateur || !Hash::check($request->mot_de_passe, $utilisateur->mot_de_passe)) {
            return back()->withErrors([
                'email' => 'Email ou mot de passe incorrect.',
            ])->withInput($request->only('email'));
        }

        // Vérifie si le compte est actif
        if ($utilisateur->statut === 'suspendu') {
            return back()->withErrors([
                'email' => 'Votre compte est suspendu. Contactez l\'administrateur.',
            ]);
        }

        // Connexion
        Auth::login($utilisateur, $request->boolean('remember'));

        // Mise à jour de la dernière connexion
        $utilisateur->update(['derniere_connexion' => now()]);

        // Notification de connexion
        $notif = Notification::create([
            'destinataire_id' => $utilisateur->id,
            'type'            => 'connexion',
            'contenu'         => 'Connexion réussie. Bon retour sur DevHub ' . $utilisateur->prenom . ' !',
            'lien'            => route('dashboard'),
            'est_lue'         => false,
            'date_creation'   => now(),
        ]);

        try { broadcast(new \App\Events\NotificationCreee($notif)); } catch (\Throwable) {}

        // Notification si profil développeur incomplet (une seule fois non lue)
        if (!$utilisateur->isAdmin() && !$utilisateur->isMentor() && !$utilisateur->profil_complete) {
            $dejaNotifie = Notification::where('destinataire_id', $utilisateur->id)
                ->where('type', 'profil_incomplet')
                ->where('est_lue', false)
                ->exists();
            if (!$dejaNotifie) {
                $notifProfil = Notification::create([
                    'destinataire_id' => $utilisateur->id,
                    'type'            => 'profil_incomplet',
                    'contenu'         => 'Votre profil est incomplet. Complétez-le pour profiter de toutes les fonctionnalités DevHub.',
                    'lien'            => route('profil.setup'),
                    'est_lue'         => false,
                    'date_creation'   => now(),
                ]);
                try { broadcast(new \App\Events\NotificationCreee($notifProfil)); } catch (\Throwable) {}
            }
        }

        return redirect()->route('welcome.splash');
    }

    // Déconnexion
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}