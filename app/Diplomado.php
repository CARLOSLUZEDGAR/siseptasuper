<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Diplomado extends Model
{
    protected $table = 'diplomados';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id', 
        'diplomado', 
        'abreviatura', 
        'descripcion', 
        'observacion', 
        'estado', 
        'sysuser'
    ];
}
