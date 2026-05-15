<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'notifications';
    public $timestamps = false;

    protected $casts = [
        'date_creation' => 'datetime',
    ];
    protected $fillable = [
        'destinataire_id', 'type', 'contenu', 'est_lue', 'lien', 'date_creation'
    ];

    public function destinataire()
    {
        return $this->belongsTo(Utilisateur::class, 'destinataire_id');
    }
}