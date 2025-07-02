<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // Importar SoftDeletes
use Spatie\Sluggable\HasSlug; // Importar HasSlug
use Spatie\Sluggable\SlugOptions; // Importar SlugOptions

class Subfolder extends Model
{
    use HasFactory, SoftDeletes, HasSlug; // Usar SoftDeletes e HasSlug

    // Campos que podem ser preenchidos em massa
    protected $fillable = [
        'name',
        'slug',
        'description',
        'status', // Adicionado 'status' conforme sua migração
    ];

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    /**
     * Get the folders that this subfolder belongs to.
     */
    public function folders()
    {
        // Relacionamento muitos-para-muitos com Folder através da tabela pivo 'folder_subfolder'
        return $this->belongsToMany(Folder::class, 'folder_subfolder');
    }

    /**
     * Get the sectors associated with the subfolder.
     */
    public function sectors()
    {
        // Relacionamento muitos-para-muitos com Sector através da tabela pivo 'subfolder_sector'
        // 'sector' é o nome da sua tabela singular
        return $this->belongsToMany(Sector::class, 'subfolder_sector');
    }

    /**
     * Get the archives for the subfolder.
     */
    public function archives()
    {
        // Relacionamento muitos-para-muitos com Archive através da tabela pivo 'archive_subfolder'
        return $this->belongsToMany(Archive::class, 'archive_subfolder');
    }
}