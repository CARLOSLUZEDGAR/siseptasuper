<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GradoController extends Controller
{
    public function CrearGrado (Request $request) {

    }

    public function ModificarGrado (Request $request) {

    }

    public function EliminarGrado (Request $request) {

    }

    public function ListarGrado (Request $request) {
        $grado = DB::table('grados as g')
                    ->select('g.*')
                    ->where('g.estado', 1)
                    ->where('fuerza_id', $request->fuerza)
                    ->orderBy('g.id', 'asc')
                    ->get();

        return response()->json(['grado' => $grado]);
    }

    public function SelectGrado (Request $request) {
        $grado = DB::table('grados as g')
                    ->select('g.*')
                    ->where('g.estado', 1)
                    ->where('g.id', $request->gradoid)
                    ->first();

        return response()->json(['grado' => $grado]);
    }
}
