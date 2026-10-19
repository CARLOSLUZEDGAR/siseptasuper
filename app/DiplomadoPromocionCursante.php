<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DiplomadoPromocionCursante extends Model
{
    protected $table = 'diplopromo_cursantes';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id', 
        'diplopromo_id', 
        'personals_id',
        'observacion', 
        'estado', 
        'sysuser'
    ];
}
