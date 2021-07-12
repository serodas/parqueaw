<?php
if ( $hora > 20000 && $hora < 50000 ) {
    
    if ( isset ( $_POST['inactivar'] ) ) { 

        // Ingresos que ya tienen factura, pero estan en el sistema.8
        $sentencia1 = " UPDATE PARQUEAW.MOVIMIENTO SET 
            FECSAL = '$fecha', HORSAL = '$hora', USUSAL = '$login', EQUSAL = 'Servidor', DIPSAL = '10.25.2.1'
        WHERE MRCODCONS = 0 AND PARQUEADERO = $parqueadero AND FECING < '$fecha' AND FECFAC > 0 AND FECSAL = 0 
            AND PLACA NOT IN ( SELECT PLACA FROM INGRESOTMP WHERE PARQUEADERO = $parqueadero ) ";
        odbc_exec($parqueo->conexion, $sentencia1);

        // Ingresos que NO se facturaron y aun estan en el sistema
        $sentencia2 = " UPDATE PARQUEAW.MOVIMIENTO SET 
            MRCODCONS = -3, FECFAC = $fecha, HORFAC = $hora, USUFAC = '$login', DIPFAC = '10.25.2.1', 
            FECSAL = '$fecha', HORSAL = '$hora', USUSAL = '$login', EQUSAL = 'Servidor', DIPSAL = '10.25.2.1'
        WHERE MRCODCONS = 0 AND PARQUEADERO = $parqueadero AND FECING < '$fecha' AND FECSAL = 0 
            AND PLACA NOT IN ( SELECT PLACA FROM INGRESOTMP WHERE PARQUEADERO = $parqueadero ) ";
        odbc_exec($parqueo->conexion, $sentencia2);

        // Limpiar la tabla temporal para el parqueadero.
        $sentencia3 = " DELETE FROM INGRESOTMP WHERE PARQUEADERO = $parqueadero ";
        odbc_exec($parqueo->conexion, $sentencia3);
        ?>
        <script>
            alert('Las placas han sido retiradas del parqueadero.');
            window.location.href="app.php?web=salidadesmarcar";
        </script>
        <?php
    }
    ?>
    <div class="row-fluid">
        <div class="span8">
            <h3>
                <a href="app.php?web=salidadesmarcar" title="Refrescar" ><i class="icon-refresh"></i></a>
                Marcar o desmarcar veh&iacute;culos
            </h3>
        </div>
        <div class="span4">
            <form method="POST" action=""><input type="submit" value="Actualizar" name="inactivar" class="btn btn-danger"></form>
        </div>
    </div>
    <hr/>
    <?php
    $sql1 = " select count(m.id) as cantidad
        from movimiento m left join ingresotmp t on m.placa = t.placa and t.parqueadero = $parqueadero
        where m.mrcodcons = 0 and m.parqueadero = $parqueadero and m.fecing < '$fecha' and m.fecsal = '0' ";
    $reg1 = odbc_exec($parqueo->conexion, $sql1);
    $rs1 = odbc_fetch_array($reg1);
    $cantidad = $rs1[odbc_field_name($reg1, 1)];
    $columna = intval ( $cantidad / 6 ) + 1;

    $sql = " select m.id, m.placa, m.fecing, m.horing, ifnull(t.placa, 'x') as temporal
        from movimiento m
            left join ingresotmp t on m.placa = t.placa and t.parqueadero = $parqueadero
        where m.mrcodcons = 0 and m.parqueadero = $parqueadero and m.fecing < '$fecha' and m.fecsal = 0
        order by m.placa asc ";

    $reg = odbc_exec($parqueo->conexion, $sql);
    if( odbc_num_rows( $reg ) ) {
        $i = 0;
        echo '<div class="row-fluid">';
            echo '<div class="span2">';
            while ( $res = odbc_fetch_array($reg) ) {
                if ( $i % $columna == 0 && $i > 0 ) {
                    echo '</div>';
                    echo '<div class="span2">';
                }
                $chequeado = $res[odbc_field_name($reg, 5)] != 'x' ? ' checked="" ' : '';
                echo '<input type="checkbox" ' . $chequeado . ' onclick="marcarDesmarcar(' . $parqueadero . ',\'' . $res[odbc_field_name($reg, 2)] . '\');" /> ';
                echo $res[odbc_field_name($reg, 2)] . '<br/>';
                $i++;
            }
            echo $cantidad . ' - ' . $i;
        echo '</div>';
    }
    echo '</div>';
    ?>
    <p>&nbsp;</p>
    <script type="text/javascript" src="../web/js/salidadesmarcar.js"></script>
    <?php
} else {
    ?>
    <h3>
        Marcar o desmarcar veh&iacute;culos
    </h3>
    <p class="alert alert-danger">
        Esta funcionalidad solo está activa entre las 2:00 y las 5:00 AM de cada día.
    </p>
    <?php
}

