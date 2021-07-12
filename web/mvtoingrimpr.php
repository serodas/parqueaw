<!DOCTYPE html>
<html lang="en">
    <head>
        <link rel="stylesheet" type="text/css" href="../css/imprimir.css" media="print" />
    </head>
    <body>
         onload="imprimir();"
        <?php
        session_start();

        if ( $_SESSION['mvtoid'] > 0 ) {

            include_once('../clases/connDb2.php');
            $parqueo = new DB2();

            $mvtoid = $_SESSION['mvtoid'];

            $parqueadero = $_SESSION['parqueadero'];
            $sql1 = " select nombre
                from parqueadero p 
                where p.id = $parqueadero and p.estado = 'A' ";
            //$reg1 = $parqueo->consulta($sql1);
            $reg1 = odbc_exec($parqueo->conexion, $sql1);
            if( odbc_num_rows( $reg1 ) ) {

                $res1 = odbc_fetch_array($reg1);
                echo $_SESSION['nombre'] = $res1[odbc_field_name($reg1, 1)];
                echo "<p>Aca vamos a impirmir una informacion toda raro, ojala funcione porque ya llevamos mucho tiempo con 
                    el mismo proceso y nada de nada.... nos vemos.... </p>";
                ?>
                <img src="../img/aplicacion.jpg" />
                <?php
                echo '<script type="text/javascript">window.print();</script>'; 

            }

        } else {

            echo "Error, esta accediendo de manera errada a la aplicacion.";

        }

        ?>
    </body>
</html>