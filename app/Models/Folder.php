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
    public function archives()
    {
        return $this->belongsToMany(Archive::class, 'archive_folder');
    }
    public function archivesBySector($sectorId)
{
    return $this->belongsToMany(Archive::class, 'archive_folder', 'folder_id', 'archive_id')
        ->whereHas('sectors', function($query) use ($sectorId) {
            $query->where('sector_id', $sectorId);
        });
}



}
