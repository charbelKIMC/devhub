<?php

namespace App\Http\Controllers;

use App\Models\Competence;
use App\Models\Notification;
use App\Models\Utilisateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;

class ProfilController extends Controller
{
    public function setupShow()
    {
        if (Auth::user()->profil_complete) {
            return redirect()->route('dashboard');
        }

        $competences = Competence::all()->groupBy('categorie');
        return view('auth.setup-profil', compact('competences'));
    }
    public function setupStore(Request $request)
    {
        $specialitesAutorisees = [
            'Développeur Frontend', 'Développeur Backend', 'Développeur Full-Stack',
            'Développeur Mobile', 'Data Scientist', 'DevOps / Cloud', 'UI/UX Designer',
            'Cybersécurité', 'Intelligence Artificielle', 'Étudiant en informatique', 'Autre',
        ];

        $request->validate([
            'specialite'  => ['nullable', 'string', 'in:' . implode(',', $specialitesAutorisees)],
            'bio'         => ['nullable', 'string', 'max:300'],
            'localisation'=> ['nullable', 'string', 'max:100', 'regex:/^[\pL0-9\s\,\.\-\']+$/u'],
            'github'      => ['nullable', 'url', 'max:255', 'regex:/^https:\/\/(www\.)?github\.com\/.+/'],
            'linkedin'    => ['nullable', 'url', 'max:255', 'regex:/^https:\/\/(www\.)?linkedin\.com\/.+/'],
            'portfolio'   => ['nullable', 'url', 'max:255'],
            'photo'       => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'competences' => ['nullable', 'array'],
            'competences.*' => ['integer', 'exists:competences,id'],
        ], [
            'specialite.in'          => 'Veuillez choisir une spécialité dans la liste.',
            'bio.max'                => 'La biographie ne peut pas dépasser 300 caractères.',
            'localisation.regex'     => 'La localisation contient des caractères non autorisés.',
            'github.url'             => 'Le lien GitHub n\'est pas une URL valide.',
            'github.regex'           => 'Le lien doit pointer vers github.com.',
            'linkedin.url'           => 'Le lien LinkedIn n\'est pas une URL valide.',
            'linkedin.regex'         => 'Le lien doit pointer vers linkedin.com.',
            'portfolio.url'          => 'Le lien portfolio n\'est pas une URL valide.',
            'photo.image'            => 'Le fichier doit être une image.',
            'photo.mimes'            => 'Seuls les formats JPG et PNG sont acceptés.',
            'photo.max'              => 'La photo ne doit pas dépasser 2 MB.',
            'competences.*.exists'   => 'Une compétence sélectionnée est invalide.',
        ]);

        $utilisateur = Auth::user();

        $data = $request->only(['specialite', 'bio', 'localisation', 'github', 'linkedin', 'portfolio']);

        // Photo
        if ($request->hasFile('photo')) {
            $data['photo'] = $this->uploadPhoto($request->file('photo'));
        }

        // Calcul pourcentage complétion
        $score = 0;
        if (!empty($data['specialite']))   $score += 20;
        if (!empty($data['bio']))          $score += 20;
        if (!empty($data['localisation'])) $score += 10;
        if (!empty($data['github']))       $score += 15;
        if (!empty($data['linkedin']))     $score += 15;
        if (!empty($data['photo']))        $score += 20;

        $data['pourcentage_completude'] = $score;
        $data['profil_complete'] = true;

        $utilisateur->update($data);

        // Compétences
        if ($request->has('competences')) {
            $competences = [];
            foreach ($request->competences as $id) {
                $competences[$id] = ['niveau_maitrise' => 'débutant'];
            }
            $utilisateur->competences()->sync($competences);
        }

        // Notification de bienvenue en base
        $notif = Notification::create([
            'destinataire_id' => $utilisateur->id,
            'type'            => 'bienvenue',
            'contenu'         => 'Bienvenue sur DevHub ' . $utilisateur->prenom . ' ! Votre profil est maintenant configuré.',
            'lien'            => route('dashboard'),
            'est_lue'         => false,
            'date_creation'   => now(),
        ]);

        // Broadcast temps réel via Pusher
        try {
            broadcast(new \App\Events\NotificationCreee($notif));
        } catch (\Throwable) {}

        // Email de bienvenue
        try {
            Mail::to($utilisateur->email)->send(new \App\Mail\BienvenueDevHub($utilisateur));
        } catch (\Throwable $e) { \Log::error('Mail bienvenue: ' . $e->getMessage()); }

        return redirect()->route('welcome.splash');
    }

    public function show($id)
    {
        $utilisateur = Utilisateur::with(['publications', 'competences'])->findOrFail($id);
        $toutesCompetences = Competence::all()->groupBy('categorie');
        return view('profil.show', compact('utilisateur', 'toutesCompetences'));
    }

    public function edit()
    {
        $competences = Competence::all()->groupBy('categorie');
        return view('profil.edit', compact('competences'));
    }

    public function update(Request $request)
    {
        $utilisateur = Auth::user();

        $data = $request->only(['specialite', 'bio', 'localisation', 'github', 'linkedin', 'portfolio', 'prenom', 'nom']);

        if ($request->hasFile('photo')) {
            if ($utilisateur->photo) {
                $this->deletePhoto($utilisateur->photo);
            }
            $data['photo'] = $this->uploadPhoto($request->file('photo'));
        }

        $utilisateur->update($data);

        // Compétences
        if ($request->has('competences')) {
            $sync = collect($request->competences)->mapWithKeys(fn($id) => [$id => ['niveau_maitrise' => 'débutant']])->toArray();
            $utilisateur->competences()->sync($sync);
        } else {
            $utilisateur->competences()->detach();
        }

        return redirect()->route('profil.show', $utilisateur->id)
            ->with('success', 'Profil mis à jour avec succès.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'mot_de_passe'     => 'required|string|min:8|same:mot_de_passe_confirmation',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->mot_de_passe)) {
            return back()->withErrors(['current_password' => 'Mot de passe actuel incorrect.']);
        }

        $user->update(['mot_de_passe' => Hash::make($request->mot_de_passe)]);

        return redirect()->route('profil.show', $user->id)
            ->with('success', 'Mot de passe mis à jour avec succès.');
    }

    private function uploadPhoto($file): string
    {
        $cloudName = env('CLOUDINARY_CLOUD_NAME');
        $preset    = env('CLOUDINARY_UPLOAD_PRESET');

        if ($cloudName && $preset) {
            try {
                $response = \Illuminate\Support\Facades\Http::attach(
                    'file',
                    file_get_contents($file->getRealPath()),
                    $file->getClientOriginalName()
                )->post("https://api.cloudinary.com/v1_1/{$cloudName}/image/upload", [
                    'upload_preset' => $preset,
                ]);

                if ($response->successful() && $response->json('secure_url')) {
                    return $response->json('secure_url');
                }
                \Log::error('Cloudinary upload failed: ' . $response->body());
            } catch (\Throwable $e) {
                \Log::error('Cloudinary upload exception: ' . $e->getMessage());
            }
        }

        return $file->store('photos', 'public');
    }

    private function deletePhoto(string $photo): void
    {
        if (!str_starts_with($photo, 'http')) {
            Storage::disk('public')->delete($photo);
        }
    }

    // Liste des développeurs pour la page publique
    public function liste()
    {
        $developpeurs = Utilisateur::where('statut', 'actif')
            ->whereIn('role', ['développeur', 'mentor'])
            ->latest('date_inscription')
            ->paginate(12);

        return view('developpeurs', compact('developpeurs'));
    }

    public function profilPublic($id)
    {
        $utilisateur = Utilisateur::with(['publications', 'competences'])->findOrFail($id);
        if ($utilisateur->statut !== 'actif') abort(404);
        return view('profil.show-profil', compact('utilisateur'));
    }
}
