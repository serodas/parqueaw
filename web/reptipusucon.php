<script>
    
    $(document).ready(function() {
        $('#registros').dataTable({
            "bPaginate": true,
            "bJQueryUI": true,
            "iDisplayLength": 15,
            "aaSorting": [],
            "bStateSave": true
        });
    });
    
</script>
<?php

if ( isset ( $_POST['informe'] ) ) {
    
    $ini = $_POST['fecini'];
    $_SESSION['fecini'] = $_POST['fecini'];
    $fin = $_POST['fecfin'];
    $_SESSION['fecfin'] = $_POST['fecfin'];
    
} else if ( $_SESSION['fecini'] != '' ) {
    
    $ini = $_SESSION['fecini'];
    $fin = $_SESSION['fecfin'];
    
} else {
    
    $ini = $fecha;
    $fin = $fecha;
    
}

?>

<form action="" method="POST" class="span12">
    <table>
        <tr>
            <td>Fecha inicial</td>
            <td><script>DateInput('fecini', true, 'YYYYMMDD', '<?php echo $ini; ?>')</script></td>
            <td>Fecha final</td>
            <td><script>DateInput('fecfin', true, 'YYYYMMDD', '<?php echo $fin; ?>')</script></td>
            <td>
                <input type="submit" class="btn btn-primary" name="informe" value="Buscar" />
            </td>
        </tr>
    </table>
</form>
<h2>
    Registros totales
    <a href="exportar/reptipusuconxls.php" target="_blank" class="btn btn-warning" title="Descargar informacion">
        <i class="icon-download-alt icon-white"></i>
    </a>
</h2>
    
<?php
    $sql = " select p.nombre as par, tu.nombre as tipusu, tv.nombre as tipveh, ev.nombre as estveh, count(p.id) 
        from parqueadero p
            inner join parqueaw.parusu pu on p.id = pu.parqueadero and pu.login = '$login' and pu.estado = 'A' 
            inner join movimiento m on p.id = m.parqueadero
            inner join motivo tu on m.tipousua = tu.criterio and tu.tabla = 'tipousua'
            inner join motivo tv on m.tipovehi = tv.criterio and tv.tabla = 'tipovehi'
            inner join motivo ev on m.estavehi = ev.criterio and ev.tabla = 'estavehi'
        where fecing between $ini and $fin
        group by p.nombre, tu.nombre, tv.nombre, ev.nombre ";
    $_SESSION['sentencia'] = $sql;
    $encabezados = "Parqueadero,Tipo usuario,Tipo de vehiculo,Estado del vehiculo,Cantidad";
    $parqueo->tabla($sql, $encabezados, '', 'registros');
?>