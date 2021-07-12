<?php
session_start();

include_once("../clases/connDb2.php");
$parqueo = new DB2();
$fecha = $parqueo->obtenerFechaDb();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: filename=\"Parqueadero-IngresosDia-Consolidado-" . $fecha . ".xls\";");
        
        $reg = $parqueo->consulta($_SESSION['sentencia']);
        
        echo "<table class=\"table tablesorter table-bordered table-hover table-condensed\">";
        echo "<thead>";
            echo "<tr>";
                echo "<th colspan=\"6\" style=\"text-align: center;\">COMFAMILIAR RISARALDA</th>";
            echo "</tr>";
            echo "<tr>";
                echo "<th colspan=\"6\" style=\"text-align: center;\">Ingresos consolidado por cajero y centro</th>";
            echo "</tr>";
            echo "<tr>";
                echo "<th>Usuario</th>";
                echo "<th>Nombre del cajero</th>";
                echo "<th>Centro de atencion</th>";
                echo "<th>Nro reg</th>";
                echo "<th>Cantidad</th>";
                echo "<th>Valor total</th>";
            echo "</tr>";
        echo "</thead>";
        echo "<tbody>";
        
        while ( $rs = odbc_fetch_array($reg) ) {

            echo "<tr>";
                echo "<td>" . $rs[odbc_field_name($reg, 1)] . "</td>";
                echo "<td>" . $rs[odbc_field_name($reg, 2)] . "</td>";
                echo "<td>" . $rs[odbc_field_name($reg, 3)] . "</td>";
                echo "<td style=\"text-align: right;\">" . $rs[odbc_field_name($reg, 4)] . "</td>";
                echo "<td style=\"text-align: right;\">" . $rs[odbc_field_name($reg, 5)] . "</td>";
                echo "<td style=\"text-align: right;\"> $ " . number_format($rs[odbc_field_name($reg, 6)]) . "</td>";
            echo "</tr>";
            
        }
        
        echo "</tbody>";
    echo "</table>";
        ?>
    </body>
</html>
