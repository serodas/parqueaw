<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>AP - Aplicacion de Parqueadero</title>
        <!--<link href="css/bootstrap.min.css" rel="stylesheet" />-->
        <link href="css/DT_bootstrap.css" rel="stylesheet" />
        <script src="js/jquery.js"></script>
        <script src="js/jquery.dataTables.js"></script>
        <script src="js/DT_bootstrap.js"></script>
        
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">
        
        <link href="css/bootstrap.css" rel="stylesheet">
        <link href="css/bootstrap-responsive.css" rel="stylesheet">
        <link href="css/estilos.css" rel="stylesheet">
        <style type="text/css">
            /* Sticky footer styles
            -------------------------------------------------- */
            html,
            body {
                height: 100%;
                /* The html and body elements cannot have any padding or margin. */
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
                background-color: #ccc;
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
                padding-top: 50px;
            }
            .container .credit {
                margin: 20px 0; text-align: center;
            }
            code {
                font-size: 80%;
            }
            
            .fijo {
                position: fixed;
                top: 70px;
                width: 20%;
            }
        </style>
        <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
          <script src="assets/js/html5shiv.js"></script>
        <![endif]-->
        
        <link rel="apple-touch-icon-precomposed" sizes="144x144" href="ico/pwa_144.png">
        <link rel="apple-touch-icon-precomposed" sizes="72X72" href="ico/pwa_72.png">
        <link rel="apple-touch-icon-precomposed" sizes="24x24" href="ico/pwa_24.png">
        <link rel="apple-touch-icon-precomposed" href="ico/pwa.png">
        <link rel="shortcut icon" href="ico/pwa_24.png">
        <script type="text/javascript" src="js/calendarDateInput.js"></script>
        <script type="text/javascript" src="js/hora.js"></script>
        <meta content="300" http-equiv="REFRESH" />
    </head>
    <body>
        <?php
//        $usr = 'ltaborda';
//        $usr = 'lhosorio';
////        $usr = 'dfgarcia';
//        $_SESSION['login'] = $usr;
//        $_SESSION['usrdb'] = $usr;
        $login = $_SESSION['login'];
        
        if ( $login != '') {
        

            include_once('clases/connDb2.php');
            $parqueo = new DB2();
            $fecha = $parqueo->obtenerFechaDb();
            $hora = $parqueo->obtenerHoraDb();
            $equipo = $parqueo->obtenerNombreEq();
            $dirip = $parqueo->obtenerIp();

            if ( isset ( $_SESSION['parqueadero'] ) )
                $parqueadero = $_SESSION['parqueadero'];
            else
                $parqueadero = 0;

            ?>

            <div id="wrap">
                <div class="navbar navbar-inverse navbar-fixed-top">
                    <div class="navbar-inner">
                        <div class="container">
                            <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                            </button>
                            <a class="brand" href="app.php?web=inicio">
                                <?php 
                                if ( isset ( $_SESSION['razsoc'] ) )
                                    echo $_SESSION['razsoc']; 
                                ?>
                            </a>
                            <div class="nav-collapse collapse">
                                <ul class="nav">
                                    <?php

                                    $sql = " select distinct m.tipo, t.nombre
                                        from menu m
                                        inner join permiso p on m.id = p.menu
                                        inner join motivo t on m.tipo = t.criterio and t.tabla = 'menu'
                                        where p.login = '%s' and m.estado = 'V' and p.estado = 'A' 
                                        order by m.tipo ";
                                    $sql = sprintf($sql, $login);
                                    $reg = $parqueo->consulta($sql);

                                    while ( $rs = odbc_fetch_array($reg) ) {

                                        // Creacion del menu principal
                                        ?>
                                        <li class="dropdown">
                                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                                <?php echo $rs[odbc_field_name($reg, 2)]; ?>
                                                <b class="caret"></b>
                                            </a>
                                            <ul class="dropdown-menu">
                                                <?php
                                                $sqlop = " select m.archivo, m.nombre, m.id, m.criterio
                                                    from menu m
                                                    inner join permiso p on m.id = p.menu
                                                    inner join motivo t on m.tipo = t.criterio and t.tabla = 'menu'
                                                    where p.login = '%s' and m.estado = 'V' and m.tipo = '%s' 
                                                        and p.estado = 'A' and m.criterio = '0'
                                                    order by m.orden asc ";
                                                $sqlopc = sprintf($sqlop, $login, $rs[odbc_field_name($reg, 1)]);
                                                $regopc = $parqueo->consulta($sqlopc);

                                                while ( $rso = odbc_fetch_array($regopc) ) {

                                                    $tipo = $rso[odbc_field_name($regopc, 4)];
                                                    if ( $rso[odbc_field_name($regopc, 1)] == 'x' ) {

                                                        ?>
                                                        <li class="dropdown-submenu">
                                                            <a tabindex="-1" href="#"><?php echo $rso[odbc_field_name($regopc, 2)]; ?></a>
                                                            <ul class="dropdown-menu">
                                                                <?php
                                                                $menuid = $rso[odbc_field_name($regopc, 3)];
                                                                $sqlsub = " select m.archivo, m.nombre 
                                                                    from menu m inner join permiso p on 
                                                                        m.id = p.menu and p.estado = 'A' and p.login = '%s' 
                                                                    where m.estado = 'V' and m.criterio = '%d' 
                                                                    order by m.orden asc ";
                                                                $sqlsubok = sprintf($sqlsub, $login, $menuid);
                                                                $regsub = $parqueo->consulta($sqlsubok);

                                                                while ( $rss = odbc_fetch_array($regsub) ) {
                                                                    ?>
                                                                    <li>
                                                                        <a href="app.php?web=<?php echo $rss[odbc_field_name($regsub, 1)]; ?>">
                                                                            <?php echo $rss[odbc_field_name($regsub, 2)]; ?>
                                                                        </a>
                                                                    </li>
                                                                    <?php
                                                                }

                                                                ?>
                                                            </ul>
                                                        </li>

                                                        <?php

                                                    } else {

                                                        ?>
                                                        <li><a href="app.php?web=<?php echo $rso[odbc_field_name($regopc, 1)]; ?>"><?php echo $rso[odbc_field_name($regopc, 2)]; ?></a></li>
                                                        <?php

                                                    }

                                                }

                                                ?>
                                            </ul>
                                        </li>
                                        <?php

                                    }

                                    $sql = " select p.id, p.nombre
                                        from parusu u inner join parqueadero p on u.parqueadero = p.id
                                        where u.login = '$login' and u.estado = 'A' ";
                                    $consulta = $parqueo->consulta($sql);
                                    if( odbc_num_rows($consulta) ) {
                                        ?>
                                        <li class="dropdown">
                                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                                <i class=""></i> Parqueaderos
                                                <b class="caret"></b>
                                            </a>
                                            <ul class="dropdown-menu">
                                                <?php
                                                while ( $res = odbc_fetch_array($consulta) ) {
                                                    echo "<li><a href=\"app.php?web=campar&par=";
                                                    echo trim($res[odbc_field_name($consulta, 1)]);
                                                    echo "\">".trim($res[odbc_field_name($consulta, 2)])."</a></li>";

                                                }                                            ?>
                                            </ul>
                                        </li>
                                        <?php

                                    }

                                    ?>

                                    <li class="dropdown">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                            Salir<b class="caret"></b>
                                        </a>
                                        <ul class="dropdown-menu">
                                            <li><a href="salir">Cerrar sesi&oacute;n</a></li>
                                        </ul>
                                    </li>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="container">
                    <?php
                    if ( isset( $_REQUEST['web'] ) ) {
                        if ( file_exists( 'web/' . $parqueo->limpiarEntradas($_REQUEST['web'], 25) . '.php' ) ) {
                            $wf = $parqueo->limpiarEntradas($_REQUEST['web'], 25);
                        } else {
                            $wf = "error404";
                        }
                    } else {
                        $wf = "inicio";
                    }

                    $parqueo->validarPermiso($wf, $login);
                    include("web/$wf.php");
                    ?>
                </div>
                <div id="push"></div>
            </div>
            <div id="footer">
                <div class="container">
                    <p class="muted credit">
                        Desarrollo realizado por el Departamento de Sistemas de Comfamiliar Risaralda<br/>
                        Ha iniciado sesi&oacute;n como <?php echo $login; ?>
                        | Usuario DB2 : <?php echo $_SESSION['usrdb']; ?>
                        | Equipo : <?php echo $equipo; ?>
                        | IP : <?php echo $dirip; ?>
                    </p>
                </div>
            </div>
            <!-- Le javascript
            ================================================== -->
            <!-- Placed at the end of the document so the pages load faster -->

            <script src="js/bootstrap-transition.js"></script>
            <script src="js/bootstrap-alert.js"></script>
            <script src="js/bootstrap-modal.js"></script>
            <script src="js/bootstrap-dropdown.js"></script>
            <script src="js/bootstrap-scrollspy.js"></script>
            <script src="js/bootstrap-tab.js"></script>
            <script src="js/bootstrap-tooltip.js"></script>
            <script src="js/bootstrap-popover.js"></script>
            <script src="js/bootstrap-button.js"></script>
            <script src="js/bootstrap-collapse.js"></script>
            <script src="js/bootstrap-carousel.js"></script>
            <script src="js/bootstrap-typeahead.js"></script>
            <?php
            
        } else {
            
            ?>
            <script type="text/javascript">
                alert('No existe un usuario logeado.');
                window.location.href='index.php';
            </script>
            <?php

        }
        ?>
    </body>
</html>
