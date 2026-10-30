<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade as PDF;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Auth;


class ReporteController extends Controller
{
    public function ReporteAntiguedadModulo(Request $request) {
    
        $request->validate([
            'promocion' => 'required|integer',
            'modulo' => 'required|integer',
            'diplomado' => 'required|integer',
            'gestion' => 'required'
        ]);

        $especialidad = DB::table('diplomados as d')
                    ->select('d.id',
                            'd.diplomado',
                            'd.abreviatura',
                            'd.descripcion')
                    ->where('d.estado', 1)
                    ->where('d.id', $request->diplomado)
                    ->orderBy('d.id', 'asc')
                    ->first(); 

        $promocion = DB::table('diplomado_promociones as dp')
                    ->select('dp.promo',)
                    ->where('dp.estado', 1)
                    ->where('dp.id', $request->promocion)
                    ->first();

        $modulo = DB::table('diplomado_modulos as dm')
                ->select('dm.*')
                ->where('dm.id', $request->modulo)
                ->first();

        $ord_modulo = $modulo->modulo;

        switch ((int) $ord_modulo) {
            case 1:
                $numero = 'PRIMER';
                break;
            case 2:
                $numero = 'SEGUNDO';
                break;
            case 3:
                $numero = 'TERCER';
                break;
            case 4:
                $numero = 'CUARTO';
                break;
            case 5:
                $numero = 'QUINTO';
                break;
            case 6:
                $numero = 'SEXTO';
                break;
            case 7:
                $numero = 'SÉPTIMO';
                break;
            case 8:
                $numero = 'OCTAVO';
                break;
            case 9:
                $numero = 'NOVENO';
                break;
            case 10:
                $numero = 'DÉCIMO';
                break;
            default:
                $numero = 'DESCONOCIDO';
        }

        // 1. Obtener las materias del módulo.
        $materias = DB::table('modulo_materias as mm')
            ->select('mm.id', 'mm.materia')
            ->where('mm.diplomod_id', $request->modulo)
            ->get();

        // 2. Obtener los cursantes de la promoción.
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
            ->get();

        // 3. Obtener las notas registradas.
        $notas = DB::table('cursante_notas')
            ->whereIn(
                'diplopromocursante_id',
                $cursantes->pluck('diplopromocursante_id')
            )
            ->whereIn('materia_id', $materias->pluck('id'))
            ->where('estado', 1)
            ->get([
                'diplopromocursante_id',
                'materia_id',
                'evaluacion_id',
                'nota'
            ]);

        $notasAgrupadas = $notas->groupBy(function ($nota) {
            return $nota->diplopromocursante_id . '-' . $nota->materia_id;
        });

        $ponderaciones = [
            1 => 0.25,
            2 => 0.25,
            3 => 0.40,
            4 => 0.10,
        ];

        // 4. Calcular los promedios por materia y por cursante.
        foreach ($cursantes as $cursante) {
            $promediosMaterias = [];
            $sumaPromedios = 0;

            foreach ($materias as $materia) {
                $clave = $cursante->diplopromocursante_id
                    . '-' . $materia->id;

                $notasMateria = $notasAgrupadas->get($clave, collect());
                $notasPorEvaluacion = $notasMateria->keyBy('evaluacion_id');

                $promedioMateria = 0;

                foreach ($ponderaciones as $evaluacionId => $porcentaje) {
                    if ($notasPorEvaluacion->has($evaluacionId)) {
                        $nota = $notasPorEvaluacion
                            ->get($evaluacionId)->nota;

                        if ($nota !== null) {
                            $promedioMateria +=
                                (float) $nota * $porcentaje;
                        }
                    }
                }

                $promedioMateria = round($promedioMateria, 2);

                $promediosMaterias[$materia->id] = $promedioMateria;
                $sumaPromedios += $promedioMateria;
            }

            $cantidadMaterias = $materias->count();

            $cursante->promedios_materias = (object) $promediosMaterias;

            $cursante->promedio_final = $cantidadMaterias > 0
                ? round($sumaPromedios / $cantidadMaterias, 2)
                : 0;
        }

        // 5. Ordenar del promedio más alto al más bajo.
        $cursantes = $cursantes
            ->sortByDesc('promedio_final')
            ->values();

        // 6. Generar el PDF usando una vista Blade.
        $pdf = PDF::loadView('reportes.promedios_modulo', [
            'especialidad' => $especialidad,
            'materias' => $materias,
            'cursantes' => $cursantes,
            'promocion' => $promocion,
            'modulo' => $numero,
        ])->setPaper('letter', 'portrait');

        return $pdf->stream('reporte_promedios_modulo.pdf');
    }

    public function ReporteAntiguedadGral(Request $request) {
        $request->validate([
            'promocion' => 'required|integer',
            'diplomado' => 'required|integer',
        ]);

        $especialidad = DB::table('diplomados as d')
                    ->select('d.id',
                            'd.diplomado',
                            'd.abreviatura',
                            'd.descripcion')
                    ->where('d.estado', 1)
                    ->where('d.id', $request->diplomado)
                    ->orderBy('d.id', 'asc')
                    ->first(); 

        $promocion = DB::table('diplomado_promociones as dp')
                    ->select('dp.promo',)
                    ->where('dp.estado', 1)
                    ->where('dp.id', $request->promocion)
                    ->first();

        // 1. Obtener todos los módulos activos de la promoción.
        $modulos = DB::table('diplomado_modulos as dm')
            ->select(
                'dm.id',
                'dm.modulo'
            )
            ->where('dm.diplopromo_id', $request->promocion)
            ->where('dm.estado', 1)
            ->orderBy('dm.modulo', 'asc')
            ->get();

        if ($modulos->isEmpty()) {
            return response()->json([
                'modulos' => [],
                'cursantes' => [],
                'mensaje' => 'La promoción no tiene módulos registrados.'
            ]);
        }

        // 2. Obtener las materias activas de todos los módulos.
        $materias = DB::table('modulo_materias as mm')
            ->select(
                'mm.id',
                'mm.diplomod_id',
                'mm.materia',
                'mm.orden'
            )
            ->whereIn('mm.diplomod_id', $modulos->pluck('id'))
            ->where('mm.estado', 1)
            ->orderBy('mm.orden', 'asc')
            ->get();

        // Agrupar las materias por módulo.
        $materiasPorModulo = $materias->groupBy('diplomod_id');

        // 3. Obtener los cursantes de la promoción.
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

        if ($cursantes->isEmpty()) {
            return response()->json([
                'modulos' => $modulos,
                'cursantes' => []
            ]);
        }

        // 4. Consultar las notas de todas las materias de la promoción.
        $notas = collect();

        if ($materias->isNotEmpty()) {
            $notas = DB::table('cursante_notas')
                ->whereIn(
                    'diplopromocursante_id',
                    $cursantes->pluck('diplopromocursante_id')
                )
                ->whereIn('materia_id', $materias->pluck('id'))
                ->where('estado', 1)
                ->get([
                    'diplopromocursante_id',
                    'materia_id',
                    'evaluacion_id',
                    'nota'
                ]);
        }

        // Agrupar las notas por cursante y materia.
        $notasAgrupadas = $notas->groupBy(function ($nota) {
            return $nota->diplopromocursante_id
                . '-'
                . $nota->materia_id;
        });

        // Ponderaciones de las evaluaciones.
        $ponderaciones = [
            1 => 0.25,
            2 => 0.25,
            3 => 0.40,
            4 => 0.10,
        ];

        // 5. Calcular los promedios de módulos para cada cursante.
        foreach ($cursantes as $cursante) {

            $promediosModulos = [];
            $sumaPromediosModulos = 0;
            $cantidadModulos = $modulos->count();

            foreach ($modulos as $modulo) {

                $materiasModulo = $materiasPorModulo->get(
                    $modulo->id,
                    collect()
                );

                $sumaPromediosMaterias = 0;
                $cantidadMaterias = $materiasModulo->count();

                foreach ($materiasModulo as $materia) {

                    $clave = $cursante->diplopromocursante_id
                        . '-'
                        . $materia->id;

                    $notasMateria = $notasAgrupadas->get(
                        $clave,
                        collect()
                    );

                    // Tomar una nota por evaluación.
                    $notasPorEvaluacion = $notasMateria
                        ->keyBy('evaluacion_id');

                    $promedioMateria = 0;

                    foreach ($ponderaciones as $evaluacionId => $porcentaje) {

                        if ($notasPorEvaluacion->has($evaluacionId)) {

                            $nota = $notasPorEvaluacion
                                ->get($evaluacionId)
                                ->nota;

                            if ($nota !== null) {
                                $promedioMateria +=
                                    (float) $nota * $porcentaje;
                            }
                        }
                    }

                    $sumaPromediosMaterias += $promedioMateria;
                }

                // Promedio del módulo = promedio de sus materias.
                $promedioModulo = $cantidadMaterias > 0
                    ? round(
                        $sumaPromediosMaterias / $cantidadMaterias,
                        2
                    )
                    : 0;

                // Asociar el promedio al ID del módulo.
                $promediosModulos[$modulo->id] = $promedioModulo;

                $sumaPromediosModulos += $promedioModulo;
            }

            $cursante->promedios_modulos = (object) $promediosModulos;

            // Promedio final = promedio de todos los módulos.
            $cursante->promedio_final = $cantidadModulos > 0
                ? round(
                    $sumaPromediosModulos / $cantidadModulos,
                    2
                )
                : 0;
        }

        // 6. Ordenar por promedio final descendente.
        $cursantes = $cursantes
            ->sortByDesc('promedio_final')
            ->values();

        // 7. Generar el PDF usando una vista Blade.
        $pdf = PDF::loadView('reportes.promedios_gral', [
            'especialidad' => $especialidad,
            'modulos' => $modulos,
            'cursantes' => $cursantes,
            'promocion' => $promocion,
            // 'modulo' => $numero,
        ])->setPaper('letter', 'portrait');

        return $pdf->stream('reporte_promedios_gral.pdf');
    }

    public function DownloadPDF(Request $request)
    {
        $filePath = storage_path("../document/personal/".$request->documento);
        if (!file_exists($filePath)) {
            return response()->json(['message' => 'File not found.'], 404);
        }
        return response()->download($filePath);
    }
}
