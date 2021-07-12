<script>
    
    $(document).ready(function() {
        $('#mvtohoras').dataTable({
            "bPaginate": true,
            "bJQueryUI": true,
            "iDisplayLength": 15,
            "aaSorting": [],
            "bStateSave": true
        });
    });
    
</script>
<script src="js/Chart.js"></script>
<meta name = "viewport" content = "initial-scale = 1, user-scalable = no">
<style>
        canvas{
        }
</style>
<?php

if ( isset ( $_POST['informe'] ) ) {
    
    $ini = $_POST['fecini'];
    $_SESSION['fecini'] = $_POST['fecini'];
    $fin = $_POST['fecfin'];
    $_SESSION['fecfin'] = $_POST['fecfin'];
    
} else if ( $_SESSION['fecini'] != '' ) {
    
    $ini = $_SESSION['fecini'];
    $fin = $_SESSION['fecfin'];
    
} else {
    
    $ini = $fecha;
    $fin = $fecha;
    
}

?>

<form action="" method="POST" class="span12">
    <table>
        <tr>
            <td>Fecha inicial</td>
            <td><script>DateInput('fecini', true, 'YYYYMMDD', '<?php echo $ini; ?>')</script></td>
            <td>Fecha final</td>
            <td><script>DateInput('fecfin', true, 'YYYYMMDD', '<?php echo $fin; ?>')</script></td>
            <td>
                <input type="submit" class="btn btn-primary" name="informe" value="Buscar" />
            </td>
        </tr>
    </table>
</form>
<h2>
    Ingresos por horas en el d&iacute;a
</h2>
<p class="alert alert-info">
    Este reporte muestra la informaci&oacute;n correspondiente a los ingresos en las fechas dadas. Los datos se consultan 
    desde las 6:00 horas hasta las 21:59 horas.
</p>
<?php
$sql = " select p.id, p.nombre, substr(bdsalud.fnhora(m.horing), 1, 2), count(*)
    from movimiento m 
        inner join parqueadero p on m.parqueadero = p.id
    where m.fecing between $ini and $fin and m.horing between 60000 and 215959
    group by p.id, p.nombre, substr(bdsalud.fnhora(m.horing), 1, 2)
    order by p.id, p.nombre, substr(bdsalud.fnhora(m.horing), 1, 2) ";
    
$datos[][] = array();
$parqueaGra = 0;
$parqueaAnt = 0;
$horaInicia = 6;
$horaSiguiente = $horaInicia;
$datos[0][0] = $parqueaGra;
$datos[0][1] = "Parqueadero";
$pon = 2;
$contar = 2;
$cantParq = 0;
$labels = "";
$reg = $parqueo->consulta($sql);
for($h = 6; $h < 22; $h++){
    $datos[0][$contar] = $h;
    $contar++;
    $labels.= "$h,";
}
$labels = substr($labels, 0, strlen($labels)-1 );
while ( $rowSelect = odbc_fetch_array($reg) ) {
    
    if ( $parqueaGra != $rowSelect[odbc_field_name($reg, 1)] ) {
        
        if ( $parqueaGra > 0 ) {
            
            for ($horaSiguiente; $horaSiguiente <= 21; $horaSiguiente++) {

                $datos[$parqueaGra][$pon] = 0;
                $pon++;

            }
            
        }
        
        $parqueaGra = $rowSelect[odbc_field_name($reg, 1)];
        $datos[$parqueaGra][0] = $parqueaGra;
        $datos[$parqueaGra][1] = $rowSelect[odbc_field_name($reg, 2)];
        $horaSiguiente = $horaInicia;
        $pon = 2;
        $cantParq++;
        
    }
    
    if ( $horaSiguiente == intval($rowSelect[odbc_field_name($reg, 3)]) ) {
        
        $datos[$parqueaGra][$pon] = $rowSelect[odbc_field_name($reg, 4)];
        $horaSiguiente++;
        $pon++;
        
    } else {
        
        for ($horaSiguiente; $horaSiguiente < intval($rowSelect[odbc_field_name($reg, 3)]); $horaSiguiente++) {
            
            $datos[$parqueaGra][$pon] = 0;
            $pon++;
            
        }
        $datos[$parqueaGra][$pon] = intval($rowSelect[odbc_field_name($reg, 4)]);
        $horaSiguiente++;
        $pon++;
        
    }
    
}

for ($horaSiguiente; $horaSiguiente <= 21; $horaSiguiente++) {

    $datos[$parqueaGra][$pon] = 0;
    $pon++;

}

$colores[0] = "Colores";
$colores[1] = "255,204,0"; // DC
$colores[2] = "21,0,255";   // FF, 0F, 32
$colores[3] = "255,0,0";    // 0C, FF, 00
$colores[4] = "151,187,205"; // 97, BB, CD
$color[0] = "Color fondo";
$color[1] = "FFCC00";
$color[2] = "3300FF";
$color[3] = "FF0000";
$color[4] = "97BBCD";

?>

    <canvas id="canvas" height="450" width="800"></canvas>
    <script>

        var barChartData = {
            labels : [<?php echo $labels; ?>],
            datasets : [
            <?php
            for( $p = 1; $p <= $cantParq; $p++ ) {

                echo "{\n fillColor : \"rgba({$colores[$p]},0.5)\", \n";
                echo "strokeColor : \"rgba({$colores[$p]},1)\", \n";
                echo "data : [";
                    for( $d = 2; $d <= 14; $d++ ) {
                        echo $datos[$p][$d];
                        if( $d < 14 )
                            echo ",";
                    }
                echo "] \n }";
                if ( $p < $cantParq )
                    echo ",";

            }
            ?>
            ]

        }
        
	var myLine = new Chart(document.getElementById("canvas").getContext("2d")).Bar(barChartData);
	
    </script>

<?php

    echo '<table class="table table-bordered table-condensed" id="mvtohoras">';
        echo '<tbody>';
            echo '<tr>';
            echo "<th>Col</th>";
                for ( $h = 1; $h < 18; $h++ ) {
                    echo "<th>{$datos[0][$h]}</th>";
                }
                echo "<th>Total</th>";
            echo '</tr>';
            for ( $p = 1; $p <= $cantParq; $p++ ) {
                echo "<tr>";
                echo "<td bgcolor=\"#{$color[$p]}\" style=\"width:10px;\" > </td>";
                    $subtot = 0;
                    for ( $h = 1; $h < 18; $h++ ) {
                        echo "<td>{$datos[$p][$h]}</td>";
                        $subtot+=$datos[$p][$h];
                    }
//                    echo "<td>{$datos[$p][$h]}</td>";
                    echo "<td>$subtot</td>";
                echo '</tr>';

            }
        echo '</tbody>';
    echo '</table>';

?>