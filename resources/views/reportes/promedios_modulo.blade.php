<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ANTIGUEDAD MODULO</title>
    
    <!-- CSS only -->
{{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous"> --}}
    <style>
        @page{
            /*margin: 0rem;
            margin-top:0rem;
            padding: 1rem;*/
            margin: 0cm 0cm;
        }
        body {
            /*margin: 0px;*/
            margin-top: 2cm;
            margin-bottom: 2cm;
            /** margenes de izquierda y derecha es menos un cm*/
            margin-left: 3cm;
            margin-right: 2cm;
            /* border: 1px solid #C00; */
        }
        header {
            position: fixed;
            top: 0cm;
            left: 3cm;
            right:2cm;
            height: 2cm;
            /*background-color: #3498DB;*/
            color: black;
            text-align: right;
            /*line-height: 10px;
            font-size: 9px;
            border: 1px solid #C00;*/
            padding-top: 0px;
            padding-bottom: 0px;
        }

        footer {
            position: fixed;
            bottom: 0cm;
            left: 3cm;
            right: 2cm;
            height: 2cm;
            /*background-color: #3498DB;*/
            color: black;
            /*text-align: right;*/
            line-height: 24px;
            /*font-size: 9px;*/
            /* border: 1px solid #C00; */
        }

        * {
            font-family: verdana, arial, sans-serif;
        }

        .reprobado {
            color: #FF0000 !important;
        }
    </style>

</head>
<body>

<header>        
    <!-- <div style="padding-top: 22px; padding-bottom: 1px;">
        <table width="100%" style="border-collapse: collapse; border-spacing: 0; /*border: black 1px;*/margin-bottom: 0px; " class="table table-bordered table-striped">
            <tbody class="table-striped">
                <tr>
                    <td style="/*border: 1px solid #000;*/ vertical-align: bottom;">
                        <div style="text-align: center; width: 230px; font-size: 11.5px; /*border: 1px solid #C00;*/">
                            <p style="margin: 1px;">FUERZA AÉREA BOLIVIANA</p>
                            <p style="margin: 1px;">DIRECCIÓN GENERAL DE AERONAVES DE ESTADO</p>
                            <p style="margin: 1px;"><u><strong>BOLIVIA</strong></u></p>
                        </div>
                    </td>
                    <td style="/*border: 1px solid #000;*/ vertical-align: bottom;">
                        <div style="padding-top: 0px; padding-bottom: 0px; text-align: right;">
                        </div>
                    </td>
                </tr>  
            </tbody>
        </table>
    </div> -->
</header> 

<footer>
    <div>
        <table width="100%" style="border-collapse: collapse; 
            border-spacing: 0; 
            /* border: black 1px; */
            margin-bottom: 5px;" class="table table-bordered table-striped">
            <tbody class="table-striped">
                <tr>
                    <td style="/*border: 1px solid #000;*/ padding-top: 25px; padding-bottom: 25px; font-size: 10pt; text-align: left; font-weight: bold;">
                        <span>Fecha de Emisión: 
                            <?php
                                $meses = array("Ene","Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic");
                                $fecha = date('dHi')."-".$meses[date('n')-1]."-".date('Y');
                                echo $fecha;
                            ?>
                        </span>
                    </td>
                    <td style="/*border: 1px solid #000;*/ padding: 0px; font-size: 10pt; text-align: right; font-weight: bold;">
                        <span>EPTA - 
                            <?php
                                $gestion_act = date("Y");
                                echo $gestion_act;
                            ?>
                        </span>
                    </td>
                </tr>  
            </tbody>
        </table>
    </div>
</footer>
    <main>  
    
        <div class="cabecera">
            <div style="padding-top: 0px; padding-bottom: 0.5cm;">
                <table width="100%" style="border-collapse: collapse; border-spacing: 0; /*border: black 1px;*/ margin-bottom: 0px; " class="table table-bordered table-striped">
                    <tbody class="table-striped">
                        <tr>
                            <td style="/*border: 1px solid #000;*/ vertical-align: top;">
                                <div style="text-align: center; width: 8cm; font-size: 10pt; /*border: 1px solid #C00;*/">
                                    <p style="margin: 1px;">FUERZA AÉREA BOLIVIANA</p>
                                    <p style="margin: 1px;">ESCUELA DE PERFECCIONAMIENTO TECNICO AERONAUTICO</p>
                                    <p style="margin: 1px;"><u><strong>BOLIVIA</strong></u></p>
                                </div>
                            </td>
                            <td style="/*border: 1px solid #000;*/ vertical-align: top;">
                                <div style="padding-top: 0px; padding-bottom: 0px; text-align: right;">
                                </div>
                            </td>
                        </tr>  
                    </tbody>
                </table>
            </div>


            <div style="padding-top: 2px; padding-bottom: 2px; text-align: center; font-size: 12pt; font-weight: bold; /*border: 1px solid #C00;*/">
                <p style="margin: 1px">PROMEDIOS FINALES DEL {{ $modulo }} MÓDULO </p>
            </div>

            @if (sizeof($cursantes) != 0)
                <table width="100%" style="border-collapse: collapse; border-spacing: 0; border: black 1px; margin-bottom: 5px; padding-top: 2px; padding-bottom: 1px;" class="table table-bordered table-striped">
                    <thead style="background-color: lightgray;">
                        <tr>
                            <th style="text-align: center; border: 1px solid #000; height:30px; font-size: 9pt;">Nº</th>
                            <th style="text-align: center; border: 1px solid #000; height:30px; font-size: 9pt;">GRADO</th>
                            <th style="text-align: center; border: 1px solid #000; height:30px; font-size: 9pt;">AP. PETERNO</th>
                            <th style="text-align: center; border: 1px solid #000; height:30px; font-size: 9pt;">AP. MATERNO</th>
                            <th style="text-align: center; border: 1px solid #000; height:30px; font-size: 9pt;">NOMBRES</th>

                            @foreach ($materias as $materia)
                                <th style="text-align: center; border: 1px solid #000; height:30px; font-size: 9pt;">{{ $materia->materia }}</th>
                            @endforeach

                            <th style="text-align: center; border: 1px solid #000; height:30px; font-size: 9pt;">PROM. FINAL</th> 
                        </tr>
                    </thead>
                    <tbody class="table-striped">
                        <?php
                            $x = 0;
                        ?>
                        @foreach($cursantes as $row)
                        <?php
                            $x = $x + 1;
                        ?> 
                        <tr>
                            <td style="border: 1px solid #000; padding: 2px; font-size: 9pt; text-align: center;">{{ $x }}</td>
                            <td style="border: 1px solid #000; padding: 2px; font-size: 9pt;">{{ $row->abreviatura }}</td>
                            <td style="border: 1px solid #000; padding: 2px; font-size: 9pt;">{{ $row->per_paterno }}</td>
                            <td style="border: 1px solid #000; padding: 2px; font-size: 9pt;">{{ $row->per_materno }}</td>
                            <td style="border: 1px solid #000; padding: 2px; font-size: 9pt;">{{ $row->per_nombre }}</td>

                            @foreach ($materias as $materia)
                                @php
                                    $promedio = $row->promedios_materias->{$materia->id} ?? 0;
                                @endphp

                                <td style="border: 1px solid #000; padding: 2px; font-size: 9pt; text-align: center;" class="{{ $promedio < 51 ? 'reprobado' : '' }}">
                                    {{ number_format($promedio, 2) }}
                                </td>
                            @endforeach

                            <td style="border: 1px solid #000; padding: 2px; font-size: 9pt; font-weight: bold; text-align: center;" class="promedio-final {{ $row->promedio_final < 51 ? 'reprobado' : '' }}">
                                {{ number_format($row->promedio_final, 2) }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div style="padding-top: 0.5cm; padding-bottom: 0px; text-align: center; /*position: fixed; bottom: 2cm; left: 3cm; right: 2cm;*/ /*font-size: 14px; font-weight: bold;*/ /*border: 1px solid #C00;*/">
                    <p style="margin: 1px; font-size: 12pt; font-weight: bold;">NO SE TIENE REGISTROS ENTRE LOS RANGOS SELECCIONADOS.</p>
                </div>
            @endif
            <div style="padding-top: 0cm; padding-bottom: 1px; text-align: right; font-size: 12pt; font-weight: normal; /*border: 1px solid #C00;*/">
                Carcaje, 
                    <?php
                        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
                        echo date('d')." de ".$meses[date('n')-1]." del ".date('Y');
                    ?>
            </div>

            <div style="padding-top: 3cm; padding-bottom: 0px; text-align: center; /*position: fixed; bottom: 2cm; left: 3cm; right: 2cm;*/ /*font-size: 14px; font-weight: bold;*/ /*border: 1px solid #C00;*/">
                <p style="margin: 1px; font-size: 12pt;">Cnl. DAEN. Boris Juan Velez Olaguivel</p>
                <p style="margin: 1px; font-size: 12pt; font-weight: bold;">COMANDANTE DE LA ESCUELA DE PERFECCIONAMIENTO</p>
                <p style="margin: 1px; font-size: 12pt; font-weight: bold;">TECNICO AERONAUTICO</p>
            </div>
        </div>
    </main>
    
    <script type="text/php">
            if(isset($pdf)) {
                $pdf->page_script('
                    $font = $fontMetrics->get_font("Arial, Helvetica, sans-serif", "normal");
                    $pdf->text(315, 759, "$PAGE_NUM - $PAGE_COUNT", $font, 10);
                ');
            }
    </script>

</body>

</html>
