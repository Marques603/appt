<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // Importar SoftDeletes

class Archive extends Model
{
    use HasFactory, SoftDeletes; // Usar SoftDeletes

    // Campos que podem ser preenchidos em massa
    protected $fillable = [
        'code',         // Conforme sua migração
        'name',
        'path',
        'extension',    // Conforme sua migração
        'revision',     // Conforme sua migração
        'user_upload',  // Conforme sua migração
        'size',         // Conforme sua migração
        'description',
        'status',       // Conforme sua migração
    ];

    /**
     * Get the sectors associated with the archive.
     */
    public function sectors()
    {
        // Relacionamento muitos-para-muitos com Sector através da tabela pivo 'archive_sector'
        return $this->belongsToMany(Sector::class, 'archive_sector');
    }

    /**
     * Get the subfolders associated with the archive.
     */
    public function subfolders()
    {
        // Relacionamento muitos-para-muitos com Subfolder através da tabela pivo 'archive_subfolder'
        return $this->belongsToMany(Subfolder::class, 'archive_subfolder');
    }

    /**
     * Get the user who uploaded the archive.
     */
    public function uploader()
    {
        // Relacionamento um-para-muitos (inverso) com User
        // A chave estrangeira 'user_upload' está na tabela 'archives'
        return $this->belongsTo(User::class, 'user_upload');
    }
}