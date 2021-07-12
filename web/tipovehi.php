<script>
    function accion(cod){
        window.location.href="app.php?web=tipovehi&id="+cod;
    }
</script>
<div class="container-fluid">
    <div class="row-fluid">
        <div class="span3">
            <?php
            $tabla = 'tipovehi';
            
            if ( isset( $_POST['actualiza'] ) ) {

                $id = $parqueo->limpiarEntradas($_POST['id'], 5);
                $nombre = $parqueo->limpiarEntradas($_POST['nombre'], 45);
                $criterio = $parqueo->limpiarEntradas($_POST['criterio'], 45);
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
                
                $nombre = $parqueo->limpiarEntradas($_POST['nombre'], 45);
                $criterio = $parqueo->limpiarEntradas($_POST['criterio'], 45);
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

            $sqlSp = " select nombre, criterio, estado from motivo where id = %d and tabla = '$tabla'";
            $sqlid = sprintf($sqlSp, $id);
            $consulta = $parqueo->consulta($sqlid);

            if( odbc_num_rows($consulta) ) {

                $res = odbc_fetch_array($consulta);
                $nombre = $res[odbc_field_name($consulta, 1)];
                $criterio = $res[odbc_field_name($consulta, 2)];
                $estado = $res[odbc_field_name($consulta, 3)];

            } else {

                $id = 0;
                $nombre = '';
                $criterio = '';
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
                <label>Criterio</label>
                <input type="text" class="span12" name="criterio" maxlength="45" value="<?php echo $criterio; ?>" >
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
                <a href="app.php?web=tipovehi" class="btn btn-primary" title="Nuevo registro">
                    <i class="icon-plus icon-white"></i>
                </a> 
                Registro tipo de Veh&iacute;culo.
            </h1>
            <?php
            $sql = " select m.id, m.nombre, m.criterio, m.estado
                from motivo m where tabla = 'tipovehi' ";
            $encabezados = "Cod.,Nombre,Criterio,Est";
            $parqueo->tabla($sql, $encabezados, 'accion', '');
            ?>
        </div>
    </div>
</div>