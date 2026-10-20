<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ModuloMateriaController extends Controller
{
    public function CrearModuloMateria (Request $request) {
        
    }

    public function ModificarModuloMateria (Request $request) {

    }

    public function EliminarModuloMateria (Request $request) {

    }

    public function ListarModuloMateria (Request $request) {
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

    public function SelectModuloMateria (Request $request) {
        $modulo_materia = DB::table('modulo_materias as mm')
                    ->select('mm.*',)
                    ->where('mm.estado', 1)
                    ->where('mm.diplomod_id', $request->modulo)
                    // ->where('dp.gestion', $request->gestion)
                    ->orderBy('mm.orden', 'asc')
                    ->get();

        return response()->json(['materia' => $modulo_materia]); 
    }
}
