<script>
    
    function marcar(source){
        checkboxes=document.getElementsByTagName('input'); //obtenemos todos los controles del tipo Input
        for(i=0;i<checkboxes.length;i++) //recoremos todos los controles
        {
            if(checkboxes[i].type == "checkbox") //solo si es un checkbox entramos
            {
                checkboxes[i].checked=source.checked; //si es un checkbox le damos el valor del checkbox que lo llamó (Marcar/Desmarcar Todos)
            }
        }
    }
    
</script>
<form action="app.php?web=permiso" method="POST">
    <table>
        <tr>
            <td><label>Asignar permisos para</label></td>
            <td>
                <?php
                if ( isset ( $_POST['usuasi'] ) ) {
                    
                    $_SESSION['usuasi'] = $_POST['usuasi'];
                    $usuasi = $_SESSION['usuasi'];
                    
                } else {
                    
                    if ( isset ( $_SESSION['usuasi'] ) ) {
                        
                        $usuasi = $_SESSION['usuasi'];
                        
                    } else {
                        
                        $usuasi = $login;
                        $_SESSION['usuasi'] = $usuasi;
                        
                    }
                    
                }
                
                $sql = "  select u.login, ifnull(c.caprinom, '') concat ' ' concat ifnull(c.casegnom, '')  
                    concat ' ' concat ifnull(c.capriape, '') concat ' ' concat ifnull(c.casegape, '')
                    from usuario u inner join bdgeshum.tbthcandi c on u.canumdocum = c.canumdocum
                    order by c.caprinom, c.casegnom, c.capriape ";
                $parqueo->select($sql, 'usuasi', $usuasi, 'span4');
                ?>
            </td>
            <td>
                <input type="submit" class="btn btn-primary" name="dato" value="Buscar" />
            </td>
        </tr>
    </table>
</form>
<?php

if ( isset ( $_POST['asignar'] ) ) {
    
    $inactivar = " update permiso set estado = 'I' where login = '$usuasi' ";
    $reg = $parqueo->consulta($inactivar);
    
    foreach( $_POST['submenu'] as $opcion ) {
        
        $buscar = " select count(*) from permiso where login = '$usuasi' and menu = '$opcion' ";
        $cons = $parqueo->consulta($buscar);
        $cant = odbc_fetch_array($cons);
        $canti = trim($cant[odbc_field_name($cons, 1)]);
        
        if( $canti > 0 ) {
            
            $actualiza = " update permiso set estado = 'A' where login = '$usuasi' and menu = '$opcion' ";
            $parqueo->consulta($actualiza);
            
        } else {
            
            $inserta = " insert into permiso (login, menu, estado, usuario, fecha, hora, equipo, dirip)
                values ('$usuasi', '$opcion', 'A', '$login', '$fecha', '$hora', '$equipo', '$dirip') ";
            $parqueo->consulta($inserta);
            
        }
//        echo "<br/>";
    }
    
}

?>
<form action="app.php?web=permiso" method="POST">
    <div class="container-fluid">
        <?php
        echo "<div class=\"row-fluid\">";

        $sql3 = " select m.id, m.nombre, op.nombre as opt, p.estado as estper, m.estado as estmen
            from menu m 
            inner join motivo op on m.tipo = op.criterio and op.tabla = 'menu'
            left join permiso p on m.id = p.menu and p.login = '$usuasi' ";
        if ( $login == 'ltaborda' or $login == 'lhosorio' ) {
            $sql3.= " where m.id > 0 ";
        } else {
            $sql3.= " where m.id <> 12 ";
        }
        $sql3.= "order by m.tipo, m.estado, m.nombre ";
        $reg3 = $parqueo->consulta($sql3);
        
        $tipo = '';
        $contar = 0;
        
            while ( $rs3 = odbc_fetch_array($reg3) ) {
                
                if ( $tipo != trim($rs3[odbc_field_name($reg3, 3)]) && $contar > 0 ) {
                    
                    $tipo = trim($rs3[odbc_field_name($reg3, 3)]);
                    echo "</table>";
                    echo "</div>";
                    echo "<div class=\"span3\">";
                    echo "<table class=\"table table-bordered table-condensed\">";
                        echo "<tr><th colspan=\"2\">" . $rs3[odbc_field_name($reg3, 3)] . "</th></tr>";
                    
                } else {
                    if ( $contar == 0 ) {
                        $tipo = trim($rs3[odbc_field_name($reg3, 3)]);
                        echo "<div class=\"span3\">";
                            echo "<table class=\"table table-bordered table-condensed\">";
                                echo "<tr><th colspan=\"2\">" . $rs3[odbc_field_name($reg3, 3)] . "</th></tr>";
                    }
                }
                $contar++;
                echo "<tr><td>";
                    if ( $rs3[odbc_field_name($reg3, 4)] == 'A') $activo = 'x';
                    else $activo = '';
                    $parqueo->checkbox("submenu[]", $rs3[odbc_field_name($reg3, 1)], $activo);
                echo " </td>";
                echo "<td>({$rs3[odbc_field_name($reg3, 5)]}) {$rs3[odbc_field_name($reg3, 2)]} </td></tr> ";
                
            }
            echo "<tr><td colspan=\"2\">&nbsp;</td></tr>";
            echo "<tr><td><input type=\"checkbox\" onclick=\"marcar(this);\" /></td>";
            echo "<td> Marcar/Desmarcar Todos </td></tr> ";
            
        echo "</table>";
        echo "</div>";
        echo "</div>";
        echo "<hr />";

        ?>
        </div>
        <div class="row-fluid">
            <input type="submit" name="asignar" class="btn btn-primary" value="Asignar" />
        </div>
    </div>
</form>