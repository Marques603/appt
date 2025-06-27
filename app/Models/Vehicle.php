<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    const STATUS_DISPONIVEL = 1;
    const STATUS_EM_TRANSITO = 2;

    protected $fillable = [
        'plate', 'brand', 'model', 'current_km', 'status', 'observations',
    ];

    public function movements()
    {
        return $this->hasMany(VehicleMovement::class);
    }

    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            self::STATUS_DISPONIVEL => 'Disponível',
            self::STATUS_EM_TRANSITO => 'Em Trânsito',
            default => 'Desconhecido',
        };
    }
}
