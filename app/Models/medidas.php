<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class medidas extends Model
{
    use HasFactory;

    protected $table = 'medidas';

    protected $fillable = ['descricao'];
    public  $timestamps = false;
}
