<?php
if ( isset ( $_POST ) ) {
    session_start();
    include_once('connDb2.php');
    $parqueo        = new DB2();
    $login          = $_SESSION['login'];
    $parqueadero    = $_POST['parqueadero'];
    $placa          = $_POST['placa'];
    
    $sentencia = " select count(*) from ingresotmp where parqueadero = '$parqueadero' and placa = '$placa' ";
    
    $reg        = odbc_exec($parqueo->conexion, $sentencia);
    $res        = odbc_fetch_array($reg);
    $cantidad   = $res[odbc_field_name($reg, 1)];
    if( $cantidad > 0 ) {
        $sql = " delete from ingresotmp where parqueadero = '$parqueadero' and placa = '$placa' ";
        odbc_exec($parqueo->conexion, $sql);
        echo json_encode(array('estado'=>true, 'mensaje'=>'Se elimina la placa'));
    } else {
        $sql = " insert into ingresotmp values ($parqueadero, '$placa')";
        odbc_exec($parqueo->conexion, $sql);
        echo json_encode(array('estado'=>true, 'mensaje'=>'Se agrega la placa'));
    }
    
}
