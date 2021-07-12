<h3>
    Listado de vehiculos activos en el sistema
    <a href="web/reportetipoxls.php" class="btn btn-warning" title="Descargar archivo de vehiculos">
        <i class="icon-download-alt icon-white"></i>
    </a>
</h3>
<?php
$sql = " select distinct v.placa, v.canumdocum, v.nombre, tu.nombre as tipousua, 
        tv.nombre as tipovehi, ev.nombre as estavehi, v.color, v.feclim,
        p.prdesproce, v.autsan, v.procedenci
    from vehiculos v
        inner join motivo tu on v.tipousua = tu.criterio and tu.tabla = 'tipousua' 
        inner join motivo tv on v.tipovehi = tv.criterio and tv.tabla = 'tipovehi' 
        inner join motivo ev on v.estavehi = ev.criterio and ev.tabla = 'estavehi' 
        left join bdgeshum.tbthcandi c on v.canumdocum = c.canumdocum 
        left join bdgeshum.tbthmaeper m on c.cacodcandi = m.cacodcandi and m.aucodestad = 'A'
        left join bdgeshum.tbthproce p on m.prcodproce = p.prcodproce
    where v.estado = 'A' ";
$encabezados = "Placa,Identidad,Nombre completo,Dependencia,Tipo de usuario,Vehiculo,Estado,Col,Fec lim,Aut,Procedencia";

$tabla = "<table class=\"table table-bordered table-condensed\" id=\"vehiculos\" >";

    $tabla.= "<thead>";
        $tabla.= "<tr>";
        $titulo = explode(',',$encabezados);
        $numreg = count($titulo);
        for($j=0; $j < $numreg; $j++ ) {
            $tabla.= "<th> {$titulo[$j]} </th>";
        }
        $tabla.= "</tr>";
    $tabla.= "</thead>";

    $reg = odbc_exec($parqueo->conexion, $sql);
    if( odbc_num_rows( $reg ) ) {

        $tabla.= "<tbody>";
        while ( $res = odbc_fetch_array($reg) ) {

            $tabla.= "<tr>";
                $tabla.= "<td> {$res[odbc_field_name($reg, 1)]} </td>";
                $tabla.= "<td> {$res[odbc_field_name($reg, 2)]} </td>";
                $tabla.= "<td> {$res[odbc_field_name($reg, 3)]} </td>";
                $tabla.= "<td>" . ucwords(strtolower($res[odbc_field_name($reg, 9)])) . "</td>";
                $tabla.= "<td> {$res[odbc_field_name($reg, 4)]} </td>";
                $tabla.= "<td> {$res[odbc_field_name($reg, 5)]} </td>";
                $tabla.= "<td> {$res[odbc_field_name($reg, 6)]} </td>";
                $tabla.= "<td style=\"width: 20px; background-color: #{$res[odbc_field_name($reg, 7)]};\"> </td>";
                $tabla.= "<td>{$res[odbc_field_name($reg, 8)]}</td>";
                $tabla.= "<td>{$res[odbc_field_name($reg, 10)]}</td>";
                $tabla.= "<td>{$res[odbc_field_name($reg, 11)]}</td>";
            $tabla.= "</tr>";

        }
        $tabla.= "</tbody>";

    } else {
        $tabla.= "<tr><td colspan=\"$numreg\"> No hay registros </td></tr>";
    }
$tabla.= "</table>";
echo $tabla;
$_SESSION['tabladedatos'] = $tabla;
?>
<script>
   $(document).ready(function() {
        $('#vehiculos').dataTable({
            "bPaginate": true,
            "bJQueryUI": true,
            "iDisplayLength": 10,
            "aaSorting": [],
            "bStateSave": true
        });
    });
</script>