<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Candidature extends Model
{
    protected $table = 'candidatures';
    protected $dates = ['date_postulation', 'date_reponse'];
    protected $casts = [
        'date_postulation' => 'datetime',
        'date_reponse'     => 'datetime',
    ];
    public $timestamps = false;

    protected $fillable = [
        'projet_collaboratif_id', 'utilisateur_id',
        'motivation', 'statut', 'date_reponse', 'date_postulation'
    ];

    public function projet()
    {
        return $this->belongsTo(ProjetCollaboratif::class, 'projet_collaboratif_id');
    }

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'utilisateur_id');
    }
}