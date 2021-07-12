<?php

if ( $parqueadero > 0 ) {
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
                    <a href="app.php?web=mvtoegreso" class="btn btn-primary">
                        <i class="icon-refresh icon-white"></i>
                    </a>
                    Visado de salida
                    <a href="app.php?web=mvtoingreso" class="btn btn-primary">
                        <i class="icon-chevron-up icon-white"></i>
                    </a>
                </h1>
                <?php

                $sql = " select m.id, m.placa, tu.nombre as tu, t.nombre as t, ev.nombre as e, m.fecing, m.horing
                    from movimiento m 
                    inner join motivo tu on m.tipousua = tu.criterio and tu.tabla = 'tipousua'
                    inner join motivo t on m.tipovehi = t.criterio and t.tabla = 'tipovehi'
                    inner join motivo ev on m.estavehi = ev.criterio and ev.tabla = 'estavehi'
                    where m.parqueadero = $parqueadero and m.fecsal = '0'
                    order by m.id desc ";
                $encabezados = "ID,Placa,Usuario,Tipo,Estado,Fecha,Hora,Duracion";
                
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
                                if ( $res[odbc_field_name($reg, 7)] > 99999 ) 
                                    $horing = substr($res[odbc_field_name($reg, 7)], 0, 4 );
                                else
                                    $horing = substr($res[odbc_field_name($reg, 7)], 0, 3 );
                                echo "$horing </td>";
                                echo "<td>";
                                if ( $fecing == $fecha ) {
                                    
                                    $tiempo = $parqueo->restarHoras($fecing, $res[odbc_field_name($reg, 7)], $fecha, $hora);
                                    echo $tiempo[0] . " hora(s) " . $tiempo[1] . " minuto(s)";
                                    
                                } else {
                                    
                                    echo "Mas de un dia";
                                    
                                }
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
                       type="password" class="span12 " name="ing" maxlength="6" autofocus="" value="" />
                <div id="cargarDatos"></div>
            </div>
        </div>
    </div>
    <?php
} else {
    
    include_once 'web/parnoasig.php';
    
}
?>
