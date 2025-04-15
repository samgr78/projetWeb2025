<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = ['title', 'description', 'cohort_id'];

    public function cohorts() {
        return $this->belongsToMany(Cohort::class, 'cohort_task');
    }

}
