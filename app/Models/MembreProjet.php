<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MembreProjet extends Model
{
    protected $table = 'membres_projet';
    public $timestamps = false;

    protected $fillable = [
        'projet_collaboratif_id', 'utilisateur_id',
        'role', 'date_rejoint', 'contribution'
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