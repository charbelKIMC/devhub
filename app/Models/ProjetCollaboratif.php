<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjetCollaboratif extends Model
{
    protected $table = 'projets_collaboratifs';
    protected $primaryKey = 'publication_id';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'publication_id', 'technologies', 'url_repo',
        'date_debut', 'date_fin', 'phase',
        'taille_equipe_max', 'taille_equipe_actuelle', 'mentor_id',
    ];

    protected $casts = [
        'technologies' => 'array'
    ];

    public function publication()
    {
        return $this->belongsTo(Publication::class, 'publication_id');
    }

    public function candidatures()
    {
        return $this->hasMany(Candidature::class, 'projet_collaboratif_id');
    }

    public function membres()
    {
        return $this->hasMany(MembreProjet::class, 'projet_collaboratif_id');
    }

    public function conversation()
    {
        return $this->hasOne(Conversation::class, 'projet_collaboratif_id');
    }
}