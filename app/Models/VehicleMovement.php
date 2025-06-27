<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleMovement extends Model
{
    protected $fillable = [
        'vehicle_id',
        'user_id',
        'gatekeeper_id',
        'departure_km',
        'return_km',
        'departure_time',
        'return_time',
        'destination',
        'observations',
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class); // Quem levou
    }

    public function gatekeeper()
    {
        return $this->belongsTo(User::class, 'gatekeeper_id'); // Quem registrou
    }
}

