<?php
session_start();
?>
<style type="text/css">
    .verde{ background-color: #093; color:#FFF; }
    .amarillo{ background-color: #FF0; color:#000; }
    .rojo{ background-color: #F00; color:#FFF; }
</style>
<?php
include_once '../clases/connDb2.php';
$conn = new DB2();
$fecha = $conn->obtenerFechaDb();
$hora = $conn->obtenerHoraDb();
$equipo = $conn->obtenerNombreEq();
$dirip = $conn->obtenerIp();

$login = $_SESSION['login'];

if ( isset ( $_GET['ing'] ) ) {
    
    $ing = strtoupper($conn->limpiarEntradas($_GET['ing'], 6));
    
    if ( !is_numeric ( $ing ) ) {
        
        $sql = " select max(id) from movimiento where placa = '$ing' ";
        $consulta = $conn->consulta($sql);
        $res = odbc_fetch_array($consulta);
        $ing = $res[odbc_field_name($consulta, 1)];
        
    }
    
    $parqueadero = $conn->limpiarEntradas($_GET['par'], 2);
    
    if ( $ing != '' ) {
        
        
        $sqlSp = " select m.mrcodcons, m.tipousua, m.fecing, m.horing, m.fecfac, m.horfac, m.fecsal, m.horsal, 
                m.estado, p.tiempo, ifnull(pr.placa, ''), m.durhor, m.placa
            from movimiento m 
            inner join parqueadero p on m.parqueadero = p.id 
            left join prueba pr on m.placa = pr.placa
            where m.id = '%d' and m.parqueadero = '%d' and m.mrcodcons > -1 ";
        $sqlid = sprintf($sqlSp, $ing, $parqueadero);
        $consulta = $conn->consulta($sqlid);
        ?>
        <p id="visado" style="font-size: 2em; text-align: center; padding: 15px 0; margin-top: 10px;">
        <?php
        
        $res = odbc_fetch_array($consulta);
        $placa = $res[odbc_field_name($consulta, 11)];
        $mrcodcons = $res[odbc_field_name($consulta, 1)];
        
        if ( $placa != '' ) {
            // Es una placa registrada de forma especial, no se realizara cobro.
            echo "<script>$(\"#visado\").addClass(\"verde\");</script>";
            echo "$placa <br/><br/>";
            echo "Visado de salida";
            $update = " update movimiento 
                set mrcodcons = -1, fecfac = '$fecha', horfac = '$hora', fecsal = '$fecha', horsal = '$hora',
                    usufac = '$login', equfac = '$equipo', dipfac = '$dirip', 
                    ususal = '$login', equsal = '$equipo', dipsal = '$dirip'
                where id = $ing and parqueadero = '$parqueadero' and placa = '$placa' ";
            $conn->consulta($update);
//            echo "<br/><br/>Linea 55";
            
        } else if( $mrcodcons > -1 ) {
            
            $tipousua = $res[odbc_field_name($consulta, 2)];
            $fecing = $res[odbc_field_name($consulta, 3)];
            $horing = trim($res[odbc_field_name($consulta, 4)]);
            $tamhoring = strlen($horing);
            if ( $tamhoring == 5 ) {
                $horing = substr($horing, 0, 3);
            } else {
                $horing = substr($horing, 0, 4);
            }
            
            $fecfac = $res[odbc_field_name($consulta, 5)];
            if ( strlen($res[odbc_field_name($consulta, 6)]) == 5 )
                $horfac = substr($res[odbc_field_name($consulta, 6)], 0, 3);
            else
                $horfac = substr($res[odbc_field_name($consulta, 6)], 0, 4);
            
            $fecsal = $res[odbc_field_name($consulta, 7)];
            $horsal = substr($res[odbc_field_name($consulta, 8)], 0, 4);
            $estado = $res[odbc_field_name($consulta, 9)];
            $durhor = $res[odbc_field_name($consulta, 12)];
            $placa = $res[odbc_field_name($consulta, 13)];
            
            if ( $fecsal != 0 ) {
                
                // Si ya tiene la hora de salida, es porque el funcionario paso nuevamente el registro.
                // Ya puede salir sin problema.
                echo "<script>$(\"#visado\").addClass(\"verde\");</script>";
                echo "$placa<br/><br/>";
                echo "Visado de salida";
                $update = " update movimiento 
                    set mrcodcons = -1, fecfac = '$fecha', horfac = '$hora', fecsal = '$fecha', horsal = '$hora',
                        usufac = '$login', equfac = '$equipo', dipfac = '$dirip', 
                        ususal = '$login', equsal = '$equipo', dipsal = '$dirip'
                    where id = $ing and parqueadero = '$parqueadero' and mrcodcons = 0 ";
                $conn->consulta($update);
//                echo "<br/><br/>Linea 92";
                
            } else {
                
                if ( $tipousua == 'E' or $tipousua == 'I' or $tipousua == 'A' or $tipousua == 'P' or $tipousua == 'V' or $tipousua == 'C' ) {
                    
                    // Es un tipo de usuario que se le autoriza la salida sin necesidad de pago.
                    echo "<script>$(\"#visado\").addClass(\"verde\");</script>";
                    echo "$placa<br/><br/>";
                    echo "Visado de salida";
                    $update = " update movimiento 
                        set mrcodcons = -1, fecfac = '$fecha', horfac = '$hora', fecsal = '$fecha', horsal = '$hora',
                            usufac = '$login', equfac = '$equipo', dipfac = '$dirip', 
                            ususal = '$login', equsal = '$equipo', dipsal = '$dirip'
                        where id = $ing and parqueadero = '$parqueadero' and mrcodcons = 0 ";
                    $conn->consulta($update);
//                    echo "<br/><br/>Linea 106";
                    
                } else {

                    // Establecer el tipo de dia
                    $tipoDia = $conn->tipoDia($fecha);
                    $sql2 = " select p.id, p.tiempo, h.inicio, h.fin 
                        from parqueadero p inner join parquehora h on p.id = h.parqueadero
                        where p.id = '$parqueadero' and h.dia = '$tipoDia' and p.estado = 'A' and h.estado = 'A' ";
                    $consulta2 = $conn->consulta($sql2);
                    $res2 = odbc_fetch_array($consulta2);
                    $parid = $res2[odbc_field_name($consulta2, 1)];
                    $tiempo = $res2[odbc_field_name($consulta2, 2)];
                    $inicio = $res2[odbc_field_name($consulta2, 3)];
                    $fin = $res2[odbc_field_name($consulta2, 4)];
                    
                    $tieing = strlen($horing) . "::" . $horing;
                    $tietot = $conn->minutosAHoraDb($horing, $tiempo);
                    
                    if ( $tietot > substr($hora, 0, strlen($hora)-2) ) {
                        
                        // Es un tipo de usuario que se le autoriza la salida sin necesidad de pago.
                        echo "<script>$(\"#visado\").addClass(\"verde\");</script>";
                        echo "$placa<br/><br/>";
                        echo "Visado de salida";
                        $update = " update movimiento 
                            set mrcodcons = -1, fecfac = '$fecha', horfac = '$hora', fecsal = '$fecha', horsal = '$hora',
                                usufac = '$login', equfac = '$equipo', dipfac = '$dirip', 
                                ususal = '$login', equsal = '$equipo', dipsal = '$dirip'
                            where id = $ing and parqueadero = '$parqueadero' and mrcodcons = 0 ";
                        $conn->consulta($update);
//                            echo "<br/><br/>Linea 135";
                        
                    } else if ( $mrcodcons == 0 ) {
                        
                        echo "<script>$(\"#visado\").addClass(\"rojo\");</script>";
                        echo "$placa<br/><br/>";
                        echo "Debe facturar<br/><br/>";
                        if ( $res[odbc_field_name($consulta, 3)] == $fecha ) {
                            
                            $tiempo = $conn->restarHoras($res[odbc_field_name($consulta, 3)], $res[odbc_field_name($consulta, 4)], $fecha, $hora);
                            if ( $tiempo[0] > 0 ) {
                                echo $tiempo[0] . " hora(s) y <br/><br/>";
                            }
                            echo $tiempo[1] . " minuto(s)";
                            
                        } else {
                            
                            echo "Mas de un dia.";
                            
                        }
//                        echo "<br/><br/>Linea 142";
                        
                    } else {
                        
                        if( $parid == 3 && ( $fecfac == $fecha ) ) {
                            
                            // Ya pago el parqueadero el dia de hoy
                            echo "<script>$(\"#visado\").addClass(\"verde\");</script>";
                            echo "$placa<br/><br/>";
                            echo "Visado de salida";
                            $update = " update movimiento set fecsal = '$fecha', horsal = '$hora',
                                ususal = '$login', equsal = '$equipo', dipsal = '$dirip'
                                where id = $ing and parqueadero = '$parqueadero' ";
                            $conn->consulta($update);
//                            echo "<br/><br/>Linea 154";
                            
                        } else if ( $parid == 3 && ( $fecfac < $fecha ) ) {
                            
                            if ( $tipoDia == 1 or $hora > $fin ) {
                                
                                // No pago el dia de hoy, pero no hay cajas para facturar, se autoriza salida.
                                echo "<script>$(\"#visado\").addClass(\"verde\");</script>";
                                echo "$placa<br/><br/>";
                                echo "Visado de salida";
                                $update = " update movimiento set fecsal = '$fecha', horsal = '$hora',
                                    ususal = '$login', equsal = '$equipo', dipsal = '$dirip'
                                    where id = $ing and parqueadero = '$parqueadero' ";
                                $conn->consulta($update);
//                                echo "<br/><br/>Linea 168";
                                
                            } else {
                                
                                // Es un tipo de usuario que se le autoriza la salida sin necesidad de pago.
                                echo "<script>$(\"#visado\").addClass(\"rojo\");</script>";
                                echo "$placa<br/><br/>";
                                echo "Debe facturar";
                                $update = " update movimiento set estado = 'E'
                                    where id = $ing and parqueadero = '$parqueadero' ";
                                $conn->consulta($update);
//                                echo "<br/><br/>Linea 179";
                                
                            }
                            
                        } else if( $parid > 0 ) {
                            
                            if ( ( $tipoDia == 0 or $tipoDia == 7 ) or ( $hora > $fin or $hora < $inicio ) ) {
                                
                                // No hay cajas para facturar, se autoriza salida.
                                echo "<script>$(\"#visado\").addClass(\"verde\");</script>";
                                echo "$placa<br/><br/>";
                                echo "Visado de salida";
                                $update = " update movimiento set fecsal = '$fecha', horsal = '$hora',
                                    ususal = '$login', equsal = '$equipo', dipsal = '$dirip'
                                    where id = $ing and parqueadero = '$parqueadero' ";
                                $conn->consulta($update);
//                                echo "<br/><br/>Linea 152";
                                
                            } else {
                                $th = strlen($hora);
                                $horaup = $hora;
                                $hora = substr($hora, 0 ,$th-2);
                                if ( $fecfac == $fecha ) {
//                                    echo "<br/><br/> linea 157 $horfac , $tiempo ";
                                    // se debe validar las horas de factura y salida
                                    $tietot = $conn->minutosAHoraDb($horfac, $tiempo);
                                    
                                    if ( $tietot < $hora ) {
                                        
                                        if ( $fecing == $fecha ) {
                                            
                                            if ( strlen($horing) == 3 )
                                                $salper = ( substr($horing,0,1) + $durhor ) . substr($horing,1,2);
                                            else
                                                $salper = ( substr($horing,0,2) + $durhor ) . substr($horing,2,2);
                                            $tietot2 = $conn->minutosAHoraDb($salper, $tiempo);
                                            
                                            if ( $tietot2 < $hora ){
                                                // Esta en el tiempo permitido, puede salir
                                                echo "<script>$(\"#visado\").addClass(\"amarillo\");</script>";
                                                echo "$placa<br/><br/>";
                                                echo "Pago parcial";
                                                $update = " update movimiento set fecing = fecfac, horing = horfac, estado = 'E'
                                                    where id = $ing and parqueadero = '$parqueadero' ";
                                                $conn->consulta($update);
//                                                echo "<br/><br/>Linea 198";
                                            } else {

                                                // Esta en el tiempo permitido, puede salir
                                                echo "<script>$(\"#visado\").addClass(\"verde\");</script>";
                                                echo "$placa<br/><br/>";
                                                echo "Visado de salida";
                                                $update = " update movimiento set fecsal = '$fecha', horsal = '$horaup', 
                                                    ususal = '$login', equsal = '$equipo', dipsal = '$dirip'
                                                    where id = $ing and parqueadero = '$parqueadero' ";
                                                $conn->consulta($update);
//                                                echo "<br/><br/>Linea 185";

                                            }
                                            
                                        } else {
                                            // Hallar la cantidad de dias pagados
                                            $hortotpag = intval( $durhor / 24 );
                                            $horres = $durhor - ( $hortotpag * 24 );
                                            //echo "**".strlen($horing)."**";
                                            if ( strlen($horing) == 3 )
                                                $salper = ( substr($horing,0,1) + $horres );
                                            else
                                                $salper = ( substr($horing,0,2) + $horres );
                                            if ( $salper > 24 ) {
                                                $salper = $salper - ( intval ($salper / 24 ) * 24 );
                                            }
                                            $tietot3 = $conn->minutosAHoraDb($salper, $tiempo);
//                                            echo $horing . "--" . $horing + $horres;
                                            substr($horing,1,2);
//                                            echo " <br/> // horres '$horres' salper '$salper' tietot3 '$tietot3' horing '$horing' hora '$hora' // <br/><br/>";
                                            if ( $tietot3 < $hora ){
                                                // Esta en el tiempo permitido, puede salir
                                                echo "<script>$(\"#visado\").addClass(\"amarillo\");</script>";
                                                echo "$placa<br/><br/>";
                                                echo "Pago parcial";
                                                $update = " update movimiento set fecing = fecfac, horing = horfac, estado = 'E'
                                                    where id = $ing and parqueadero = '$parqueadero' ";
                                                $conn->consulta($update);
//                                                echo "<br/><br/>Linea 231";
                                            } else {

                                                // Esta en el tiempo permitido, puede salir
                                                echo "<script>$(\"#visado\").addClass(\"verde\");</script>";
                                                echo "$placa<br/><br/>";
                                                echo "Visado de salida";
                                                $update = " update movimiento set fecsal = '$fecha', horsal = '$horaup', 
                                                    ususal = '$login', equsal = '$equipo', dipsal = '$dirip'
                                                    where id = $ing and parqueadero = '$parqueadero' ";
                                                $conn->consulta($update);
//                                                echo "<br/><br/>Linea 241";
                                                
                                            }
                                            
                                        }
                                        
                                    } else {
                                        
                                        // Esta en el tiempo permitido, puede salir
                                        echo "<script>$(\"#visado\").addClass(\"verde\");</script>";
                                        echo "$placa<br/><br/>";
                                        echo "Visado de salida";
                                        $update = " update movimiento set fecsal = '$fecha', horsal = '$horaup', 
                                            ususal = '$login', equsal = '$equipo', dipsal = '$dirip'
                                            where id = $ing and parqueadero = '$parqueadero' ";
                                        $conn->consulta($update);
//                                        echo "<br/><br/>Linea 286";
                                        
                                    }
//                                    echo "<br/><br/>Linea 180 total $tietot";
                                } else {
                                    
                                    // Es un tipo de usuario que se le autoriza la salida sin necesidad de pago.
                                    echo "<script>$(\"#visado\").addClass(\"rojo\");</script>";
                                    echo "$placa<br/><br/>";
                                    echo "Debe facturar";
                                    $update = " update movimiento set estado = 'E'
                                        where id = $ing and parqueadero = '$parqueadero' ";
                                    $conn->consulta($update);
//                                    echo "<br/><br/>Linea 299";
                                }
                                
                            }
                            
                        } else {
                            
                            echo "<script>$(\"#visado\").addClass(\"verde\");</script>";
                            echo "$placa<br/><br/>";
                            echo " No existe<br/><br/>
                                Informacion de<br/><br/>
                                fechas y horas.<br/><br/>
                                Comunicarse con<br/><br/>
                                los administradores.";
//                            echo "<br/><br/>Linea penultima";
                            
                        }
                        
                    }
                    
                }
                
            }
            
        } else {
            
            echo "<script>$(\"#visado\").addClass(\"verde\");</script>";
            echo "Sin placa<br/><br/>";
            echo "Visado de salida";
//            echo "<br/><br/>Linea Final";
            
        }
        echo "</p>";
        
    }
    
    if ( isset ( $tipousua ) ) {
        
        if ( $tipousua == 'I' && $placa != '' ) {
            
            $up = " update vehiculos set estado = 'I' where placa = '$placa' ";
            $conn->consulta($up);
            
        }
        
    }
    
}

?>