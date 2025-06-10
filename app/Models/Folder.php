<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Folder extends Model
{
    use SoftDeletes;

    protected $table = 'folder';

    protected $fillable = [
        'name',
        'description',
        'status',
    ];

    public function responsibleUsers()
    {
    return $this->belongsToMany(User::class, 'folder_responsible_user', 'folder_id', 'user_id');
    }
    public function documents()
    {
        return $this->belongsToMany(Document::class, 'document_macro');
    }

}
