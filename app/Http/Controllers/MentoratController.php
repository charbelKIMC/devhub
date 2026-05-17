<?php

namespace App\Http\Controllers;

use App\Models\RelationMentorat;
use App\Models\Utilisateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class MentoratController extends Controller
{
    // ─── Demander un mentor (voie développeur) ───
    public function showDemanderMentor()
    {
        $mentors = Utilisateur::where('role', 'mentor')
                              ->where('statut', 'actif')
                              ->get();

        $dejaRelation = RelationMentorat::where('mentore_id', Auth::id())
                                        ->where('statut', 'active')
                                        ->exists();

        return view('mentorat.demander', compact('mentors', 'dejaRelation'));
    }

    public function demanderMentor(Request $request)
    {
        $request->validate([
            'mentor_id'  => 'required|exists:utilisateurs,id',
            'motivation' => 'required|string|min:30|max:500',
        ], [
            'motivation.required' => 'Veuillez expliquer pourquoi vous souhaitez un mentor.',
            'motivation.min'      => 'Votre motivation doit contenir au moins 30 caractères.',
        ]);

        // Vérifier si déjà un mentor actif
        $dejaRelation = RelationMentorat::where('mentore_id', Auth::id())
            ->where('statut', 'active')
            ->exists();

        if ($dejaRelation) {
            return back()->withErrors(['mentor' => 'Vous avez déjà un mentor actif.']);
        }

        // Vérifier si déjà en attente
        if (Auth::user()->en_attente_mentor) {
            return back()->withErrors(['mentor' => 'Vous avez déjà une demande en cours de traitement.']);
        }

        // Enregistrer la demande
        Auth::user()->update([
            'en_attente_mentor'  => 1,
            'motivation_mentor'  => $request->motivation,
        ]);

        // Récupérer le mentor souhaité
        $mentorSouhaite = Utilisateur::find($request->mentor_id);

        // Envoyer un EMAIL à l'admin
        $admin = Utilisateur::where('role', 'admin')->first();
        if ($admin) {
            try {
                \Illuminate\Support\Facades\Mail::to($admin->email)
                    ->send(new \App\Mail\DemandeMentorAdmin(Auth::user(), $mentorSouhaite));
            } catch (\Exception $e) {
                // Ne pas bloquer si email échoue
            }
        }

        return back()->with('success', 'Votre demande a été transmise à l\'administrateur. Vous serez notifié dès qu\'un mentor vous sera attribué.');
    }

    // ─── Espace mentor ───
    public function espaceMentor()
    {
        $user = Auth::user();

        if (!$user->isMentor()) {
            abort(403);
        }

        $mentores = RelationMentorat::with('mentore')
                                    ->where('mentor_id', $user->id)
                                    ->where('statut', 'active')
                                    ->get();

        return view('mentorat.espace', compact('mentores'));
    }

    // ─── Candidature externe ───
    public function showCandidatureExterne()
    {
        return view('mentorat.candidature-externe');
    }

    public function candidatureExterne(Request $request)
    {
        $specialitesAutorisees = [
            'Développeur Frontend','Développeur Backend','Développeur Full-Stack',
            'Développeur Mobile','Data Scientist','DevOps / Cloud','UI/UX Designer',
            'Cybersécurité','Intelligence Artificielle','Architecte logiciel','Autre',
        ];
        $anneesAutorisees = ['1-3 ans','3-5 ans','5-10 ans','10+ ans'];
        $typesIdentite    = ['cni','passeport','permis','autre'];

        $request->validate([
            'prenom'            => ['required','string','max:50','regex:/^[\pL\s\-\']+$/u'],
            'nom'               => ['required','string','max:50','regex:/^[\pL\s\-\']+$/u'],
            'email'             => ['required','email:rfc','max:255','unique:utilisateurs,email',
                                    Rule::unique('candidatures_mentor_externe','email')->where(fn($q) => $q->where('statut','!=','refusée'))],
            'telephone'         => ['required','string','max:20','regex:/^\+?[0-9\s\-\(\)]{8,20}$/'],
            'localisation'      => ['required','string','max:100','regex:/^[\pL0-9\s\,\.\-]+$/u'],
            'specialite'        => ['required','string','in:'.implode(',', $specialitesAutorisees)],
            'annees_experience' => ['required','string','in:'.implode(',', $anneesAutorisees)],
            'experience'        => ['required','string','min:50','max:2000'],
            'portfolio'         => ['required','url','max:255'],
            'linkedin'          => ['required','url','max:255','regex:/^https:\/\/(www\.)?linkedin\.com\/.+/'],
            'motivation'        => ['required','string','min:100','max:1500'],
            'references'        => ['required','string','min:20','max:1000'],
            'type_identite'     => ['required','string','in:'.implode(',', $typesIdentite)],
            'numero_identite'   => ['required','string','max:50','regex:/^[A-Za-z0-9\-\s]+$/'],
            'cv'                => ['required','file','mimes:pdf,doc,docx','max:5120'],
            'piece_identite'    => ['required','file','mimes:pdf,jpg,jpeg,png','max:5120'],
        ], [
            'prenom.regex'            => 'Le prénom ne doit contenir que des lettres.',
            'nom.regex'               => 'Le nom ne doit contenir que des lettres.',
            'email.unique'            => 'Une candidature active existe déjà avec cet email.',
            'telephone.regex'         => 'Le numéro de téléphone n\'est pas valide.',
            'localisation.regex'      => 'La localisation contient des caractères non autorisés.',
            'specialite.in'           => 'Veuillez choisir une spécialité dans la liste.',
            'annees_experience.in'    => 'Veuillez choisir une tranche d\'expérience valide.',
            'experience.min'          => 'Le résumé de parcours doit contenir au moins 50 caractères.',
            'linkedin.regex'          => 'Le lien doit pointer vers linkedin.com.',
            'motivation.min'          => 'La motivation doit contenir au moins 100 caractères.',
            'references.min'          => 'Veuillez fournir au moins une référence avec un contact.',
            'numero_identite.regex'   => 'Le numéro de pièce ne doit contenir que des lettres et chiffres.',
            'cv.mimes'                => 'Le CV doit être au format PDF, DOC ou DOCX.',
            'piece_identite.mimes'    => 'La pièce d\'identité doit être au format PDF, JPG ou PNG.',
        ]);

        // Supprimer l'ancienne candidature refusée si le candidat retente
        \App\Models\CandidatureMentorExterne::where('email', $request->email)
            ->where('statut', 'refusée')
            ->delete();

        // Sauvegarde des fichiers
        $cheminCv       = $request->file('cv')->store('candidatures/cv', 'public');
        $cheminIdentite = $request->file('piece_identite')->store('candidatures/identites', 'public');

        // Sauvegarde en base
        $candidature = \App\Models\CandidatureMentorExterne::create([
            'prenom'            => $request->prenom,
            'nom'               => $request->nom,
            'email'             => $request->email,
            'telephone'         => $request->telephone,
            'localisation'      => $request->localisation,
            'specialite'        => $request->specialite,
            'annees_experience' => $request->annees_experience,
            'experience'        => $request->experience,
            'portfolio'         => $request->portfolio,
            'linkedin'          => $request->linkedin,
            'motivation'        => $request->motivation,
            'references'        => $request->references,
            'type_identite'     => $request->type_identite,
            'numero_identite'   => $request->numero_identite,
            'chemin_cv'         => $cheminCv,
            'chemin_identite'   => $cheminIdentite,
            'statut'            => 'en_attente',
        ]);

        // Email de confirmation au candidat
        try {
            \Illuminate\Support\Facades\Mail::send('emails.candidature-recue', [
                'prenom' => $request->prenom,
            ], function ($mail) use ($request) {
                $mail->to($request->email)
                     ->subject('Candidature mentor reçue – DevHub');
            });
        } catch (\Throwable $e) { \Log::error('Mail candidat mentor: ' . $e->getMessage()); }

        // Email uniquement à l'admin (pas de notification in-app)
        $admin = Utilisateur::where('role', 'admin')->first();
        if ($admin) {
            try {
                \Illuminate\Support\Facades\Mail::send('emails.admin-nouvelle-candidature', [
                    'candidature' => $candidature,
                    'lien'        => route('admin.candidatures.externes'),
                ], function ($mail) use ($admin) {
                    $mail->to($admin->email)
                         ->subject('Nouvelle candidature mentor – DevHub');
                });
            } catch (\Throwable $e) { \Log::error('Mail admin candidature mentor: ' . $e->getMessage()); }
        }

        return back()->with('success', 'Votre candidature a bien été enregistrée. Un email de confirmation vous a été envoyé. Notre équipe vous contactera dans 5 à 10 jours ouvrables.');
    }
}