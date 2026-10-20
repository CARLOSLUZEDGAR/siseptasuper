<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DiplomadoPromocionCursanteController extends Controller
{
    public function CrearDiplomadoPromocionCursante (Request $request) {
        
    }

    public function ModificarDiplomadoPromocionCursante (Request $request) {

    }

    public function EliminarDiplomadoPromocionCursante (Request $request) {

    }

    public function ListarDiplomadoPromocionCursante (Request $request) {
        $diplomado_promocion_cursantes = DB::table('diplopromo_cursantes as dpc')
                    ->join('personals as p', 'p.id', 'dpc.personals_id')
                    ->join('grados as g', 'g.id', 'p.per_grado_id')
                    ->select('dpc.id as diplopromocursante_id',
                            'dpc.personals_id',
                            'g.abreviatura',
                            'p.per_paterno',
                            'p.per_materno',
                            'p.per_nombre')
                    ->where('dpc.estado', 1)
                    ->where('dpc.diplopromo_id', $request->promocion)
                    ->where('p.estado', 1)
                    ->orderBy('p.id', 'asc')
                    ->get();

        return response()->json(['cursante' => $diplomado_promocion_cursantes]); 
    }
}
