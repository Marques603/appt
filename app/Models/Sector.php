<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sector extends Model
{
    use SoftDeletes;

    protected $table = 'sector';

    protected $fillable = [
        'name', 'acronym', 'status',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
    public function costCenters()
    {
    return $this->belongsToMany(CostCenter::class, 'cost_center_sector');
    }
    public function responsibleUsers()
    {
    return $this->belongsToMany(User::class, 'sector_responsible_user');
    }
    public function documents()
    {
        return $this->belongsToMany(Document::class, 'document_sector');
    }
    public function folders()
    {
    return $this->belongsToMany(Folder::class, 'folder_sector');
    }

    /**
     * Get the archives associated with the sector.
     */
    public function archives()
    {
        // Relacionamento muitos-para-muitos com Archive através da tabela pivo 'archive_sector'
        return $this->belongsToMany(Archive::class, 'archive_sector');
    }

}
