<?php

namespace App\Http\Controllers;

use App\Diplomado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DiplomadoController extends Controller
{
    public function CrearDiplomado (Request $request) {
        try {
            // VALIDACIONES
            $request->validate([
                'especialidad' => 'required',
                'abreviatura'  => 'required'
            ]);
            // VERIFICAR SI EXISTE
            $existe = DB::table('diplomados')
                ->where('diplomado', $request->especialidad)
                ->exists();

            if ($existe) {
                return response()->json([
                    'success' => false,
                    'mensaje' => 'La especialidad ya se encuentra registrada'
                ], 200);
            }
            
            // INICIAR TRANSACCIÓN
            DB::beginTransaction();
            // REGISTRAR CLASIFICACION
            $especialidad = Diplomado::create([
                'diplomado'     => mb_strtoupper($request->especialidad),
                'abreviatura'   => mb_strtoupper($request->abreviatura),
                'descripcion'   => mb_strtoupper($request->descripcion),
                'observacion'   => 'SIN NOVEDAD',
                'estado'        => 1,
                'sysuser'       => Auth::user()->id
            ]);
            // CONFIRMAR
            DB::commit();

            return response()->json([
                'success'       => true,
                'mensaje'       => 'Especialidad registrada correctamente',
                'especialidad' => $especialidad,
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'mensaje' => 'Ocurrió un error al registrar la especialidad',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function ModificarDiplomado (Request $request) {

    }

    public function EliminarDiplomado (Request $request) {

    }

    public function ListarDiplomado (Request $request) {

        $buscar = $request->buscar;
        if ($buscar == '') {
            $diplomado = DB::table('diplomados as d')
                    ->select('d.*')
                    ->where('d.estado', 1)
                    ->orderBy('d.id', 'asc')
                    ->paginate(10);

            return response()->json([
                'pagination' => [
                    'total'         => $diplomado->total(),
                    'current_page'  => $diplomado->currentPage(),
                    'per_page'      => $diplomado->perPage(),
                    'last_page'     => $diplomado->lastPage(),
                    'from'          => $diplomado->firstItem(),
                    'to'            => $diplomado->lastItem(),
                ],
                'diplomado' => $diplomado
            ]); 
        } else {
            $diplomado = DB::table('diplomados as d')
                    ->select('d.*')
                    ->where('d.estado', 1)
                    ->where(function($q) use ($buscar){
                        $q->where('d.diplomado','LIKE','%'.$buscar.'%');
                    }) 
                    ->orderBy('d.id', 'asc')
                    ->paginate(10);

            return response()->json([
                'pagination' => [
                    'total'         => $diplomado->total(),
                    'current_page'  => $diplomado->currentPage(),
                    'per_page'      => $diplomado->perPage(),
                    'last_page'     => $diplomado->lastPage(),
                    'from'          => $diplomado->firstItem(),
                    'to'            => $diplomado->lastItem(),
                ],
                'diplomado' => $diplomado
            ]); 
        }
    }

    public function SelectDiplomado (Request $request) {
        $diplomado = DB::table('diplomados as d')
                    ->select('d.*')
                    ->where('d.estado', 1)
                    ->orderBy('d.id', 'asc')
                    ->get();

        // return response()->json([
        //     'pagination' => [
        //         'total'         => $diplomado->total(),
        //         'current_page'  => $diplomado->currentPage(),
        //         'per_page'      => $diplomado->perPage(),
        //         'last_page'     => $diplomado->lastPage(),
        //         'from'          => $diplomado->firstItem(),
        //         'to'            => $diplomado->lastItem(),
        //     ],
        //     'diplomado' => $diplomado
        // ]); 

        return response()->json(['diplomado' => $diplomado]);
    }

}
