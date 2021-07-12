<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
    </head>
    <!--<body>-->
    <body onload="javascript:alCargar()">
        <iframe src="../web/mvtoingresoimp.php?mvtoid=<?php echo $_GET['mvtoid']; ?>" width="400" height="500" ></iframe>
        
        <script>
            function alCargar()
            {
                setTimeout( "cerrarVentana()", 3000 );
            }

            function cerrarVentana()
            {
                window.close();
            }

        </script>
        
    </body>
</html>
