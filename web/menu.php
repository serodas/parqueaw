<script>
    function accion(cod){
        window.location.href="app.php?web=menu&id="+cod;
    }
    $(document).ready(function() {
        $('#menu').dataTable({
            "bPaginate": true,
            "bJQueryUI": true,
            "iDisplayLength": 10,
            "aaSorting": [],
            "bStateSave": true
        });
    });
</script>
<div class="container-fluid">
    <div class="row-fluid">
        <div class="span3">
            <?php
            if ( isset( $_POST['actualiza'] ) ) {

                $id = $parqueo->limpiarEntradas($_POST['id'], 5);
                $archivo = $parqueo->limpiarEntradas($_POST['archivo'], 25);
                $nombre = $parqueo->limpiarEntradas($_POST['nombre'], 45);
                $tipo = $parqueo->limpiarEntradas($_POST['tipo'], 5);
                $criterio = $parqueo->limpiarEntradas($_POST['criterio'], 5);
                $orden = $parqueo->limpiarEntradas($_POST['orden'], 2);
                $estado = $parqueo->limpiarEntradas($_POST['estado'], 1);

                $sentencia = " update menu set archivo = '%s', nombre = '%s', tipo = '%s', criterio = '%s',
                    orden = '%d', estado = '%s', usuario = '%s', fecha = '%s', hora = '%s', equipo = '%s',
                    dirip = '%s' where id = '%d' ";
                $sql = sprintf($sentencia, $archivo, $nombre, $tipo, $criterio, $orden, $estado, $login, $fecha, $hora, $equipo, $dirip, $id);

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

                $archivo = $parqueo->limpiarEntradas($_POST['archivo'], 25);
                $nombre = $parqueo->limpiarEntradas($_POST['nombre'], 45);
                $tipo = $parqueo->limpiarEntradas($_POST['tipo'], 5);
                $criterio = $parqueo->limpiarEntradas($_POST['criterio'], 5);
                $orden = $parqueo->limpiarEntradas($_POST['orden'], 2);
                $estado = $parqueo->limpiarEntradas($_POST['estado'], 1);

                $sentencia = " insert into menu (archivo, nombre, tipo, criterio, orden, estado, usuario, fecha, hora, equipo, dirip) values
                    ('%s', '%s', '%s', '%s', '%d', '%s', '%s', '%s', '%s', '%s', '%s') ";
                $sql = sprintf($sentencia, $archivo, $nombre, $tipo, $criterio, $orden, $estado, $login, $fecha, $hora, $equipo, $dirip);
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

            $sqlSp = " select nombre, archivo, tipo, orden, estado, criterio from menu where id = %d ";
            $sqlid = sprintf($sqlSp, $id);
            $consulta = $parqueo->consulta($sqlid);

            if( odbc_num_rows($consulta) ) {

                $res = odbc_fetch_array($consulta);
                $nombre = $res[odbc_field_name($consulta, 1)];
                $archivo = $res[odbc_field_name($consulta, 2)];
                $tipo = $res[odbc_field_name($consulta, 3)];
                $orden = $res[odbc_field_name($consulta, 4)];
                $estado = $res[odbc_field_name($consulta, 5)];
                $criterio = $res[odbc_field_name($consulta, 6)];

            } else {

                $id = 0;
                $nombre = '';
                $archivo = '';
                $tipo = '';
                $orden = '99';
                $estado = 'A';
                $criterio = '';

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
                <label>Nombre del men&uacute;</label>
                <input type="text" class="span12" name="nombre" maxlength="45" value="<?php echo $nombre; ?>" />
                <label>Archivo relacionado</label>
                <input type="text" class="span12" name="archivo" maxlength="25" value="<?php echo $archivo; ?>" >
                <label>Opci&oacute;n principal</label>
                <?php
                $sql = " select criterio, nombre from motivo where tabla = 'menu' ";
                $parqueo->select($sql, 'tipo', $tipo, 'span12');
                ?>
                <label>Criterio</label>
                <input type="text" class="span12" name="criterio" maxlength="5" value="<?php echo $criterio; ?>" >
                <label>&Oacute;rden</label>
                <input type="text" class="span12" name="orden" maxlength="2" value="<?php echo $orden; ?>" >
                <label>Tipo menu</label>
                <?php
                $sql = " select criterio, nombre from motivo where tabla = 'tipomenu' and estado = 'A' ";
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
                <a href="app.php?web=menu" class="btn btn-primary" title="Nuevo registro">
                    <i class="icon-plus icon-white"></i>
                </a> 
                Men&uacute;
            </h1>
            <?php
            $sql = " select m.id, t.nombre as tipo, m.nombre, m.archivo, m.criterio, m.orden, tm.nombre as tipomenu
                from menu m 
                inner join motivo t on m.tipo = t.criterio and t.tabla = 'menu'
                inner join motivo tm on m.estado = tm.criterio and tm.tabla = 'tipomenu' ";
            $encabezados = "Cod.,Menu,Submenu,Archivo,Criterio,Orden,Est";
            $parqueo->tabla($sql, $encabezados, 'accion', 'menu');
            ?>
        </div>
    </div>
</div>