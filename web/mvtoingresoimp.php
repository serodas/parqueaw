<?php
session_start();

if ( isset ( $_GET['mvtoid'] ) ) {
    $mvtoid = $_GET['mvtoid'];
} else {
    $mvtoid = $_SESSION['mvtoid'];
}
//$_SESSION['tarcar'] = 1200;
//$_SESSION['tarmot'] = 600;
//$_SESSION['parqueadero'] = 1;
//$_SESSION['nombre'] = "locura";
//$mvtoid = 1;

if ( $mvtoid > 0 ) {

    include_once('../clases/connDb2.php');
    $parqueo = new DB2();
    $parqueadero = $_SESSION['parqueadero'];
    
    if($parqueadero == 1){
        $direccion = 'Av.Circunvalar 3-01';
    }else{
        if($parqueadero == 2){
            $direccion = 'Calle 22 4-40';
        }else{
            if($parqueadero == 3){
                $direccion = utf8_decode('Km 11 Vía Cerritos, Sector Galicia');
            }else{
                $direccion = utf8_decode('Dosquebradas');
            }
        }
    }

    $sql1 = " select nombre from parqueadero p 
        where p.id = $parqueadero and p.estado = 'A' ";

    $sql2 = " select mrcodcons from movimiento m
        where m.id = $mvtoid";
    
    $reg1 = odbc_exec($parqueo->conexion, $sql1);
    $reg2 = odbc_exec($parqueo->conexion, $sql2);
    if ( odbc_num_rows( $reg1 ) ) {
        
        $res1 = odbc_fetch_array($reg1);
        $_SESSION['nombre'] = $res1[odbc_field_name($reg1, 1)];
        
    }
    if ( odbc_num_rows( $reg2 ) ) {
        
        $res2 = odbc_fetch_array($reg2);
        $mrcodcons = $res2[odbc_field_name($reg2, 1)];
    }

    $facturadoMicomfamiliar = false;
    if($mrcodcons > 0){
        $facturadoMicomfamiliar = true;
    }
    
    require('fpdf/code39.php');
    
    class PDF_AutoPrint extends PDF_Code39
    
    {
        
        function AutoPrint($dialog=false){
            //Abre el cuadro para la impresion o envia a impresion diractamente.
            $param=($dialog ? 'true' : 'false');
            $script="print($param);";
            $this->IncludeJS($script);
        }
        
        function AutoPrintToPrinter($server, $printer, $dialog=false) {
            
            $script = "var pp = getPrintParam();";
            if($dialog)
                $script .= "pp.interactive = pp.constants.interactionLevel.full;";
            else
                $script .= "pp.interactive = pp.constants.interactionLevel.automatic;";
            $script .= "pp.printerName = '\\\\\\\\".$server."\\\\".$printer."';";
            $script .= "print(pp);";
            $this->IncludeJS($script);
            
        }
        
    }
    
    
    // tirilla hace referencia a un atributo en la clase, para establecer el tamanio de la hoja (ancho)
    $pdf = new PDF_AutoPrint('P','mm','tirilla');
    $pdf->AliasNbPages();
    $pdf->AddPage();
    $pdf->SetMargins(1, 1);
    $pdf->SetFont('Arial','',8);
    $pdf->Ln(-8);
    
    $pdf->Cell(72,5,utf8_decode("Parqueadero ".$_SESSION['nombre']), 0, 0, 'C');
    $pdf->Ln(3);
    $pdf->Cell(72,5,"NIT. 891.480.000-1", 0, 0, 'C');
    $pdf->Ln(3);
    $pdf->Cell(72,5,$direccion, 0, 0, 'C');
    $pdf->Ln(3);
    $pdf->Cell(72,5,"Tiquete de ingreso ", 0, 0, 'C');
    $pdf->Ln(4);
    
    $sql = " select m.placa, ifnull(v.nombre, 'Particular'), m.fecing, m.horing, 
            ev.nombre as estavehi, tv.nombre as tipov, m.tipovehi
        from movimiento m 
        left join vehiculos v on m.placa = v.placa and v.estado = 'A'
        inner join motivo ev on m.estavehi = ev.criterio and ev.tabla = 'estavehi' 
        inner join motivo tv on m.tipovehi = tv.criterio and tv.tabla = 'tipovehi' 
        where m.id = '%d' ";
    
    $sqlid = sprintf($sql, $mvtoid);
    $consulta = $parqueo->consulta($sqlid);

    if( odbc_num_rows($consulta) ) {

        $res = odbc_fetch_array($consulta);
        $placa = $res[odbc_field_name($consulta, 1)];
        $perso = ucwords(strtolower($res[odbc_field_name($consulta, 2)]));
        $fecin = $res[odbc_field_name($consulta, 3)];
        $fecin = substr($fecin, 0, 4) . "/" . substr($fecin, 4, 2) . "/" . substr($fecin, 6, 2);
        $horin = trim($res[odbc_field_name($consulta, 4)]);
        $tamanio = strlen($horin);
        switch ($tamanio) {
            case 1: // S
                $horaingreso = '00:00';
                break;
            case 2: // SS
                $horaingreso = '00:00';
                break;
            case 3: // M:SS
                $horaingreso = '00:0' . substr($horin, 0, 1);
                break;
            case 4: // MM:SS
                $horaingreso = '00:' . substr($horin, 0, 2);
                break;
            case 5: // H:MM:SS
                $horaingreso = '0' . substr($horin, 0, 1) . ':' . substr($horin, 1, 2);
                break;
            case 6: // HH:MMM:SS
                $horaingreso = substr($horin, 0, 2) . ':' . substr($horin, 2, 2);
                break;
            default :
                $horaingreso = '';
                break;
        }
//        if ( $horin > 99999 ) {
//            $horin = substr($horin, 0, 2) . ":" . substr($horin, 2, 2);
//        } else {
//            $horin = substr($horin, 0, 1) . ":" . substr($horin, 1, 2);
//        }
        $estavehi = $res[odbc_field_name($consulta, 5)];
        $tipovehi = $res[odbc_field_name($consulta, 6)];
        $tv = $res[odbc_field_name($consulta, 7)];
        
    }
    
    $pdf->Cell(40,5,"Propietario: " .utf8_decode($perso), 0, 0, 'J');
    $pdf->Ln(3);
    $pdf->Cell(20,5,"Placa", 0, 0, 'J');
    $pdf->Cell(20,5,"Fecha", 0, 0, 'J');
    $pdf->Cell(20,5,"Hora", 0, 0, 'J');
    $pdf->Cell(10,5,"Tipo", 0, 0, 'J');
    $pdf->Ln(3);
    $pdf->Cell(20,5,$placa, 0, 0, 'J');
    $pdf->Cell(20,5,$fecin, 0, 0, 'J');
    $pdf->Cell(20,5,$horaingreso, 0, 0, 'J');
    $pdf->Cell(10,5,$tipovehi, 0, 0, 'J');
    $pdf->Ln(3);
    $pdf->Cell(40,5,"Estado: " .utf8_decode($estavehi), 0, 0, 'J');
    
    if ( $tv == 'M' ) {
        $tarifa = $_SESSION['tarmot'];
    } else {
        $tarifa = $_SESSION['tarcar'];
    }
    if ( $parqueadero == 3 ) {
        $pdf->Cell(30,5,"Dia: $ " .  number_format($tarifa), 0, 0, 'J');
        $pdf->Ln(4);
        $mensaje= $facturadoMicomfamiliar ? 'Pagado desde Micomfamiliar' : 'Recuerde que debe cancelar el parqueadero al ingreso al parque Consotá';
        $pdf->MultiCell(72,3,utf8_decode($mensaje), 0, 'J');
    } else {
        $pdf->Cell(30,5,"Hora/fraccion: $ " .  number_format($tarifa), 0, 0, 'J');
    }
    $pdf->Ln(1);
    
    $tam = strlen($mvtoid);
    if ( $tam <  4 ) {
        switch ($tam) {
            case 1: $mvtoid = "0000".$mvtoid; break;
            case 2: $mvtoid = "000".$mvtoid; break;
            case 3: $mvtoid = "00".$mvtoid; break;
            case 4: $mvtoid = "0".$mvtoid; break;
            default : $mvtoid = $mvtoid;
        }
    }
    
    if ( $parqueadero == 3 ) {
        $pdf->Code39(3,35,$mvtoid, 1,10);
        $pdf->Ln(18);
        $pdf->MultiCell(72,3,utf8_decode('Recuerde que debe cancelar el parqueadero al ingreso al parque Consotá'), 0, 'J');
    } else {
        $pdf->Code39(3,30,$mvtoid, 1,10);
        $pdf->Ln(20);
    }
    $pdf->MultiCell(72,3,utf8_decode('Este recurso contribuye al Programa de Atención a la Discapacidad de nuestros usuarios afiliados a la Caja de Compensación Familiar "P.A.D".'), 0, 'J');
    $pdf->Ln(1);
    $pdf->MultiCell(72,3,utf8_decode('Favor informar en el punto de pago y/o supervisor de seguridad de la existencia de objetos de valor dentro del vehículo, de igual forma de los equipos anexos y/o complementarios del vehículo. Dicho reporte debe dejarse por escrito. Este tiquete es solo un control de ingreso y salida de vehículos; no es un comprobante de recibo del vehículo, ni constituye prueba de depósito ni de contrato alguno.'), 0, 'J');
    $pdf->Ln(1);
    $pdf->MultiCell(72,3,utf8_decode('Antes de abordar el vehículo pasar por el punto de pago.'), 0, 'J');
    $pdf->Ln(1);
    $pdf->MultiCell(72,3,utf8_decode('Póliza de responsabilidad civil número 022235434/0, Alianza Seguros S.A. Teléfono 3152400, reconocimiento de incidentes. 1) informar y reportar el incidente al coordinador de vigilancia de manera inmediata.  2) Comfamiliar Risaralda da respuesta al usuario y solicita soportes pertinentes (si aplica)  3)Allianza seguros realiza el pago del evento (si Aplica).'), 0, 'J');
    $pdf->AutoPrint(false);
    $pdf->Output();
    
} else {
    
    echo "Error, esta accediendo de manera errada a la aplicacion.";
    
}
?>
