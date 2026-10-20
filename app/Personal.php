<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Personal extends Model
{
    protected $table = 'personals';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id', 
        'per_fuerza_id',
        'per_grado_id',
        'per_paterno', 
        'per_materno', 
        'per_nombre', 
        'per_fecha_nacimiento',
        'per_sexo', 
        'per_ci', 
        'per_cm',
        'per_celular', 
        'per_mail', 
        'per_foto', 
        'per_direccion', 
        'estado', 
        'sysuser'
    ];
}
