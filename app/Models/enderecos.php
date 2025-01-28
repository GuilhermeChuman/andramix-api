<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class enderecos extends Model
{
    use HasFactory;

    protected $table = 'enderecos';

    protected $fillable = ['rua', 'bairro', 'cidade', 'numero'];
    public  $timestamps = false;
}
