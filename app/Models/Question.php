<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $table = 'questions';
    protected $fillable = [
        'question',
        'knowledge_id',
    ];

    public function knowledge()
    {
        return $this->belongsTo(Knowledge::class);
    }
    public function answers()
    {
        return $this->hasMany(Answer::class);
    }
}
