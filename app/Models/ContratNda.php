<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContratNda extends Model
{
    protected $table = 'contrats_nda';

    protected $fillable = [
        'candidature_id', 'utilisateur_id', 'publication_id',
        'token', 'chemin_pdf', 'ip_signature', 'signe_le', 'statut',
    ];

    protected $casts = [
        'signe_le' => 'datetime',
    ];

    public function candidature()
    {
        return $this->belongsTo(Candidature::class);
    }

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class);
    }

    public function publication()
    {
        return $this->belongsTo(Publication::class)->with(['auteur', 'projetCollaboratif']);
    }
}
