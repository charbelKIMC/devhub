<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{
    public function showForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'prenom'       => ['required', 'string', 'max:50', 'regex:/^[\pL\s\-\']+$/u'],
            'nom'          => ['required', 'string', 'max:50', 'regex:/^[\pL\s\-\']+$/u'],
            'pseudo'       => ['required', 'string', 'regex:/^[a-zA-Z][a-zA-Z0-9_]{2,49}$/', 'unique:utilisateurs,pseudo'],
            'email'        => ['required', 'email:rfc', 'max:255', 'unique:utilisateurs,email'],
            'telephone'    => ['required', 'string', 'max:20', 'regex:/^\+?[0-9\s\-\(\)]{8,20}$/', 'unique:utilisateurs,telephone'],
            'mot_de_passe' => ['required', 'string', 'min:8', 'same:mot_de_passe_confirmation'],
        ], [
            'prenom.required'      => 'Le prénom est obligatoire.',
            'prenom.regex'         => 'Le prénom ne doit contenir que des lettres.',
            'nom.required'         => 'Le nom est obligatoire.',
            'nom.regex'            => 'Le nom ne doit contenir que des lettres.',
            'pseudo.required'      => 'Le pseudo est obligatoire.',
            'pseudo.regex'         => 'Le pseudo doit commencer par une lettre et contenir uniquement lettres, chiffres ou _ (min. 3 caractères).',
            'pseudo.unique'        => 'Ce pseudo est déjà utilisé, veuillez en choisir un autre.',
            'email.required'       => 'L\'adresse email est obligatoire.',
            'email.email'          => 'Le format de l\'adresse email n\'est pas valide.',
            'email.unique'         => 'Cette adresse email est déjà associée à un compte.',
            'telephone.required'   => 'Le numéro de téléphone est obligatoire.',
            'telephone.regex'      => 'Le numéro de téléphone n\'est pas valide (ex: +229 01 XX XX XX XX).',
            'telephone.unique'     => 'Ce numéro de téléphone est déjà associé à un compte.',
            'mot_de_passe.required'=> 'Le mot de passe est obligatoire.',
            'mot_de_passe.min'     => 'Le mot de passe doit contenir au moins 8 caractères.',
            'mot_de_passe.same'    => 'Les mots de passe ne correspondent pas.',
        ]);

        // Vérification DNS : le domaine de l'email doit exister réellement
        $domain = substr(strrchr($request->email, '@'), 1);
        $domainValide = false;
        try {
            $domainValide = checkdnsrr($domain, 'MX') || checkdnsrr($domain, 'A');
        } catch (\Throwable) {
            $domainValide = false;
        }

        if (!$domainValide) {
            throw ValidationException::withMessages([
                'email' => 'Cette adresse email n\'est pas valide. Veuillez renseigner un email réel.',
            ]);
        }

        // Supprimer une éventuelle ancienne tentative non confirmée
        DB::table('inscriptions_en_attente')->where('email', $request->email)->delete();

        // Générer un token sécurisé
        $token = bin2hex(random_bytes(32));

        // Stocker les données en attente (mot de passe déjà haché)
        DB::table('inscriptions_en_attente')->insert([
            'token'      => $token,
            'email'      => $request->email,
            'donnees'    => encrypt(json_encode([
                'prenom'       => $request->prenom,
                'nom'          => $request->nom,
                'pseudo'       => $request->pseudo,
                'email'        => $request->email,
                'telephone'    => $request->telephone,
                'mot_de_passe' => Hash::make($request->mot_de_passe),
            ])),
            'expire_a'   => now()->addHours(24),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Envoyer l'email de vérification
        try {
            Mail::send('emails.verification-email', [
                'prenom' => $request->prenom,
                'lien'   => route('email.verify', ['token' => $token]),
            ], function ($mail) use ($request) {
                $mail->to($request->email)
                     ->subject('Confirmez votre adresse email – DevHub');
            });
        } catch (\Throwable $e) {
            \Log::error('Email vérification échoué : ' . $e->getMessage());
        }

        return redirect()->route('email.verification.sent')->with('email', $request->email);
    }
}
