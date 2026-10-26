<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DiplomadoModuloController extends Controller
{
    public function CrearDiplomadoModulo (Request $request) {
        
    }

    public function ModificarDiplomadoModulo (Request $request) {

    }

    public function EliminarDiplomadoModulo (Request $request) {

    }

    public function ListarDiplomadoModulo (Request $request) {

        $buscar = $request->buscar;
        if ($buscar == '') {
            $diplomado_modulo = DB::table('diplomado_modulos as dm')
                    ->rightjoin('diplomado_promociones as dp', 'dm.diplopromo_id', 'dp.id')
                    ->join('diplomados as d', 'dp.diplomado_id', 'd.id')
                    ->select('dm.id',
                            'd.diplomado',
                            'dp.gestion',
                            'dp.promo',
                            'dm.modulo',
                            'dm.estado')
                    ->orderBy('dp.gestion', 'desc')
                    ->orderBy('d.id', 'asc')
                    ->orderBy('dp.promocion', 'desc')
                    ->orderBy('dm.modulo', 'asc')
                    ->paginate(10);

            return response()->json([
                'pagination' => [
                    'total'         => $diplomado_modulo->total(),
                    'current_page'  => $diplomado_modulo->currentPage(),
                    'per_page'      => $diplomado_modulo->perPage(),
                    'last_page'     => $diplomado_modulo->lastPage(),
                    'from'          => $diplomado_modulo->firstItem(),
                    'to'            => $diplomado_modulo->lastItem(),
                ],
                'diplomado' => $diplomado_modulo
            ]); 
        } else {
            $diplomado_modulo = DB::table('diplomado_modulos as dm')
                    ->rightjoin('diplomado_promociones as dp', 'dm.diplopromo_id', 'dp.id')
                    ->join('diplomados as d', 'dp.diplomado_id', 'd.id')
                    ->select('dm.id',
                            'd.diplomado',
                            'dp.gestion',
                            'dp.promo',
                            'dm.modulo',
                            'dm.estado')
                    ->where(function($q) use ($buscar){
                        $q->where('d.diplomado','LIKE','%'.$buscar.'%')
                        ->orWhere('dp.gestion','LIKE','%'.$buscar.'%')
                        ->orWhere('dp.promo','LIKE','%'.$buscar.'%')
                        ->orWhere('dm.modulo','LIKE','%'.$buscar.'%');
                    }) 
                    ->orderBy('dp.gestion', 'desc')
                    ->orderBy('d.id', 'asc')
                    ->orderBy('dp.promocion', 'desc')
                    ->orderBy('dm.modulo', 'asc')
                    ->paginate(10);

            return response()->json([
                'pagination' => [
                    'total'         => $diplomado_modulo->total(),
                    'current_page'  => $diplomado_modulo->currentPage(),
                    'per_page'      => $diplomado_modulo->perPage(),
                    'last_page'     => $diplomado_modulo->lastPage(),
                    'from'          => $diplomado_modulo->firstItem(),
                    'to'            => $diplomado_modulo->lastItem(),
                ],
                'diplomado' => $diplomado_modulo
            ]); 
        }
    }

    public function SelectDiplomadoModulo (Request $request) {
        $diplomado_modulo = DB::table('diplomado_modulos as dm')
                    ->select('dm.*',)
                    ->where('dm.estado', 1)
                    ->where('dm.diplopromo_id', $request->promocion)
                    // ->where('dp.gestion', $request->gestion)
                    ->orderBy('dm.modulo', 'asc')
                    ->get();

        return response()->json(['modulo' => $diplomado_modulo]); 
    }

    public function SelectGestionDiplomadoModulo (Request $request) {
        $gestiones = DB::table('diplomado_modulos as dm')
                    ->join('diplomado_promociones as dp', 'dm.diplopromo_id', 'dp.id')
                    ->select('dp.gestion')
                    ->where('dp.estado', 1)
                    ->where('dm.estado', 1)
                    ->where('dp.diplomado_id', $request->diplomadoid)
                    ->distinct()
                    ->orderBy('dp.gestion', 'asc')
                    ->get();

        return response()->json(['gestion' => $gestiones]); 
    }

    public function SelectPromocionDiplomadoModulo (Request $request) {
        $promociones = DB::table('diplomado_promociones as dp')
                    // ->join('diplomado_promociones as dp', 'dm.diplopromo_id', 'dp.id')
                    ->select('dp.id',
                            'dp.promocion',
                            'dp.promo')
                    ->where('dp.estado', 1)
                    ->where('dp.diplomado_id', $request->diplomadoid)
                    ->where('dp.gestion', $request->gestion)
                    ->where('dp.estado', 1)
                    ->orderBy('dp.promocion', 'asc')
                    ->get();

        return response()->json(['promocion' => $promociones]); 
    }

    public function BuscarDiplomadoModulo (Request $request) {
        $diplomado_modulo = DB::table('diplomado_modulos as dm')
                        ->select('dm.id',
                                'dm.modulo',
                                'dm.inicio_mod',
                                'dm.fin_mod')
                        ->where('dm.diplopromo_id', $request->diplopromo_id)
                        ->where('dm.estado', 1)
                        ->orderBy('dm.modulo', 'asc')
                        ->get();

        return response()->json(['modulo' => $diplomado_modulo]);
    }
}
