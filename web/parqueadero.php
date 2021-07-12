<script>
    
    function accion(cod){
        window.location.href="app.php?web=parqueadero&id="+cod;
    }
    
</script>
<div class="container-fluid">
    <div class="row-fluid">
        <div class="span3">
            <?php
            if ( isset( $_POST['actualiza'] ) ) {

                $id = $parqueo->limpiarEntradas($_POST['id'], 5);
                $nombre = $parqueo->limpiarEntradas($_POST['nombre'], 45);
                $cupocar = $parqueo->limpiarEntradas($_POST['cupocar'], 5);
                $cupomot = $parqueo->limpiarEntradas($_POST['cupomot'], 5);
                $ascodarea = $parqueo->limpiarEntradas($_POST['ascodarea'], 1);
                $cacodcenat = $parqueo->limpiarEntradas($_POST['cacodcenat'], 4);
                $tiempo = $parqueo->limpiarEntradas($_POST['tiempo'], 2);
                $estado = $parqueo->limpiarEntradas($_POST['estado'], 1);
                $sentencia = " update parqueadero set cupocar = '%s', cupomot = '%s', ascodarea = '%s', cacodcenat = '%s', nombre = '%s', 
                    tiempo = '%s', estado = '%s', usuario = '%s', fecha = '%s', hora = '%s', equipo = '%s', dirip = '%s' 
                    where id = '%d' ";
                $sql = sprintf($sentencia, $cupocar, $cupomot, $ascodarea, $cacodcenat, $nombre, $tiempo, $estado, $login, $fecha, $hora, $equipo, $dirip, $id);

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
                $cupocar = $parqueo->limpiarEntradas($_POST['cupocar'], 5);
                $cupomot = $parqueo->limpiarEntradas($_POST['cupomot'], 5);
                $ascodarea = $parqueo->limpiarEntradas($_POST['ascodarea'], 1);
                $cacodcenat = $parqueo->limpiarEntradas($_POST['cacodcenat'], 4);
                $tiempo = $parqueo->limpiarEntradas($_POST['tiempo'], 2);
                $estado = $parqueo->limpiarEntradas($_POST['estado'], 1);

                $sentencia = " insert into parqueadero (nombre, cupocar, cupomot, ascodarea, cacodcenat, tiempo, estado, usuario, fecha, hora, equipo, dirip) values
                    ('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s') ";
                $sql = sprintf($sentencia, $nombre, $cupocar, $cupomot, $ascodarea, $cacodcenat, $tiempo, $estado, $login, $fecha, $hora, $equipo, $dirip);
                $parqueo->consulta($sql);

                if ( $parqueo ) {
                    
                    $buspar = " select max(id) from parqueadero ";
                    $rb = $parqueo->consulta($buspar);
                    $rsb = odbc_fetch_array($rb);
                    $parqueadero = $rsb[odbc_field_name($rb, 1)];
                            
                    // Insertar todos los horarios en cero
                    $sqldia = " select criterio from motivo where tabla = 'diasemana' and estado = 'A' ";
                    $rg = $parqueo->consulta($sqldia);
                    while ( $rsd = odbc_fetch_array($rg) ) {
                        
                        $dia = $rsd[odbc_field_name($rg, 1)];
                        $insert = " insert into parquehora (parqueadero, dia, inicio, fin, estado, usuario, fecha, hora, equipo, dirip) 
                            values ($parqueadero, '$dia', '000000', '000000', 'A', '$login', '$fecha', '$hora', '$equipo', '$dirip') ";
                        $parqueo->consulta($insert);
                    }
                    
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
                
            } else if ( isset ( $_POST['horarios'] ) ) {
                
                $parqueadero = $_POST['parqueadero'];
                $sqldia = " select criterio from motivo where tabla = 'diasemana' and estado = 'A' ";
                $rg = $parqueo->consulta($sqldia);
                while ( $rsd = odbc_fetch_array($rg) ) {

                    $dia = $rsd[odbc_field_name($rg, 1)];
                    $ini = $_POST["ini$dia"]."00";
                    $fin = $_POST["fin$dia"]."00";
                    
                    $update = " update parquehora set inicio = '$ini', fin = '$fin', usuario = '$login', 
                        fecha = '$fecha', hora = '$hora', equipo = '$equipo', dirip = '$dirip'
                        where parqueadero = $parqueadero and dia = '$dia' ";
                    $parqueo->consulta($update);
                    
                }
                
            } else {
                echo '';
            }

            if ( isset( $_GET['id'] ) ) {
                $id = $parqueo->limpiarEntradas( $_GET['id'], 5);
            } else {
                $id = 0;
            }

            $sqlSp = " select nombre, cupocar, cupomot, cacodcenat, tiempo, estado, ascodarea from parqueadero where id = %d ";
            $sqlid = sprintf($sqlSp, $id);
            $consulta = $parqueo->consulta($sqlid);

            if( odbc_num_rows($consulta) ) {

                $res = odbc_fetch_array($consulta);
                $nombre = $res[odbc_field_name($consulta, 1)];
                $cupocar = $res[odbc_field_name($consulta, 2)];
                $cupomot = $res[odbc_field_name($consulta, 3)];
                $cacodcenat = $res[odbc_field_name($consulta, 4)];
                $tiempo = $res[odbc_field_name($consulta, 5)];
                $estado = $res[odbc_field_name($consulta, 6)];
                $ascodarea = $res[odbc_field_name($consulta, 7)];

            } else {

                $id = 0;
                $nombre = '';
                $cupocar = 0;
                $cupomot = 0;
                $cacodcenat = '';
                $tiempo = '';
                $estado = 'A';
                $ascodarea = '';

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
                <label>Nombre del parqueadero</label>
                <input type="text" class="span12" name="nombre" maxlength="45" value="<?php echo $nombre; ?>" />
                <label>Cupo de carros</label>
                <input type="text" class="span12" name="cupocar" maxlength="5" value="<?php echo $cupocar; ?>" >
                <label>Cupo de motos</label>
                <input type="text" class="span12" name="cupomot" maxlength="5" value="<?php echo $cupomot; ?>" >
                <label>&Aacute;rea de servicio</label>
                <?php
                $sql = " SELECT ASCODAREA, ASDESAREA FROM BDMUF.TBBDARESER WHERE ASCODAREA IN ('0', '3', 'G','PD') AND ASCODEST = 'A' ";
                $parqueo->select($sql, 'ascodarea', $ascodarea, 'span12');
                ?>
                <label>Centro</label>
                <?php
                $sql = " SELECT CACODCENAT, CADESCENAT FROM BDMUF.TBBDCENATE WHERE CACODCENAT IN ('PQ1', 'PQ2', '301','PQ3') AND CACODESTAD = 'A' ";
                $parqueo->select($sql, 'cacodcenat', $cacodcenat, 'span12');
                ?>
                <label>Tiempo para salida</label>
                <input type="text" class="span12" name="tiempo" maxlength="2" value="<?php echo $tiempo; ?>" >
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
            <hr/>
            <?php
            if ( isset( $_GET['id'] ) ) {
                ?>
                <form action="app.php?web=parqueadero" method="POST">
                    <a href="#horarios" role="button" class="btn btn-inverse" data-toggle="modal">
                        +<i class="icon-user icon-white"></i>
                    </a>
                    <div id="horarios" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h3 id="myModalLabel">Horarios de atenci&oacute;n</h3>
                        </div>
                        <div class="modal-body">
                            <table class="table table-bordered table-condensed">
                                <thead>
                                    <th style="width: 33%;">D&iacute;a</th>
                                    <th style="width: 33%;">Inicio</th>
                                    <th style="width: 33%;">Fin</th>
                                </thead>
                                <tbody>
                                    <?php
                                    $sqldia = " select d.criterio, d.nombre, h.inicio, h.fin
                                        from motivo d inner join parquehora h on d.criterio = h.dia
                                        where d.estado = 'A' and d.tabla = 'diasemana' and h.parqueadero = '{$_GET['id']}' ";
                                    $rg = $parqueo->consulta($sqldia);
                                    while ( $rsd = odbc_fetch_array($rg) ) {
                                        $dia = $rsd[odbc_field_name($rg, 1)];
                                        echo "<tr>";
                                            echo "<td> {$rsd[odbc_field_name($rg, 2)]} </td>";
                                            echo "<td><input type=\"text\" name=\"ini$dia\" id=\"fecha\" maxlength=\"4\" value=\"";
                                                echo substr($rsd[odbc_field_name($rg, 3)], 0, 4);
                                            echo "\" class=\"span12\" /> </td>";
                                            echo "<td><input type=\"text\" name=\"fin$dia\" id=\"fecha\" maxlength=\"4\" value=\"";
                                                echo substr($rsd[odbc_field_name($rg, 4)], 0, 4);
                                            echo "\" class=\"span12\" /> </td>";
                                        echo "</tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                            <input type="hidden" name="parqueadero" value="<?php echo $_GET['id']; ?>" />
                        </div>
                        <div class="modal-footer">
                            <input type="submit" name="horarios" class="btn btn-primary" value="Registrar datos" />
                        </div>
                    </div>
                </form>
                <?php
            }
            ?>
        </div>
        <div class="span9">
            <h1>
                <a href="app.php?web=parqueadero" class="btn btn-primary" title="Nuevo registro">
                    <i class="icon-plus icon-white"></i>
                </a> 
                Parqueaderos
            </h1>
            <?php
            $sql = " select m.id, m.nombre, m.cupocar, m.cupomot, a.asdesarea, c.cadescenat, m.tiempo, m.estado
                from parqueadero m 
                inner join BDMUF.TBBDARESER a on m.ascodarea = a.ASCODAREA
                inner join BDMUF.TBBDCENATE c on m.cacodcenat = c.cacodcenat
                where a.ASCODAREA IN ('0', '3', 'G','PD') AND a.ASCODEST = 'A'
                and c.CACODCENAT IN ('PQ1', 'PQ2', '301','PQ3') AND c.CACODESTAD = 'A' ";
            $encabezados = "Cod.,Nombre,Carros,Motos,Area,Centro,Tiempo,Est";
            $parqueo->tabla($sql, $encabezados, 'accion', 'parqueadero');
            ?>
        </div>
    </div>
</div>