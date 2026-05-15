<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Avis extends Model
{
    protected $table = 'avis';
    public $timestamps = false;

    protected $fillable = [
        'publication_id', 'auteur_id', 'note', 'commentaire', 'date_avis'
    ];

    protected $casts = [
        'date_avis' => 'datetime',
    ];

    public function publication()
    {
        return $this->belongsTo(Publication::class, 'publication_id');
    }

    public function auteur()
    {
        return $this->belongsTo(Utilisateur::class, 'auteur_id');
    }
}