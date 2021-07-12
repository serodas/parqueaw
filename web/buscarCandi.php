<?php

include_once '../clases/connDb2.php';
$conn = new DB2();
$fecha = $conn->obtenerFechaDb();
$hora = $conn->obtenerHoraDb();

if ( isset ( $_GET['canumdocum'] ) ) {
    
    $canumdocum = $_GET['canumdocum'];
    $sql = " SELECT IFNULL(CAPRINOM,''), IFNULL(CASEGNOM,''), IFNULL(CAPRIAPE,''), IFNULL(CASEGAPE,'') 
        FROM BDGESHUM.TBTHCANDI 
        WHERE CANUMDOCUM = '$canumdocum' ";
    $consulta = $conn->consulta($sql);
    $res = odbc_fetch_array($consulta);
    
    echo "<label>Nombre completo</label>";
    
    if ( trim($res[odbc_field_name($consulta, 1)]) != '' ) {
        
        $nombreCompleto = ucwords(strtolower(trim($res[odbc_field_name($consulta, 1)]) . " " . trim($res[odbc_field_name($consulta, 2)]) . " " . trim($res[odbc_field_name($consulta, 3)]) . " " . trim($res[odbc_field_name($consulta, 4)])));
//        echo "<input type=\"hidden\" class=\"span12\" name=\"nombre\" value=\"$nombreCompleto\" /> ";
        echo "<input type=\"text\" class=\"span12\" name=\"nombre\"  value=\"$nombreCompleto\" />";
        
    } else {
        
        $sql = " select v.nombre from vehiculos v where v.canumdocum = '$canumdocum' ";
        $consulta = $conn->consulta($sql);
        $res = odbc_fetch_array($consulta);
        $nombre = trim($res[odbc_field_name($consulta, 1)]);

        if( $nombre != '' ) {

            echo "<input type=\"text\" class=\"span12\" name=\"nombre\" value=\"$nombre\" /> ";
        
        } else {
            
            echo "<input type=\"text\" class=\"span12\" name=\"nombre\" value=\"\" /> ";
        
        }
        
    }
    
}

?>
