<?php

if ( isset ( $_POST['informe'] ) ) {
    $ini = $_POST['fecini'];
    $_SESSION['fecini'] = $_POST['fecini'];
    $fin = $_POST['fecfin'];
    $_SESSION['fecfin'] = $_POST['fecfin'];
} else  {
    if ( isset ( $_SESSION['fecini'] ) && $_SESSION['fecini'] != '' ) {
        $ini = $_SESSION['fecini'];
        $fin = $_SESSION['fecfin'];
    } else {
        $ini = $fecha;
        $fin = $fecha;
    }
}
?>

<form action="" method="POST" class="span12">
    <table>
        <tr>
            <td>
                Fecha inicial<br/>
                <script>DateInput('fecini', true, 'YYYYMMDD', '<?php echo $ini; ?>')</script>
            </td>
            <td>
                Fecha final<br/>
                <script>DateInput('fecfin', true, 'YYYYMMDD', '<?php echo $fin; ?>')</script>
            </td>
            <td>
                <br/>
                <input type="submit" class="btn btn-primary" name="informe" value="Buscar" />
            </td>
        </tr>
    </table>
</form>
<h2>
    Vehículos sancionados que duran 1 d&iacute;a o m&aacute;s en el parqueadero
</h2>
    
<?php
    $sql = "SELECT M.ID, P.NOMBRE AS PARQUEADERO, M.PLACA, IFNULL(V.NOMBRE, '-') AS NOMBRE, M.MRCODCONS, M.FECING, M.FECFAC, M.FECSAL 
        FROM PARQUEAW.MOVIMIENTO M 
            INNER JOIN PARQUEAW.PARQUEADERO P ON M.PARQUEADERO = P.ID
            LEFT JOIN PARQUEAW.VEHICULOS V ON M.PLACA = V.PLACA
        WHERE M.FECING < M.FECSAL AND M.TIPOUSUA = 'S' AND M.FECING BETWEEN $ini AND $fin ";
    $_SESSION['sentencia'] = $sql;
    $encabezados = "Id,Parqueadero,Placa,Nombre de usuario, Recibo,Fec ing,Fec fac,Fec sal,Ing-Fac,Ing-Sal,Fac-Sal";
    
    $reg = odbc_exec($parqueo->conexion, $sql);
    if( odbc_num_rows( $reg ) ) {
        echo "<table class=\"table table-bordered table-condensed\" id=\"registros\" >";
            echo "<thead>";
                echo "<tr>";
                $titulo = explode(',',$encabezados);
                $numreg = count($titulo);
                for($j=0; $j < $numreg; $j++ ) {
                    echo "<th> {$titulo[$j]} </th>";
                }
                echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            while ( $res = odbc_fetch_array($reg) ) {
                echo "<tr>";
                    echo "<td>{$res[odbc_field_name($reg, 1)]}</td>";
                    echo "<td>{$res[odbc_field_name($reg, 2)]}</td>";
                    echo "<td>{$res[odbc_field_name($reg, 3)]}</td>";
                    echo "<td>{$res[odbc_field_name($reg, 4)]}</td>";
                    echo "<td>{$res[odbc_field_name($reg, 5)]}</td>";
                    echo "<td>{$res[odbc_field_name($reg, 6)]}</td>";
                    echo "<td>{$res[odbc_field_name($reg, 7)]}</td>";
                    echo "<td>{$res[odbc_field_name($reg, 8)]}</td>";
                    echo "<td>";
                        echo $parqueo->getDiferenciaDias($res[odbc_field_name($reg, 6)], $res[odbc_field_name($reg, 7)]);
                    echo "</td>";
                    echo "<td>";
                        echo $parqueo->getDiferenciaDias($res[odbc_field_name($reg, 6)], $res[odbc_field_name($reg, 8)]);
                    echo "</td>";
                    echo "<td>";
                        echo $parqueo->getDiferenciaDias($res[odbc_field_name($reg, 7)], $res[odbc_field_name($reg, 8)]);
                    echo "</td>";
                echo "</tr>";

            }
            echo "</tbody>";
        echo "</table>";
    } else {
        echo "<div class=\"alert alert-danger\"> No hay registros </div>";
    }
    
    
?>
<script>
    
    $(document).ready(function() {
        $('#registros').dataTable({
            "bPaginate": true,
            "bJQueryUI": true,
            "iDisplayLength": 15,
            "aaSorting": [],
            "bStateSave": true
        });
    });
    
</script>