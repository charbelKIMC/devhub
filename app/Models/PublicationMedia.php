<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PublicationMedia extends Model
{
    protected $table = 'publication_medias';
    public $timestamps = false;

    protected $fillable = [
        'publication_id', 'chemin', 'ordre'
    ];

    public function getUrlAttribute(): string
    {
        if (str_starts_with($this->chemin, 'http')) return $this->chemin;
        return asset('storage/' . $this->chemin);
    }

    public function publication()
    {
        return $this->belongsTo(Publication::class, 'publication_id');
    }
}