<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favori extends Model
{
    protected $table = 'favoris';
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = [
        'utilisateur_id', 'publication_id', 'date_ajout'
    ];

    protected $casts = [
        'date_ajout' => 'datetime',
    ];

    public function publication()
    {
        return $this->belongsTo(Publication::class, 'publication_id');
    }

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'utilisateur_id');
    }
}
