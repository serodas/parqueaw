<script type="text/javascript">
    function accion(dia){
        window.location.href="app.php?web=festivo&dia="+dia;
    }
</script>
<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <form action="app.php?web=festivo" method="POST">
                <label>Seleccione el a&ntilde;o</label>
                <?php
                $anio = date("Y");
                if ( isset ( $_POST['as'] ) ) {
                    
                    $as = $parqueo->limpiarEntradas($_POST['as'], 10);
                    $_SESSION['as'] = $as;
                    
                } else {
                    
                    if ( isset ( $_SESSION['as'] ) ) {
                        
                        $as = $_SESSION['as'];
                        
                    } else {
                        
                        $as = $anio;
                        
                    }
                    
                }
                ?>
                <select name="as" class="span2">
                    <?php
                    
                    for ( $a = 2014; $a <= ($anio + 1 ); $a++) {
                        
                        ?>
                        <option value="<?php echo $a; ?>" <?php if ( $a == $as )echo " selected=\"\" "; ?> >
                            A&ntilde;o <?php echo $a; ?>
                        </option>
                        <?php
                        
                    }
                    ?>
                </select>
                <input value="Buscar" name="buscar" type="submit" class="btn btn-primary" />
            </form>
        </div>
    </div>
</div>
<?php

if ( isset ( $_REQUEST['dia'] ) ) {
    
    $dia = $parqueo->limpiarEntradas($_REQUEST['dia'], 10);

    if ( $dia >= $fecha ) {
        
        $sql = " select count(estado) from festivo where fecfes = '%s' ";
        $sql2 = sprintf($sql, $dia);
        $reg2 = $parqueo->consulta($sql2);
        $rs2 = odbc_fetch_array($reg2);
        $cant = $rs2[odbc_field_name($reg2, 1)];
        
        if ( $cant == 1 ) {

            $sql = " select estado from festivo where fecfes = '%s' ";
            $sql2 = sprintf($sql, $dia);
            $reg2 = $parqueo->consulta($sql2);
            $rs2 = odbc_fetch_array($reg2);
            $estado = $rs2[odbc_field_name($reg2, 1)];
            
            if ( $estado == 'I' ) {
                
                $sql = " update festivo set estado = 'A', fecha = '$fecha', usuario = '$login' where fecfes = '$dia' ";
                
            } else if ( $estado == 'A' ) {
                
                $sql = " update festivo set estado = 'I', fecha = '$fecha', usuario = '$login' where fecfes = '$dia' ";
            } else {
                
                echo "";
                
            }
            
        } else {
            
            $sql = " insert into festivo values ('$dia', 'A', '$login', '$fecha', '$hora', '$equipo', '$dirip') ";
            
        }
        
        $parqueo->consulta($sql);
        
    } else {
        
        ?>
        <script type="text/javascript">
            alert('No puede editar fechas anteriores.');
        </script>
        <?php
        
    }
}

$meses = explode(',', 'Enero,Febrero,Marzo,Abril,Mayo,Junio,Julio,Agosto,Septiembre,Octubre,Noviembre,Diciembre');

for ($i = 0; $i < 12; $i++) {
    
    if (( $i % 3 ) == 0) {
        
        ?>
        <div class="container-fluid">
            <div class="row-fluid">
        <?php
        
    }
    
    $parqueo->mes($as, $i+1, $meses[$i]);
    if (( $i % 3 ) == 2) {
        
        ?>
            </div>
        </div>
        <?php
        
    }
    
}
?>