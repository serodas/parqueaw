<?php
session_start();

if ( isset( $_POST['ingresar'] ) ) {

    require_once("/../var/www/lib/autenticacion.class.php");
    $existe = Autenticacion::traducirUsuario($_POST['us'], $_POST['pw'], 102);
    
    if ( isset ( $existe['login'] ) ) {
//    if ( !$existe['errorCode'] ) {
        
        $_SESSION['login'] = $_POST['us'];
        $_SESSION['usrdb'] = strtoupper( $existe['login'] );
        
        ?>
        <script type="text/javascript">
            window.location.href='validar.php';
        </script>
        <?php
        
    } else {
        
        ?>
        <script type="text/javascript">
            alert('Error en el usuario o la clave.');
            window.location.href='index.php';
        </script>
        <?php
        
    }
    
} else {
    
    if (isset($_GET['token'])) {
        
        // reemplazar el "72" por el código de su sistema
        $codsistema = "102";

        // se incluye librería de autenticación del servidor
        include_once '../../lib/autenticacion.class.php';

        // se obtiene el token enviado por SEC
        $token = "";
        if (isset($_GET['token']))
            $token = $_GET['token'];

        // se descifra la información del token
        $auth = new Autenticacion;
        $data = $auth->traducirToken($token);
        //var_dump($data);
        // se valida el proceso de descifrado
        $valido = false;
        if (!$data["error"]) {
            $usuario = $data["usuario"];
            $clave = $data["clave"];
            $_SESSION['login'] = $data["usuario"];

            // se valida que el usuario tenga permisos para usar el sistema
            $ret = $auth->traducirUsuario($usuario, $clave, $codsistema);
            if (!array_key_exists("errorCode", $ret) or $ret["errorCode"] == "501")
                $valido = true;
            //var_dump($ret);
        }


        if ($valido) {
            // código para un usuario validado correctamente
            session_start();
            $_SESSION["login"] = $data["usuario"];
            // header( 'Location: validar.php' );
            $_SESSION['usrdb'] = strtoupper( $existe['login'] );

            ?>
            <script type="text/javascript">
                window.location.href='validar.php';
            </script>
            <?php

        } else {

            // código para un usuario NO validado correctamente
            // header( 'Location: index.php' );
            echo "nada";
        }

    } else {
    
        ?>

        <!DOCTYPE html>
        <html lang="en">
            <head>
                <meta charset="utf-8">
                <title>Parqueadero - Web App</title>
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <meta name="description" content="">
                <meta name="author" content="">
                <link href="css/bootstrap.css" rel="stylesheet">
                <style type="text/css">
                    /* Sticky footer styles
                    -------------------------------------------------- */
                    html,
                    body {
                        height: 100%;
                        background-image: url('img/bg.png');
                        background-attachment: fixed;
                        background-repeat: repeat-x;
                        color: #000;
                        background-color: #fff;
                        font-size: 14px;
                        font-family: tahoma, verdana;
                    }
                    /* Wrapper for page content to push down footer */
                    #wrap {
                        min-height: 100%;
                        height: auto !important;
                        height: 100%;
                        /* Negative indent footer by it's height */
                        margin: 0 auto -60px;
                    }
                    /* Set the fixed height of the footer here */
                    #push,
                    #footer {
                        height: 60px;
                    }
                    #footer {
                        background-color: #f5f5f5;
                    }
                    /* Lastly, apply responsive CSS fixes as necessary */
                    @media (max-width: 767px) {
                        #footer {
                            margin-left: -20px;
                            margin-right: -20px;
                            padding-left: 20px;
                            padding-right: 20px;
                        }
                    }
                    /* Custom page CSS
                    -------------------------------------------------- */
                    /* Not required for template or sticky footer method. */
                    #wrap > .container {
                        padding-top: 10px;
                        /*background-color: #FFF;*/
                    }
                    .container .credit {
                        margin: 20px 0; text-align: center;
                    }
                    code {
                        font-size: 80%;
                    }
                </style>
                <link href="css/bootstrap-responsive.css" rel="stylesheet">
                <link href="css/estilos.css" rel="stylesheet">

                <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
                <!--[if lt IE 9]>
                  <script src="../assets/js/html5shiv.js"></script>
                <![endif]-->

                <link rel="apple-touch-icon-precomposed" sizes="144x144" href="ico/aa-144.png">
                <link rel="apple-touch-icon-precomposed" sizes="72x72" href="ico/aa-72.png">
                <link rel="apple-touch-icon-precomposed" sizes="24X24" href="ico/aa-24.png">
                <link rel="shortcut icon" href="ico/favicon.png">
                <script type="text/javascript" src="captcha.js"></script>
            </head>
            <body>
                <div id="wrap">
                    <div class="container">
                        <div class="navbar">
                            <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                            </button>
                            <div class="navbar-inner">
                                <div class="container">
                                    <a class="brand" href="index.php">Parqueadero - Aplicaci&oacute;n Web</a>
                                    <div class="nav-collapse collapse">
                                        <form class="navbar-form pull-right" action="" method="POST" >
                                            <div class="input-prepend">
                                                <span class="add-on">
                                                    <span class="icon-user"></span>
                                                </span>
                                                <input class="input-small" name="us" type="text" placeholder="Usuario">
                                            </div>
                                            <div class="input-prepend">
                                                <span class="add-on">
                                                    <span class="icon-eye-close"></span>
                                                </span>
                                                <input class="input-small" name="pw" type="password" placeholder="Clave">
                                            </div>
                                            <div class="input-prepend">
                                                <button type="submit" name="ingresar" class="btn btn-primary">...</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="container-fluid">
                            <div class="row-fluid">
                                <div class="span5">
                                    <img src="img/logoComfa.png" class="span12" />
                                </div>
                                <div class="span7" style="font-size:16px; font-weight: bold;">
                                    <p>&nbsp;</p>
                                    <h1>Parqueadero Web</h1>
                                    <hr/>
                                    <p>&nbsp;</p>
                                    <p>
                                        Esta es una aplicaci&oacute;n Web creada para la administraci&oacute;n y control de los
                                        parqueaderos
                                    </p>
                                    <p>&nbsp;</p>
                                    <p>
                                        La aplicaci&oacute;n est&aacute; desarrollada bajo el lenguaje de programaci&oacute;n php
                                        y con una interface BOOTSTRAP (basada en Twitter), con dise&ntilde;o adaptable a cualquier
                                        dispositivo movil.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="push"></div>
                </div>
                <div id="footer">
                    <div class="container">
                        <p class="muted credit">
                            Desarrollo realizado por el Departamento de Sistemas de Comfamiliar Risaralda
                        </p>
                    </div>
                </div>
                <script src="js/jquery.js"></script>
                <script src="js/bootstrap-collapse.js"></script>
            </body>
        </html>
    <?php
    }
}
?>
