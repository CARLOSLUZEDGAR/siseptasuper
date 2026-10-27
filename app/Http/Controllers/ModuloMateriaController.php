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
        $buscar = $request->buscar;
        if ($buscar == '') {
            $materias = DB::table('modulo_materias as mm')
                    ->rightJoin('diplomado_modulos as dm', 'mm.diplomod_id', 'dm.id')
                    ->join('diplomado_promociones as dp', 'dm.diplopromo_id', 'dp.id')
                    ->join('diplomados as d', 'dp.diplomado_id', 'd.id')
                    ->select('mm.id',
                            'd.diplomado',
                            'dp.gestion',
                            'dp.promo',
                            'dp.promocion',
                            'dm.modulo',
                            'mm.materia',
                            'mm.estado')
                    ->orderBy('dp.gestion', 'desc')
                    ->orderBy('d.id', 'asc')
                    ->orderBy('dp.promocion', 'desc')
                    ->orderBy('dm.modulo', 'asc')
                    ->orderBy('mm.orden', 'asc')
                    ->paginate(10);

            return response()->json([
                'pagination' => [
                    'total'         => $materias->total(),
                    'current_page'  => $materias->currentPage(),
                    'per_page'      => $materias->perPage(),
                    'last_page'     => $materias->lastPage(),
                    'from'          => $materias->firstItem(),
                    'to'            => $materias->lastItem(),
                ],
                'materia' => $materias
            ]); 
        } else {
            $materias = DB::table('modulo_materias as mm')
                    ->join('diplomado_modulos as dm', 'mm.diplomod_id', 'dm.id')
                    ->join('diplomado_promociones as dp', 'dm.diplopromo_id', 'dp.id')
                    ->join('diplomados as d', 'dp.diplomado_id', 'd.id')
                    ->select('mm.id',
                            'd.diplomado',
                            'dp.gestion',
                            'dp.promo',
                            'dp.promocion',
                            'dm.modulo',
                            'mm.materia',
                            'mm.estado')
                    ->where(function($q) use ($buscar){
                        $q->where('d.diplomado','LIKE','%'.$buscar.'%')
                        ->orWhere('dp.gestion','LIKE','%'.$buscar.'%')
                        ->orWhere('dp.promo','LIKE','%'.$buscar.'%')
                        ->orWhere('dm.modulo','LIKE','%'.$buscar.'%')
                        ->orWhere('mm.materia','LIKE','%'.$buscar.'%');
                    }) 
                    ->orderBy('dp.gestion', 'desc')
                    ->orderBy('d.id', 'asc')
                    ->orderBy('dp.promocion', 'desc')
                    ->orderBy('dm.modulo', 'asc')
                    ->orderBy('mm.orden', 'asc')
                    ->paginate(10);

            return response()->json([
                'pagination' => [
                    'total'         => $materias->total(),
                    'current_page'  => $materias->currentPage(),
                    'per_page'      => $materias->perPage(),
                    'last_page'     => $materias->lastPage(),
                    'from'          => $materias->firstItem(),
                    'to'            => $materias->lastItem(),
                ],
                'materia' => $materias
            ]); 
        } 
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

    public function BuscarModuloMateria(Request $request)
{
    $request->validate([
        'diplomod_id' => 'required|integer'
    ]);

    // Buscar el módulo seleccionado
    $modulo = DB::table('diplomado_modulos as dm')
        ->select(
            'dm.id',
            'dm.modulo',
            'dm.inicio_mod',
            'dm.fin_mod'
        )
        ->where('dm.id', $request->diplomod_id)
        ->where('dm.estado', 1)
        ->first();

    if (!$modulo) {
        return response()->json([
            'success' => false,
            'mensaje' => 'El módulo seleccionado no existe o está inactivo.'
        ], 404);
    }

    // Buscar únicamente las materias de ese módulo
    $materias = DB::table('modulo_materias as mm')
        ->select(
            'mm.id',
            'mm.orden',
            'mm.materia',
            'mm.docente'
        )
        ->where('mm.diplomod_id', $modulo->id)
        ->where('mm.estado', 1)
        ->orderBy('mm.orden', 'asc')
        ->get();

    $modulo->materias = $materias->map(function ($materia, $index) {
        return [
            'id'      => $materia->id,
            'numero'  => $materia->orden ?? ($index + 1),
            'nombre'  => $materia->materia,
            'docente' => $materia->docente ?? ''
        ];
    })->values();

    return response()->json([
        'success' => true,
        'modulo' => $modulo
    ]);
}
}
