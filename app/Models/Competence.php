<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Competence extends Model
{
    protected $table = 'competences';
    public $timestamps = false;
    
    protected $fillable = [
        'nom', 'categorie'
    ];

    public function utilisateurs()
    {
        return $this->belongsToMany(
            Utilisateur::class,
            'utilisateur_competences',
            'competence_id',
            'utilisateur_id'
        )->withPivot('niveau_maitrise');
    }
}