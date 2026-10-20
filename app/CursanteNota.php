<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CursanteNota extends Model
{
    protected $table = 'cursante_notas';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id', 
        'materia_id',
        'diplopromocursante_id', 
        'personals_id', 
        'evaluacion_id', 
        'nota',
        'observacion', 
        'estado', 
        'sysuser'
    ];
}
