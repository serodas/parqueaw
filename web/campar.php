<?php

if ( isset ( $_GET['par'] ) ) {
    
    $par = $_GET['par'];
    $sql = " select count(*) from parusu where login = '$login' and parqueadero = '$par' and estado = 'A' ";
    $consulta = $parqueo->consulta($sql);
    $res = odbc_fetch_array($consulta);
    $numrows = $res[odbc_field_name($consulta, 1)];

    if( $numrows > 0 ) {

        $sql = " select nombre, ascodarea, cacodcenat 
            from parqueadero where id = '$par' ";
        $consulta = $parqueo->consulta($sql);
        $res = odbc_fetch_array($consulta);
        $_SESSION['razsoc'] = $res[odbc_field_name($consulta, 1)];
        $_SESSION['parqueadero'] = $par;
        
        $usrdb = strtoupper($_SESSION['usrdb']);
        $ascodarea = $res[odbc_field_name($consulta, 2)];
        $cacodcenat = $res[odbc_field_name($consulta, 3)];
        
        $unidadmedida = ( $par == 3 ) ? 3 : 2;
        
        $sql = " select t.tatarifa
            from bdmuf.tbfatarifa t
                inner join bdmuf.tbactare a on t.aacod = a.aacod
            where t.cacod = 'D'
                and t.taunimed in ('$unidadmedida') and a.accodactiv = 5110
                and t.aucodestad = 'A' and a.ascodarea = '$ascodarea' ";
        $consulta = $parqueo->consulta($sql);
        $res = odbc_fetch_array($consulta);
        $_SESSION['tarcar'] = $res[odbc_field_name($consulta, 1)];
        
        $sql = " select t.tatarifa
            from bdmuf.tbfatarifa t
                inner join bdmuf.tbactare a on t.aacod = a.aacod
            where t.cacod = 'D'
                and t.taunimed in ('$unidadmedida') and a.accodactiv = 5182
                and t.aucodestad = 'A' and a.ascodarea = '$ascodarea' ";
        $consulta = $parqueo->consulta($sql);
        $res = odbc_fetch_array($consulta);
        $_SESSION['tarmot'] = $res[odbc_field_name($consulta, 1)];
        
        $sql = " update BDUTIL.TBSGUSU set AREAADMON = '$ascodarea', CENTROADM = '$cacodcenat'
                where USCODUSUAR = '$usrdb' ";
        $consulta = $parqueo->consulta($sql);
        
        ?>
        <script>
            window.location.href="app.php?web=inicio";
        </script>
        <?php
        
    } else {
       
        ?>
        <script>
            alert("No tiene permisos para este parqueadero.");
            window.location.href="app.php?web=inicio";
        </script>
        <?php
        
    }
    
}

?>
