<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DiplomadoController extends Controller
{
    public function CrearDiplomado (Request $request) {

    }

    public function ModificarDiplomado (Request $request) {

    }

    public function EliminarDiplomado (Request $request) {

    }

    public function ListarDiplomado (Request $request) {
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
