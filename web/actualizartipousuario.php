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
                        $tipousua = $parqueo->limpiarEntradas($_POST['tipousua'], 1);
                    
                        $sentencia = " update vehiculos set tipousua = '%s', usuario = '%s', fecha = '%s', hora = '%s', 
                            equipo = '%s', dirip = '%s' where placa = '%s' ";
                        $sql = sprintf($sentencia, $tipousua, $login, $fecha, $hora, $equipo, $dirip, $placa);
                        
                        $actualiza = $parqueo->consulta($sql);

                        if ( $actualiza ) {

                            // Validar si tiene registros activos de movimientos (ingresos al parqueadero).
                            $sql = " select m.id
                                from movimiento m where m.placa = '$placa' and m.fecsal = 0 order by m.id desc ";
                            $consulta = $parqueo->consulta($sql);
                            $res = odbc_fetch_array($consulta);
                            $movimiento = trim($res[odbc_field_name($consulta, 1)]);
                            if( $movimiento > 0 ) {

                                $update = " update movimiento set tipousua = '$tipousua'
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
            <form action="app.php?web=actualizartipousuario" method="POST">
                <h3>Actualizar</h3>
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
                       onblur="javascript:cargarDatos(this.value);" readonly/>
                <div id="cargarDatos"></div>
                <label>Tipo de usuario</label>
                <?php
                $sql = " select criterio, nombre from motivo where tabla = 'tipousua' and estado = 'A' 
                    order by nombre ";
                //$parqueo->select($sql, 'tipousua', $tipousua, 'span12');
                ?>
                <select name="tipousua" class="span12">
                    <?php
                    $resultado = odbc_exec($parqueo->conexion, $sql);
                    while ($rowSelect = odbc_fetch_array($resultado)) {
                        ?><option value="<?php echo $rowSelect[odbc_field_name($resultado, 1)]; ?>"
                        <?php if ( trim($rowSelect[odbc_field_name($resultado, 1)]) == $tipousua ) echo ' selected="" '; ?> >
                        <?php echo ucwords(strtolower($rowSelect[odbc_field_name($resultado, 2)])); ?>
                        </option>
                        <?php
                    }
                ?></select>
                <button type="submit" class="btn btn-primary" name="actualiza">Actualizar</button>
            </form>
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
        window.location.href="app.php?web=actualizartipousuario&placa="+cod;
    }
    
    function cargarDatos(canumdocum){
        
        $("#cargarDatos").cargar("web/buscarCandi.php?canumdocum=" + canumdocum);
        
    }
    
</script>