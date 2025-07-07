<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Folder extends Model
{
    use HasFactory, SoftDeletes, HasSlug;

    protected $fillable = [
        'name', 'slug', 'description', 'status', 'parent_id',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    // Relacionamento recursivo para pastas filhas
    public function children()
    {
        return $this->hasMany(Folder::class, 'parent_id');
    }

    // Relacionamento recursivo para pasta pai
    public function parent()
    {
        return $this->belongsTo(Folder::class, 'parent_id');
    }
    public function sectors()
    {
    return $this->belongsToMany(Sector::class, 'folder_sector');
    }
    public function fullPath()
    {
        if ($this->parent) {
            return $this->parent->fullPath() . ' > ' . $this->name;
        }
        return $this->name;
    }
    public function fullPathArray()
    {
    $path = collect();
    $folder = $this;
    while ($folder) {
        $path->prepend($folder);
        $folder = $folder->parent;
    }
    return $path;
    }public function archives()
{
    return $this->belongsToMany(Archive::class, 'archive_folder', 'folder_id', 'archive_id');
}

}
