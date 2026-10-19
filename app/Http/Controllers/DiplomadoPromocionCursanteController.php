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
        $diplomado_promocion = DB::table('diplomado_promociones as dp')
                    ->join('diplomados as d', 'dp.diplomado_id', 'd.id')
                    ->join('periodos as p', 'dp.periodo_id', 'p.id')
                    ->select('dp.id',
                            'd.diplomado',
                            'dp.promo',
                            'dp.gestion')
                    ->where('d.estado', 1)
                    ->where('dp.estado', 1)
                    ->where('p.estado', 1)
                    ->orderBy('dp.gestion', 'desc')
                    ->orderBy('d.id', 'asc')
                    ->get();

        return response()->json([
            'pagination' => [
                'total'         => $diplomado_promocion->total(),
                'current_page'  => $diplomado_promocion->currentPage(),
                'per_page'      => $diplomado_promocion->perPage(),
                'last_page'     => $diplomado_promocion->lastPage(),
                'from'          => $diplomado_promocion->firstItem(),
                'to'            => $diplomado_promocion->lastItem(),
            ],
            'diplomado_promocion' => $diplomado_promocion
        ]); 
    }
}
