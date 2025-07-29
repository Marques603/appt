<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Position extends Model
{
use SoftDeletes;

protected $table = 'position'; //

protected $fillable = [
'code', 'name', 'description', 'parent_id', 'level', 'is_leader', 'status'
];

public function parent()
{
return $this->belongsTo(Position::class, 'parent_id');
}

public function children()
{
return $this->hasMany(Position::class, 'parent_id');
}

public function users()
{
return $this->belongsToMany(User::class, 'position_user')->withTimestamps()->withTrashed();
}
}