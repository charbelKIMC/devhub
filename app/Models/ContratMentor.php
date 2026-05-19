<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContratMentor extends Model
{
    protected $table    = 'contrats_mentor';
    protected $fillable = ['mentor_id', 'chemin_pdf', 'ip_signature', 'signe_le', 'statut'];

    protected $casts = [
        'signe_le' => 'datetime',
    ];

    public function mentor(): BelongsTo
    {
        return $this->belongsTo(Utilisateur::class, 'mentor_id');
    }
}
