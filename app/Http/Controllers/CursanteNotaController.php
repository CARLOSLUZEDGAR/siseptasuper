<?php

namespace App\Http\Controllers;

use App\CursanteNota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CursanteNotaController extends Controller
{
    public function CrearCursanteNota (Request $request) {
        try {
            DB::beginTransaction();
                foreach ($request->cursantes as $curs) {
                    $nota = CursanteNota::create([
                        'materia_id' => $request->materia,
                        'diplopromocursante_id' => $curs['diplopromocursante_id'],
                        'personals_id' => $curs['personals_id'],
                        'evaluacion_id' => $request->evaluacion,
                        'nota' => $curs['nota'],
                        'observacion' => 'REGISTRO SIN NOVEDAD',
                        'estado' => 1,
                        'sysuser' => Auth::user()->id
                    ]);
                }
            DB::commit();
            return response()->json([
                'success' => true,
                'mensaje' => 'Notas de cursantes registrados correctamente',
                'cursante' => $request->cursantes
                // 'situacion_evento' => $situacion_evento
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'mensaje' => 'Ocurrió un error al registrar las notas de los cursantes',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function ModificarCursanteNota (Request $request) {

    }

    public function EliminarCursanteNota (Request $request) {

    }

    public function ListarCursanteNota (Request $request) {
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

    public function SelectCursanteNota (Request $request) {
        $diplomado_modulo = DB::table('diplomado_modulos as dm')
                    ->select('dm.*',)
                    ->where('dm.estado', 1)
                    ->where('dm.diplopromo_id', $request->promocion)
                    // ->where('dp.gestion', $request->gestion)
                    ->orderBy('dm.modulo', 'asc')
                    ->get();

        return response()->json(['modulo' => $diplomado_modulo]); 
    }
}
