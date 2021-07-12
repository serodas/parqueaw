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
    Ingresos detallado por dia, cajero y actividad
    <a href="exportar/repingdiadetxls.php" target="_blank" class="btn btn-warning" title="Descargar informacion">
        <i class="icon-download-alt icon-white"></i>
    </a>
</h2>
    
<?php
$sql = "select f.canumcaja, g.usnomusuar, a.mrfecate, p.nombre, d.acdesactiv, b.manumcant, b.manumvalun,  b.manumval 
    from bdmuf.tbfamovrec a
	inner join bdmuf.tbfamovact b on a.mrcodcons = b.mrcodcons
	inner join bdmuf.tbactare c on b.aacod = c.aacod
	inner join bdmuf.tbfaactivi d on d.accodactiv = c.accodactiv
	inner join bdmuf.tbfaciecaj f on f.caconscaja = a.caconscaja
	inner join bdutil.tbsgusu g on g.uscodusuar = f.uscodusuar
        inner join parqueaw.parqueadero p on a.cacodcenat = p.cacodcenat
            inner join parqueaw.parusu pu on p.id = pu.parqueadero and pu.login = '$login' and pu.estado = 'A'
    where a.cacodcenat in ('PQ1', 'PQ2', '301')
	and a.mrfecate between $ini and $fin
	and a.mrnumdoc > 0
	and a.aucodestad = 'A'
	and a.mocodmotiv = '14'
	and d.actipo = 'P' 
    order by a.mrfecate ";
    
    $_SESSION['sentencia'] = $sql;
    $encabezados = "Caja,Usuario,Fecha,Centro,Actividad,Cant,Valor,Total";
    $parqueo->tabla($sql, $encabezados, '', 'registros');
?>