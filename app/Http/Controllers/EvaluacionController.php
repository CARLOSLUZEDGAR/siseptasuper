<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EvaluacionController extends Controller
{
    public function CrearEvaluacion (Request $request) {

    }

    public function ModificarEvaluacion (Request $request) {

    }

    public function EliminarEvaluacion (Request $request) {

    }

    public function ListarEvaluacion (Request $request) {
        $evaluacion = DB::table('evaluaciones as e')
                    ->select('e.*')
                    ->where('e.estado', 1)
                    ->orderBy('e.id', 'asc')
                    ->get();

        return response()->json(['evaluacion' => $evaluacion]);
    }
}
