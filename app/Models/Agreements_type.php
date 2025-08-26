<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agreements_type extends Model
{
    protected $table = 'agreements_type'; // nome da tabela, se não seguir plural padrão
    protected $fillable = ['type', 'description'];

public function agreements()
{
    return $this->hasMany(Agreements::class);
}

}