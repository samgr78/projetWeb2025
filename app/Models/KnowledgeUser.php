<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KnowledgeUser extends Model
{
    protected $table = 'knowledges_users';
    protected $fillable = [
        'user_id',
        'knowledge_id',
    ];
}
