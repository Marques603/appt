<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    protected $fillable = [
        'provider',
        'description',
        'valor',
        'payday',
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
    public function created_by()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
 public function costCenter()
    {
        return $this->belongsTo(CostCenter::class);
    }
}
