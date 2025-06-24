<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Position extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'position'; // Nome da tabela no singular

    protected $fillable = ['name','description','status'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'position_user');
    }
}
