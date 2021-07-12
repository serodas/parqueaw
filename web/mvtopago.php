<script>
    function accion(cod){
        window.location.href="app.php?web=mvtopago&mvto="+cod;
    }
    
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
<?php

if ( isset ( $_POST['ingresar'] ) ) {
    
    $placa = strtoupper($parqueo->limpiarEntradas($_POST['placa'], 6));
    $mrcodcons = 0;
    $tipousua = $parqueo->limpiarEntradas($_POST['tipousua'], 2);
    $tipovehi = $parqueo->limpiarEntradas($_POST['tipovehi'], 1);
    $estavehi = $parqueo->limpiarEntradas($_POST['estavehi'], 2);
    $fecing = $fecha;
    $horing = $hora;
    $fecfac = 0;
    $horfac = 0;
    $fecsal = 0;
    $horsal = 0;
    $durdia = 0;
    $durhor = 0;
    
    $sentencia = " insert into movimiento (parqueadero, placa, mrcodcons, tipousua, tipovehi, estavehi, fecing, horing, fecfac, horfac, fecsal, horsal, durdia, durhor, estado, usuario, fecha, hora, equipo, dirip)
        values ('$parqueadero', '$placa', '$mrcodcons', '$tipousua', '$tipovehi', '$estavehi', '$fecing', '$horing', 
            '$fecfac', '$horfac', '$fecsal', '$horsal', '$durdia', '$durhor', 'A', '$login', '$fecha', '$hora', '$equipo', 
            '$dirip')";
    $parqueo->consulta($sentencia);

    if ($parqueo) {
        ?>
        <script>
            alert("Registro creado con exito.");
        </script>
        <?php
    } else {
        ?>
        <script>
            alert("Error al guardar el dato. Intentelo de nuevo.");
        </script>
        <?php
    }
    
}

?>
<div class="container-fluid">
    <div class="row-fluid">
        <div class="span9">
            <h1>Simulador de pago</h1>
            <?php
            
            $sql = " select m.id, m.placa, tu.nombre as tu, t.nombre as t, ev.nombre as e, m.fecing, substr(m.horing, 1, 4)
                from movimiento m 
                inner join motivo tu on m.tipousua = tu.criterio and tu.tabla = 'tipo'
                inner join motivo t on m.tipovehi = t.criterio and t.tabla = 'tipovehi'
                inner join motivo ev on m.estavehi = ev.criterio and ev.tabla = 'estavehi'
                where m.parqueadero = $parqueadero and m.mrcodcons = 0 
                order by m.id desc ";
            $encabezados = "Id,Placa,Usuario,Tipo,Estado,Fecha,Hora";
            $parqueo->tabla($sql, $encabezados, 'accion', 'ingresos');
            ?>
        </div>
        <div class="span3">
            <?php
            if ( $_GET['mvto'] > 0 ) {
                
                $mvto = $parqueo->limpiarEntradas($_GET['mvto'], 5);
                echo $mvto;
                
                
                
                
            }
            ?>
            <form action="app.php?web=mvtopago" method="POST">
                <h1>
                    <a href="app.php?web=mvtopago" class="btn btn-primary">
                        <i class="icon-plus icon-white"></i>
                    </a>
                    Pago
                </h1>
                <input type="text" class="span12 " name="placa" maxlength="45" autofocus="" value="" 
                       onblur="javascript:cargarDatos(this.value);" style="text-transform: uppercase;" />
                <div id="cargarDatos"></div>
            </form>
        </div>
    </div>
</div>