<?php
session_start();

$login = trim($_SESSION['login']);
//echo $login . " ***";
include_once('clases/connDb2.php');
$parqueo = new DB2();

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>AP - Aplicacion de Parqueadero</title>
        <meta name="author" content="">
        <link rel="apple-touch-icon-precomposed" sizes="144x144" href="ico/pwa_144.png">
        <link rel="apple-touch-icon-precomposed" sizes="72X72" href="ico/pwa_72.png">
        <link rel="apple-touch-icon-precomposed" sizes="24x24" href="ico/pwa_24.png">
        <link rel="apple-touch-icon-precomposed" href="ico/pwa.png">
        <link rel="shortcut icon" href="ico/pwa_24.png">
    </head>
    <body>
        <?php
        
        if ( $login != '' ) {

            // Validar si el usuario tiene un parqueadero ligado a sus permisos.
            $sql = " select count(*) from parusu where login = '$login' and estado = 'A' ";
            $sql = sprintf($sql, $login);
            $reg = $parqueo->consulta($sql);
            $rs = odbc_fetch_array($reg);

            if ( $rs[odbc_field_name($reg, 1)] == 1 ) {

                $sql = " select parqueadero from parusu 
                    where login = '$login' and estado = 'A' ";
                $sql = sprintf($sql, $login);
                $reg = $parqueo->consulta($sql);
                $rs = odbc_fetch_array($reg);
                $par = $rs[odbc_field_name($reg, 1)];
                ?>
                <script>
                    window.location.href="app.php?web=campar&par=<?php echo $par; ?>";
                </script>
                <?php

            }
            ?>
            <script>
                window.location.href="app.php?web=inicio";
            </script>
            <?php
        
        } else {
            
            ?>
            <script>
                alert("Debe iniciar sesion.");
                window.location.href="index.php";
            </script>
            <?php
            
        }
        ?>
    </body>
</html>