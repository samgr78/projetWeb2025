<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CohortUser extends Model
{
    protected $table = 'cohorts_users';
    protected $fillable = ['cohort_id', 'user_id'];
}
