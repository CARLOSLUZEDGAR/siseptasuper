<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DiplomadoPromocionCursanteController extends Controller
{
    public function CrearDiplomadoPromocionCursante (Request $request) {
        
    }

    public function ModificarDiplomadoPromocionCursante (Request $request) {

    }

    public function EliminarDiplomadoPromocionCursante (Request $request) {

    }

    public function ListarDiplomadoPromocionCursante(Request $request) {
        $request->validate([
            'promocion' => 'required|integer',
            'materia' => 'required|integer',
        ]);

        $cursantes = DB::table('diplopromo_cursantes as dpc')
            ->join('personals as p', 'p.id', '=', 'dpc.personals_id')
            ->join('grados as g', 'g.id', '=', 'p.per_grado_id')
            ->select(
                'dpc.id as diplopromocursante_id',
                'dpc.personals_id',
                'g.abreviatura',
                'p.per_paterno',
                'p.per_materno',
                'p.per_nombre'
            )
            ->where('dpc.estado', 1)
            ->where('dpc.diplopromo_id', $request->promocion)
            ->where('p.estado', 1)
            ->orderBy('p.id', 'asc')
            ->get();

        $idsCursantes = $cursantes->pluck('diplopromocursante_id');

        $notas = DB::table('cursante_notas')
            ->where('materia_id', $request->materia)
            ->where('estado', 1)
            ->whereIn('diplopromocursante_id', $idsCursantes)
            ->get();

        // Relacionar cada nota con su cursante y evaluación.
        $notasPorClave = [];

        foreach ($notas as $nota) {
            $clave = $nota->diplopromocursante_id . '-' .
                    $nota->evaluacion_id;

            // Si hay registros duplicados históricos, conservar
            // el primero encontrado para mostrarlo.
            if (!isset($notasPorClave[$clave])) {
                $notasPorClave[$clave] = $nota;
            }
        }

        $campos = [
            1 => 'pri_parcial',
            2 => 'seg_parcial',
            3 => 'ex_final',
            4 => 'apr_docente',
        ];

        $cursantes = $cursantes->map(function ($personal) use (
            $notasPorClave,
            $campos
        ) {
            foreach ($campos as $evaluacionId => $campo) {
                $clave = $personal->diplopromocursante_id . '-' .
                        $evaluacionId;

                $existe = isset($notasPorClave[$clave]);

                $personal->{$campo} = $existe
                    ? $notasPorClave[$clave]->nota
                    : null;

                $personal->{$campo . '_registrada'} = $existe;
            }

            return $personal;
        });

        return response()->json([
            'cursante' => $cursantes,
        ]);
    }
}
