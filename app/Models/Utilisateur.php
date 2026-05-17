<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
//use Illuminate\Database\Eloquent\Model;

class Utilisateur extends Authenticatable {
    use Notifiable;

    protected $table = 'utilisateurs';
    protected $authPasswordName = 'mot_de_passe';
    public $timestamps = true;
    const CREATED_AT = 'date_inscription';
    const UPDATED_AT = 'date_modification';
    

    protected $fillable = [
        'pseudo',
        'email',
        'mot_de_passe',
        'prenom',
        'nom',
        'telephone',
        'specialite',
        'experience',
        'bio',
        'github',
        'linkedin',
        'portfolio',
        'localisation',
        'photo',
        'profil_complete',
        'pourcentage_completude',
        'demande_mentor',
        'en_attente_mentor',
        'role',
        'statut',
        'motivation_mentor',
        'mentor_prefere_id',
    ];

    protected $hidden = [
        'mot_de_passe',
    ];

    protected $casts = [
        'derniere_connexion' => 'datetime',
        'date_inscription'   => 'datetime',
    ];
    public function getAuthPassword()
    {
        return $this->mot_de_passe;
    }

    // Relations
    public function publications()
    {
        return $this->hasMany(Publication::class, 'auteur_id');
    }

    public function competences()
    {
        return $this->belongsToMany(Competence::class, 'utilisateur_competences', 'utilisateur_id', 'competence_id')
                    ->withPivot('niveau_maitrise');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'destinataire_id');
    }

    public function mentors()
    {
        return $this->hasMany(RelationMentorat::class, 'mentore_id');
    }

    public function mentores()
    {
        return $this->hasMany(RelationMentorat::class, 'mentor_id');
    }

    public function projetsEnMentorat()
    {
        return $this->hasMany(\App\Models\ProjetCollaboratif::class, 'mentor_id');
    }

    public function conversations()
    {
        return $this->belongsToMany(Conversation::class, 'participants_conversation', 'utilisateur_id', 'conversation_id');
    }

    public function getPhotoUrlAttribute(): ?string
    {
        if (!$this->photo) return null;
        if (str_starts_with($this->photo, 'http')) return $this->photo;
        return asset('storage/' . $this->photo);
    }

    // Helpers
    public function isDeveloppeur()
    {
        return $this->role === 'développeur';
    }

    public function isMentor()
    {
        return $this->role === 'mentor';
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isActif()
    {
        return $this->statut === 'actif';
    }
}




