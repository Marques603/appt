<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArchiveLog extends Model
{
    protected $fillable = [
        'archive_id',
        'user_id',
        'ip_address',
        'user_agent',
    ];

    public $timestamps = true; // padrão, pode omitir


    public function archive()
    {
        return $this->belongsTo(Archive::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
