<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Grado extends Model
{
    protected $table = 'grados';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id', 
        'fuerza_id', 
        'grado',
        'abreviatura', 
        'observacion', 
        'estado', 
        'sysuser'
    ];
}
