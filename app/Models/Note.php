<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    protected $fillable = [
        'provider',
        'pdf_file', // Campo obrigatório para o arquivo PDF
        'description',
        'valor',
        'payday',
        'note_number', // Adicionando o campo note_number
        'approval_position_id', // ID da posição de aprovação, pode ser nulo inicialmente
        'cost_center_id', // Usando o ID do centro de custo
        'created_by', // Usuário que criou a nota
        'status', // O status será "Aguardando Aprovação" por padrão
    ];

    protected $casts = [
        'payday' => 'date',
        'valor' => 'decimal:2',
    ];

    public function getStatusAttribute($value)
    {
        return $value ?? 'Aguardando Aprovação';
    }
    public function createdBy()
{
    return $this->belongsTo(User::class, 'created_by');
}

    public function costCenter()
    {
        return $this->belongsTo(CostCenter::class);
    }
    public function approvalPosition()
    {
        return $this->belongsTo(Position::class, 'approval_position_id');
    }
}
