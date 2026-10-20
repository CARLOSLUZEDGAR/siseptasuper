<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Evaluacion extends Model
{
    protected $table = 'evaluaciones';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id', 
        'evaluacion', 
        'abreviatura', 
        'porcentaje',
        'observacion', 
        'estado', 
        'sysuser'
    ];
}
