<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Archive extends Model
{
    use SoftDeletes;

    protected $table = 'archive';

    protected $fillable = ['code', 'description', 'user_upload', 'revision', 'file_path', 'file_type', 'status'];

    // Relacionamento com Macro
    public function folders()
    {
        return $this->belongsToMany(Macro::class, 'folder_macro');
    }

    // Relacionamento com Sector
    public function sectors()
    {
        return $this->belongsToMany(Sector::class, 'folder_sector');
    }

    // Relacionamento com Approval
    public function approvals()
    {
        return $this->hasMany(DocumentApproval::class);
    }

    // Relacionamento com User (quem fez o upload)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_upload');
    }
}
