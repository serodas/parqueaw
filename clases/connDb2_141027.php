<?php

date_default_timezone_set('America/Bogota');

class DB2 {
    
    var $conexion;
    
    function db2() {
        
        $database = 'PARQUEAW';
        $hostname = '10.25.2.8';
        $port = '1433';
        $user = 'PARQUEAW';
        $password = 'P4RQUE4W';
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
    
    /**
     * Funcion para obtener la fecha actual
     * @author: Luigi OsoQui
     * @link: http://www.osoqui.com
     * @return: date, la fecha actual (AAAA-MM-DD)
     */
    public function obtenerFecha(){
        return date("Y-m-d");
    }
    
    /**
     * Funcion para obtener la fecha actual en formato decimal, especial para DB2
     * @author: Luigi OsoQui
     * @link: http://www.osoqui.com
     * @return: decimal, la fecha actual (AAAAMMDD)
     */
    public function obtenerFechaDb(){
        return date("Ymd");
    }
    
    /**
     * 
     * @param type $fecini
     * @param type $fecfin
     * @return type
     */
    public function getDiferenciaDias( $fecini, $fecfin ){
        
        $datetime1 = date_create(substr($fecini,0,4)."-".substr($fecini,4,2)."-".substr($fecini,6,2));
        $datetime2 = date_create(substr($fecfin,0,4)."-".substr($fecfin,4,2)."-".substr($fecfin,6,2));
        $intervalo = date_diff($datetime1, $datetime2);
        return $intervalo->format('%a');
        
    }
    
    /**
     * Ejemplo para anadir o sumar un numero determinado de dias a una fecha en php. 
     * Muy facil haciendo uso de la funcion strtotime de php.
     * Para restar dias a una fecha seguimos el mismo proceso, solo que cambiando el operador ‘+’ por el ‘-’.
     * @param una fecha tipo date
     * @return dia siguiente tipo date
     * @link http://www.bufa.es/php-sumar-restar-dias-fecha/ 
     */
    public function diaSiguiente($fecha, $cant = "+1") {
        $nuevafecha = strtotime ( "$cant day" , strtotime ( $fecha ) ) ;
        $nuevafecha = date ( 'Y-m-d' , $nuevafecha );
        $nuevafecha = substr($nuevafecha,0, 4) . substr($nuevafecha,5, 2) . substr($nuevafecha,8, 2);
        return $nuevafecha;
    }
    
    /**
     * Funcion para obtener la hora actual en formato decimal, especial para DB2, sin segundos.
     * @author: Luigi OsoQui
     * @link: http://www.osoqui.com
     * @return: decimal, la hora actual (HHMMSS)
     */
    public function obtenerHM(){
        return date("Hi");
    }
    
    /**
     * Funcion para obtener la hora actual en formato decimal, especial para DB2, con los segundo
     * @author: Luigi OsoQui
     * @link: http://www.osoqui.com
     * @return: decimal, la hora actual (HHMMSS)
     */
    public function obtenerHoraDb(){
        return intval(date("His"));
    }
    
    /**
     * 
     * @param type $hora
     * @return string
     */
    public function cortarHora($hora) {
        
        $horaFinal = "00:00:00";
        $tamhor = trim(strlen($hora));
        
        switch ($tamhor) {
            case 1:
                $horaFinal = "00:00:$hora";
                break;
            case 2:
                $horaFinal = "00:00:$hora";
                break;
            case 3:
                $horaFinal = "00:".substr($hora,0,1).":".substr($hora,1,2);
                break;
            case 4:
                $horaFinal = "00:".substr($hora,0,2).":".substr($hora,2,2);
                break;
            case 5:
                $horaFinal = substr($hora,0,1).":".substr($hora,1,2).":".substr($hora,3,2);
                break;
            case 6:
                $horaFinal = substr($hora,0,2).":".substr($hora,2,2).":".substr($hora,4,2);
                break;
            default :
                $horaFinal = "00:00:00";
        }
        
        return $horaFinal;
        
    }
    
    public function getDiferenciaFechas($fecini, $horini, $fecfin, $horfin, $tipo = "M") {
        
        // http://www.blogdephp.com/como-calcular-la-diferencia-entre-dos-fechas-en-php-strtotime/
        $fecin = substr($fecini,0,4) . "-" . substr($fecini,4,2) . "-" . substr($fecini,6,2);
        $horin = $this->cortarHora($horini);
        $fecfi = substr($fecfin,0,4) . "-" . substr($fecfin,4,2) . "-" . substr($fecfin,6,2);
        $horfi = $this->cortarHora($horfin);
        
        $segundos = strtotime("$fecfi $horfi") - strtotime("$fecin $horin");
        
        switch ($tipo){
            case 'D': $dif=intval($segundos/60/60/24);
                break;
            case 'H': $dif=intval($segundos/60/60);
                break;
            case 'M': $dif=intval($segundos/60);
                break;
            default : $dif=$segundos;
                break;
        }
        return $dif;
        
    }
    
    /**
     * Funcion para obtener la direccion ip del cliente
     * @author: Luigi OsoQui
     * @link: http://www.osoqui.com
     * @return: varchar, con el valor de ip (255.255.255.255)
     */
    public function obtenerIp(){
        return $_SERVER['REMOTE_ADDR'];
    }
    
    /**
     * Funcion para obtener el nombre del equipo del cliente
     * @author: Luigi OsoQui
     * @link: http://www.osoqui.com
     * @return: varchar, con el valor
     */
    public function obtenerNombreEq(){
//        $hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);
        return gethostname();
//        return $hostname;
    }
    
    /**
     * Funcion para obtener una fecha completa.
     * @author: Luigi OsoQui
     * @link: http://www.osoqui.com
     * @param date $fecha la fecha que queremos procesar.
     * @return: varchar, (14 de Noviembre de 1978)
     */
    public function fechacompleta($fecha){
        $d = intval(substr($fecha, 8, 2));
        $m = $this->getNombreMes(strtolower(intval(substr($fecha, 5, 2))));
        $a = intval(substr($fecha, 0, 4));
        
        return " $d de $m de $a ";
    }
    
    /**
     * Funcion para obtener una fecha completa.
     * @author: Luigi OsoQui
     * @link: http://www.osoqui.com
     * @param decimal $fecha la fecha que queremos procesar, esta fecha esta en formato DB2
     * @return: varchar, (14 de Noviembre de 1978)
     */
    public function fechacompletaDb($fecha){
        $d = intval(substr($fecha, 6, 2));
        $m = $this->getNombreMes(intval(substr($fecha, 4, 2)));
        $a = intval(substr($fecha, 0, 4));
        
        return "  $d de $m de $a";
    }
    
    /**
     * 
     * @param type $anio
     * @param string $mes
     * @param type $nombre
     */
    public function mes($anio, $mes, $nombre) {
        echo '<div class="span4">';
        echo '<h3>'.$nombre.'</h3>';
        echo '<table class="table table-bordered table-condensed"><thead><tr>';
        $semana = explode(',', 'Do,Lu,Ma,Mi,Ju,Vi,Sa');
        for($d = 0; $d < sizeof($semana); $d++){
            echo "<td>" . $semana[$d] . "</td>";
        }
        echo '</tr></thead>';
        $diaInicial = date( "w", strtotime( $anio.'-'.$mes.'-01' ) );
        $diaFinal = date("t", mktime(0, 0, 0, $mes, 1, $anio ) );
        $contarDias = 0;
        if($mes <10 ) $mes = '0'.$mes;
        for($s=0; $s < 5; $s++){
            echo "<tr>";
            for($ds = 0; $ds < 7; $ds++){
                if($contarDias <9 ) $nd = '0'.($contarDias+1); else $nd = $contarDias+1;
                
                $sql = " select count(*) from festivo where fecfes = '" . $anio . $mes . $nd . "' and estado = 'A' ";
                $reg = $this->consulta($sql);
                $rs = odbc_fetch_array($reg);
                $esFes = $rs[odbc_field_name($reg, 1)];
                
                echo '<td';
                if ( $esFes == 1 ){
                    echo ' class="alert alert-info" ';
                }
                    if ( $ds == $diaInicial && $contarDias == 0 ) {
                        echo " onClick=\"javascript:accion('" . $anio . $mes . $nd . "')\" >";
                        echo $contarDias = $contarDias + 1;
                    } else if ($contarDias > 0 && $contarDias < $diaFinal){
                        echo " onClick=\"javascript:accion('" . $anio . $mes . $nd . "')\" >";
                        echo $contarDias = $contarDias + 1;
                    } else {
                        echo " &nbsp; ";
                    }
                echo "</td>";
            }
            echo "</tr>";
        }
        echo '</table>';
        echo '</div>';
    }
    
    /**
     * Creacion de una lista de registros sencilla, un tipo select de html
     * @author: Luigi OsoQui
     * @link: http://www.osoqui.com
     * @param: $sql sentencia a ejecutar, $nombre nombre del campo, $valor valor por defecto, $clase estilo a aplicar, $blanco = 'N' define si muestra option en blanco
     * @return: lista con value=codigo y descripcion.
     */
    function select($sql, $nombre, $valor, $clase, $blanco = 'N') {
        $resultado = odbc_exec($this->conexion, $sql);
        echo '<select name="'.$nombre.'" class="'.$clase.'">';
        if( $blanco == 'S')
            echo '<option value=""></option>';
        while ($rowSelect = odbc_fetch_array($resultado)) {
            echo '<option value="'.$rowSelect[odbc_field_name($resultado, 1)].'" ';
            if($rowSelect[odbc_field_name($resultado, 1)] == $valor) echo ' selected="" ';
            echo '>';
            echo ucwords(strtolower($rowSelect[odbc_field_name($resultado, 2)]));
            echo '</option>';
        }
        echo "</select>";
    }
    
    // Tabla sencilla
    public function tablaSencilla($sql, $encabezados){
        echo '<table class="table table-bordered table-condensed">';
            echo '<thead><tr>';
            $titulo = explode(',',$encabezados);
            $numreg = count($titulo);
            for($j=0;$j<$numreg;$j++)
                echo '<th>'.$titulo[$j].'</th>';
            echo '</tr></thead>';
            echo '<tbody>';
            $reg = $this->consulta($sql);
            while ( $rowSelect = odbc_fetch_array($reg) ) {
                echo "<tr>";
                for($i = 1; $i <= $numreg; $i++){
                    echo '<td>';
                    echo $rowSelect[odbc_field_name($reg, $i)];
                    echo '</td>';
                }
                echo '<tr>';
            }
            echo '</tbody>';
        echo '</table>';
    }
    
    
    // Creacion de una tabla con seleccion por campo.
    public function tabla($sql, $encabezados, $script, $id = "") {
        echo "<table class=\"table table-bordered table-condensed\"";
            if ( $id != "" ) echo " id=\"$id\" ";
            echo '>';

            echo "<thead>";
                echo "<tr>";
                $titulo = explode(',',$encabezados);
                $numreg = count($titulo);
                for($j=0; $j < $numreg; $j++ ) {
                    echo "<th> {$titulo[$j]} </th>";
                }
                echo "</tr>";
            echo "</thead>";
            
            $reg = odbc_exec($this->conexion, $sql);
            if( odbc_num_rows( $reg ) ) {
        

                echo "<tbody>";
                while ( $res = odbc_fetch_array($reg) ) {
                    
                    if ( $script != "" ) {
                        echo "<tr onClick=\"javascript:$script('".$res[odbc_field_name($reg, 1)]."')\">";
                    } else {
                        echo "<tr>";
                    }
                    for ($i = 1; $i <= $numreg; $i++ ) {
                        echo "<td> {$res[odbc_field_name($reg, $i)]} </td>";
                    }
                    echo "</tr>";

                }
                echo "</tbody>";

            } else {
                echo "<tr><td colspan=\"$numreg\"> No hay registros </td></tr>";
            }
        echo "</table>";
    }
    
    public function checkbox($nombre, $valor, $activo = ''){
        echo "<input type=\"checkbox\" name=\"$nombre\"";
        if( $activo != '') 
            echo " checked=\"\" ";
        echo " value=\"$valor\" ";
        echo " />";
    }
    
    public function getNombreMes($valor) {
        switch ($valor) {
            case 1:
                $mes = "Enero";
                break;
            case 2:
                $mes = "Febrero";
                break;
            case 3:
                $mes = "Marzo";
                break;
            case 4:
                $mes = "Abril";
                break;
            case 5:
                $mes = "Mayo";
                break;
            case 6:
                $mes = "Junio";
                break;
            case 7:
                $mes = "Julio";
                break;
            case 8:
                $mes = "Agosto";
                break;
            case 9:
                $mes = "Septiembre";
                break;
            case 10:
                $mes = "Octubre";
                break;
            case 11:
                $mes = "Noviembre";
                break;
            case 12:
                $mes = "Diciembre";
                break;
            Default:
                $mes = "Sin mes seleccionado";
        }
        return $mes;
    }
    
    function validarPlaca($placa){
        $cont = 0;
        for($p=0; $p < 7; $p++){
            $vr = substr($placa, $p, 1);
            if ( is_numeric( $vr ) ) {
                $cont++;
            }
        }
        return $cont;
    }
    
    /***  
     * Funciones para la seguridad y sanimiento de entradas
     * http://css-tricks.com/snippets/php/sanitize-database-inputs/
     * ***/
    
    /**
     * 
     * @param type $input valor para realizar la limpieza
     * @return type
     */
    public function cleanInput($input) {
        $search = array(
          '@<script[^>]*?>.*?</script>@si',   // Strip out javascript
          '@<[\/\!]*?[^<>]*?>@si',            // Strip out HTML tags
          '@<style[^>]*?>.*?</style>@siU',    // Strip style tags properly
          '@<![\s\S]*?--[ \t\n\r]*>@'         // Strip multi-line comments
        );

          $output = preg_replace($search, '', $input);
          return $output;
        }
    
    /**
     * 
     * @param type $input valor al que le realizamos la validacion de los datos.
     * @param type $nro cantidad de caracteres maxima permitida.
     * @link www.dvwa.com Down vulnerable web application
     * @return type
     */
    public function limpiarEntradas($input, $nro = '')  {
        if (is_array($input)) {
            foreach($input as $var=>$val) {
                $output[$var] = limpiarEntradas($val);
            }
        }
        else {
            if (get_magic_quotes_gpc()) {
                $input = stripslashes($input);
            }
            $output  = $this->cleanInput($input);
            //$output = mysql_real_escape_string($input);
        }
        
        $output = str_replace("../", "", $output);
        if ( $nro != '' ){
            $output = substr($output, 0, $nro);
        }
        return $output;
    }
    
    /**
     * Funcion para validar si el archivo al que se accede esta permitido para el usuario logueado.
     * @param $archivo nombre del archivo a validar
     * @param $usu nombre del usuario a validar
     * 
     * @example validarPermiso("mi_archivo" , "mi_usuario")
     */
    public function validarPermiso($archivo, $usu) {
        if($archivo == "inicio" or $archivo == "error404") {
            echo "";
        } else {
            $sentencia = " select p.id from menu m, permiso p
                where m.archivo = '$archivo' and m.id = p.menu and p.login = '$usu' and p.estado = 'A' ";
            $contar = $this->consulta($sentencia);
            
            $rso = odbc_fetch_array($contar);
            $id = $rso[odbc_field_name($contar, 1)];
            if ( $id == '' ) {
                ?>
                <script type="text/javascript">
                    alert('No tiene autorizacion para abrir el vinculo.');
                    window.location.href="javascript:history.back()";
                </script>
                <?php
            }
        }
    }
    
    function tipoDia($fecha) {
        $sql = " select count(*) from festivo where fecfes = '$fecha' and estado = 'A' ";
        $reg = odbc_exec($this->conexion, $sql);
        $rso = odbc_fetch_array($reg);
        
        if( $rso[odbc_field_name($reg, 1)] > 0 ) {
            $dia = 7;
        } else {
            $fecha = substr($fecha, 0, 4)."-".substr($fecha, 4, 2)."-".substr($fecha, 6, 2);
            $dia = date( "w", strtotime( $fecha ) );
        }
        return $dia;
    }
    
}
?>