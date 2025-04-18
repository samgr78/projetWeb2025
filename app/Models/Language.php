<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    protected $fillable = ['name', 'difficulty',];

    public function knowledges()
    {
        return $this->belongsToMany(Knowledge::class, 'knowledges_languages');
    }
}
