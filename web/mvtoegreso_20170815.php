<?php

if ( $parqueadero > 0 ) {
    
    if ( $parqueadero == 1 ) {
        
        $numerocajaabiertas = $parqueo->validarIngresoSalida(" SELECT COUNT(*) FROM BDMUF.TBFACIECAJ 
            WHERE ( USCODUSUA = TRIM('{$_SESSION['usrdb']}') OR USCODUSUAR = TRIM('{$_SESSION['usrdb']}') ) AND CAFECCAJA = '$fecha' AND MOCODMOTIV <> 103 ");
        if ( $numerocajaabiertas > 0 or ( $dirip == '10.25.20.84' or $dirip == '10.25.20.29' ) ) {
            ?>
            <h1>
                No permite ingresos
            </h1>
            <p class="alert alert-danger">
                Dos posibles causas: <br/>
                - Usted tiene caja abierta y por lo tanto no se le permite realizar salidas de vehiculos en el sistema.<br/>
                - Está trabajando desde el equipo de ingreso y no debe registrar salidas <br/>
                Cualquier novedad al respecto, comuniquese con su jefe inmediato.
            </p>
            <?php
            exit;
        }
    }
    
    ?>
    <script>

        jQuery.fn.cargar = function(url) {
            $(document).ready(function(){
                $("#cargarDatos").load(url);
            });
        };

        $(document).ready(function() {
            
            $('#ingresos').dataTable({
                "bPaginate": true,
                "bJQueryUI": true,
                "iDisplayLength": 10,
                "aaSorting": [],
                "bStateSave": true
            });
            
            $('#validaIngreso').keypress(function(e){
                if(e.keyCode === 13){
                    cargarDatos(this.value,<?php echo $parqueadero; ?>);
                    $('#validaIngreso').val("");
                }
            });
            
        });

        function cargarDatos(ing, par){

            $("#cargarDatos").cargar("web/mvtoegresodet.php?ing=" + ing + "&par=" + par);

        }

    </script>

    <div class="container-fluid">
        <div class="row-fluid">
            <div class="span9">
                <h1>
                    <a href="app.php?web=mvtoegreso" class="btn btn-primary" title="Refrescar pantalla">
                        <i class="icon-refresh icon-white"></i>
                    </a>
                    Visado de salida
                    <a href="app.php?web=mvtoingreso" class="btn btn-primary" title="Ir a ingresos">
                        <i class="icon-chevron-up icon-white"></i>
                    </a>
                </h1>
                <?php
                
                $diaAtras = $parqueo->diaSiguiente($fecha, "-4");
                
                $sql = " select m.id, m.placa, tu.nombre as tu, t.nombre as t, ev.nombre as e, m.fecing, m.horing,
                        m.fecfac, m.horfac
                    from movimiento m 
                    inner join motivo tu on m.tipousua = tu.criterio and tu.tabla = 'tipousua'
                    inner join motivo t on m.tipovehi = t.criterio and t.tabla = 'tipovehi'
                    inner join motivo ev on m.estavehi = ev.criterio and ev.tabla = 'estavehi'
                    where m.parqueadero = $parqueadero and m.fecsal = '0'
                        and m.fecing between $diaAtras and $fecha
                    order by m.id desc ";
                $encabezados = "ID,Placa,Usuario,Tipo,Estado,Fecha,Hora,Duracion,Fec fac,Hor fac";
                
                echo "<table class=\"table table-bordered table-condensed\" id=\"ingresos\"  >";
                    echo "<thead>";
                        echo "<tr>";
                        $titulo = explode(',',$encabezados);
                        $numreg = count($titulo);
                        for($j=0; $j < $numreg; $j++ ) {
                            echo "<th> {$titulo[$j]} </th>";
                        }
                        echo "</tr>";
                    echo "</thead>";

                    $reg = odbc_exec($parqueo->conexion, $sql);
                    if( odbc_num_rows( $reg ) ) {
                        echo "<tbody>";
                            while ( $res = odbc_fetch_array($reg) ) {
                            
                            $fecing = $res[odbc_field_name($reg, 6)];
                            echo "<tr>";
                                echo "<td> {$res[odbc_field_name($reg, 1)]} </td>";
                                echo "<td> {$res[odbc_field_name($reg, 2)]} </td>";
                                echo "<td> {$res[odbc_field_name($reg, 3)]} </td>";
                                echo "<td> {$res[odbc_field_name($reg, 4)]} </td>";
                                echo "<td> {$res[odbc_field_name($reg, 5)]} </td>";
                                echo "<td style=\"text-align: right;\"> {$res[odbc_field_name($reg, 6)]} </td>";
                                echo "<td style=\"text-align: right;\">";
                                    $horing = explode(':', $parqueo->cortarHora($res[odbc_field_name($reg, 7)]));
                                    echo $horing[0] . ":" . $horing[1];
                                echo "</td>";
                                echo "<td>";
                                    $minutos = $parqueo->getDiferenciaFechas($fecing, $res[odbc_field_name($reg, 7)], $fecha, $hora);
                                    $dias = intval($minutos/60/24);
                                    $horas = intval(($minutos - ($dias*24*60))/60);
                                    if ( $dias > 0 ) {
                                        echo $dias . " d ";
                                    }
                                    if ( $minutos >= 60 ) {
                                        echo $horas . " h ";
                                    }
                                    echo intval($minutos - ($dias*24*60) - $horas*60 ) . " m";
                                echo "</td>";
                                echo "<td> {$res[odbc_field_name($reg, 8)]} </td>";
                                echo "<td>";
                                    $horfac = explode(':', $parqueo->cortarHora($res[odbc_field_name($reg, 9)]));
                                    echo $horfac[0] . ":" . $horfac[1];
                                echo "</td>";
                            echo "</tr>";

                        }
                        echo "</tbody>";

                    } else {
                        echo "<tr><td colspan=\"$numreg\"> No hay registros </td></tr>";
                    }
                echo "</table>";
                
                ?>
            </div>
            <div class="span3">
                <h1>
                    Salida
                </h1>
                <p>
                    Escriba la placa o ingreso
                </p>
                <input id="validaIngreso" style="text-transform: uppercase;font-size: 2em; padding: 10px; margin: 2px; height: 50px;"
                       type="text" class="span12 " name="ing" maxlength="8" autofocus="" value="" />
                <div id="cargarDatos"></div>
            </div>
        </div>
    </div>
    <?php
} else {
    include_once 'web/parnoasig.php';
}

