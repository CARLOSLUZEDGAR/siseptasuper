<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Fuerza extends Model
{
    protected $table = 'fuerzas';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id', 
        'fuerza', 
        'abreviatura', 
        'observacion', 
        'estado', 
        'sysuser'
    ];
}
