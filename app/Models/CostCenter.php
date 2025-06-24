<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CostCenter extends Model
{
    use SoftDeletes;

    protected $table = 'cost_center';

    protected $fillable = ['name', 'code', 'status'];

    public function sectors()
    {
        return $this->belongsToMany(Sector::class, 'cost_center_sector');
    }
}
