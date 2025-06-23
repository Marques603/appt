<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentApproval extends Model
{
    protected $table = 'document_approvals';

   
    protected $fillable = [
        'document_id',
        'user_id',
        'status',
        'comments',
        'approved_at',
        'approval_type',
        'approval_level',
    ];

    protected $casts = [
    'approved_at' => 'datetime',
];

    public $timestamps = true;

    // Relacionamento com documento
    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    // Relacionamento com usuário (quem aprovou)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
