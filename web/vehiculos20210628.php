<div class="container-fluid">
    <div class="row-fluid">
        <div class="span3">
            <?php
            
            if ( isset ( $_POST['placa'] ) ) {
                
                // Se usa strlen en el nombre para verificar que se registre un nombre con mas de 10 caracteres.
                if ( trim($_POST['placa']) == '' or trim($_POST['canumdocum']) == '' or trim($_POST['canumdocum']) == 0 or trim($_POST['nombre']) == '' or strlen(trim($_POST['nombre'])) < 10  ) {
                    
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
                        $tipousua = $parqueo->limpiarEntradas($_POST['tipousua'], 1);
                        $tipovehi = $parqueo->limpiarEntradas($_POST['tipovehi'], 1);
                        $canumdocum = $parqueo->limpiarEntradas($_POST['canumdocum'], 15);
                        $color = $parqueo->limpiarEntradas($_POST['color'], 6);
                        $estavehi = $parqueo->limpiarEntradas($_POST['estavehi'], 2);
                        $estado = $parqueo->limpiarEntradas($_POST['estado'], 1);
                        $feclim = $parqueo->limpiarEntradas($_POST['feclim'], 8);
                        $autsan = $parqueo->limpiarEntradas($_POST['autsan'], 1);
                        $procedenci = $parqueo->limpiarEntradas($_POST['procedenci'], 100);
                        
                        if ( $autsan == 'S' && $tipousua == 'S' )
                            $tipousua = 'E';
                        
                        $sentencia = " update vehiculos set nombre = '%s', tipousua = '%s', tipovehi = '%s', canumdocum = '%s', 
                            color = '%s', estavehi = '%s', estado = '%s', usuario = '%s', fecha = '%s', hora = '%s', 
                            equipo = '%s', dirip = '%s', feclim = '%s', autsan = '%s', procedenci = '%s' where placa = '%s' ";
                        $sql = sprintf($sentencia, $nombre, $tipousua, $tipovehi, $canumdocum, $color, $estavehi, $estado, $login, $fecha, $hora, $equipo, $dirip, $feclim, $autsan, $procedenci, $placa);
                        
                        $actualiza = $parqueo->consulta($sql);

                        if ( $actualiza ) {

                            // Validar si tiene registros activos de movimientos (ingresos al parqueadero).
                            $sql = " select m.id
                                from movimiento m where m.placa = '$placa' and m.fecsal = 0 order by m.id desc ";
                            $consulta = $parqueo->consulta($sql);
                            $res = odbc_fetch_array($consulta);
                            $movimiento = trim($res[odbc_field_name($consulta, 1)]);
                            if( $movimiento > 0 ) {

                                $update = " update movimiento set tipousua = '$tipousua', tipovehi = '$tipovehi'
                                    where id = '$movimiento' and placa = '$placa' ";
                                $parqueo->consulta($update);

                            }

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

                        $placa = strtoupper($parqueo->limpiarEntradas($_POST['placa'], 10));
                        $nombre = $parqueo->limpiarEntradas($_POST['nombre'], 45);
                        $tipousua = $parqueo->limpiarEntradas($_POST['tipousua'], 1);
                        $tipovehi = $parqueo->limpiarEntradas($_POST['tipovehi'], 1);
                        $canumdocum = $parqueo->limpiarEntradas($_POST['canumdocum'], 15);
                        $color = $parqueo->limpiarEntradas($_POST['color'], 6);
                        $estavehi = $parqueo->limpiarEntradas($_POST['estavehi'], 2);
                        $estado = $parqueo->limpiarEntradas($_POST['estado'], 1);
                        $feclim = $parqueo->limpiarEntradas($_POST['feclim'], 8);
                        $autsan = $parqueo->limpiarEntradas($_POST['autsan'], 1);
                        $procedenci = $parqueo->limpiarEntradas($_POST['procedenci'], 100);
                        
                        if ( $autsan == 'S' && $tipousua == 'S' )
                            $tipousua = 'E';
                        
                        // Verificar que solo tenga registrado un solo vehiculo
                        $sql = " select count(*) from vehiculos where canumdocum = '$canumdocum' ";
                        $consulta = $parqueo->consulta($sql);
                        $res = odbc_fetch_array($consulta);
                        $nroveh = trim($res[odbc_field_name($consulta, 1)]);

                        if ( $nroveh > 0 ) {

                            ?>
                            <script>
                                alert("El usuario ya tiene registrado un vehiculo. \nTenga en cuenta esta informacion.");
        //                        window.location.href="app.php?web=vehiculos";
                            </script>
                            <?php

                        }

                        $sentencia = " insert into vehiculos (placa, nombre, tipousua, tipovehi, canumdocum, color, estavehi, estado, 
                            usuario, fecha, hora, equipo, dirip, feclim, autsan, procedenci) values
                            ('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s') ";
                        $sql = sprintf($sentencia, $placa, $nombre, $tipousua, $tipovehi, $canumdocum, $color, $estavehi, $estado, $login, $fecha, $hora, $equipo, $dirip, $feclim, $autsan, $procedenci);
                        $parqueo->consulta($sql);

                        if ($parqueo) {

                            // Validar si tiene registros activos de movimientos (ingresos al parqueadero).
                            $sql = " select m.id
                                from movimiento m where m.placa = '$placa' and m.mrcodcons = 0 order by m.id desc ";
                            $consulta = $parqueo->consulta($sql);
                            $res = odbc_fetch_array($consulta);
                            $movimiento = trim($res[odbc_field_name($consulta, 1)]);
                            if( $movimiento > 0 ) {

                                $update = " update movimiento set tipousua = '$tipousua', tipovehi = '$tipovehi'
                                    where id = '$movimiento' and placa = '$placa' ";
                                $parqueo->consulta($update);

                            }

                            ?>
                            <script>
                                alert("Informacion creada con extio.");
                                window.location.href="app.php?web=vehiculos";
                            </script>
                            <?php
                        } else {
                            ?>
                            <script>
                                alert("Error en la creacion de los datos.");
                                window.location.href="app.php?web=vehiculos";
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

            if ( isset( $_GET['letra'] ) ) {
                $letra = $parqueo->limpiarEntradas( $_GET['letra'], 4);
                $_SESSION['letra'] = $letra;
                if ( $letra == 'Todo' )
                    $letra = '';
            } else {
                if ( isset ( $_SESSION['letra'] ) ) {
                    $letra = $_SESSION['letra'];
                } else {
                    $letra = 'A';
                }
            }
            
            $sqlSp = " select v.placa, v.tipousua, v.tipovehi, v.canumdocum, v.color, v.estavehi, v.estado,
                v.nombre, v.feclim, v.autsan, v.procedenci
                from vehiculos v where v.placa = '%s' ";
            $sqlid = sprintf($sqlSp, $placa1);
            $consulta = $parqueo->consulta($sqlid);

            if( odbc_num_rows($consulta) ) {
                
                $res = odbc_fetch_array($consulta);
                $placa = strtoupper(trim($res[odbc_field_name($consulta, 1)]));
                $tipousua = $res[odbc_field_name($consulta, 2)];
                $tipovehi = $res[odbc_field_name($consulta, 3)];
                $canumdocum = trim($res[odbc_field_name($consulta, 4)]);
                $color = $res[odbc_field_name($consulta, 5)];
                $estavehi = $res[odbc_field_name($consulta, 6)];
                $estado = trim($res[odbc_field_name($consulta, 7)]);
                if ( $placa == '' ) {
                    $existePlaca = 0;
                    $placa = $placa1;
                } else {
                    $existePlaca = 1;
                }
                $nombre = trim($res[odbc_field_name($consulta, 8)]);
                $feclim = trim($res[odbc_field_name($consulta, 9)]);
                if ( $feclim == 0 )
                    $feclim = $fecha;
                $autsan = trim($res[odbc_field_name($consulta, 10)]);
                $procedenci = trim($res[odbc_field_name($consulta, 11)]);
                
            } else {
                
                $placa = $placa1;
                $tipousua = 'E';
                $tipovehi = 'M';
                $canumdocum = '0';
                $color = '000000';
                $estavehi = '20';
                $estado = 'A';
                $existePlaca = 0;
                $nombre = '';
                $feclim = $fecha;
                $autsan = "N";
                $procedenci = "";
                
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
            <form action="app.php?web=vehiculos" method="POST">
                <h3>Actualizar / Nuevo</h3>
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
                <input type="text" class="span12" name="canumdocum" maxlength="45" autofocus="" value="<?php echo $canumdocum; ?>" 
                       onblur="javascript:cargarDatos(this.value);" />
                <div id="cargarDatos"></div>
                <label>Tipo de usuario</label>
                <?php
                $sql = " select criterio, nombre from motivo where tabla = 'tipousua' and estado = 'A' 
                    order by nombre ";
                //$parqueo->select($sql, 'tipousua', $tipousua, 'span12');
                ?>
                <select name="tipousua" class="span12" onchange="javascript:validarTipo(this.value)">
                    <?php
                    $resultado = odbc_exec($parqueo->conexion, $sql);
                    while ($rowSelect = odbc_fetch_array($resultado)) {
                        ?><option value="<?php echo $rowSelect[odbc_field_name($resultado, 1)]; ?>"
                        <?php if ( trim($rowSelect[odbc_field_name($resultado, 1)]) == $tipousua ) echo ' selected="" '; ?> >
                        <?php echo ucwords(strtolower($rowSelect[odbc_field_name($resultado, 2)])); ?>
                        </option>
                        <?php
                    }
                ?></select><?php
                
                
                ?>
                <label>Procedencia</label>
                <input type="text" class="span12" name="procedenci" maxlength="100" value="<?php echo $procedenci; ?>" />
                <label>Tipo veh&iacute;culo</label>
                <?php
                $tipovehi = " select criterio, nombre from motivo where tabla = 'tipovehi' and estado = 'A'
                    order by nombre ";
                $parqueo->select($tipovehi, 'tipovehi', $tipo, 'span12');
                ?>
                <label>Fecha limite (para invitados)</label>
                <script>DateInput('feclim', true, 'YYYYMMDD', '<?php echo $feclim; ?>')</script>
                <label>Estado del veh&iacute;culo</label>
                <?php
                $sql = " select criterio, nombre from motivo where tabla = 'estavehi' and estado = 'A'
                    order by nombre ";
                $parqueo->select($sql, 'estavehi', $estavehi, 'span12');
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
                ?>
                <label>Autorizar Exoneraci&oacute;n</label>
                <?php
                if ( $login == 'gtabares' or $login == 'lhosorio' ) {
                    $sql = " select criterio, nombre from motivo where tabla = 'sn' and estado = 'A' ";
                    $parqueo->select($sql, 'autsan', $autsan, 'span12');
                } else {
                    ?>
                    <input type="hidden" name="autsan" value="<?php echo $autsan; ?>" />
                    <p class="alert alert-info">No tiene autorización para modificar la exoneración</p>
                    <?php
                }
                
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
                <a href="app.php?web=vehiculos" class="btn btn-primary" title="Nuevo registro">
                    <i class="icon-plus icon-white"></i>
                </a> 
                Personal y sus veh&iacute;culos
                <a href="web/vehiculosxls.php" class="btn btn-warning" title="Descargar archivo de vehiculos">
                    <i class="icon-download-alt icon-white"></i>
                </a> 
            </h1>
            <div class="pagination pagination-mini pagination-centered">
                <ul>
                    <?php
                    $letras = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','Todo');
                    for ( $i = 0; $i < 27; $i++) {
                        echo "<li ";
                        if ( $letra == $letras[$i] ) echo " class=\"active\" ";
                        echo " ><a href=\"app.php?web=vehiculos&letra={$letras[$i]}\" ";
                        echo ">{$letras[$i]}</a></li>";
                    }
                    ?>
                </ul>
            </div>
            <?php
            $sql = " select distinct v.placa, v.canumdocum, v.nombre, tu.nombre as tipousua, 
                    tv.nombre as tipovehi, ev.nombre as estavehi, v.color, v.estado, v.feclim,
                    p.prdesproce, v.autsan, v.procedenci
                from vehiculos v
                    inner join motivo tu on v.tipousua = tu.criterio and tu.tabla = 'tipousua' 
                    inner join motivo tv on v.tipovehi = tv.criterio and tv.tabla = 'tipovehi' 
                    inner join motivo ev on v.estavehi = ev.criterio and ev.tabla = 'estavehi' 
                    left join bdgeshum.tbthcandi c on v.canumdocum = c.canumdocum 
                    left join bdgeshum.tbthmaeper m on c.cacodcandi = m.cacodcandi and m.aucodestad = 'A'
                    left join bdgeshum.tbthproce p on m.prcodproce = p.prcodproce ";
            if ( $letra != 'Todo' ) {
                $sql.= " where v.placa like '$letra%' ";
            }
            $encabezados = "Placa,Identidad,Nombre completo,Dependencia / tipo,Vehiculo,Estado,Col,Fec lim,Aut,Procedencia,E";
            
            $tabla = "<table class=\"table table-bordered table-condensed\" id=\"vehiculos\" >";

                $tabla.= "<thead>";
                    $tabla.= "<tr>";
                    $titulo = explode(',',$encabezados);
                    $numreg = count($titulo);
                    for($j=0; $j < $numreg; $j++ ) {
                        $tabla.= "<th> {$titulo[$j]} </th>";
                    }
                    $tabla.= "</tr>";
                $tabla.= "</thead>";

                $reg = odbc_exec($parqueo->conexion, $sql);
                if( odbc_num_rows( $reg ) ) {
                    
                    $tabla.= "<tbody>";
                    while ( $res = odbc_fetch_array($reg) ) {

                        $tabla.= "<tr onClick=\"javascript:accion('".$res[odbc_field_name($reg, 1)]."')\" ";
                            if ( $res[odbc_field_name($reg, 8)] == 'I' ) 
                                    $tabla.= " class=\"alert alert-error\" ";
                        $tabla.= ">";
                            $tabla.= "<td> {$res[odbc_field_name($reg, 1)]} </td>";
                            $tabla.= "<td> {$res[odbc_field_name($reg, 2)]} </td>";
                            $tabla.= "<td> {$res[odbc_field_name($reg, 3)]} </td>";
                            $tabla.= "<td>";
                                if ( trim($res[odbc_field_name($reg, 10)]) != "" )
                                    $tabla.= ucwords(strtolower($res[odbc_field_name($reg, 10)]));
                                else
                                    $tabla.= $res[odbc_field_name($reg, 4)];
                            $tabla.= "</td>";
                            $tabla.= "<td> {$res[odbc_field_name($reg, 5)]} </td>";
                            $tabla.= "<td> {$res[odbc_field_name($reg, 6)]} </td>";
                            $tabla.= "<td style=\"width: 20px; background-color: #{$res[odbc_field_name($reg, 7)]};\"> </td>";
                            $tabla.= "<td>{$res[odbc_field_name($reg, 9)]}</td>";
                            $tabla.= "<td>{$res[odbc_field_name($reg, 11)]}</td>";
                            $tabla.= "<td>{$res[odbc_field_name($reg, 12)]}</td>";
                            $tabla.= "<td>{$res[odbc_field_name($reg, 8)]}</td>";
                        $tabla.= "</tr>";

                    }
                    $tabla.= "</tbody>";

                } else {
                    $tabla.= "<tr><td colspan=\"$numreg\"> No hay registros </td></tr>";
                }
            $tabla.= "</table>";
            echo $tabla;
            $_SESSION['tabladedatos'] = $tabla;
            ?>
        </div>
    </div>
</div>
<script>
    
    jQuery.fn.cargar = function(url) {
        $(document).ready(function(){
            $("#cargarDatos").load(url);
        });
    };
    
    function accion(cod){
        window.location.href="app.php?web=vehiculos&placa="+cod;
    }
    
    $(document).ready(function() {
        $('#vehiculos').dataTable({
            "bPaginate": true,
            "bJQueryUI": true,
            "iDisplayLength": 10,
            "aaSorting": [],
            "bStateSave": true
        });
    });
    
    function cargarDatos(canumdocum){
        
        $("#cargarDatos").cargar("web/buscarCandi.php?canumdocum=" + canumdocum);
        
    }
    
</script>