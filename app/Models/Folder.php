<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // Importar SoftDeletes
use Spatie\Sluggable\HasSlug; // Importar HasSlug
use Spatie\Sluggable\SlugOptions; // Importar SlugOptions

class Folder extends Model
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
            ->generateSlugsFrom('name') // Gerar slug a partir do campo 'name'
            ->saveSlugsTo('slug');     // Salvar o slug no campo 'slug'
    }

    /**
     * Get the subfolders for the folder.
     */
    public function subfolders()
    {
        // Relacionamento muitos-para-muitos com Subfolder através da tabela pivo 'folder_subfolder'
        return $this->belongsToMany(Subfolder::class, 'folder_subfolder');
    }
}