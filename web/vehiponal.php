<script>
    
    jQuery.fn.cargar = function(url) {
        $(document).ready(function(){
            $("#cargarDatos").load(url);
        });
    };
    
    function accion(cod){
        window.location.href="app.php?web=vehiponal&placa="+cod;
    }
    
    $(document).ready(function() {
        $('#vehiponal').dataTable({
            "bPaginate": true,
            "bJQueryUI": true,
            "iDisplayLength": 10,
            "aaSorting": [],
            "bStateSave": true
        });
    });
    
</script>

<?php

if ( isset ( $_POST['placa'] ) ) {

    if ( trim($_POST['placa']) == '' or trim($_POST['canumdocum']) == '' or trim($_POST['canumdocum']) == 0 or trim($_POST['nombre']) == '' ) {

        ?>
        <div class="alert alert-error">
            Esta registrando placas en blanco, no hay nombres o no tiene identidad valida.<br/> Intentelo de nuevo.
            <button class="close" data-dismiss="alert">×</button>
        </div>
        <?php

    } else {

        if ( isset( $_POST['actualiza'] ) ) {

            $placa = strtoupper($parqueo->limpiarEntradas($_POST['placa'], 10));
            $nombre = $parqueo->limpiarEntradas($_POST['nombre'], 45);
            $tipovehi = $parqueo->limpiarEntradas($_POST['tipovehi'], 1);
            $canumdocum = $parqueo->limpiarEntradas($_POST['canumdocum'], 15);
            $color = $parqueo->limpiarEntradas($_POST['color'], 6);
            $estado = $parqueo->limpiarEntradas($_POST['estado'], 1);

            $sentencia = " update vehiponal set nombre = '%s', tipovehi = '%s', canumdocum = '%s', 
                color = '%s', estado = '%s', usuario = '%s', fecha = '%s', hora = '%s', 
                equipo = '%s', dirip = '%s' where placa = '%s' ";
            $sql = sprintf($sentencia, $nombre, $tipovehi, $canumdocum, $color, $estado, $login, $fecha, $hora, $equipo, $dirip, $placa);

            $actualiza = $parqueo->consulta($sql);

        } else if ( isset( $_POST['nuevo'] ) ) {

            $placa = strtoupper($parqueo->limpiarEntradas($_POST['placa'], 10));
            $nombre = $parqueo->limpiarEntradas($_POST['nombre'], 45);
            $tipovehi = $parqueo->limpiarEntradas($_POST['tipovehi'], 1);
            $canumdocum = $parqueo->limpiarEntradas($_POST['canumdocum'], 15);
            $color = $parqueo->limpiarEntradas($_POST['color'], 6);
            $estado = $parqueo->limpiarEntradas($_POST['estado'], 1);

            $sentencia = " insert into vehiponal (placa, nombre, tipovehi, canumdocum, color, estado, 
                usuario, fecha, hora, equipo, dirip) values
                ('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s') ";
            $sql = sprintf($sentencia, $placa, $nombre, $tipovehi, $canumdocum, $color, $estado, $login, $fecha, $hora, $equipo, $dirip);
            $inserta = $parqueo->consulta($sql);

            if ( $inserta ) {

                ?>
                <script>
                    alert("Informacion creada con extio.");
                    window.location.href="app.php?web=vehiponal";
                </script>
                <?php

            } else {

                ?>
                <script>
                    alert("Error en la creacion de los datos.");
                    window.location.href="app.php?web=vehiponal";
                </script>
                <?php

            }

        } else {

            echo '';

        }

    }

}

if ( isset( $_GET['placa'] ) ) {
    $placa1 = strtoupper($parqueo->limpiarEntradas( $_GET['placa'], 6));
} else {
    $placa1 = '';
}

$sqlSp = " select v.placa, v.tipovehi, v.canumdocum, v.color, v.estado, v.nombre
    from vehiponal v where v.placa = '%s' ";
$sqlid = sprintf($sqlSp, $placa1);
$consulta = $parqueo->consulta($sqlid);

if( odbc_num_rows($consulta) ) {

    $res = odbc_fetch_array($consulta);
    $placa = strtoupper(trim($res[odbc_field_name($consulta, 1)]));
    $tipovehi = $res[odbc_field_name($consulta, 2)];
    $canumdocum = trim($res[odbc_field_name($consulta, 3)]);
    $color = $res[odbc_field_name($consulta, 4)];
    $estado = trim($res[odbc_field_name($consulta, 5)]);
    if ( $placa == '' ) {
        $existePlaca = 0;
        $placa = $placa1;
    } else {
        $existePlaca = 1;
    }
    $nombre = trim($res[odbc_field_name($consulta, 6)]);

} else {

    $placa = $placa1;
    $tipovehi = 'M';
    $canumdocum = '0';
    $color = '000000';
    $estado = 'A';
    $existePlaca = 0;
    $nombre = '';

}

$tip = $parqueo->validarPlaca($placa);
if ( $tip == 2 ){
    $tipo = "M";
} else if ( $tip == 3 ) {
    $tipo = "C";
} else {
    $tipo = "C";
}
?>
<div class="container-fluid">
    <div class="row-fluid">
        <div class="span3">
            <img src="img/policia-nacional.png" class="span12" />
            <form action="app.php?web=vehiponal" method="POST">
                <label>Placa</label>
                <?php
                if ( $existePlaca == 1 ) {
                    ?>
                    <input type="hidden" name="placa" value="<?php echo $placa; ?>" />
                    <input type="text" class="span12"  value="<?php echo $placa; ?>" disabled=""/>
                    <?php
                } else {
                    ?>
                    <input type="text" class="span12" name="placa" maxlength="6" autofocus="" value="<?php echo $placa; ?>" onchange="javascript:accion(this.value);" />
                    <?php
                }
                ?>
                <label>Documento de identidad</label>
                <input type="text" class="span12" name="canumdocum" maxlength="15" autofocus="" value="<?php echo $canumdocum; ?>" />
                <label>Nombre completo</label>
                <input type="text" class="span12" name="nombre" maxlength="45" autofocus="" value="<?php echo $nombre; ?>" />
                <label>Tipo veh&iacute;culo</label>
                <?php
                $tipovehi = " select criterio, nombre from motivo where tabla = 'tipovehi' and estado = 'A'
                    order by nombre ";
                $parqueo->select($tipovehi, 'tipovehi', $tipo, 'span12');
                ?>
                <label>Color del veh&iacute;culo</label>
                <?php
                $sql = " select criterio, nombre from motivo where tabla = 'color' and estado = 'A'
                    order by nombre ";
                $parqueo->select($sql, 'color', $color, 'span12');
                ?>
                <label>Estado</label>
                <?php
                $sql = " select criterio, nombre from motivo where tabla = 'motivo' and estado = 'A' ";
                $parqueo->select($sql, 'estado', $estado, 'span12');
                
                if ( $existePlaca == 1 ) {
                    ?>
                    <button type="submit" class="btn btn-primary" name="actualiza">Actualizar</button>
                    <?php
                } else {
                    ?>
                    <button type="submit" class="btn btn-primary" name="nuevo">Crear</button>
                    <?php
                }
                ?>
            </form>
        </div>
        <div class="span9">
            <h1>
                <a href="app.php?web=vehiponal" class="btn btn-primary" title="Nuevo registro">
                    <i class="icon-plus icon-white"></i>
                </a> 
                Policia Nacional
            </h1>
            <?php
            
            $sql = " select v.placa, v.canumdocum, v.nombre, tv.nombre as tipovehi, v.color, v.estado
                from vehiponal v
                inner join motivo tv on v.tipovehi = tv.criterio and tv.tabla = 'tipovehi' ";
            $encabezados = "Placa,Identidad,Nombre completo,Vehiculo,Estado,Col";
            
            echo "<table class=\"table table-bordered table-condensed\" id=\"vehiculos\" >";

                echo "<thead>";
                    echo "<tr>";
                    $titulo = explode(',',$encabezados);
                    $numreg = count($titulo);
                    for($j=0; $j < $numreg; $j++ ) {
                        echo "<th> {$titulo[$j]} </th>";
                    }
                    echo "</tr>";
                echo "</thead>";

                $reg = odbc_exec($parqueo->conexion, $sql);
                if( odbc_num_rows( $reg ) ) {


                    echo "<tbody>";
                    while ( $res = odbc_fetch_array($reg) ) {

                        echo "<tr onClick=\"javascript:accion('".$res[odbc_field_name($reg, 1)]."')\" ";
                            if ( $res[odbc_field_name($reg, 6)] == 'I' ) echo " class=\"alert alert-error\" ";
                        echo ">";
                            echo "<td> {$res[odbc_field_name($reg, 1)]} </td>";
                            echo "<td> {$res[odbc_field_name($reg, 2)]} </td>";
                            echo "<td> {$res[odbc_field_name($reg, 3)]} </td>";
                            echo "<td> {$res[odbc_field_name($reg, 4)]} </td>";
                            echo "<td>{$res[odbc_field_name($reg, 6)]}</td>";
                            echo "<td style=\"width: 20px; background-color: #{$res[odbc_field_name($reg, 5)]};\"> </td>";
                        echo "</tr>";

                    }
                    echo "</tbody>";

                } else {
                    echo "<tr><td colspan=\"$numreg\"> No hay registros </td></tr>";
                }
            echo "</table>";
            ?>
        </div>
    </div>
</div>
