<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Knowledge extends Model
{
    protected $fillable = [
        'name',
        'language_id',
        'question_number',
        'question_number',
        'difficulty',
    ];
}
