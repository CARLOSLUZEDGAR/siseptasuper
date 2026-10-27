<?php

namespace App\Http\Controllers;

use App\DiplomadoModulo;
use App\ModuloMateria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
                    ->where('dm.diplopromo_id', $request->diplopromo_id)
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
        $modulos = DB::table('diplomado_modulos as dm')
            ->select(
                'dm.id',
                'dm.modulo',
                'dm.inicio_mod',
                'dm.fin_mod'
            )
            ->where('dm.diplopromo_id', $request->diplopromo_id)
            ->where('dm.estado', 1)
            ->orderBy('dm.modulo', 'asc')
            ->get();

        foreach ($modulos as $modulo) {

            $materias = DB::table('modulo_materias as mm')
                ->select(
                    'mm.id',
                    'mm.materia',
                    'mm.docente'
                )
                ->where('mm.diplomod_id', $modulo->id)
                ->orderBy('mm.orden', 'asc')
                ->get();

            $modulo->materias = $materias->values()->map(
                function ($materia, $index) {
                    return [
                        'id'      => $materia->id,
                        'numero'  => $index + 1,
                        'nombre'  => $materia->materia,
                        'docente' => $materia->docente
                    ];
                }
            );
        }

        return response()->json([
            'modulo' => $modulos
        ]);
    }

    public function CrearNuevosModulosMaterias(Request $request)
{
    $request->validate([
        'diplopromo_id' => 'required|integer|exists:diplomado_promociones,id',
        'modulos' => 'present|array',
        'modulos.*.id' => 'nullable|integer',
        'modulos.*.modulo' => 'required',
        'modulos.*.ini_mod' => 'nullable|date',
        'modulos.*.fin_mod' => 'nullable|date',
        'modulos.*.materias' => 'sometimes|array',
        'modulos.*.materias.*.id' => 'nullable|integer',
        'modulos.*.materias.*.numero' => 'nullable|integer',
        'modulos.*.materias.*.nombre' => 'required|string',
        'modulos.*.materias.*.docente' => 'nullable|string',
    ]);

    try {
        DB::beginTransaction();

        $diplomado_modulo = null;

        foreach ($request->modulos as $mod) {

            /*
             * 1. ACTUALIZAR O REGISTRAR EL MÓDULO
             */

            if (!empty($mod['id'])) {

                // Buscar el módulo existente y verificar su promoción
                $diplomado_modulo = DiplomadoModulo::where(
                    'id',
                    $mod['id']
                )
                ->where('diplopromo_id', $request->diplopromo_id)
                ->where('estado', 1)
                ->first();

                if (!$diplomado_modulo) {
                    throw new \Exception(
                        'El módulo indicado no existe o no pertenece a esta promoción.'
                    );
                }

                // Actualizar únicamente las fechas
                $diplomado_modulo->inicio_mod = $mod['ini_mod'] ?? null;
                $diplomado_modulo->fin_mod = $mod['fin_mod'] ?? null;
                if ($diplomado_modulo->isDirty()) {
                    $diplomado_modulo->save();
                }

            } else {

                // Obtener el último número de módulo de esta promoción
    $ultimoModulo = DiplomadoModulo::where(
        'diplopromo_id',
        $request->diplopromo_id
    )->max('modulo');

    // Calcular el siguiente número
    $siguienteModulo = ((int) $ultimoModulo) + 1;

    // Registrar el nuevo módulo
    $diplomado_modulo = DiplomadoModulo::create([
        'diplopromo_id' => $request->diplopromo_id,
        'modulo' => $siguienteModulo,
        'inicio_mod' => $mod['ini_mod'] ?? null,
        'fin_mod' => $mod['fin_mod'] ?? null,
        'observacion' => 'MÓDULO DE LA PROMOCIÓN ' .
            $request->diplopromo_id,
        'estado' => 1,
        'sysuser' => Auth::user()->id,
    ]);
            }

            $moduloId = $diplomado_modulo->id;

            /*
             * 2. ACTUALIZAR O REGISTRAR LAS MATERIAS
             */

            foreach (($mod['materias'] ?? []) as $mat) {

                if (!empty($mat['id'])) {

                    // La materia debe pertenecer al módulo correspondiente
                    $modulo_materia = ModuloMateria::where(
                        'id',
                        $mat['id']
                    )
                    ->where('diplomod_id', $moduloId)
                    ->where('estado', 1)
                    ->first();

                    if (!$modulo_materia) {
                        throw new \Exception(
                            'La materia indicada no existe o no pertenece a este módulo.'
                        );
                    }

                    // Actualizar nombre y docente de la materia
                    $modulo_materia->orden = $mat['numero'] ??
                        $modulo_materia->orden;

                    $modulo_materia->materia = mb_strtoupper(
                        $mat['nombre']
                    );

                    $modulo_materia->docente = mb_strtoupper(
                        $mat['docente'] ?? ''
                    );

                    if ($modulo_materia->isDirty()) {
                        $modulo_materia->save();
                    }

                } else {

                    // Obtener el último orden de materias de este módulo
    $ultimoOrden = ModuloMateria::where(
        'diplomod_id',
        $moduloId
    )->max('orden');

    // Calcular el siguiente orden
    $siguienteOrden = ((int) $ultimoOrden) + 1;

    // Registrar la nueva materia
    ModuloMateria::create([
        'diplomod_id' => $moduloId,
        'orden' => $siguienteOrden,
        'materia' => mb_strtoupper($mat['nombre']),
        'docente' => mb_strtoupper($mat['docente'] ?? ''),
        'observacion' => 'MATERIA DEL MÓDULO ' .
            $diplomado_modulo->modulo .
            ' DE LA PROMOCIÓN ' .
            $request->diplopromo_id,
        'estado' => 1,
        'sysuser' => Auth::user()->id,
    ]);
                }
            }
        }

        DB::commit();

        return response()->json([
            'success' => true,
            'mensaje' => 'Los módulos y las materias se guardaron correctamente.',
        ], 200);

    } catch (\Throwable $e) {

        DB::rollBack();

        report($e);

        return response()->json([
            'success' => false,
            'mensaje' => 'Ocurrió un error al guardar los módulos y las materias.',
            'error' => $e->getMessage(),
        ], 500);
    }
}
}
