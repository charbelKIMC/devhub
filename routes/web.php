

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\MentorActivationController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\PublicationController;
use App\Http\Controllers\CandidatureController;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\CommentaireController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\FavoriController;
use App\Http\Controllers\AvisController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\MentoratController;
use App\Http\Controllers\RechercheController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MembreProjetController;
use App\Http\Controllers\ProjetPersonnelController;
use App\Http\Controllers\ContratNdaController;

// ─── Page d'accueil ───────────────────────────────────────────
Route::get('/', function () {
    return view('welcome');
})->name('home');

// ─── Authentification (visiteurs non connectés) ──────────────
Route::middleware('guest')->group(function () {
    Route::get('/inscription',  [RegisterController::class, 'showForm'])->name('register');
    Route::post('/inscription', [RegisterController::class, 'register']);

    Route::get('/connexion',    [LoginController::class, 'showForm'])->name('login');
    Route::post('/connexion',   [LoginController::class, 'login']);

    Route::get('/verification-email-envoye', [EmailVerificationController::class, 'showSent'])->name('email.verification.sent');

    Route::get('/mot-de-passe-oublie',              [PasswordResetController::class, 'showForm'])->name('password.request');
    Route::post('/mot-de-passe-oublie/envoyer-otp', [PasswordResetController::class, 'sendOtp'])->name('password.send-otp');
    Route::post('/mot-de-passe-oublie/verifier-otp',[PasswordResetController::class, 'verifierOtp'])->name('password.verify-otp');
    Route::post('/mot-de-passe-oublie/reinitialiser',[PasswordResetController::class, 'reinitialiser'])->name('password.reinitialiser');
});

// ─── Déconnexion ─────────────────────────────────────────────
Route::post('/deconnexion', [LoginController::class, 'logout'])->name('logout');

// ─── Vérification email (accessible sans session) ────────────
Route::get('/verifier-email/{token}', [EmailVerificationController::class, 'verify'])->name('email.verify');

// ─── Activation compte mentor (accessible sans session) ──────
Route::get('/mentor/activation/{token}',  [MentorActivationController::class, 'showSetPassword'])->name('mentor.activation');
Route::post('/mentor/activation/{token}', [MentorActivationController::class, 'activate'])->name('mentor.activation.store');

// ─── Routes publiques (visiteurs) ────────────────────────────
Route::get('/publications/{id}/lire', [PublicationController::class, 'showPublic'])->name('publications.public');
Route::get('/explorer',               [PublicationController::class, 'explorer'])->name('explorer');
Route::get('/developpeurs',           [ProfilController::class, 'liste'])->name('developpeurs');
Route::get('/developpeurs/{id}',      [ProfilController::class, 'profilPublic'])->name('profil.public');
Route::get('/devenir-mentor',         [MentoratController::class, 'showCandidatureExterne'])->name('mentor.externe');
Route::post('/devenir-mentor',        [MentoratController::class, 'candidatureExterne'])->name('mentor.externe.store');

// ─── Routes authentifiées — Développeur/Mentor (pas admin) ───
Route::middleware(['auth', 'not.admin', 'not.mentor'])->group(function () {

    Route::get('/dashboard', [PublicationController::class, 'feed'])->name('dashboard');

    // Dashboard sections
    Route::get('/dashboard/publications',   [DashboardController::class, 'publications'])->name('dashboard.publications');
    Route::get('/dashboard/collaborations', [DashboardController::class, 'collaborations'])->name('dashboard.collaborations');
    Route::get('/dashboard/avis',           [DashboardController::class, 'avis'])->name('dashboard.avis');
    Route::get('/dashboard/statistiques',   [DashboardController::class, 'statistiques'])->name('dashboard.statistiques');
    Route::get('/dashboard/favoris',        [DashboardController::class, 'favoris'])->name('dashboard.favoris');

    // Setup profil après inscription
    Route::get('/setup-profil',  [ProfilController::class, 'setupShow'])->name('profil.setup');
    Route::post('/setup-profil', [ProfilController::class, 'setupStore'])->name('profil.setup.store');

    // Profil
    Route::get('/profil/modifier',      [ProfilController::class, 'edit'])->name('profil.edit');
    Route::put('/profil/modifier',      [ProfilController::class, 'update'])->name('profil.update');
    Route::post('/profil/mot-de-passe', [ProfilController::class, 'updatePassword'])->name('password.update');

    // Publications
    Route::get('/publications',           [PublicationController::class, 'index'])->name('publications.index');
    Route::get('/publications/creer',     [PublicationController::class, 'create'])->name('publications.create');
    Route::post('/publications',          [PublicationController::class, 'store'])->name('publications.store');
    Route::get('/publications/{id}',      [PublicationController::class, 'show'])->name('publications.show');
    Route::get('/publications/{id}/edit', [PublicationController::class, 'edit'])->name('publications.edit');
    Route::put('/publications/{id}',      [PublicationController::class, 'update'])->name('publications.update');
    Route::delete('/publications/{id}',   [PublicationController::class, 'destroy'])->name('publications.destroy');

    // Candidatures collaboratives
    Route::post('/candidatures',                [CandidatureController::class, 'store'])->name('candidatures.store');
    Route::put('/candidatures/{id}/accepter',   [CandidatureController::class, 'accepter'])->name('candidatures.accepter');
    Route::put('/candidatures/{id}/refuser',    [CandidatureController::class, 'refuser'])->name('candidatures.refuser');
    Route::delete('/candidatures/{id}/retirer', [MembreProjetController::class, 'retirer'])->name('collaborations.retirer');

    // Interactions
    Route::post('/likes/{publication_id}',   [LikeController::class, 'toggle'])->name('likes.toggle');
    Route::post('/favoris/{publication_id}', [FavoriController::class, 'toggle'])->name('favoris.toggle');
    Route::post('/commentaires',             [CommentaireController::class, 'store'])->name('commentaires.store');
    Route::delete('/commentaires/{id}',      [CommentaireController::class, 'destroy'])->name('commentaires.destroy');
    Route::post('/avis',                     [AvisController::class, 'store'])->name('avis.store');

    // Notifications
    Route::get('/notifications',             [NotificationController::class, 'index'])->name('notifications.index');
    Route::put('/notifications/{id}',        [NotificationController::class, 'marquerLue'])->name('notifications.lue');
    Route::post('/notifications/tout-lire',  [NotificationController::class, 'marquerToutesLues'])->name('notifications.toutLire');

    // Projets personnels
    Route::get('/projets-personnels',                   [ProjetPersonnelController::class, 'index'])->name('projets-personnels.index');
    Route::get('/projets-personnels/creer',             [ProjetPersonnelController::class, 'create'])->name('projets-personnels.create');
    Route::post('/projets-personnels',                  [ProjetPersonnelController::class, 'store'])->name('projets-personnels.store');
    Route::put('/projets-personnels/{id}/terminer',      [ProjetPersonnelController::class, 'terminer'])->name('projets-personnels.terminer');
    Route::put('/publications/{id}/terminer-collab',     [PublicationController::class, 'terminerCollab'])->name('publications.terminer-collab');

    // Contrat NDA (collaboration)
    Route::get('/contrat-nda/{token}',         [ContratNdaController::class, 'show'])->name('nda.show');
    Route::post('/contrat-nda/{token}/signer', [ContratNdaController::class, 'signer'])->name('nda.signer');

    // Recherche
    Route::get('/recherche', [RechercheController::class, 'index'])->name('recherche.index');
});

Route::middleware(['auth'])->group(function () {
    // Splash + Dashboard
    Route::get('/bienvenue', function () {return view('auth.splash');})->name('welcome.splash');
    
    // Messagerie
    Route::get('/messagerie',                              [ConversationController::class, 'index'])->name('messagerie.index');
    Route::post('/messagerie',                             [ConversationController::class, 'store'])->name('messagerie.store');
    Route::get('/messagerie/{id}',                         [ConversationController::class, 'show'])->name('messagerie.show');
    Route::delete('/messagerie/{id}/supprimer',            [ConversationController::class, 'supprimer'])->name('messagerie.supprimer');
    Route::post('/messagerie/{id}/messages',               [MessageController::class, 'store'])->name('messages.store');
    Route::get('/messagerie/{id}/messages/poll',           [MessageController::class, 'poll'])->name('messages.poll');
    Route::post('/messagerie/{id}/messages/lire',          [MessageController::class, 'marquerLus'])->name('messages.lire');
    Route::delete('/messagerie/{id}/messages/supprimer-miens', [MessageController::class, 'supprimerMiens'])->name('messages.supprimerMiens');
    Route::get('/messages/non-lus',                        [MessageController::class, 'countNonLus'])->name('messages.nonLus');

    // Profil
    Route::get('/profil/{id}',          [ProfilController::class, 'show'])->name('profil.show');
    Route::get('/notifications/poll',        [NotificationController::class, 'poll'])->name('notifications.poll');  
});

// ─── Routes Admin ─────────────────────────────────────────────
Route::middleware(['auth', 'is.admin'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Gestion utilisateurs
    Route::get('/utilisateurs',                [AdminController::class, 'utilisateurs'])->name('utilisateurs');
    Route::put('/utilisateurs/{id}/suspendre', [AdminController::class, 'suspendre'])->name('utilisateurs.suspendre');
    Route::put('/utilisateurs/{id}/activer',   [AdminController::class, 'activer'])->name('utilisateurs.activer');
    Route::delete('/utilisateurs/{id}',        [AdminController::class, 'supprimerUtilisateur'])->name('utilisateurs.supprimer');
    Route::get('/utilisateurs/{id}/detail', [AdminController::class, 'detailUtilisateur'])->name('utilisateurs.detail');
    
    // Gestion mentors — candidatures externes uniquement
    Route::get('/candidatures-externes',           [AdminController::class, 'candidaturesExternes'])->name('candidatures.externes');
    Route::put('/candidatures-externes/{id}/valider', [AdminController::class, 'validerExterne'])->name('candidatures.externes.valider');
    Route::put('/candidatures-externes/{id}/refuser', [AdminController::class, 'refuserExterne'])->name('candidatures.externes.refuser');
    Route::get('/candidatures-externes/{id}', [AdminController::class, 'detailCandidatureExterne'])->name('candidatures.externes.detail');
    Route::put('/mentors/relations/{id}/terminer', [AdminController::class, 'terminerRelation'])->name('mentors.relations.terminer');

    // Attribution mentors aux développeurs
    Route::post('/mentors/attribuer', [AdminController::class, 'attribuerMentor'])->name('mentors.attribuer');

    // Attribution mentor aux projets collaboratifs
    Route::post('/projets-collaboratifs/{id}/attribuer-mentor', [AdminController::class, 'attribuerMentorProjet'])->name('projets.attribuer.mentor');

    // Modération publications
    Route::get('/publications',          [AdminController::class, 'publications'])->name('publications');
    Route::delete('/publications/{id}',  [AdminController::class, 'supprimerPublication'])->name('publications.supprimer');

    // Consultation contrats NDA
    Route::get('/contrats-nda',              [AdminController::class, 'contratsNda'])->name('contrats.index');
    Route::get('/contrats-nda/{id}/telecharger', [AdminController::class, 'telechargerNda'])->name('contrats.telecharger');
});

Route::middleware(['auth', 'is.mentor'])->prefix('mentor')->name('mentor.')->group(function () {
    Route::get('/espace', [MentoratController::class, 'espaceMentor'])->name('espace');
});

