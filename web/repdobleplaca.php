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
<h1>
    Personal con varios vehiculos registrados
</h1>

<?php
$sql = "select v.canumdocum, v.nombre, v.placa, tu.nombre as tu, tv.nombre as tv, e.nombre as estado
    from vehiculos v
        inner join motivo tu on v.tipousua = tu.criterio and tu.tabla = 'tipousua'
        inner join motivo tv on v.tipovehi = tv.criterio and tv.tabla = 'tipovehi'
        inner join motivo e on v.estado = e.criterio and e.tabla = 'motivo'
    where v.canumdocum in (
        select ve.canumdocum from vehiculos ve
        where ve.estado = 'A'
        group by ve.canumdocum
        having count(ve.placa) > 1
    )";
$encabezados = "Identidad,A nombre de,Placa,Tipo usuario,Tipo vehiculo,Estado";
$parqueo->tabla($sql, $encabezados, '', 'vehiculos');
?>