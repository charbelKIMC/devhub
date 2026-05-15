<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Utilisateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

class PasswordResetController extends Controller
{
    public function showForm()
    {
        return view('auth.forgot-password');
    }

    // Étape 1 : envoyer l'OTP par email
    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:utilisateurs,email',
        ], [
            'email.required' => 'L\'adresse email est obligatoire.',
            'email.exists'   => 'Aucun compte n\'est associé à cette adresse email.',
        ]);

        $otp = str_pad(random_int(0, 9999), 4, '0', STR_PAD_LEFT);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            ['token' => $otp, 'created_at' => now()]
        );

        try {
            Mail::to($request->email)->send(new \App\Mail\OtpResetPassword(
                $otp,
                Utilisateur::where('email', $request->email)->value('prenom')
            ));
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'message' => 'Impossible d\'envoyer l\'email. Veuillez réessayer.'], 500);
        }

        return response()->json(['success' => true]);
    }

    // Étape 2 : vérifier l'OTP
    public function verifierOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp'   => 'required|string|size:4',
        ]);

        $record = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$record) {
            return response()->json(['success' => false, 'message' => 'Code introuvable. Renvoyez un nouveau code.'], 422);
        }

        // Expiration : 2 minutes
        if (now()->diffInSeconds($record->created_at) > 120) {
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            return response()->json(['success' => false, 'message' => 'Code expiré. Veuillez en demander un nouveau.'], 422);
        }

        if ($record->token !== $request->otp) {
            return response()->json(['success' => false, 'message' => 'Code incorrect.'], 422);
        }

        // Marquer l'OTP comme vérifié (token passe à "verified_EMAIL")
        DB::table('password_reset_tokens')->where('email', $request->email)
            ->update(['token' => 'verified_' . md5($request->email), 'created_at' => now()]);

        return response()->json(['success' => true]);
    }

    // Étape 3 : nouveau mot de passe
    public function reinitialiser(Request $request)
    {
        $request->validate([
            'email'         => 'required|email|exists:utilisateurs,email',
            'mot_de_passe'  => 'required|string|min:8|same:confirmation',
            'confirmation'  => 'required|string',
        ], [
            'mot_de_passe.min'  => 'Le mot de passe doit contenir au moins 8 caractères.',
            'mot_de_passe.same' => 'Les mots de passe ne correspondent pas.',
        ]);

        $record = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('token', 'verified_' . md5($request->email))
            ->first();

        if (!$record) {
            return response()->json(['success' => false, 'message' => 'Session expirée. Recommencez depuis le début.'], 422);
        }

        Utilisateur::where('email', $request->email)
            ->update(['mot_de_passe' => Hash::make($request->mot_de_passe)]);

        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return response()->json(['success' => true]);
    }
}
