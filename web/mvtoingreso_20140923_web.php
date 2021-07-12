<?php

if ( $parqueadero > 0 ) {
    ?>
    <script>
        function accion(cod){
            window.location.href="app.php?web=mvtoingreso&id="+cod;
        }

        function reimpresion(cod){
            //window.open('web/mvtoingresowimp.php?mvtoid='+cod,'_blank');
            window.open("web/mvtoingresowimp.php?mvtoid="+cod,"Ingreso","width=500,height=800,menubar=no");
        }

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
        });

        function cargarDatos(placa){

            $("#cargarDatos").cargar("web/mvtoingresodet.php?placa=" + placa);

        }

    </script>
    <?php

    if ( isset ( $_POST['ingresar'] ) ) {

        $placa = strtoupper(trim($parqueo->limpiarEntradas($_POST['placa'], 6)));
        $mrcodcons = 0;

        if ( $placa != '' ) {

            $sqlSp = " select v.tipousua, v.tipovehi
                from vehiculos v 
                where v.placa = '%s' and v.estado = 'A' ";
            $sqlid = sprintf($sqlSp, $placa);
            $consulta = $parqueo->consulta($sqlid);
            $res = odbc_fetch_array($consulta);

            if( trim($res[odbc_field_name($consulta, 2)]) != '' ) {

                $tipovehi = $res[odbc_field_name($consulta, 2)];
                $tipousua = $res[odbc_field_name($consulta, 1)];

            } else {
                
                $tip = $parqueo->validarPlaca($placa);
                if ( $tip == 2 ){
                    $tipo = "M";
                } else {
                    $tipo = "C";
                }
                $tipovehi = $tipo;
                
                if ( $parqueadero == 3 ) {

                    $tipousua = $parqueo->limpiarEntradas($_POST['tipousua2'], 1);

                } else {

                    $tipousua = 'X';

                }
                
            }
            
            $estavehi = $parqueo->limpiarEntradas($_POST['estavehi'], 2);
            $fecing = $fecha;
            $horing = $hora;
            $fecfac = 0;
            $horfac = 0;
            $fecsal = 0;
            $horsal = 0;
            $durdia = 0;
            $durhor = 0;

            // Si esa placa tiene un registro activo de otro ingreso, se cambiaran las fechas de facturado y visado.
            $update = " update movimiento 
                set mrcodcons = -1, fecfac = '$fecha', horfac = '$hora', fecsal = '$fecha', horsal = '$hora',
                    usufac = '$login', equfac = '$equipo', dipfac = '$dirip', 
                    ususal = '$login', equsal = '$equipo', dipsal = '$dirip'
                where placa = '$placa' and mrcodcons = 0 ";
            $parqueo->consulta($update);

            // Insertar el registro del ingreso de vehiculo en la tabla de movimientos.
            $sentencia = " insert into movimiento (parqueadero, placa, mrcodcons, tipousua, tipovehi, estavehi, 
                fecing, horing, fecfac, horfac, fecsal, horsal, durdia, durhor, estado, usuario, fecha, hora, equipo, 
                dirip, placaini, usufac, equfac, dipfac, ususal, equsal, dipsal)
                values ('$parqueadero', '$placa', '$mrcodcons', '$tipousua', '$tipovehi', '$estavehi', '$fecing', '$horing', 
                    '$fecfac', '$horfac', '$fecsal', '$horsal', '$durdia', '$durhor', 'A', '$login', '$fecha', '$hora', '$equipo', 
                    '$dirip', '', '', '', '', '', '', '')";
            $parqueo->consulta($sentencia);

            if ($parqueo) {

                $sql = " select max(id) from movimiento where parqueadero = '%s' and placa = '%s' and tipousua = '%s'
                    and tipovehi = '%s' and fecing = '%s' ";
                $sql = sprintf($sql, $parqueadero, $placa, $tipousua, $tipovehi, $fecha);
                $reg = odbc_exec($parqueo->conexion, $sql);
                if( odbc_num_rows( $reg ) ) {

                    $res = odbc_fetch_array($reg);
                    $_SESSION['mvtoid'] = $res[odbc_field_name($reg, 1)];
                    ?>
                    <script type="text/javascript">
//                        window.open('web/mvtoingresowimp.php','_blank');
                        window.open("web/mvtoingresowimp.php?mvtoid=<?php echo $_SESSION['mvtoid']; ?>","Ingreso","width=500,height=500,menubar=no");
                        window.location.href="app.php?web=mvtoingreso";
                    </script>
                    <?php

                }

            } else {
                ?>
                <script>
                    alert("Error al guardar el dato. Intentelo de nuevo.");
                </script>
                <?php
            }

        }

    }

    ?>
    <div class="container-fluid">
        <div class="row-fluid">
            <div class="span4 letraGrande">
                <form action="" method="POST">
                    <h1 style="font-size: 2em; padding: 10px;">
                        <a href="app.php?web=mvtoingreso" class="btn btn-primary">
                            <i class="icon-plus icon-white"></i>
                        </a>
                        Entrada
                        <a href="app.php?web=mvtoegreso" class="btn btn-primary">
                            <i class="icon-chevron-down icon-white"></i>
                        </a>
                    </h1>
                    <input style="font-size: 2em; padding: 10px; margin: 2px; height: 50px;" type="text" class="span12 " name="placa" maxlength="45" autofocus="" value="" 
                           onblur="javascript:cargarDatos(this.value);" style="text-transform: uppercase;" />
                    <label style="font-size: 2em; padding: 10px;">Estado del veh&iacute;culo</label>
                    <?php
                    $tipos = " select criterio, nombre from motivo where tabla = 'estavehi' and estado = 'A' order by criterio ";
                    $parqueo->select($tipos, 'estavehi', '20', 'span12');
                    
                    if ( $parqueadero == 3 ) {
                        ?>
                        <label style="font-size: 2em; padding: 10px;">Tipo de usuario</label>
                        <?php
                        $tipo = " select criterio, nombre from motivo 
                            where tabla = 'tipousua' and estado = 'A' and criterio in ('A','E','I','P','V','X')
                            order by nombre ";
                        $parqueo->select($tipo, 'tipousua2', 'X', 'span12');
                    }
                    ?>
                    <button type="submit" class="btn btn-primary" name="ingresar" style="margin: 10px 20px; padding: 10px; font-size: 2em;">Ingresar</button>
                    <div id="cargarDatos"></div>
                </form>
            </div>
            <div class="span8">
                <h1>Veh&iacute;culos en el parqueadero</h1>
                <?php

                // fecing, horing, fecfac, horfac, fecsal, horsal, durdia, durhor, estado, usuario, fecha, hora, equipo, dirip
                $sql = " select m.id, m.placa, tu.nombre as tu, t.nombre as t, ev.nombre as e, m.fecing, m.horing
                    from movimiento m 
                    inner join motivo tu on m.tipousua = tu.criterio and tu.tabla = 'tipousua'
                    inner join motivo t on m.tipovehi = t.criterio and t.tabla = 'tipovehi'
                    inner join motivo ev on m.estavehi = ev.criterio and ev.tabla = 'estavehi'
                    where m.parqueadero = $parqueadero and m.mrcodcons = 0 and m.fecsal = '0'
                    order by m.id desc ";
                $encabezados = "Placa,Usuario,Tipo,Estado,Fecha,Hora";
                
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

                            echo "<tr onClick=\"javascript:reimpresion('".$res[odbc_field_name($reg, 1)]."')\">";
                                echo "<td> {$res[odbc_field_name($reg, 2)]} </td>";
                                echo "<td> {$res[odbc_field_name($reg, 3)]} </td>";
                                echo "<td> {$res[odbc_field_name($reg, 4)]} </td>";
                                echo "<td> {$res[odbc_field_name($reg, 5)]} </td>";
                                echo "<td style=\"text-align: right;\"> {$res[odbc_field_name($reg, 6)]} </td>";
                                echo "<td style=\"text-align: right;\">";
                                if ( $res[odbc_field_name($reg, 7)] > 99999 ) 
                                    echo substr($res[odbc_field_name($reg, 7)], 0, 4 );
                                else
                                    echo substr($res[odbc_field_name($reg, 7)], 0, 3 );
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
        </div>
    </div>
    <?php
} else {
    
    include_once 'web/parnoasig.php';
    
}
?>