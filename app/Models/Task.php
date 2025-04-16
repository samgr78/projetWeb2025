<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = ['title', 'description'];

    public function cohort(){
        return $this->belongsToMany(Cohort::class, 'cohorts_tasks');
    }

}
