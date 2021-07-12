hola
<?php
session_start();

include_once('../clases/connDb2.php');
$db2 = new db2();
$fecha = $db2->obtenerFechaDb();
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
        header("Content-Disposition: filename=\"vehiculo-" . $fecha . ".xls\";");
        
        echo '<table>';
            echo "<tr>";
                echo "<td colspan=\"9\">Comfamiliar Risaralda</td>";
            echo "</tr>";
            echo "<tr>";
                echo "<td colspan=\"9\">Reporte de vehiculos registrados</td>";
            echo "</tr>";
        echo '</table>';
        echo $_SESSION['tabladedatos'];
        
        ?>
    </body>
</html>
