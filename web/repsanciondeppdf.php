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
            $this->Cell(0,10,utf8_decode("Documento impreso el " . date("Y-m-d") . " a las " . date("H:i") . "  <>  Página " . $this->PageNo() . "/{nb}"),0,0,'C');
        }
        
        function SetCol($col) {
            //Establecer la posición de una columna dada
            $this->col=$col;
            $x=10+$col*30;
            $this->SetLeftMargin($x);
            $this->SetX($x);
        }

        function AcceptPageBreak() {
            //Método que acepta o no el salto automático de página
            if($this->col<5) {
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
    
//    $procesos = " select distinct p.prcodproce, p.prdesproce, v.placa, v.nombre
//        from vehiculos v
//            inner join movimiento m on v.placa = m.placa and m.fecsal = 0 and v.estado = 'A'
//            left join bdgeshum.tbthcandi c on v.canumdocum = c.canumdocum 
//            left join bdgeshum.tbthmaeper mp on c.cacodcandi = mp.cacodcandi and mp.aucodestad = 'A'
//            left join bdgeshum.tbthproce p on mp.prcodproce = p.prcodproce
//        where m.parqueadero = $parqueadero
//        group by p.prcodproce, p.prdesproce, v.placa, v.nombre
//        order by p.prcodproce, p.prdesproce ";
    
    $procesos = " select distinct v.placa
    from vehiculos v
        inner join movimiento m on v.placa = m.placa and m.fecsal = 0 and v.estado = 'A'
    where m.parqueadero = $parqueadero and v.tipousua = 'E'
    group by v.placa
    order by v.placa ";

    $reg = odbc_exec($parqueo->conexion, $procesos);
    $tamcelda = 30;
    while ( $res = odbc_fetch_array($reg) ) {
        
        $pdf->Cell(5,4,"", 1, 0, 'J');
        $pdf->Cell($tamcelda,4,$res[odbc_field_name($reg, 1)], 0, 0, 'J');
        $pdf->Ln();
    
    }
    
    $pdf->SetCol(0);
    $pdf->Ln(5);
    
    $pdf->SetTitle("Vehiculos en el parqueadero");
    $pdf->Output();
    
} else {
    
    echo "Error, esta accediendo de manera errada a la aplicacion.";
    
}
?>
