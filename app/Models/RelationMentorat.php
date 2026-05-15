<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RelationMentorat extends Model
{
    protected $table = 'relation_mentorat';
    public $timestamps = false;

    protected $fillable = [
        'mentor_id', 'mentore_id', 'statut', 'date_debut', 'date_fin'
    ];
    protected $casts = [
        'date_debut' => 'datetime',
        'date_fin' => 'datetime',
    ];

    public function mentor()
    {
        return $this->belongsTo(Utilisateur::class, 'mentor_id');
    }

    public function mentore()
    {
        return $this->belongsTo(Utilisateur::class, 'mentore_id');
    }
}