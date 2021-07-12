<h1>
    Ayudas para el men&uacute; de Opciones
</h1>
<hr/>
<div class="tabbable tabs-left">
    <ul class="nav nav-tabs" id="myTab">
        <li class="active"><a href="#inicio">Inicio</a></li>
        <li><a href="#entradas">Entradas</a></li>
        <li><a href="#salidas">Salidas</a></li>
        <li><a href="#correccion">Correción de ingresos</a></li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="inicio">
            <h3>
                Inicio
            </h3>
            <p>
                Es la pantalla de bienvenida al sistema de información.
            </p>
            <div class="row-fluid">
                <div class="span7">
                    <p>&nbsp;</p>
                    <h1>Control de Parqueadero</h1>
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
                <div class="span5">
                    <img src="img/aplicacion.jpg" class="span12" />
                </div>
            </div>
        </div>
        <div class="tab-pane" id="entradas">
            <h3>
                Entradas
            </h3>
            <p>
                Es la pantalla donde se registran las entradas al parqueadero. Esta pantalla sólo funciona si tiene un 
                parqueadero seleccionado, si no es así, la página le muestra un mensaje informando que debe seleccionar 
                un parqueadero para realizar los respectivos registros de ingresos.
            </p>
            <pre>SELECT ID, PARQUEADERO, PLACA, TIPOUSUA, TIPOVEHI, ESTAVEHI, FECING, HORA
FROM MOVIMIENTO</pre>
            <ul>
                <li>
                    <strong>Id</strong><em>(ID)</em> : es el identificador del registro. Llave principal de la tabla
                </li>
                <li>
                    <strong>Parqueadero</strong><em>(PARQUEADERO)</em> : es el código del parqueadero.
                </li>
                <li>
                    <strong>Placa</strong><em>(PLACA)</em> : es la placa del vehículo que ingresó al sistema.
                </li>
                <li>
                    <strong>Tipo de usuario</strong><em>(TIPOUSUA)</em> : es el tipo de usuario registrado en el movimiento.
                </li>
                <li>
                    <strong>Tipo de vehículo</strong><em>(TIPOVEHI)</em> : es el tipo de vehículo registrado en el movimiento.
                </li>
                <li>
                    <strong>Estado del vehículo</strong><em>(ESTAVEHI)</em> : es el estado del vehículo registrado en el movimiento.
                </li>
                <li>
                    <strong>Fecha de ingreso</strong><em>(FECING)</em> : es la fecha de ingreso del vehículo en el sistema.
                </li>
                <li>
                    <strong>Hora de ingreso</strong><em>(HORING)</em> : es la hora de ingreso del vehículo en el sistema.
                </li>
                <li>
                    <strong></strong><em>(PLACAINI)</em> : es la placa de ingreso, en caso de quedar mal regsitrada y 
                    se debe cambiar (desde Opciones/Corección de ingresos), se le pueda realizar el respectivo seguimiento
                    al registro en el sistema.
                </li>
                <li>
                    <strong>Auditoria</strong> <em>(ESTADO, USUARIO, FECHA, HORA, EQUIPO, DIRIP)</em>: Son los campos
                    de auditoria por defecto para todas las tablas.
                </li>
            </ul>
        </div>
        <div class="tab-pane" id="salidas">
            <h3>
                Salidas
            </h3>
            <p>
                Es la pantalla donde se registran las salidas de parqueadero. Esta pantalla sólo funciona si tiene un 
                parqueadero seleccionado, si no es así, la página le muestra un mensaje informando que debe seleccionar 
                un parqueadero para realizar los respectivos registros de salidas.
            </p>
            <pre>SELECT 
ID, PARQUEADERO, PLACA, MRCODCONS, TIPOUSUA, TIPOVEHI, ESTAVEHI, FECING, HORING, FECFAC, HORFAC, FECSAL, HORSAL, DURDIA, DURHOR, ESTADO, USUARIO, FECHA, HORA, EQUIPO, DIRIP, PLACAINI, USUFAC, EQUFAC, DIPFAC, USUSAL, EQUSAL, DIPSAL
FROM MOVIMIENTO</pre>
            <ul>
                <li>
                    <strong>Id</strong><em>(ID)</em> : es el identificador del registro. Llave principal de la tabla
                </li>
                <li>
                    <strong>Parqueadero</strong><em>(PARQUEADERO)</em> : es el código del parqueadero.
                </li>
                <li>
                    <strong>Placa</strong><em>(PLACA)</em> : es la placa del vehículo que ingresó al sistema.
                </li>
                <li>
                    <strong>Tipo de usuario</strong><em>(TIPOUSUA)</em> : es el tipo de usuario registrado en el movimiento.
                </li>
                <li>
                    <strong>Tipo de vehículo</strong><em>(TIPOVEHI)</em> : es el tipo de vehículo registrado en el movimiento.
                </li>
                <li>
                    <strong>Estado del vehículo</strong><em>(ESTAVEHI)</em> : es el estado del vehículo registrado en el movimiento.
                </li>
                <li>
                    <strong>Fecha de ingreso</strong><em>(FECING)</em> : es la fecha de ingreso del vehículo en el sistema.
                </li>
                <li>
                    <strong>Hora de ingreso</strong><em>(HORING)</em> : es la hora de ingreso del vehículo en el sistema.
                </li>
                <li>
                    <strong>Fecha de factura</strong><em>(FECFAC)</em> : es la fecha que se factura el registro, este proceso se 
                    realiza desde MUF.
                </li>
                <li>
                    <strong>Hora de factura</strong><em>(HORFAC)</em> : es la hora que se factura el registro, este proceso se 
                    realiza desde MUF.
                </li>
                <li>
                    <strong>Fecha de salida</strong><em>(FECSAL)</em> : es la fecha de salida del vehículo.
                </li>
                <li>
                    <strong>Hora de salida</strong><em>(HORSAL)</em> : es la hora de salida del vehículo.
                </li>
                <li>
                    <strong>Duración en días</strong><em>(DURDIA)</em> : es la cantidad de días que dura el vehículo en el parqueadero.
                    Este proceso se realiza desde MUF
                </li>
                <li>
                    <strong>Duración en horas</strong><em>(DURHOR)</em> : es la cantidad de horas que dura el vehículo en el parqueadero.
                    Este proceso se realiza desde MUF
                </li>
                <li>
                    <strong>Auditoria</strong> <em>(ESTADO, USUARIO, FECHA, HORA, EQUIPO, DIRIP)</em>: Son los campos
                    de auditoria por defecto para todas las tablas.
                </li>
                <li>
                    <strong></strong><em>(PLACAINI)</em> : es la placa de ingreso, en caso de quedar mal regsitrada y 
                    se debe cambiar (desde Opciones/Corección de ingresos), se le pueda realizar el respectivo seguimiento
                    al registro en el sistema.
                </li>
                <li>
                    <strong>Usuario que factura</strong><em>(USUFAC)</em> : es el nombre del usuario del sistema que realiza la 
                    factura. Este proceso se realiza desde MUF
                </li>
                <li>
                    <strong>Equipo donde se factura</strong><em>(EQUFAC)</em> :  es el nombre del equipo de sistemas donde se realiza la 
                    factura. Este proceso se realiza desde MUF
                </li>
                <li>
                    <strong>Dirección IP donde se factura</strong><em>(DIPFAC)</em> : es la dirección IP del equipo de sistemas donde se realiza la 
                    factura. Este proceso se realiza desde MUF
                </li>
                <li>
                    <strong>Usuario que da salida</strong><em>(USUSAL)</em> : es el registro del usuario que le da salida al vehículo
                    en el sistema de información.
                </li>
                <li>
                    <strong>Equipo donde se da salida</strong><em>(EQUSAL)</em> : es el nombre del equipo de cómputo donde se
                    marca la salida del vehículo.
                </li>
                <li>
                    <strong>Dirección IP de salida</strong><em>(DIPSAL)</em> : es la dirección IP del equipo donde se registra la 
                    salida del vehículo.
                </li>
            </ul>
        </div>
        <div class="tab-pane" id="correccion">
            <h3>
                Corrección de ingresos
            </h3>
            <p>
                Esta pantalla ha sido diseñada para realizar las correcciones necesarias en el sistema de información en
                lo que corresponde a la tabla <em>MOVIMIENTO</em>.
            </p>
            <ul>
                <li>
                    <strong>Placa</strong> <em>PLACA</em> : es la nueva placa que se registrará en el sistema, esto 
                    cambia el campo "PLACA" de la tabla "MOVIMIENTO" en la base de datos. Para poder hacer la respectiva
                    trasabilidad de la información, está el campo "PLACAINI" la cual no se modifica.
                </li>
                <li>
                    <strong>Tipo de usuario</strong> <em>(TIPOUSUA)</em> : es para modificar el tipo de usuario
                    registrado inicialmente en el sistema.
                </li>
                <li>
                    <strong>Tipo de vehículo</strong> <em>(TIPOVEHI)</em> : es para modificar el tipo de vehículo
                    registrado inicialmente en el sistema.
                </li>
                <li>
                    <strong>Autorizar salida</strong> <em></em> : es para autoriza la salida al vehículo, sin importar
                    el tipo. Lo que realiza es que al campo "MRCODONS" lo pone con valor "1" y así el sistema le
                    permitirá marcar la salida.
                </li>
            </ul>
            <?php
            $diaAtras = $parqueo->diaSiguiente($fecha, "-15");
            ?>
            <p class="alert alert-error">
                Estos registros son demasiados, deben ser actualizados los ingresos con vencimiento de más de 15 días
            </p>
            <p>
                Para ello, ejecute la siguiente sentencia SQL<br/>
            </p>
            <pre>UPDATE PARQUEAW.MOVIMIENTO SET 
    FECFAC = FECING, HORFAC = HORING, FECSAL = FECING, HORSAL = HORING, USUFAC = 'Automatico', 
    DIPFAC = '10.25.2.1', USUSAL = 'Automatico', EQUSAL = 'Servidor', DIPSAL = '10.25.2.1'
WHERE MRCODCONS = 0 AND FECING < <?php echo $diaAtras; ?> AND FECFAC = 0  </pre>
            <p>
                Y finalmente, ejecute la siguiente sentencia SQL<br/>
            </p>
            <pre>UPDATE PARQUEAW.MOVIMIENTO SET 
    FECFAC = FECING, HORFAC = HORING, FECSAL = FECING, HORSAL = HORING, USUFAC = 'Automatico', 
    DIPFAC = '10.25.2.1', USUSAL = 'Automatico', EQUSAL = 'Servidor', DIPSAL = '10.25.2.1'
WHERE MRCODCONS = 0 AND FECING < <?php echo $diaAtras; ?> AND FECSAL = 0  </pre>
        </div>
    </div>
</div>
<script>
    
    $('#myTab a').click(function(e) {
        e.preventDefault();
        $(this).tab('show');
    });
    
</script>