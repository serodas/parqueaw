<?php
session_start();

//$_SESSION['parqueadero'] = 1;
$parqueadero = $_SESSION['parqueadero'];

if ( $parqueadero > 0 ) {

    include_once('../clases/connDb2.php');
    $parqueo = new DB2();
    
    require('fpdf/fpdf.php');
    
    class PDF extends FPDF {
        
        var $col=0;
        var $y=0;
        var $y0=20;
        
        function Header()
        {
//            $this->Image('fpdf/logoempresa.jpg',5,5,25);
            $this->SetFont('Arial','B',10);
            $this->Cell(190,5,"COMFAMILIAR RISARALDA", 0, 0, 'C');
            $this->Ln(5);
            $this->Cell(190,5,utf8_decode("Listado de vehículos en el parqueadero ordenado por placa"), 0, 0, 'C');
            $this->Ln(5);
        }

        function CheckPageBreak($h) {
            if ($this->GetY() + $h > $this->PageBreakTrigger)
            $this->AddPage($this->CurOrientation);
        }
        
        function Footer()
        {
            $this->SetY(-15);
            $this->SetFont('Arial','I',10);
            $this->Cell(0,10,utf8_decode('Página '.$this->PageNo().'/{nb}'),0,0,'C');
        }
        
        function SetCol($col) {
            //Establecer la posición de una columna dada
            $this->col=$col;
            $x=10+$col*97;
            $this->SetLeftMargin($x);
            $this->SetX($x);
        }

        function AcceptPageBreak() {
            //Método que acepta o no el salto automático de página
            if($this->col<1) {
                //Ir a la siguiente columna
                $this->SetCol($this->col+1);
                //Establecer la ordenada al principio
                $this->SetY($this->y0);
                //Seguir en esta página
                return false;
            } else {
                //Volver a la primera columna
                $this->SetCol(0);
                //Salto de página
                return true;
            }
        }
        
    }
    
    $pdf = new PDF();
    $pdf->AliasNbPages();
    $pdf->AddPage();
    $pdf->SetMargins(10, 10, 5);
    $pdf->SetFont('Arial','',7);
    
    $procesos = " select distinct p.prcodproce, p.prdesproce, v.placa, v.nombre
        from vehiculos v
            inner join movimiento m on v.placa = m.placa and m.fecsal = 0 and v.estado = 'A'
            left join bdgeshum.tbthcandi c on v.canumdocum = c.canumdocum 
            left join bdgeshum.tbthmaeper mp on c.cacodcandi = mp.cacodcandi and mp.aucodestad = 'A'
            left join bdgeshum.tbthproce p on mp.prcodproce = p.prcodproce
        where m.parqueadero = $parqueadero
        group by p.prcodproce, p.prdesproce, v.placa, v.nombre
        order by v.placa ";

    $reg = odbc_exec($parqueo->conexion, $procesos);
    
    if( odbc_num_rows( $reg ) ) {
        
        $desproce = "";
        while ( $res = odbc_fetch_array($reg) ) {
            
            $pdf->Cell(5,4,"", 1, 0, 'J');
            $pdf->Cell(12,4,$res[odbc_field_name($reg, 3)], 0, 0, 'J');
            if ( trim($res[odbc_field_name($reg, 2)]) == '' ){
                    
                $pdf->Cell(40,4,"Sin proceso ", 0, 0, 'J');
                    
            } else {

                $pdf->Cell(40,4,substr($res[odbc_field_name($reg, 2)],0,25), 0, 0, 'J');

            }
            
            $pdf->Cell(25,4,$res[odbc_field_name($reg, 4)], 0, 0, 'J');
            $pdf->Ln();
              
        }
        
    }
    $pdf->SetCol(0);
    
//    $pdf->Ln(10);
//    $pdf->MultiCell(0,5,utf8_decode("Este documento nos muestra las horas extras diurnas, nocturnas, festivas y recargos generados en la quincena correspondiente del xxxxx cualquier irregularidad en la información, por favor comunicarse con el grupo de Soporte de Sistemas. "),0,'J');
    $pdf->Ln(5);
    
    $pdf->SetTitle("Vehiculos en el parqueadero");
//    $pdf->SetY(65);
//    $pdf->ImprimirArchivo();
//    $pdf->ImprimirArchivo(2);
    $pdf->Output();
    
} else {
    
    echo "Error, esta accediendo de manera errada a la aplicacion.";
    
}
?>
