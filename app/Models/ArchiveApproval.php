<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArchiveApproval extends Model
{
    protected $table = 'archive_approvals';

    protected $fillable = [
        'archive_id',
        'user_id',
        'approved_at',
    ];

    public $timestamps = true;

    // Relacionamento com documento
    public function archive()
    {
        return $this->belongsTo(Archive::class);
    }

    // Relacionamento com usuário (quem aprovou)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
