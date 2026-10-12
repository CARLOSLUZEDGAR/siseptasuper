<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PeriodoController extends Controller
{
    public function CrearPeriodo (Request $request) {

    }

    public function ModificarPeriodo (Request $request) {

    }

    public function EliminarPeriodo (Request $request) {

    }

    public function ListarPeriodo (Request $request) {
        $periodo = DB::table('periodos as p')
                    ->select('p.*')
                    ->where('p.estado', 1)
                    ->orderBy('p.id', 'asc')
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

        return response()->json(['periodo' => $periodo]);
    }
}
