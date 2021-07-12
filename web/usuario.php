<script>
    
    function accion(cod){
        window.location.href="app.php?web=usuario&id="+cod;
    }
    
    $(document).ready(function() {
        $('#usuario').dataTable({
            "bPaginate": true,
            "bJQueryUI": true,
            "iDisplayLength": 10,
            "aaSorting": [],
            "bStateSave": true
        });
    });
    
    function buscarCandidato(numdoc){
        
        window.location.href="app.php?web=usuario&numdoc=" + numdoc;
        
    }
    
</script>
<div class="container-fluid">
    <div class="row-fluid">
        <div class="span3">
            <?php
            if ( isset( $_POST['actualiza'] ) ) {

                $id = $parqueo->limpiarEntradas($_POST['id'], 5);
                $estado = $parqueo->limpiarEntradas($_POST['estado'], 1);

                $sentencia = " update usuario set estado = '%s', usuario = '%s', fecha = '%s', 
                    hora = '%s', equipo = '%s', dirip = '%s' 
                    where id = '%d' ";
                $sql = sprintf($sentencia, $estado, $login, $fecha, $hora, $equipo, $dirip, $id);

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

                $loginsec = $parqueo->limpiarEntradas($_POST['loginsec'], 15);
                $canumdocum = $parqueo->limpiarEntradas($_POST['canumdocum'], 15);
                $estado = $parqueo->limpiarEntradas($_POST['estado'], 1);

                $sentencia = " insert into usuario (login, canumdocum, estado, usuario, fecha, hora, equipo, dirip) values
                    ('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s') ";
                $sql = sprintf($sentencia, $loginsec, $canumdocum, $estado, $login, $fecha, $hora, $equipo, $dirip);
                $parqueo->consulta($sql);

                if ($parqueo) {
                    ?>
                    <script>
                        alert("Informacion creada con extio.");
                        window.location.href="app.php?web=usuario";
                    </script>
                    <?php
                } else {
                    ?>
                    <script>
                        alert("Error en la creacion de los datos.");
                        window.location.href="app.php?web=usuario";
                    </script>
                    <?php
                }
                
            } elseif ( isset( $_POST['asignaparqueadero'] ) ) {
                
                $usuasi = $parqueo->limpiarEntradas($_POST['usuasi'], 15);
                $sql = " update parusu set estado = 'I' where login = '$usuasi' ";
                $parqueo->consulta($sql);
                
                foreach( $_POST['parquea'] as $dato ) {

                    $buscar = " select count(*) from parusu where login = '$usuasi' and parqueadero = '$dato' ";
                    $consulta = $parqueo->consulta($buscar);
                    
                    $res = odbc_fetch_array($consulta);
                    $numrows = $res[odbc_field_name($consulta, 1)];
                    
                    if( $numrows > 0 ) {
                        
                        $actualiza = " update parusu set estado = 'A' where login = '$usuasi' and parqueadero = '$dato' ";
                        $parqueo->consulta($actualiza);
                        
                    } else {
                        
                        $insertar = " insert into parusu (parqueadero, login, estado, usuario, fecha, hora, equipo, dirip)
                            values ('$dato', '$usuasi', 'A', '$login', '$fecha', '$hora', '$equipo', '$dirip')";
                        $parqueo->consulta($insertar);
                        
                    }
                    
                }
                
            } else {
                echo '';
            }

            if ( isset( $_GET['id'] ) ) {
                $id = $parqueo->limpiarEntradas( $_GET['id'], 5);
            } else {
                $id = 0;
            }
            if ( isset( $_GET['numdoc'] ) ) {
                $numdoc = $parqueo->limpiarEntradas( $_GET['numdoc'], 15);
            } else {
                $numdoc = 0;
            }

            $sqlSp = " select ifnull(u.canumdocum, c.canumdocum), u.login, u.estado, 
                ifnull(c.caprinom, '') concat ' ' concat ifnull(c.casegnom, ''),
                ifnull(c.capriape, '') concat ' ' concat ifnull(c.casegape, '')
                from bdgeshum.tbthcandi c
                left join usuario u on u.canumdocum = c.canumdocum
                where u.id = %d or c.canumdocum = '%s' and c.aucodestad = 'A' and c.canumdocum <> 0 ";
            $sqlid = sprintf($sqlSp, $id, $numdoc);
            $consulta = $parqueo->consulta($sqlid);

            if( odbc_num_rows($consulta) ) {
                
                $res = odbc_fetch_array($consulta);
                $canumdocum = trim($res[odbc_field_name($consulta, 1)]);
                $loginsec = trim($res[odbc_field_name($consulta, 2)]);
                $estado = trim($res[odbc_field_name($consulta, 3)]);
                $nombres = ucwords(strtolower($res[odbc_field_name($consulta, 4)]));
                $apellidos = ucwords(strtolower($res[odbc_field_name($consulta, 5)]));

            } else {
                
                $id = 0;
                $canumdocum = '';
                $loginsec = '';
                $estado = 'A';
                $nombres = '';
                $apellidos = '';

            }
                ?>
            <form action="app.php?web=usuario" method="POST">
                <h3>Actualizar / Nuevo</h3>
                <?php
                if ( isset( $_GET['id'] ) or $loginsec != '' ) {
                    ?>
                    <input type="hidden" name="id" value="<?php echo $id; ?>" />
                    <label>Documento de identidad</label>
                    <strong><?php echo number_format( $canumdocum ); ?></strong> <br/><br/>
                    <label>Login de SEC</label>
                    <strong><?php echo $loginsec; ?></strong> <br/><br/>
                    <label>Nombre(s)</label>
                    <strong><?php echo $nombres; ?></strong> <br/><br/>
                    <label>Apellido(s)</label>
                    <strong><?php echo $apellidos; ?></strong> <br/><br/>
                    <label>Estado</label>
                    <?php
                    $sql = " select criterio, nombre from motivo where tabla = 'motivo' and estado = 'A' ";
                    $parqueo->select($sql, 'estado', $estado, 'span12');
                    ?>
                    <button type="submit" class="btn btn-primary" name="actualiza">Actualizar</button>
                    <?php
                    
                } else {
                    
                    ?>
                    <label>Documento de identidad</label>
                    <input type="text" class="span12" name="canumdocum" maxlength="45" value="<?php echo $canumdocum; ?>" onchange="javascript:buscarCandidato(this.value);" />
                    <label>Login de SEC</label>
                    <input type="text" class="span12" name="loginsec" maxlength="25" value="<?php echo $loginsec; ?>" >
                    <label>Nombre(s)</label>
                    <input type="text" class="span12" value="<?php echo $nombres; ?>" >
                    <label>Apellido(s)</label>
                    <input type="text" class="span12" value="<?php echo $apellidos; ?>" >
                    <label>Estado</label>
                    <?php
                    $sql = " select criterio, nombre from motivo where tabla = 'motivo' and estado = 'A' ";
                    $parqueo->select($sql, 'estado', $estado, 'span12');
                    ?>
                    <button type="submit" class="btn btn-primary" name="nuevo">Crear</button>
                    <?php
                }
                ?>
            </form>
            
            <?php
            if ( isset( $_GET['id'] ) or $loginsec != '' ) {
                ?>
                <hr/>
                <a href="#myModal" role="button" class="btn btn-danger" data-toggle="modal">
                    <i class="icon-edit icon-white"></i>
                </a>
                 
                <form action="app.php?web=usuario&loginsec=<?php echo $loginsec; ?>" method="POST" >
                    <input type="hidden" name="usuasi" value="<?php echo $loginsec; ?>" />
                    <div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h3 id="myModalLabel">Asignaci&oacute;n de parqueaderos</h3>
                        </div>
                        <div class="modal-body">
                            <?php
                            $sql = " select p.id, p.nombre, ifnull(pu.estado, 'x'), pu.id as puid
                                from parqueadero p left join parusu pu on p.id = pu.parqueadero and login = '$loginsec'  ";
                            $consulta = $parqueo->consulta($sql);

                            if( odbc_num_rows($consulta) ) {
                                
                                echo "<table class=\"table table-bordered table-condensed\" >";
                                while ( $res = odbc_fetch_array($consulta) ) {
                                    
                                    echo "<tr>";
                                        echo "<td>";
                                        if ( $res[odbc_field_name($consulta, 3)] == 'A') $activo = 'x';
                                        else $activo = '';
                                        $parqueo->checkbox("parquea[]", trim($res[odbc_field_name($consulta, 1)]), $activo);
                                        echo "</td>";
                                        echo "<td>".trim($res[odbc_field_name($consulta, 2)]) . "</td>";
                                    echo "</tr>";
                                    
                                }
                                echo "</table>";
                                
                            }
                            ?>
                        </div>
                        <div class="modal-footer">
                            <input type="submit" name="asignaparqueadero" class="btn btn-primary" value="Guardar registros" />
                        </div>
                    </div>
                </form>
                <?php
            }
            ?>
        </div>
        <div class="span9">
            <h1>
                <a href="app.php?web=usuario" class="btn btn-primary" title="Nuevo registro">
                    <i class="icon-plus icon-white"></i>
                </a> 
                Usuarios
            </h1>
            <?php
            $sql = " select u.id, u.canumdocum, u.login, ifnull(c.caprinom, '') concat ' ' concat ifnull(c.casegnom, ''), 
            ifnull(c.capriape, '') concat ' ' concat ifnull(c.casegape, ''), ifnull(p.nombre, ' '), 
            u.estado, ifnull(pu.estado , ' ') as parest
            from usuario u inner join bdgeshum.tbthcandi c on u.canumdocum = c.canumdocum 
            left join parusu pu on u.login = pu.login 
            left join parqueadero p on pu.parqueadero = p.id ";
            $encabezados = "Id,Identidad,Login SEC,Nombres,Apellidos,Parqueadero,EU,EP";
            $parqueo->tabla($sql, $encabezados, 'accion', 'usuario');
            ?>
        </div>
    </div>
</div>