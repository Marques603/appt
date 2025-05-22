<?php

// app/Models/Company.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Company extends Model
{
    use HasFactory;

    protected $table = 'company';

    protected $fillable = [
        'name',
        'corporate_name',
        'cnpj',
        'status',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'company_user');
    }
    public function responsibles()
{
    return $this->belongsToMany(User::class, 'company_responsible_user');
}

}

