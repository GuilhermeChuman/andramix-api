<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tipos_endereco extends Model
{
    use HasFactory;

    
    protected $table = 'tipos_endereco';

    protected $fillable = ['descricao'];
    public  $timestamps = false;
}
