<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class CohortUser extends Pivot
{
    protected $table = 'cohort_user';

    protected $fillable = [
        'user_id',
        'cohort_id',
    ];
}
