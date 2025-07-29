<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Plan extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'description', 'status'];

    // Relacionamento com usuários "comuns"
    public function users()
    {
        return $this->belongsToMany(User::class, 'plan_user', 'plan_id', 'user_id')->withTimestamps()->withPivot('deleted_at');
    }

    // Relacionamento com usuários responsáveis
    public function responsibleUsers()
    {
        return $this->belongsToMany(User::class, 'plan_responsible_user', 'plan_id', 'user_id')->withTimestamps()->withPivot('deleted_at');
    }
    public function archives()
    {   
    return $this->belongsToMany(Archive::class, 'archive_plan', 'plan_id', 'archive_id');
    }

}
