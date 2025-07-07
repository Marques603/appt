<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Archive extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'description',
        'user_upload',
        'revision',
        'file_path',
        'file_type',
        'status',
    ];

    // Usuário que fez o upload
    public function uploader()
    {
        return $this->belongsTo(User::class, 'user_upload');
    }

    // Muitos para muitos com setores
    public function plans()
    {
    return $this->belongsToMany(Plan::class, 'archive_plan', 'archive_id', 'plan_id');
    }    
    // Muitos para muitos com pastas
    public function folders()
    {
        return $this->belongsToMany(Folder::class, 'archive_folder');
    }

    // Logs de acesso
    public function logs()
    {
        return $this->hasMany(ArchiveLog::class);
    }
}
