<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Utilisateur;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class MentorActivationController extends Controller
{
    public function showSetPassword(string $token)
    {
        $record = DB::table('mentors_en_attente_activation')->where('token', $token)->first();

        if (!$record) {
            return redirect()->route('home')->withErrors([
                'token' => 'Ce lien d\'activation est invalide.',
            ]);
        }

        if (now()->gt(\Carbon\Carbon::parse($record->expire_a))) {
            DB::table('mentors_en_attente_activation')->where('token', $token)->delete();
            return redirect()->route('home')->withErrors([
                'token' => 'Ce lien d\'activation a expiré (72h). Veuillez contacter DevHub.',
            ]);
        }

        $donnees = json_decode(decrypt($record->donnees), true);

        return view('auth.mentor-set-password', [
            'token'  => $token,
            'prenom' => $donnees['prenom'],
            'email'  => $donnees['email'],
        ]);
    }

    public function activate(Request $request, string $token)
    {
        $record = DB::table('mentors_en_attente_activation')->where('token', $token)->first();

        if (!$record) {
            return redirect()->route('home')->withErrors(['token' => 'Lien invalide.']);
        }

        if (now()->gt(\Carbon\Carbon::parse($record->expire_a))) {
            DB::table('mentors_en_attente_activation')->where('token', $token)->delete();
            return redirect()->route('home')->withErrors(['token' => 'Lien expiré. Contactez DevHub.']);
        }

        $request->validate([
            'mot_de_passe'              => ['required', 'string', 'min:8', 'same:mot_de_passe_confirmation'],
            'mot_de_passe_confirmation' => ['required', 'string'],
        ], [
            'mot_de_passe.required'     => 'Le mot de passe est obligatoire.',
            'mot_de_passe.min'          => 'Le mot de passe doit contenir au moins 8 caractères.',
            'mot_de_passe.same'         => 'Les mots de passe ne correspondent pas.',
        ]);

        $donnees = json_decode(decrypt($record->donnees), true);

        // Générer un pseudo unique
        $base   = Str::slug($donnees['prenom'] . '.' . $donnees['nom']);
        $pseudo = $base;
        $i = 1;
        while (Utilisateur::where('pseudo', $pseudo)->exists()) {
            $pseudo = $base . $i++;
        }

        // Créer le compte mentor
        $mentor = Utilisateur::create([
            'prenom'           => $donnees['prenom'],
            'nom'              => $donnees['nom'],
            'email'            => $donnees['email'],
            'telephone'        => $donnees['telephone'],
            'pseudo'           => $pseudo,
            'mot_de_passe'     => Hash::make($request->mot_de_passe),
            'role'             => 'mentor',
            'statut'           => 'actif',
            'specialite'       => $donnees['specialite'],
            'localisation'     => $donnees['localisation'],
            'portfolio'        => $donnees['portfolio'] ?? null,
            'linkedin'         => $donnees['linkedin'] ?? null,
            'profil_complete'  => false,
            'date_inscription' => now(),
        ]);

        // Supprimer le token
        DB::table('mentors_en_attente_activation')->where('token', $token)->delete();

        // Créer le contrat mentor en attente de signature
        \App\Models\ContratMentor::create([
            'mentor_id' => $mentor->id,
            'statut'    => 'en_attente',
        ]);

        // Connexion automatique
        Auth::login($mentor);
        $request->session()->regenerate();

        // Email de confirmation
        try {
            Mail::send('emails.mentor-compte-cree', ['mentor' => $mentor], function ($mail) use ($mentor) {
                $mail->to($mentor->email)->subject('Votre compte mentor est activé – DevHub');
            });
        } catch (\Throwable $e) { Log::error('Mail mentor compte cree: ' . $e->getMessage()); }

        return redirect()->route('mentor.contrat.show');
    }
}
