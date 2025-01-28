<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class clientes extends Model
{
    use HasFactory;

    protected $table = 'clientes';

    protected $fillable = ['cpf_cnpj', 'nome', 'telefone', 'porcentagem_areia', 'porcentagem_concreto'];
    public  $timestamps = false;
}
