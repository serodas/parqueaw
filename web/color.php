<script>
    function accion(cod){
        window.location.href="app.php?web=color&id="+cod;
    }
</script>
<link rel="stylesheet" href="css/pick-a-color-1.1.8.min.css">
<div class="container-fluid">
    <div class="row-fluid">
        <div class="span3">
            <?php
            if ( isset( $_POST['actualiza'] ) ) {

                $id = $parqueo->limpiarEntradas($_POST['id'], 5);
                $tabla = 'color';
                $nombre = $parqueo->limpiarEntradas($_POST['nombre'], 45);
                $criterio = strtoupper($parqueo->limpiarEntradas($_POST['criterio'], 45));
                $estado = $parqueo->limpiarEntradas($_POST['estado'], 1);
                $sentencia = " update motivo set tabla = '%s', nombre = '%s', criterio = '%s', 
                    estado = '%s', usuario = '%s', fecha = '%s', hora = '%s', equipo = '%s', dirip = '%s' 
                    where id = '%d' ";
                $sql = sprintf($sentencia, $tabla, $nombre, $criterio, $estado, $login, $fecha, $hora, $equipo, $dirip, $id);

                $parqueo->consulta($sql);

                if ($parqueo) {
                    ?>
                    <div class="alert alert-info">
                        Informaci&oacute;n actualizada con &eacute;xito.
                        <button class="close" data-dismiss="alert">×</button>
                    </div>
                    <?php
                } else {
                    ?>
                    <div class="alert alert-error">
                        Se ha presentado un error al insertar, por favor intente de nuevo o contacte al administrador.
                        <button class="close" data-dismiss="alert">×</button>
                    </div>
                    <?php
                }
            } elseif ( isset( $_POST['nuevo'] ) ) {

                $tabla = 'color';
                $nombre = $parqueo->limpiarEntradas($_POST['nombre'], 45);
                $criterio = strtoupper($parqueo->limpiarEntradas($_POST['criterio'], 45));
                $estado = $parqueo->limpiarEntradas($_POST['estado'], 1);

                $sentencia = " insert into motivo (tabla, nombre, criterio, estado, usuario, fecha, hora, equipo, dirip) values
                    ('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s') ";
                $sql = sprintf($sentencia, $tabla, $nombre, $criterio, $estado, $login, $fecha, $hora, $equipo, $dirip);
                $parqueo->consulta($sql);

                if ($parqueo) {
                    ?>
                    <div class="alert alert-info">
                        Informaci&oacute;n registrada con &eacute;xito.
                        <button class="close" data-dismiss="alert">×</button>
                    </div>
                    <?php
                } else {
                    ?>
                    <div class="alert alert-error">
                        Se ha presentado un error al registrar, por favor intente de nuevo o contacte al administrador.
                        <button class="close" data-dismiss="alert">×</button>
                    </div>
                    <?php
                }

            } else {
                echo '';
            }

            if ( isset( $_GET['id'] ) ) {
                $id = $parqueo->limpiarEntradas( $_GET['id'], 5);
            } else {
                $id = 0;
            }

            $sqlSp = " select nombre, tabla, criterio, estado from motivo where id = '%d' ";
            $sqlid = sprintf($sqlSp, $id);
            $consulta = $parqueo->consulta($sqlid);

            if( odbc_num_rows($consulta) ) {

                $res = odbc_fetch_array($consulta);
                $nombre = $res[odbc_field_name($consulta, 1)];
                $criterio = $res[odbc_field_name($consulta, 3)];
                $estado = $res[odbc_field_name($consulta, 4)];

            } else {

                $id = 0;
                $nombre = '';
                $criterio = 'FFFFFF';
                $estado = 'A';

            }
                ?>
            <form action="" method="POST">
                <h3>Actualizar / Nuevo</h3>
                <?php
                if ( isset( $_GET['id'] ) ) {
                    ?>
                    <input type="hidden" name="id" value="<?php echo $id; ?>" />
                    <?php
                }
                ?>
                <label>Nombre</label>
                <input type="text" class="span12" name="nombre" maxlength="45" value="<?php echo $nombre; ?>" />
                <style type="text/css">
                    
                    .input-group-btn:last-child>.btn,.input-group-btn:last-child>.dropdown-toggle,
                    .input-group-btn:first-child>.btn:not(:first-child){border-bottom-left-radius:0;border-top-left-radius:0}
                    .input-group-addon:last-child{border-left:0}.input-group-btn{position:relative;white-space:nowrap}
                    .input-group-btn:first-child>.btn{margin-right:-1px}
                    .input-group-btn:last-child>.btn{margin-left:-1px}
                    .input-group-btn>.btn{position:relative}
                    .input-group-btn>.btn+.btn{margin-left:-4px}
                    .input-group-btn>.btn:hover,.input-group-btn>.btn:active{z-index:2}
                    
                </style>
                <label>Seleccione el color</label>
                <input type="hidden" name="criterio" maxlength="6" value="<?php echo $criterio; ?>" class="pick-a-color form-control" >
                <br/>
                <label>Estado</label>
                <?php
                $sql = " select criterio, nombre from motivo where tabla = 'motivo' and estado = 'A' ";
                $parqueo->select($sql, 'estado', $estado, 'span12');

                if ( $id > 0 ) {
                    ?>
                    <button type="submit" class="btn btn-primary" name="actualiza">Actualizar</button>
                    <?php
                }else{
                    ?>
                    <button type="submit" class="btn btn-primary" name="nuevo">Crear</button>
                    <?php
                }
                ?>
            </form>
        </div>
        <div class="span9">
            <h1>
                <a href="app.php?web=color" class="btn btn-primary" title="Nuevo registro">
                    <i class="icon-plus icon-white"></i>
                </a> 
                Registro color de Veh&iacute;culo / Empleado.
            </h1>
            <?php
            $sql = " select m.id, m.nombre, m.criterio, m.estado
                from motivo m where tabla = 'color' ";
            $encabezados = "Cod.,Nombre,Valor Hexadecimal,Col,Est";
            
            echo "<table class=\"table table-bordered table-condensed\">";

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
                            if ( $res[odbc_field_name($reg, 4)] == 'I' ) echo " class=\"alert alert-error\" ";
                        echo ">";
                            echo "<td> {$res[odbc_field_name($reg, 1)]} </td>";
                            echo "<td> {$res[odbc_field_name($reg, 2)]} </td>";
                            echo "<td> {$res[odbc_field_name($reg, 3)]} </td>";
                            echo "<td style=\"width: 20px; background-color: #{$res[odbc_field_name($reg, 3)]};\"> </td>";
                            echo "<td> {$res[odbc_field_name($reg, 4)]} </td>";
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
<script src="js/jquery-1.9.1.min.js"></script>
<script src="js/tinycolor-0.9.15.min.js"></script>
<script src="js/pick-a-color.js"></script>
<script type="text/javascript">
	
    $(document).ready(function () {

        $(".pick-a-color").pickAColor({
            showSpectrum        : true,
            showSavedColors     : true,
            saveColorsPerElement: true,
            fadeMenuToggle      : true,
            showAdvanced	: true,
            showBasicColors     : true,
            showHexInput        : true,
            allowBlank		: true
        });

    });

</script>