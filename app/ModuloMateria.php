<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ModuloMateria extends Model
{
    protected $table = 'modulo_materias';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id', 
        'diplomod_id', 
        'orden', 
        'materia', 
        'docente',
        'observacion', 
        'estado', 
        'sysuser'
    ];
}
