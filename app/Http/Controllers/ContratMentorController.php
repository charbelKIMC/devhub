<?php

namespace App\Http\Controllers;

use App\Mail\AdminMentorContratSigne;
use App\Mail\MentorContratSigne;
use App\Models\ContratMentor;
use App\Models\Utilisateur;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class ContratMentorController extends Controller
{
    public function show()
    {
        $mentor = Auth::user();

        if (!$mentor->isMentor()) {
            abort(403);
        }

        $contrat = ContratMentor::firstOrCreate(
            ['mentor_id' => $mentor->id],
            ['statut'    => 'en_attente']
        );

        if ($contrat->statut === 'signé') {
            return redirect()->route('mentor.espace');
        }

        return view('contrats.mentor-signer', compact('mentor', 'contrat'));
    }

    public function signer(Request $request)
    {
        $mentor = Auth::user();

        if (!$mentor->isMentor()) {
            abort(403);
        }

        $request->validate([
            'accord' => 'required|accepted',
        ], [
            'accord.required' => 'Vous devez lire et accepter les termes du contrat.',
            'accord.accepted'  => 'Vous devez cocher la case pour confirmer votre engagement.',
        ]);

        $contrat = ContratMentor::where('mentor_id', $mentor->id)
            ->where('statut', 'en_attente')
            ->firstOrFail();

        $now = now();

        // Génération PDF (non bloquante)
        $pdfContenu = null;
        $chemin     = null;
        try {
            $pdf = Pdf::loadView('contrats.mentor-pdf', [
                'mentor'        => $mentor,
                'contrat'       => $contrat,
                'dateSignature' => $now,
                'ipSignature'   => $request->ip(),
            ])->setPaper('a4', 'portrait');

            $pdfContenu = $pdf->output();
            $filename   = 'contrat_mentor_' . $mentor->id . '_' . $now->format('Ymd_His') . '.pdf';
            $chemin     = 'contrats_mentor/' . $filename;
            Storage::disk('public')->put($chemin, $pdfContenu);
        } catch (\Throwable $e) {
            Log::error('PDF contrat mentor: ' . $e->getMessage());
        }

        $contrat->update([
            'statut'       => 'signé',
            'chemin_pdf'   => $chemin,
            'ip_signature' => $request->ip(),
            'signe_le'     => $now,
        ]);

        // Email au mentor avec PDF
        try {
            if ($pdfContenu) {
                Mail::to($mentor->email)->send(new MentorContratSigne($mentor, $pdfContenu));
            }
        } catch (\Throwable $e) {
            Log::error('Mail contrat mentor signe mentor: ' . $e->getMessage());
        }

        // Email à l'admin avec PDF
        $admin = Utilisateur::where('role', 'admin')->first();
        if ($admin) {
            try {
                if ($pdfContenu) {
                    Mail::to($admin->email)->send(new AdminMentorContratSigne($mentor, $pdfContenu));
                }
            } catch (\Throwable $e) {
                Log::error('Mail contrat mentor signe admin: ' . $e->getMessage());
            }
        }

        return redirect()->route('mentor.espace')
            ->with('success', 'Contrat signé ! Bienvenue sur DevHub, ' . $mentor->prenom . ' !');
    }
}
