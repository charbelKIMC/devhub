<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjetPersonnel extends Model
{
    protected $table = 'projets_personnels';
    protected $primaryKey = 'publication_id';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'publication_id', 'technologies', 'url_demo',
        'url_repo', 'date_debut', 'date_fin', 'statut_projet'
    ];

    protected $casts = [
        'technologies' => 'array'
    ];

    public function publication()
    {
        return $this->belongsTo(Publication::class, 'publication_id');
    }
}