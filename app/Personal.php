<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Personal extends Model
{
    protected $table = 'personals';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id', 'id_nacionalidad', 'per_paterno', 'per_materno', 'per_nombre', 'per_fecha_nacimiento',
        'per_sexo', 'per_ci', 'per_celular', 'per_mail', 'per_cm', 'per_foto', 'per_direccion', 'estado', 'sysuser'
    ];
}
