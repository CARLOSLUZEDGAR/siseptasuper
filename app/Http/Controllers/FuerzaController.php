<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FuerzaController extends Controller
{
    public function CrearFuerza (Request $request) {

    }

    public function ModificarFuerza (Request $request) {

    }

    public function EliminarFuerza (Request $request) {

    }

    public function ListarFuerza (Request $request) {
        $fuerza = DB::table('fuerzas as f')
                    ->select('f.*')
                    ->where('f.estado', 1)
                    ->orderBy('f.id', 'asc')
                    ->get();

        return response()->json(['fuerza' => $fuerza]);
    }
}
