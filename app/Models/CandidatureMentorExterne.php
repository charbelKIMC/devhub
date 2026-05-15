<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CandidatureMentorExterne extends Model
{
    protected $table = 'candidatures_mentor_externe';
    public $timestamps = false;

    protected $fillable = [
        'prenom', 'nom', 'email', 'telephone', 'localisation',
        'specialite', 'annees_experience', 'experience',
        'portfolio', 'linkedin', 'motivation', 'references',
        'type_identite', 'numero_identite',
        'chemin_cv', 'chemin_identite', 'statut',
    ];
}