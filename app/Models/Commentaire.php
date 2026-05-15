<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Commentaire extends Model
{
    protected $table = 'commentaires';
    public $timestamps = false;

    protected $casts = [
        'date_commentaire'  => 'datetime',
        'date_modification' => 'datetime',
    ];

    protected $fillable = [
        'publication_id', 'utilisateur_id',
        'id_parent', 'contenu', 'est_modifiable'
    ];

    public function publication()
    {
        return $this->belongsTo(Publication::class, 'publication_id');
    }

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'utilisateur_id');
    }

    public function parent()
    {
        return $this->belongsTo(Commentaire::class, 'id_parent');
    }

    public function reponses()
    {
        return $this->hasMany(Commentaire::class, 'id_parent');
    }
}