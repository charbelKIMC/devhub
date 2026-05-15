<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $table = 'messages';
    public $timestamps = false;

    protected $casts = [
        'date_envoi' => 'datetime',
    ];

    protected $fillable = [
        'conversation_id', 'expediteur_id',
        'contenu', 'date_envoi', 'est_lu', 'est_premier_contact', 'est_systeme',
    ];

    public function conversation()
    {
        return $this->belongsTo(Conversation::class, 'conversation_id');
    }

    public function expediteur()
    {
        return $this->belongsTo(Utilisateur::class, 'expediteur_id');
    }
}