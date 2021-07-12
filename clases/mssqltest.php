<?php

class DB2 {
    
    var $conexion;
    
    function db2() {
        
        $database = 'PARQUEAW';
        $hostname = '10.25.2.7';
        $port = '1433';
        $user = 'PARQUEAW';
        $password = 'P4RQU34W';
        $driver = 'DB2';
        $conn_string = "DRIVER=iSeries Access ODBC Driver; SYSTEM=$hostname; DBQ=$database";

        if (!$this->conexion = odbc_connect($conn_string, $user, $password, SQL_CUR_USE_ODBC)) {
            return 'Error!';
        } else {
            return 'Success!';
        }
        odbc_close($this->conexion);
    }
    
    public function consulta($sql) {
        
        $resultado = odbc_exec($this->conexion, $sql);
        if (!$resultado) {
            echo 'Error de ODBC: <br/>' . odbc_errormsg();
            echo '<br/> Error de ODBC codigo: ' . odbc_error();
            exit;
        }
        return $resultado;
        
    }
    
    
}

$conectar = new DB2();

//$da = $conectar->numeroRegsitros("select id from menu");
//echo $da;

$reg = $conectar->consulta("select id from motivo");
//$da = $conectar->obtenerDatos($sql);
$contar = 0;
//while ($row = $conectar->obtenerDatos("select id from menu") ) {
if( odbc_num_rows($reg) ) {
    while ($row = odbc_fetch_array($reg) ) {
        echo $row[odbc_field_name($reg, 1)];
        echo " - $contar <br/>";
        $contar++;
        if ( $contar == 10 )
            break;
    }
}else {
    echo "no hay datos";
}

?>