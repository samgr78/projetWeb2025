<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cohorts_knowledge extends Model
{
    protected $table        = 'Cohorts_knowledge';
    protected $fillable     = ['cohort_id', 'knowledge_id', 'note'];
}
