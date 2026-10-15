<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DiplomadoModulo extends Model
{
    protected $table = 'diplomado_modulos';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id', 
        'diplopromo_id', 
        'modulo', 
        'inicio_mod', 
        'fin_mod',
        'observacion', 
        'estado', 
        'sysuser'
    ];
}
