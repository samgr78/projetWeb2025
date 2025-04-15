<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Knowledge extends Model
{
    protected $table = 'knowledges';
    protected $fillable = [
        'name',
        'question_number',
        'answer_number',
        'difficulty',
    ];

    public function languages()
    {
        return $this->belongsToMany(Language::class, 'knowledges_languages');
    }
    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function cohorts(){
        return $this->belongsToMany(Cohort::class, 'cohorts_knowledge')
            ->withPivot('note')
            ->withTimestamps();
    }
}
