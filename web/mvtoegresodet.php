<?php
session_start();
?>
<style type="text/css">
       .verde{ background-color: #093; color:#FFF; }
    .amarillo{ background-color: #FF0; color:#000; }
        .rojo{ background-color: #F00; color:#FFF; }
        .azul{ background-color: #30C; color:#FFF; }
     .celeste{ background-color: #0CF; color:#000; }
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
    
    $ing = strtoupper($conn->limpiarEntradas($_GET['ing']));
    
    if ( !is_numeric ( $ing ) ) {
        
        $sql = " select max(id) from movimiento where placa = '$ing' ";
        $consulta = $conn->consulta($sql);
        $res = odbc_fetch_array($consulta);
        $ing = $res[odbc_field_name($consulta, 1)];
        
    }
    
    $parqueadero = $conn->limpiarEntradas($_GET['par'], 2);
    
    if ( $ing != '' ) {
        
        $sqlSp = " select m.mrcodcons, m.tipousua, m.fecing, m.horing, m.fecfac, m.horfac, m.fecsal, 
                m.horsal, m.estado, p.tiempo, ifnull(pr.placa, '') as placaperm, m.durhor, m.placa, 
                ifnull(v.nombre, '') as empleado, m.id
            from movimiento m 
                inner join parqueadero p on m.parqueadero = p.id 
                left join prueba pr on m.placa = pr.placa
                left join vehiculos v on m.placa = v.placa
            where m.id = '%d' and m.parqueadero = '%d' and m.mrcodcons > -1 and fecsal = 0 ";
        $sqlid = sprintf($sqlSp, $ing, $parqueadero);
        $consulta = $conn->consulta($sqlid);
        
        ?>
        <p id="visado" style="font-size: 2em; text-align: center; padding: 15px 0; margin-top: 10px;">
        <?php
        
        $res = odbc_fetch_array($consulta);
        $idmvto = $res[odbc_field_name($consulta, 15)];
        
        if ( $idmvto > 0 ) {
            
            $mrcodcons = $res[odbc_field_name($consulta, 1)];
            $tipousua = $res[odbc_field_name($consulta, 2)];
            $fecing = $res[odbc_field_name($consulta, 3)];
            $horing = trim($res[odbc_field_name($consulta, 4)]);
            $fecfac = $res[odbc_field_name($consulta, 5)];
            $horfac = trim($res[odbc_field_name($consulta, 6)]);
            $fecsal = $res[odbc_field_name($consulta, 7)];
            $horsal = $res[odbc_field_name($consulta, 8)];
            $estado = $res[odbc_field_name($consulta, 9)];
            $tiempo = $res[odbc_field_name($consulta, 10)];
            $placaper = trim($res[odbc_field_name($consulta, 11)]);
            $durhor = $res[odbc_field_name($consulta, 12)];
            $placa = $res[odbc_field_name($consulta, 13)];
            $nombrePersona = trim($res[odbc_field_name($consulta, 14)]);
            
            // Visado de salida por tiempo
            $autorizarsalida = false;
            switch ($parqueadero) {
                case 1:
                    if ( date('H:i') < '06:00' ) {
                        $autorizarsalida = true;
                    } else {
                        if ( date('H:i') > '21:30' ) {
                            $autorizarsalida = true;
                        } else {
                            if ( date( "w", strtotime( $fecha ) ) == 0 ) {
                                $autorizarsalida = true;
                            }
                        }
                    }
                    break;
                case 2:
                    if ( date('H:i') < '06:00' ) {
                        $autorizarsalida = true;
                    } else {
                        if ( date('H:i') > '22:00' ) {
                            $autorizarsalida = true;
                        } else {
                            if ( date( "w", strtotime( $fecha ) ) == 0 ) {
                                $autorizarsalida = true;
                            }
                        }
                    }
                    break;
                default :
                    $autorizarsalida = false;
            }
            
            if ( $autorizarsalida ) {
                $update = " update movimiento set ";
                if ( $mrcodcons == 0 ) {
                    $update.= " mrcodcons = -1, ";
                }
                if ( $fecfac == 0 ) {
                    $update.= " fecfac = '$fecha', horfac = '$hora', usufac = '$login', equfac = '$equipo', dipfac = '$dirip', ";
                }
                $update.= " fecsal = '$fecha', horsal = '$hora', ususal = '$login', equsal = '$equipo', dipsal = '$dirip'
                    where id = $ing and parqueadero = '$parqueadero' and placa = '$placa' ";
                $conn->consulta($update);
                
                echo "<script>$(\"#visado\").addClass(\"azul\");</script>";
                echo "$placa <br/><br/>";
                echo "Visado de salida";
                exit;
            }
            
            /*
            if ( ( ( $parqueadero == 1 && ( date('H:i') < '06:00' or date('H:i') > '21:30' ) ) or date( "w", strtotime( $fecha ) ) == 0 ) 
                or ( ( $parqueadero == 2 && ( date('H:i') < '06:00' or date('H:i') > '22:00' ) ) or date( "w", strtotime( $fecha ) ) == 0 )
                     ) {
                // or date( "w", strtotime( $fecha ) ) == 0
            //if ( ( $parqueadero == 1 or $parqueadero == 2 ) && ( ( date('H:i') < '06:00' or date('H:i') > '21:30' ) or date( "w", strtotime( $fecha ) ) == 0 ) ) {
                $update = " update movimiento set ";
                if ( $mrcodcons == 0 ) {
                    $update.= " mrcodcons = -1, ";
                }
                if ( $fecfac == 0 ) {
                    $update.= " fecfac = '$fecha', horfac = '$hora', usufac = '$login', equfac = '$equipo', dipfac = '$dirip', ";
                }
                $update.= " fecsal = '$fecha', horsal = '$hora', ususal = '$login', equsal = '$equipo', dipsal = '$dirip'
                    where id = $ing and parqueadero = '$parqueadero' and placa = '$placa' ";
                $conn->consulta($update);
                
                echo "<script>$(\"#visado\").addClass(\"azul\");</script>";
                echo "$placa <br/><br/>";
                echo "Visado de salida";
                exit;
            }
            */
            //echo "Datos completos mrcodcons $mrcodcons - tipousua $tipousua <br/>";
            
            if ( $fecsal == 0 ) {

                $tiempoTotal = '';
                $mining = $conn->getDiferenciaFechas($fecing, $horing, $fecha, $hora);
                $dias = intval($mining/60/24);
                $horas = intval(($mining - ($dias*24*60))/60);
                if ( $dias > 0 ) {
                    $tiempoTotal.= $dias . "/ ";
                }
                if ( $mining >= 60 ) {
                    $tiempoTotal.= $horas . ":";
                }
                $tiempoTotal.= intval($mining - ($dias*24*60) - $horas*60 );
                //echo " mining $mining ";
                if ( $placaper != '' ) {
                    // Es una placa registrada de forma especial, no se realizara cobro.
                    $update = " update movimiento set ";
                    if ( $mrcodcons == 0 ) {
                        $update.= " mrcodcons = -1, ";
                    }
                    if ( $fecfac == 0 ) {
                        $update.= " fecfac = '$fecha', horfac = '$hora', usufac = '$login', equfac = '$equipo', dipfac = '$dirip', ";
                    }
                    $update.= " fecsal = '$fecha', horsal = '$hora', ususal = '$login', equsal = '$equipo', dipsal = '$dirip'
                        where id = $ing and parqueadero = '$parqueadero' and placa = '$placa' ";
                    $conn->consulta($update);
                    echo "<script>$(\"#visado\").addClass(\"azul\");</script>";
                    echo "$placa <br/><br/>";
                    echo "Visado de salida";
                    echo "<br/><br/> Tiempo $tiempoTotal";
                    if ( $nombrePersona != '' ) {
                        echo "<br/><br/> $nombrePersona";
                    }
                    //echo "<br/><br/>Linea 85";

                } else {

                    if ( $tipousua == 'E' && $parqueadero != '4') {

                        // Si ya tiene la hora de salida, es porque el funcionario paso nuevamente el registro.
                        // O si es uno de los usuarios permitidos.
                        // Ya puede salir sin problema.
                        $update = " update movimiento set ";
                        if ( $mrcodcons == 0 ) {
                            $update.= " mrcodcons = -1, ";
                        }
                        if ( $fecfac == 0 ) {
                            $update.= " fecfac = '$fecha', horfac = '$hora', usufac = '$login', equfac = '$equipo', dipfac = '$dirip', ";
                        }
                        $update.= " fecsal = '$fecha', horsal = '$hora', ususal = '$login', equsal = '$equipo', dipsal = '$dirip'
                            where id = $ing and parqueadero = '$parqueadero' and placa = '$placa' ";
                        $conn->consulta($update);
                        echo "<script>$(\"#visado\").addClass(\"azul\");</script>";
                        echo "$placa <br/><br/>";
                        echo "Visado de salida";
                        echo "<br/><br/> Tiempo $tiempoTotal";
                        if ( $nombrePersona != '' ) {
                            echo "<br/><br/> $nombrePersona";
                        }
        //                echo "<br/><br/>Linea 110";

                    } else if ( $fecsal != 0 or $tipousua == 'I' or $tipousua == 'A' or $tipousua == 'P' or $tipousua == 'V' or $tipousua == 'C' ) {

                        // Si ya tiene la hora de salida, es porque el funcionario paso nuevamente el registro.
                        // O si es uno de los usuarios permitidos.
                        // Ya puede salir sin problema.
                        $update = " update movimiento set ";
                        if ( $mrcodcons == 0 ) {
                            $update.= " mrcodcons = -1, ";
                        }
                        if ( $fecfac == 0 ) {
                            $update.= " fecfac = '$fecha', horfac = '$hora', usufac = '$login', equfac = '$equipo', dipfac = '$dirip', ";
                        }
                        $update.= " fecsal = '$fecha', horsal = '$hora', ususal = '$login', equsal = '$equipo', dipsal = '$dirip'
                            where id = $ing and parqueadero = '$parqueadero' and placa = '$placa' ";
                        $conn->consulta($update);
                        echo "<script>$(\"#visado\").addClass(\"celeste\");</script>";
                        echo "$placa <br/><br/>";
                        echo "Visado de salida";
                        echo "<br/><br/> Tiempo $tiempoTotal";
                        if ( $nombrePersona != '' ) {
                            echo "<br/><br/> $nombrePersona";
                        }
        //                echo "<br/><br/>Linea 110";

                    } else {

                        // Validamos si es el parque recreacional Comfamiliar Galicia.
                        // Para no contar por minutos-horas si no por dias.
                        // echo "<br/><br/>Linea 164";
                        if ( $parqueadero == 3 ) {

                            if ( $fecfac == $fecha ) {

                                $update = " update movimiento set ";
                                if ( $mrcodcons == 0 ) {
                                    $update.= " mrcodcons = -1, ";
                                }
                                if ( $fecfac == 0 ) {
                                    $update.= " fecfac = '$fecha', horfac = '$hora', usufac = '$login', equfac = '$equipo', dipfac = '$dirip', ";
                                }
                                $update.= " fecsal = '$fecha', horsal = '$hora', ususal = '$login', equsal = '$equipo', dipsal = '$dirip'
                                    where id = $ing and parqueadero = '$parqueadero' and placa = '$placa' ";
                                $conn->consulta($update);
                                echo "<script>$(\"#visado\").addClass(\"verde\");</script>";
                                echo "$placa <br/><br/>";
                                echo "Visado de salida";
                                echo "<br/><br/> Tiempo $tiempoTotal";
        //                        echo "<br/><br/>Linea 134";

                            } else {

                                echo "<script>$(\"#visado\").addClass(\"rojo\");</script>";
                                echo "$placa <br/><br/>";
                                echo "Debe facturar";
                                echo "<br/><br/> Tiempo $tiempoTotal";
                                echo "<br/><br/>Linea 191";

                            }

                        } else {

                            // No es registo de prueba, no es empleado, no es usuario permitido.
                            // Validamos que no lleva mas de X minutos en el parqueadero.

                            // echo "<br/><br/>Linea 200 mining $mining tiempo $tiempo <br/>";
                            if ( $mining <= $tiempo ) {

                                $update = " update movimiento set ";
                                if ( $mrcodcons == 0 ) {
                                    $update.= " mrcodcons = -1, ";
                                }
                                if ( $fecfac == 0 ) {
                                    $update.= " fecfac = '$fecha', horfac = '$hora', usufac = '$login', equfac = '$equipo', dipfac = '$dirip', ";
                                }
                                $update.= " fecsal = '$fecha', horsal = '$hora', ususal = '$login', equsal = '$equipo', dipsal = '$dirip'
                                    where id = $ing and parqueadero = '$parqueadero' and placa = '$placa' ";
                                $conn->consulta($update);
                                echo "<script>$(\"#visado\").addClass(\"verde\");</script>";
                                echo "$placa <br/><br/>";
                                echo "Visado de salida";
                                echo "<br/><br/> Tiempo $tiempoTotal";
                                if ( $nombrePersona != '' ) {
                                    echo "<br/><br/> $nombrePersona";
                                }
                                // echo "<br/><br/>Linea 220";

                            } else {
                                // echo "<br/><br/>Linea 223";
                                // Validamos que tenga una factura realizada para revisar el tiempo permitido
                                // Si no es asi, se debe realizar la factura.
                                $mindis = ( $durhor * 60 ) + ( $tiempo * 2 );
                                // echo " durhor $durhor ** $tiempo { mining $mining <= mindis $mindis } ";
                                
                                if ( $fecfac == 0 ) {
                                    echo "<script>$(\"#visado\").addClass(\"rojo\");</script>";
                                    echo "$placa <br/><br/>";
                                    echo "Debe facturar";
                                    echo "<br/><br/> Tiempo $tiempoTotal";
                                    if ( $nombrePersona != '' ) {
                                        echo "<br/><br/> $nombrePersona";
                                    }
                                    // echo "<br/><br/>Linea 237";
                                } else if ( $mining <= $mindis ) {

                                    $update = " update movimiento set ";
                                    if ( $mrcodcons == 0 ) {
                                        $update.= " mrcodcons = -1, ";
                                    }
                                    if ( $fecfac == 0 ) {
                                        $update.= " fecfac = '$fecha', horfac = '$hora', usufac = '$login', equfac = '$equipo', dipfac = '$dirip', ";
                                    }
                                    $update.= " fecsal = '$fecha', horsal = '$hora', ususal = '$login', equsal = '$equipo', dipsal = '$dirip'
                                        where id = $ing and parqueadero = '$parqueadero' and placa = '$placa' ";
                                    $conn->consulta($update);
                                    echo "<script>$(\"#visado\").addClass(\"verde\");</script>";
                                    echo "$placa <br/><br/>";
                                    echo "Visado de salida";
                                    echo "<br/><br/> Tiempo $tiempoTotal";
                                    if ( $nombrePersona != '' ) {
                                        echo "<br/><br/> $nombrePersona";
                                    }
                                    // echo "<br/><br/>Linea 256";

                                } else {

                                    // Ahora validamos los tiempos de ingreso y salida.
                                    // Establecer el tipo de dia
                                    $tipoDia = $conn->tipoDia($fecha);
                                    $sql2 = " select h.inicio, h.fin 
                                        from parqueadero p inner join parquehora h on p.id = h.parqueadero
                                        where p.id = '$parqueadero' and h.dia = '$tipoDia' and p.estado = 'A' and h.estado = 'A' ";
                                    $consulta2 = $conn->consulta($sql2);
                                    $res2 = odbc_fetch_array($consulta2);
                                    $inicio = $res2[odbc_field_name($consulta2, 1)];
                                    $fin = $res2[odbc_field_name($consulta2, 2)];
                                    // echo "<br/><br/>Linea 271";
                                    if ( ( $tipoDia == 0 or $tipoDia == 7 ) or ( $hora > $fin or $hora < $inicio ) ) {

                                        // No hay cajas para facturar, se autoriza salida.
                                        $update = " update movimiento set ";
                                        if ( $mrcodcons == 0 ) {
                                            $update.= " mrcodcons = -1, ";
                                        }
                                        if ( $fecfac == 0 ) {
                                            $update.= " fecfac = '$fecha', horfac = '$hora', usufac = '$login', equfac = '$equipo', dipfac = '$dirip', ";
                                        }
                                        $update.= " fecsal = '$fecha', horsal = '$hora', ususal = '$login', equsal = '$equipo', dipsal = '$dirip'
                                            where id = $ing and parqueadero = '$parqueadero' and placa = '$placa' ";
                                        $conn->consulta($update);
                                        echo "<script>$(\"#visado\").addClass(\"verde\");</script>";
                                        echo "$placa<br/><br/>";
                                        echo "Visado de salida";
                                        echo "<br/><br/> Tiempo $tiempoTotal";
                                        if ( $nombrePersona != '' ) {
                                            echo "<br/><br/> $nombrePersona";
                                        }
                                        // echo "<br/><br/>Linea 292";

                                    } else {

                                        if ( $mindis < $tiempo ) {
//                                            echo " mindis > tiempo $mindis > $tiempo <br/><br/>";
                                            //echo $horvis = $horing + ( $durhor * 10000 );
                                            $update = " update movimiento set mrcodcons = 0, estado = 'E' ";
                                            if ( $fecfac > 0 ){
                                                $update.= ", fecing = fecfac, horing = $horvis ";
                                            }
                                            $update.= " where id = $ing and parqueadero = '$parqueadero' and placa = '$placa' ";
                                            
                                            $conn->consulta($update);
                                            echo "<script>$(\"#visado\").addClass(\"amarillo\");</script>";
                                            echo "$placa<br/><br/>";
                                            echo "Pago parcial";
                                            echo "<br/><br/> Tiempo $tiempoTotal";
                                            if ( $nombrePersona != '' ) {
                                                echo "<br/><br/> $nombrePersona";
                                            }
                                            // echo "<br/><br/>Linea 313";

                                        } else {

                                            echo "<script>$(\"#visado\").addClass(\"rojo\");</script>";
                                            echo "$placa <br/><br/>";
                                            echo "Debe facturar";
                                            echo "<br/><br/> Tiempo $tiempoTotal";
                                            if ( $nombrePersona != '' ) {
                                                echo "<br/><br/> $nombrePersona";
                                            }
                                            // echo "<br/><br/>Linea 324";

                                        }

                                    }

                                }

                            }

                        }

                    }

                }
            
            } else {
                
                echo "<script>$(\"#visado\").addClass(\"verde\");</script>";
                echo "Registro inactivo <br/><br/>";
                echo "Puede salir <br/><br/>";
                echo "Recibo ya salio";
                
            }
            
        } else {
            
            echo "<script>$(\"#visado\").addClass(\"verde\");</script>";
            echo "Registro inactivo <br/><br/>";
            echo "Puede salir <br/><br/>";
            echo "No hay datos";
            
        }
        ?>
        </p>
        <?php
        
    }
    
    if ( isset ( $tipousua ) ) {
        
        if ( $tipousua == 'I' && $placa != '' ) {
            
            $up = " update vehiculos set estado = 'I' where placa = '$placa' ";
            $conn->consulta($up);
            
        }
        
    }
    
}

?>