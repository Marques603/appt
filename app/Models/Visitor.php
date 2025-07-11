<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    protected $table = 'visitors';

    protected $fillable = [
        'name',
        'document',
        'typevisitor',
        'company',
        'service',
        'parking',
        'vehicle_plate',
        'vehicle_model',
        'status',
        'created_by', // Usuário que criou o visitante
    ];



    protected $casts = [
        'status' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getTypeVisitorAttribute($value)
    {
        return ucfirst(strtolower($value));
    }
}
