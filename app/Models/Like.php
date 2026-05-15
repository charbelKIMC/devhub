<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    protected $table = 'likes';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'utilisateur_id', 'publication_id', 'date_like'
    ];

    protected $casts = [
        'date_like' => 'datetime',
    ];

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'utilisateur_id');
    }

    public function publication()
    {
        return $this->belongsTo(Publication::class, 'publication_id');
    }
}