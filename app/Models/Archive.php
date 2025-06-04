<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Archive extends Model
{
    use SoftDeletes;

    protected $table = 'archive';

    protected $fillable = [
        'code', 'description', 'user_upload', 'revision', 'file_path', 'file_type', 'status'
    ];

    public function macros()
    {
        return $this->belongsToMany(Macro::class, 'archive_macro');
    }

    public function sectors()
    {
        return $this->belongsToMany(Sector::class, 'archive_sector');
    }

    public function approvals()
    {
        return $this->hasMany(ArchiveApproval::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_upload');
    }

    public function fileHistory()
    {
        return $this->hasMany(ArchiveFileHistory::class);
    }
}
