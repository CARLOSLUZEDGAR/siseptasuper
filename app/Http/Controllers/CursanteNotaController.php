<?php

namespace App\Http\Controllers;

use App\CursanteNota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CursanteNotaController extends Controller
{
    public function CrearCursanteNota(Request $request) {
        $request->validate([
            'promocion' => 'required|integer',
            'materia' => 'required|integer',

            'notas' => 'required|array|min:1',
            'notas.*.diplopromocursante_id' => 'required|integer',
            'notas.*.evaluacion_id' => 'required|integer|in:1,2,3,4',
            'notas.*.nota' => 'required|numeric|min:0|max:100',
        ]);

        try {
            DB::beginTransaction();

            $registradas = 0;

            foreach ($request->notas as $item) {

                // Comprobar que el cursante pertenece a la promoción.
                $cursante = DB::table('diplopromo_cursantes')
                    ->where('id', $item['diplopromocursante_id'])
                    ->where('diplopromo_id', $request->promocion)
                    ->where('estado', 1)
                    ->first();

                if (!$cursante) {
                    throw new \Exception(
                        'Uno de los cursantes no pertenece a la promoción seleccionada.'
                    );
                }

                // Verificar si ya existe una nota activa.
                $existe = DB::table('cursante_notas')
                    ->where(
                        'diplopromocursante_id',
                        $cursante->id
                    )
                    ->where('materia_id', $request->materia)
                    ->where('evaluacion_id', $item['evaluacion_id'])
                    ->where('estado', 1)
                    ->exists();

                // No insertar ni modificar una nota existente.
                if ($existe) {
                    continue;
                }

                CursanteNota::create([
                    'materia_id' => $request->materia,
                    'diplopromocursante_id' => $cursante->id,
                    'personals_id' => $cursante->personals_id,
                    'evaluacion_id' => $item['evaluacion_id'],
                    'nota' => $item['nota'],
                    'observacion' => 'REGISTRO SIN NOVEDAD',
                    'estado' => 1,
                    'sysuser' => Auth::user()->id,
                ]);

                $registradas++;
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'mensaje' => $registradas > 0
                    ? "Se registraron {$registradas} notas correctamente."
                    : 'No había notas nuevas para registrar.',
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'mensaje' => 'Ocurrió un error al registrar las notas.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function ModificarCursanteNota (Request $request) {

    }

    public function EliminarCursanteNota (Request $request) {

    }

    public function ListarPromediosModulo(Request $request) {
        $request->validate([
        'promocion' => 'required|integer',
        'modulo' => 'required|integer',
        ]);

        // 1. Obtener las materias del módulo seleccionado.
        $materias = DB::table('modulo_materias as mm')
            ->select(
                'mm.id',
                'mm.materia'
            )
            ->where('mm.diplomod_id', $request->modulo)
            ->get();

        if ($materias->isEmpty()) {
            return response()->json([
                'materias' => [],
                'cursantes' => [],
                'mensaje' => 'El módulo seleccionado no tiene materias.'
            ]);
        }

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
            ->orderBy('p.id', 'asc')
            ->get();

        if ($cursantes->isEmpty()) {
            return response()->json([
                'materias' => $materias,
                'cursantes' => []
            ]);
        }

        // 3. Consultar las notas registradas para esas materias y cursantes.
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

        // Agrupar las notas por cursante y materia.
        $notasAgrupadas = $notas->groupBy(function ($nota) {
            return $nota->diplopromocursante_id . '-' . $nota->materia_id;
        });

        // Ponderaciones de cada evaluación.
        $ponderaciones = [
            1 => 0.25, // Primer parcial
            2 => 0.25, // Segundo parcial
            3 => 0.40, // Examen final
            4 => 0.10, // Apreciación docente
        ];

        // 4. Calcular el promedio de cada materia y el promedio del módulo.
        foreach ($cursantes as $cursante) {
            $promediosMaterias = [];
            $sumaPromedios = 0;

            foreach ($materias as $materia) {
                $clave = $cursante->diplopromocursante_id
                    . '-' . $materia->id;

                $notasMateria = $notasAgrupadas->get($clave, collect());

                // Si existen varias filas de una misma evaluación,
                // se toma la primera. Lo ideal es impedir duplicados en BD.
                $notasPorEvaluacion = $notasMateria->keyBy('evaluacion_id');

                $promedioMateria = 0;

                foreach ($ponderaciones as $evaluacionId => $porcentaje) {
                    if ($notasPorEvaluacion->has($evaluacionId)) {
                        $nota = $notasPorEvaluacion->get($evaluacionId)->nota;

                        if ($nota !== null) {
                            $promedioMateria += (float) $nota * $porcentaje;
                        }
                    }
                }

                $promedioMateria = round($promedioMateria, 2);

                // Guardar el promedio asociado al ID de la materia.
                $promediosMaterias[$materia->id] = $promedioMateria;

                // Sumar el promedio de la materia al promedio del módulo.
                $sumaPromedios += $promedioMateria;
            }

            $cantidadMaterias = $materias->count();

            $cursante->promedios_materias = (object) $promediosMaterias;

            $cursante->promedio_final = $cantidadMaterias > 0
                ? round($sumaPromedios / $cantidadMaterias, 2)
                : 0;
        }

        // Ordenar del promedio más alto al más bajo.
        $cursantes = $cursantes
            ->sortByDesc('promedio_final')
            ->values();

        return response()->json([
            'materias' => $materias,
            'cursantes' => $cursantes
        ]);

    }

    public function SelectCursanteNota (Request $request) {
        $diplomado_modulo = DB::table('diplomado_modulos as dm')
                    ->select('dm.*',)
                    ->where('dm.estado', 1)
                    ->where('dm.diplopromo_id', $request->promocion)
                    // ->where('dp.gestion', $request->gestion)
                    ->orderBy('dm.modulo', 'asc')
                    ->get();

        return response()->json(['modulo' => $diplomado_modulo]); 
    }
}
