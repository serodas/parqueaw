<?php

if ( $parqueadero > 0 ) {
    
    ?>
    <script>
        
        $(document).ready(function() {
            
            $('#ingresos').dataTable({
                "bPaginate": true,
                "bJQueryUI": true,
                "iDisplayLength": 10,
                "aaSorting": [],
                "bStateSave": true
            });
            
        });
        
    </script>


    <h1>
        Veh&iacute;culos en el parqueadero
        <a href="web/repsancionpdf.php" target="_blank" class="btn btn-primary" title="Descargar informacion">
            <i class="icon-download-alt icon-white"></i>
        </a> 
        <a href="web/repsanciondeppdf.php" target="_blank" class="btn btn-danger" title="Descargar informacion">
            <i class="icon-download-alt icon-white"></i>
        </a>
    </h1>
    <?php
    $procesos = " select distinct p.prcodproce, ifnull(p.prdesproce, 'Sin proceso'), count(m.id)
        from vehiculos v
            inner join movimiento m on v.placa = m.placa and m.fecsal = 0 and v.estado = 'A'
            left join bdgeshum.tbthcandi c on v.canumdocum = c.canumdocum 
            left join bdgeshum.tbthmaeper mp on c.cacodcandi = mp.cacodcandi and mp.aucodestad = 'A'
            left join bdgeshum.tbthproce p on mp.prcodproce = p.prcodproce
        where m.parqueadero = $parqueadero
        group by p.prcodproce, p.prdesproce ";
    
    $parqueo->tabla($procesos, "Codigo,Descripcion,Cantidad", '', 'ingresos');
    
    
} else {
    
    include_once 'web/parnoasig.php';
    
}

?>
