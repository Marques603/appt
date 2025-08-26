<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agreements extends Model
{
    protected $table = 'agreements';
    
    protected $fillable = [
        'id',
        'name',
        'description',
        'contact',
        'road_name',
        'city',
        'number',
        'created_at',
        'updated_at',
        'agreements_type_id',
    ];

    public function agreementsType()
    {
            return $this->belongsTo(Agreements_type::class);
        }       
}
