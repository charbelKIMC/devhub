<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $table = 'articles';
    protected $primaryKey = 'publication_id';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'publication_id', 'categorie', 'temps_lecture', 'tags'
    ];

    protected $casts = [
        'tags' => 'array'
    ];

    public function publication()
    {
        return $this->belongsTo(Publication::class, 'publication_id');
    }
}