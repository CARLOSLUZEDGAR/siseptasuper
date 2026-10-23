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

    public function EditarDiplomado (Request $request) {
        try {
            // VALIDACIONES
            $request->validate([
                'id' => 'required',
                'especialidad' => 'required',
                'abreviatura'  => 'required',
            ]);
            // VERIFICAR SI LA ESPECIALIDAD EXISTE
            $existeEspecialidad = DB::table('diplomados')
                ->where('diplomado', $request->especialidad)
                ->where('id', '!=', $request->id)
                ->exists();

            if ($existeEspecialidad) {
                return response()->json([
                    'success' => false,
                    'mensaje' => 'La especialidad ya se encuentra registrado.'
                ], 200);
            }

            // INICIAR TRANSACCIÓN
            DB::beginTransaction();
            // EDITAR CLASIFICACION
            DB::table('diplomados')
                ->where('id', $request->id)
                ->update([
                    'diplomado'     => $request->especialidad,
                    'abreviatura'   => $request->abreviatura,
                    'descripcion'   => $request->descripcion,
                    'observacion'   => 'ESPECIALIDAD EDITADA',
                    'sysuser'       => Auth::user()->id,
                    'updated_at'    => now()
                ]);
            // CONFIRMAR
            DB::commit();

            return response()->json([
                'success' => true,
                'mensaje' => 'Especialidad editada correctamente'
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'mensaje' => 'Ocurrió un error al editar la especialidad',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function CambiarEstadoDiplomado (Request $request) {
        try {
            // VALIDACIONES
            $request->validate([
                'id'        => 'required',
                'estado'    => 'required',
            ]);
            
            $estado = 1 - $request->estado;
            // INICIAR TRANSACCIÓN
            DB::beginTransaction();
            // EDITAR CLASIFICACION
            DB::table('diplomados')
                ->where('id', $request->id)
                ->update([
                    'estado'        => $estado,
                    'sysuser'       => Auth::user()->id,
                    'updated_at'    => now()
                ]);
            // CONFIRMAR
            DB::commit();

            if($request->estado == 1) {
                return response()->json([
                    'success' => true,
                    'mensaje' => 'Especialidad deshabilitada correctamente'
                ], 200);
            }else {
                return response()->json([
                    'success' => true,
                    'mensaje' => 'Especialidad habilitada correctamente'
                ], 200);
            }

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'mensaje' => 'Ocurrió un error al cambiar de estado la especialidad',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function ListarDiplomado (Request $request) {

        $buscar = $request->buscar;
        if ($buscar == '') {
            $diplomado = DB::table('diplomados as d')
                    ->select('d.id',
                            'd.diplomado',
                            'd.abreviatura',
                            'd.descripcion',
                            'd.estado')
                    // ->where('d.estado', 1)
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
                    ->select('d.id',
                            'd.diplomado',
                            'd.abreviatura',
                            'd.descripcion',
                            'd.estado')
                    // ->where('d.estado', 1)
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

        return response()->json(['diplomado' => $diplomado]);
    }

    public function MostrarDiplomado (Request $request) {
        $especialidad = DB::table('diplomados as d')
                    ->select('d.id',
                            'd.diplomado',
                            'd.abreviatura',
                            'd.descripcion')
                    ->where('d.estado', 1)
                    ->where('d.id', $request->especialidadid)
                    ->orderBy('d.id', 'asc')
                    ->first(); 

        return response()->json(['especialidad' => $especialidad]);
    }

}
