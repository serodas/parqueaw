<script>
    function accion(id){
        window.location.href="app.php?web=prueba&pru="+id;
    }
</script>
<div class="container-fluid">
    <div class="row-fluid">
        <div class="span3">
            <?php
            if ( isset( $_GET['pru'] ) ) {

                $id = $parqueo->limpiarEntradas($_GET['pru'], 5);

                $sentencia = " update prueba set estado = 'I', usuario = '$login', 
                    fecha = '$fecha', hora = '$hora', equipo = '$equipo', dirip = '$dirip' 
                    where id = $id ";
                $inactiva = $parqueo->consulta($sentencia);

                if ( $inactiva ) {
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
                
                $placa = trim(strtoupper($parqueo->limpiarEntradas($_POST['placa'], 6)));
                
                if ( $placa != '' ) {
                    
                    $buscar = " select estado from prueba where placa = '$placa' ";
                    $consulta = $parqueo->consulta($buscar);
                    $res = odbc_fetch_array($consulta);
                    $estado = $res[odbc_field_name($consulta, 1)];

                    if ( $estado == 'A' ) {

                        $ejecuto = true;

                    } else if ( $estado == 'I' ) {

                        $sentencia = " update prueba set estado = 'A', usuario = '$login', 
                            fecha = '$fecha', hora = '$hora', equipo = '$equipo', dirip = '$dirip' 
                            where placa = '$placa' ";
                        $ejecuto = $parqueo->consulta($sentencia);

                    } else {

                        $sentencia = " insert into prueba (placa, estado, usuario, fecha, hora, equipo, dirip) values
                            ('$placa', 'A', '$login', '$fecha', '$hora', '$equipo', '$dirip') ";
                        $ejecuto = $parqueo->consulta($sentencia);

                    }

                    if ( $ejecuto ) {
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
                    ?>
                    <div class="alert alert-error">
                        No se permiten placas en blanco.
                        <button class="close" data-dismiss="alert">×</button>
                    </div>
                    <?php
                }
                    
            } else {
                echo "";
            }

            ?>
            <form action="app.php?web=prueba" method="POST">
                <h3>Nueva placa</h3>
                <label>Placa</label>
                <input type="text" class="span12" name="placa" maxlength="6" />
                
                <button type="submit" class="btn btn-primary" name="nuevo">Nuevo</button>
                
            </form>
        </div>
        <div class="span9">
            <h1>
                <a href="app.php?web=prueba" class="btn btn-primary">
                    <i class="icon-plus icon-white"></i>
                </a> 
                Tipos de usuarios
            </h1>
            <?php
            $sql = " select p.id, p.placa, p.estado, p.usuario, p.fecha, substr(p.hora,1,4), p.equipo, p.dirip
                from prueba p where estado = 'A' ";
            $encabezados = "Id,Placa,Est,Usuario,Fecha,Hora,Equipo,IP";
            $parqueo->tabla($sql, $encabezados, 'accion', '');
            ?>
        </div>
    </div>
</div>