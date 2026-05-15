<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $table = 'conversations';
    public $timestamps = false;

    protected $fillable = [
        'type_conversation',
        'projet_collaboratif_id',
        'nom',
        'statut_acceptation',
        'dernier_message_date',
        'est_mentorat',
        'relation_mentorat_id',
        'date_creation',
        'est_active',
    ];

    protected $casts = [
        'est_mentorat' => 'boolean',
        'est_active'   => 'boolean',
    ];

    public function messages()
    {
        return $this->hasMany(Message::class, 'conversation_id');
    }

    public function participants()
    {
        return $this->belongsToMany(
            Utilisateur::class,
            'participants_conversation',
            'conversation_id',
            'utilisateur_id'
        );
    }

    public function projet()
    {
        return $this->belongsTo(ProjetCollaboratif::class, 'projet_collaboratif_id');
    }

    public function dernierMessage()
    {
        return $this->hasOne(Message::class, 'conversation_id')->latest();
    }
}