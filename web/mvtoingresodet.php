<?php
include_once '../clases/connDb2.php';
$conn = new DB2();

if ( isset ( $_GET['placa'] ) ) {
    
    $placa = strtoupper($_GET['placa']);
    
    if ( $placa != '' ) {

        $sqlSp = " select v.nombre as persona, v.tipousua, tu.nombre as tipo, v.color, v.tipovehi
            from vehiculos v inner join motivo tu on v.tipousua = tu.criterio and tabla = 'tipousua'
            where v.placa = '%s' and v.estado = 'A' ";
        $sqlid = sprintf($sqlSp, $placa);
        $consulta = $conn->consulta($sqlid);

        $res = odbc_fetch_array($consulta);
            $persona = $res[odbc_field_name($consulta, 1)];
            $tipousua = $res[odbc_field_name($consulta, 2)];
            $tiponomb = $res[odbc_field_name($consulta, 3)];
            $color = $res[odbc_field_name($consulta, 4)];
            $tipovehi = $res[odbc_field_name($consulta, 5)];
            
        if ( $persona != "" ) {
            
            echo "<label style=\"font-size: 2em; padding: 10px;\">Color veh&iacute;culo</label>";
            echo "<input type=\"text\" style=\"background-color:#$color;\" class=\"span12\" />";
            echo "<label style=\"font-size: 2em; padding: 10px;\">A nombre de</label>";
            echo "<input type=\"text\" value=\"$persona\" class=\"span12\" disabled />";
            echo "<label style=\"font-size: 2em; padding: 10px;\">Tipo usuario</label>";
            echo "<input type=\"text\" value=\"$tiponomb\" class=\"span12\" disabled />";
            echo "<input type=\"hidden\" value=\"$tipousua\" name=\"tipousua\" />";
            echo "<input type=\"hidden\" value=\"$tipovehi\" name=\"tipovehi\" />";

        } else {
            
            echo "<input type=\"hidden\" value=\"X\" name=\"tipousua\" />";

            $tip = $conn->validarPlaca($placa);
            if ( $tip == 2 ){
                $tipo = "M";
            } else if ( $tip == 3 ) {
                $tipo = "C";
            } else {
                $tipo = "C";
            }
            echo "<input type=\"hidden\" value=\"$tipo\" name=\"tipovehi\" />";
            echo "<input type=\"text\" value=\"Usuario Externo\" class=\"span12\" disabled />";
            
        }
        
    }
    
}
