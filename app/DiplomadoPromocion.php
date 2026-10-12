<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DiplomadoPromocion extends Model
{
    protected $table = 'diplomado_promociones';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id', 
        'diplomado_id', 
        'promocion', 
        'promo', 
        'gestion',
        'periodo_id',
        'observacion', 
        'estado', 
        'sysuser'
    ];
}
