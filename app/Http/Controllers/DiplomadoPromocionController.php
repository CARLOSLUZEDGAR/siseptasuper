<?php

namespace App\Http\Controllers;

use App\DiplomadoModulo;
use App\DiplomadoPromocion;
use App\ModuloMateria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DiplomadoPromocionController extends Controller
{
    public function CrearDiplomadoPromocion (Request $request) {
        try {
            $request->validate([
                'diplomado' => 'required',
                'gestion'   => 'required',
                'periodo'   => 'required',
                'promocion' => 'required'
            ]);

            DB::beginTransaction();
                $diplomado_promocion = DiplomadoPromocion::create([
                    'diplomado_id' => $request->diplomado,
                    'promocion'     => 1,
                    'promo'         => $request->promocion,
                    'gestion'       => $request->gestion,
                    'periodo_id'   => $request->periodo,
                    'observacion'  => 'REGISTRO SIN NOVEDAD',
                    'estado'       => 1,
                    'sysuser'      => Auth::user()->id
                ]);

                if (is_array($request->modulos) && count($request->modulos) > 0) {
                    foreach ($request->modulos as $mod) {
                        $diplomado_modulo = DiplomadoModulo::create([
                            'diplopromo_id' => $diplomado_promocion->id,
                            'modulo'        => $mod['modulo'],
                            'inicio_mod'    => $mod['ini_mod'],
                            'fin_mod'       => $mod['fin_mod'],
                            'observacion'   => 'SIN NOVEDAD '.$mod['modulo'],
                            'estado'        => 1,
                            'sysuser'       => Auth::user()->id
                        ]);

                        foreach ($mod['materias'] as $mat) {
                            $modulo_materia = ModuloMateria::create([
                                'diplomod_id'   => $diplomado_modulo->id,
                                'orden'         => $mat['numero'],
                                'materia'       => $mat['nombre'],
                                'docente'       => $mat['docente'],
                                'observacion'   => 'SIN NOVEDAD '.$mat['numero'],
                                'estado'        => 1,
                                'sysuser'       => Auth::user()->id
                            ]);
                        }
                    }
                }
            DB::commit();
                return response()->json([
                    'success' => true,
                    'mensaje' => 'Promoción registrada correctamente',
                    'diplomado_promo' => $diplomado_promocion
                    // 'situacion_evento' => $situacion_evento
                ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'mensaje' => 'Ocurrió un error al registrar la promoción',
                'error'   => $e->getMessage()
            ], 500);
        }

    }

    public function ModificarDiplomadoPromocion (Request $request) {

    }

    public function EliminarDiplomadoPromocion (Request $request) {

    }

    public function ListarDiplomadoPromocion (Request $request) {
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
                    ->paginate(10);

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

    public function UltimoDiplomadoPromocion (Request $request) {
        // $gestion_max = DB::table('diplomado_promociones as dp')
        //                 ->select('gestion')
        //                 ->where('diplomado_id', $request->diplomado)
        //                 ->where('estado', 1)
        //                 ->orderBy('gestion', 'desc')
        //                 ->first();

        $ultimo_promocion = DB::table('diplomado_promociones as dp')
                    ->join('diplomados as d', 'dp.diplomado_id', 'd.id')
                    ->join('periodos as p', 'dp.periodo_id', 'p.id')
                    ->select('dp.id',
                            'd.diplomado',
                            'dp.promo',
                            'dp.promocion',
                            'dp.gestion')
                    ->where('d.estado', 1)
                    ->where('dp.estado', 1)
                    ->where('p.estado', 1)
                    ->where('dp.diplomado_id', $request->diplomado)
                    ->orderBy('dp.gestion', 'desc')
                    // ->orderBy('d.id', 'asc')
                    ->first();

        return response()->json(['ultimopromo' => $ultimo_promocion]); 
    }

    public function SelectDiplomadoPromocion (Request $request) {
        $diplomado_promocion = DB::table('diplomado_promociones as dp')
                    ->select('dp.*',)
                    ->where('dp.estado', 1)
                    ->where('dp.diplomado_id', $request->especialidad)
                    ->where('dp.gestion', $request->gestion)
                    ->orderBy('dp.promocion', 'desc')
                    ->get();

        return response()->json(['promocion' => $diplomado_promocion]); 
    }
}
