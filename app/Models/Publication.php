<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Publication extends Model
{
    protected $table = 'publications';

    const CREATED_AT = 'date_publication';
    const UPDATED_AT = 'date_modification';

    protected $casts = [
        'date_publication'  => 'datetime',
        'date_modification' => 'datetime',
    ];

    protected $fillable = [
        'auteur_id', 'type', 'titre', 'contenu',
        'statut', 'nb_vues', 'nb_likes', 'nb_commentaires'
    ];

    public function auteur()
    {
        return $this->belongsTo(Utilisateur::class, 'auteur_id');
    }

    public function commentaires()
    {
        return $this->hasMany(Commentaire::class, 'publication_id');
    }

    public function likes()
    {
        return $this->hasMany(Like::class, 'publication_id');
    }

    public function favoris()
    {
        return $this->hasMany(Favori::class, 'publication_id');
    }

    public function avis()
    {
        return $this->hasMany(Avis::class, 'publication_id');
    }

    public function medias()
    {
        return $this->hasMany(PublicationMedia::class, 'publication_id')->orderBy('ordre');
    }

    public function article()
    {
        return $this->hasOne(Article::class, 'publication_id');
    }

    public function idee()
    {
        return $this->hasOne(Idee::class, 'publication_id');
    }

    public function projetPersonnel()
    {
        return $this->hasOne(ProjetPersonnel::class, 'publication_id');
    }

    public function projetCollaboratif()
    {
        return $this->hasOne(ProjetCollaboratif::class, 'publication_id');
    }
}