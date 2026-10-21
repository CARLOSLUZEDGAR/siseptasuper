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
        ]);

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
            'materias' => $materias,
            'cursantes' => $cursantes,
            'promocion' => $request->promocion,
            'modulo' => $numero,
        ])->setPaper('letter', 'portrait');

        return $pdf->stream('reporte_promedios.pdf');

    }

    public function GenerarReportePersonal(Request $request)
    {
        $personal = DB::table('personals as p')
                    ->join('personal_licencias as pl','p.id','pl.id_personal')
                    ->join('nacionalidads as n','p.id_nacionalidad','n.id')
                    ->join('entidads as e','pl.id_entidad','e.id')
                    ->join('grados as g','pl.id_grado','g.id')
                    ->join('licencias as l','pl.id_licencia','l.id')
                    // ->join('habilitacions as h','pl.id_habilitacion','h.id')
                    ->join('competencia_linguisticas as cl','pl.id_comp_linguistica','cl.id')
                    ->select('p.id as id_personal',
                        'n.nacionalidad',
                        'n.pais',
                        'p.per_foto',
                        'p.per_nombre',
                        'p.per_paterno',
                        'p.per_materno',
                        'p.per_ci',
                        'p.per_sexo',
                        'p.per_fecha_nacimiento',
                        'pl.id as id_licencia',
                        'pl.id_entidad',
                        'e.entidad',
                        'g.abreviatura',
                        'l.licencia',
                        'l.traduccion',
                        'pl.id_habilitacion',
                        // 'h.habilitacion',
                        // 'h.traduccion as htraduccion',
                        'cl.nivel',
                        'cl.traduccion as cltraduccion',
                        'pl.observacion',
                        'pl.fecha_emision',
                        'pl.fecha_expiracion'
                        )
                    ->where('p.id',$request->id_p)
                    ->where('pl.estado',1)
                    ->first();

        $arrayIdHabilitacionAct = json_decode($personal->id_habilitacion, true);
        $cantidad = count($arrayIdHabilitacionAct);

        $arrayHabilitacionesAct = DB::table('habilitacions')
                            ->select('id', 'habilitacion', 'traduccion')
                            ->whereIn('id', $arrayIdHabilitacionAct)
                            ->get();

        $personal_licencias = DB::table('personal_licencias as pl')
                            ->join('categorias as c','pl.id_categoria','c.id')
                            ->join('entidads as e','pl.id_entidad','e.id')
                            ->join('grados as g','pl.id_grado','g.id')
                            ->join('licencias as l','pl.id_licencia','l.id')
                            // ->join('habilitacions as h','pl.id_habilitacion','h.id')
                            ->join('competencia_linguisticas as cl','pl.id_comp_linguistica','cl.id')
                            ->select('pl.id',
                                    'c.categoria',
                                    'e.entidad',
                                    'g.abreviatura',
                                    'l.licencia',
                                    'pl.id_habilitacion',
                                    // 'h.habilitacion',
                                    'cl.nivel',
                                    'pl.fecha_emision')
                            ->where('pl.id_personal',$request->id_p)
                            ->orderBy('pl.fecha_emision','asc')
                            ->get();
        
        // Recorremos los resultados
        foreach ($personal_licencias as $pl) {
            // Decodificamos el JSON a array
            $arrayIdHabilitaciones = json_decode($pl->id_habilitacion, true);

            if (is_array($arrayIdHabilitaciones) && count($arrayIdHabilitaciones) > 0) {
                // Obtenemos las habilitaciones de esos IDs
                $arrayHabilitaciones = DB::table('habilitacions')
                    ->select('id', 'habilitacion', 'traduccion')
                    ->whereIn('id', $arrayIdHabilitaciones)
                    ->get();

                // Le agregamos la lista al objeto actual
                $pl->habilitaciones = $arrayHabilitaciones;
            } else {
                $pl->habilitaciones = collect(); // colección vacía
            }
        }

        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        $fecha_emision = date('d')."/".date('n')."/".date('Y');
        // $qr = QrCode::encoding('UTF-8')->size(100)->generate("No. LICENCIA: $personal->per_ci\nGRADO: $personal->abreviatura\nAPELLIDO(S): $personal->per_paterno $personal->per_materno\nNOMBRE(S): $personal->per_nombre\nTITULO: $personal->licencia\nHABILITACION: $personal->habilitacion\nFECHA: $fecha_emision");
        $qr = QrCode::encoding('UTF-8')->size(100)->generate("No. LICENCIA: $personal->per_ci\nGRADO: $personal->abreviatura\nAPELLIDO(S): $personal->per_paterno $personal->per_materno\nNOMBRE(S): $personal->per_nombre\nTITULO: $personal->licencia\nFECHA: $fecha_emision");
        $codigo = $qr;

        $pdf = PDF::loadView('reportes.dat_personal',['personal'=>$personal,
                                                    'arrayHabilitaciones' => $arrayHabilitaciones,
                                                    'qr'=>$codigo,
                                                    'licencias'=>$personal_licencias
                                                    ])
        //8.3cm 5cm
        ->setPaper('letter', 'portrait');                                               
        
        return $pdf->stream($personal->per_ci.'PERSONAL_DAT.pdf');
        // return $pdf->download($personal->id_licencia.'-'.$personal->per_ci.'.pdf');
    }

    public function GenerarReporteLicencias(Request $request)
    {
        
        $fechade = date_create($request->de);
        $de = date_format($fechade,"d/m/Y");

        $fechade = date_create($request->hasta);
        $hasta = date_format($fechade,"d/m/Y");
                                    
        // $de = $request->de;
        // $hasta = $request->hasta;

        $personal_licencias = DB::table('personal_licencias as pl')
                            ->join('categorias as c','pl.id_categoria','c.id')
                            ->join('entidads as e','pl.id_entidad','e.id')
                            ->join('grados as g','pl.id_grado','g.id')
                            ->join('licencias as l','pl.id_licencia','l.id')
                            // ->join('habilitacions as h','pl.id_habilitacion','h.id')
                            ->join('competencia_linguisticas as cl','pl.id_comp_linguistica','cl.id')
                            ->join('personals as p','pl.id_personal','p.id')
                            ->select('pl.id',
                                    'c.categoria',
                                    'e.entidad',
                                    'g.abreviatura',
                                    'l.licencia',
                                    'pl.id_habilitacion',
                                    // 'h.habilitacion',
                                    'cl.nivel',
                                    'pl.fecha_emision',
                                    'p.per_nombre',
                                    'p.per_paterno',
                                    'p.per_materno')
                            // ->where('pl.fecha_emision','2024-04-06')
                            ->whereBetween('pl.fecha_emision',[$de,$hasta])
                            ->orderBy('pl.fecha_emision','asc')
                            ->get();

        // Recorremos los resultados
        foreach ($personal_licencias as $pl) {
            // Decodificamos el JSON a array
            $arrayIdHabilitaciones = json_decode($pl->id_habilitacion, true);

            if (is_array($arrayIdHabilitaciones) && count($arrayIdHabilitaciones) > 0) {
                // Obtenemos las habilitaciones de esos IDs
                $arrayHabilitaciones = DB::table('habilitacions')
                    ->select('id', 'habilitacion', 'traduccion')
                    ->whereIn('id', $arrayIdHabilitaciones)
                    ->get();

                // Le agregamos la lista al objeto actual
                $pl->habilitaciones = $arrayHabilitaciones;
            } else {
                $pl->habilitaciones = collect(); // colección vacía
            }
        }

        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        $fecha_emision = date('d')."/".date('n')."/".date('Y');
        $qr = QrCode::encoding('UTF-8')->size(100)->generate("FECHA: $fecha_emision\nDE: $de\nHASTA: $hasta");
        $codigo = $qr;

        $pdf = PDF::loadView('reportes.list_licencias',['qr'=>$codigo,
                                                'licencias'=>$personal_licencias,
                                                'de'=>$de,
                                                'hasta'=>$hasta
                                                ])
        //8.3cm 5cm
        ->setPaper('letter', 'landscape');                                               
        
        return $pdf->stream($de.'-'.$hasta.'pdf');
        // return $pdf->download($personal->id_licencia.'-'.$personal->per_ci.'.pdf');
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
