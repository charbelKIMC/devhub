<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UtilisateurCompetence extends Model
{
    protected $table = 'utilisateur_competences';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'utilisateur_id', 'competence_id', 'niveau_maitrise'
    ];
}