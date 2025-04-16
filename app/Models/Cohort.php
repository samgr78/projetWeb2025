<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cohort extends Model
{
    protected $table        = 'cohorts';
    protected $fillable     = ['school_id', 'name', 'description', 'start_date', 'end_date'];

    public function knowledge(){
        return $this->hasMany(Knowledge::class);
    }

    public function task(){
        return $this->belongToMany(Task::class, 'cohorts_tasks');
    }
}
