<?php

if ( $parqueadero > 0 ) {
    ?>
    <script>
        
        $(document).ready(function() {
            $("#ingerr").DataTable({
                "bJQueryUI": true,
                "iDisplayLength": 15,
                "aaSorting": [],
                "bStateSave": true
            });
        });

        function accion(cod){
            window.location.href="app.php?web=mvtoingrerr&id="+cod;
        }
        
    </script>
    <?php

    if ( isset ( $_POST['modificar'] ) ) {
        
        $iding = strtoupper(trim($parqueo->limpiarEntradas($_POST['iding'], 15)));
        $placa = strtoupper(trim($parqueo->limpiarEntradas($_POST['placa'], 6)));
        $placaing = strtoupper(trim($parqueo->limpiarEntradas($_POST['placaing'], 6)));
        
        if ( $placa != '' && $placaing != '' ) {
            
            $tipousua = strtoupper(trim($parqueo->limpiarEntradas($_POST['tipousua'], 1)));
            $tipovehi = strtoupper(trim($parqueo->limpiarEntradas($_POST['tipovehi'], 2)));
            $pagoaut = strtoupper(trim($parqueo->limpiarEntradas($_POST['pagoaut'], 8)));
            $obsaut = strtoupper(trim($parqueo->limpiarEntradas($_POST['obsaut'], 200)));
            
            $update = " update movimiento 
                set placa = '$placa', tipousua = '$tipousua', tipovehi = '$tipovehi', placaini = '$placaing' ";
            
            if ( $pagoaut > 0 && strlen($obsaut) > 10  ) {
                
                $update.= ",pagoaut = $pagoaut, obsaut = '$obsaut', usuaut = '$login', "
                        . " fecaut = '$fecha', horaut = '$hora', equaut = '$equipo', dipaut = '$dirip' ";
                
            }
            
            if ( isset ( $_POST['autosal'] ) ) {
                $update.= ", mrcodcons = 1,  fecfac = '$fecha', horfac = '$hora' ";
            }
            
            $update.= " where id = '$iding' and parqueadero = '$parqueadero' and placa = '$placaing' ";
            $actualiza = $parqueo->consulta($update);
            
        } else {
            
            ?>
            <script>
                alert("No se permiten placas en blanco.\nIntentelo nuevamente.");
                window.location.href="app.php?web=mvtoingrerr";
            </script>
            <?php
            
        }
        
    }

    ?>
    <div class="container-fluid">
        <div class="row-fluid">
            <div class="span3">
                <h1>
                    Modificar datos
                </h1>
                <?php
                if ( isset ( $_GET['id'] ) ) {

                    $slqcor = " select id, placa, tipousua, tipovehi, pagoaut, obsaut "
                            . " from movimiento where id = {$_GET['id']} ";
                    $regcor = odbc_exec($parqueo->conexion, $slqcor);
                    $rescor = odbc_fetch_array($regcor);

                    if ( trim($rescor[odbc_field_name($regcor, 1)]) == '' ) {

                        echo "Sin dato";

                    } else {

                        $iding = trim($rescor[odbc_field_name($regcor, 1)]);
                        $placa = $rescor[odbc_field_name($regcor, 2)];
                        $tipousua = $rescor[odbc_field_name($regcor, 3)];
                        $tipovehi = $rescor[odbc_field_name($regcor, 4)];
                        $pagoaut = $rescor[odbc_field_name($regcor, 5)];
                        $obsaut = $rescor[odbc_field_name($regcor, 6)];
                        ?>
                        <form action="" method="POST">

                            <input type="hidden" name="iding" value="<?php echo $iding; ?>" >
                            <input type="hidden" name="placaing" value="<?php echo $placa; ?>" >
                            <label>Placa</label>
                            <input type="text" class="span12" name="placa" maxlength="7" value="<?php echo $placa; ?>" >
                            <label>Tipo de Usuario</label>
                            <?php
                            $sql = " select criterio, nombre from motivo where tabla = 'tipousua' and estado = 'A' ";
                            $parqueo->select($sql, 'tipousua', $tipousua, 'span12');
                            ?>
                            <label>Tipo de vehiculo</label>
                            <?php
                            $sql = " select criterio, nombre from motivo where tabla = 'tipovehi' and estado = 'A' ";
                            $parqueo->select($sql, 'tipovehi', $tipovehi, 'span12');
                            ?>
                            <label>Autorizar salida</label>
                            <?php
                            $parqueo->checkbox("autosal", "1", "");
                            ?>
                            <br/>
                            <br/>
                            <label>Valor autorizado a pagar</label>
                            <input type="text" class="span12" name="pagoaut" maxlength="8" value="<?php echo $pagoaut; ?>" >
                            <label>Observacion</label>
                            <textarea class="span12" name="obsaut" maxlength="200" rows="3" ><?php echo $obsaut; ?></textarea>
                            <br/>
                            <button type="submit" class="btn btn-primary" name="modificar" >Modificar</button>
                        </form>
                        <?php

                    }

                }
                ?>
            </div>
            <div class="span9">
                <h1>
                    Veh&iacute;culos en el parqueadero
                    <a href="app.php?web=mvtoingrerr" class="btn btn-primary" title="Limpiar datos">
                        <i class="icon-refresh icon-white"></i>
                    </a>
                </h1>
                <?php

                $sql = " select m.id, m.placa, tu.nombre as tu, t.nombre as t, ev.nombre as e, 
                        m.fecing, m.horing, m.fecfac, m.horfac
                    from movimiento m 
                    inner join motivo tu on m.tipousua = tu.criterio and tu.tabla = 'tipousua'
                    inner join motivo t on m.tipovehi = t.criterio and t.tabla = 'tipovehi'
                    inner join motivo ev on m.estavehi = ev.criterio and ev.tabla = 'estavehi'
                    where m.parqueadero = $parqueadero and m.mrcodcons = 0 and m.fecsal = 0
                    order by m.id desc ";
                
                echo "<table class=\"table table-bordered table-condensed\" id=\"ingerr\"  >";

                    echo "<thead>";
                        echo "<tr>";
                            echo "<th>Placa</th>";
                            echo "<th>Usuario</th>";
                            echo "<th>Tipo</th>";
                            echo "<th>Estado</th>";
                            echo "<th>Fec Ing</th>";
                            echo "<th>Hor Ing</th>";
                            echo "<th>Fec Fac</th>";
                            echo "<th>Hor Fac</th>";
                        echo "</tr>";
                    echo "</thead>";

                    $reg = odbc_exec($parqueo->conexion, $sql);
                    if( odbc_num_rows( $reg ) ) {
                        echo "<tbody>";
                        while ( $res = odbc_fetch_array($reg) ) {

                            echo "<tr onClick=\"javascript:accion('".$res[odbc_field_name($reg, 1)]."')\">";
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
                                echo "<td style=\"text-align: right;\"> {$res[odbc_field_name($reg, 8)]} </td>";
                                echo "<td style=\"text-align: right;\">";
                                if ( $res[odbc_field_name($reg, 9)] > 99999 ) 
                                    echo substr($res[odbc_field_name($reg, 9)], 0, 4 );
                                else
                                    echo substr($res[odbc_field_name($reg, 9)], 0, 3 );
                                echo "</td>";
                            echo "</tr>";
                            
                        }
                        echo "</tbody>";
                        
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