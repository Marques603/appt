<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Categorie extends Model
{
    use SoftDeletes;

    protected $table = 'categorie';

    protected $fillable = [
        'name',
        'description',
        'status',
    ];

    public function responsibleUsers()
    {
    return $this->belongsToMany(User::class, 'categorie_responsible_user', 'categorie_id', 'user_id');
    }
    public function documents()
    {
        return $this->belongsToMany(Document::class, 'document_macro');
    }


}
