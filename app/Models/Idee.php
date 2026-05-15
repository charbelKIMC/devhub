<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Idee extends Model
{
    protected $table = 'idees';
    protected $primaryKey = 'publication_id';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'publication_id', 'domaine', 'complexite'
    ];

    public function publication()
    {
        return $this->belongsTo(Publication::class, 'publication_id');
    }
}