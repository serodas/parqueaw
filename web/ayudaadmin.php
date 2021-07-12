<h1>
    Ayudas para el men&uacute; de Administraci&oacute;n
</h1>
<hr/>
<div class="tabbable tabs-left">
    <ul class="nav nav-tabs" id="myTab">
        <li class="active"><a href="#vehiculo">Vehículo</a></li>
        <li><a href="#parqueade">Parqueaderos</a></li>
        <li><a href="#ponal">Solicitud Policia Nacional</a></li>
        <li><a href="#tipovehi">Tipo de vehiculo</a></li>
        <li><a href="#tipousua">Tipo de usuario</a></li>
        <li><a href="#estavehi">Estado del vehiculo</a></li>
        <li><a href="#colorveh">Colores</a></li>
        <li><a href="#festivos">D&iacute;as festivos</a></li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="vehiculo">
            <h3>
                vehículo
            </h3>
            <p>
                Contiene el listado de los vehículo que han sido registrados en el sistema, bien sean empleados, 
                particulares u otro tipo pre-definido.
            </p>
            <pre>
select PLACA,NOMBRE,TIPOUSUA,TIPOVEHI,CANUMDOCUM,COLOR,ESTAVEHI,ESTADO,USUARIO,FECHA,HORA,EQUIPO,DIRIP 
from VEHICULOS</pre>
            <ul>
                <li>
                    <strong>Placa</strong> <em>(PLACA)</em>: campo único, es el identificador de la tabla.
                </li>
                <li>
                    <strong>Nombre</strong> <em>(NOMBRE)</em>: del dueño del vehículo. Si está registrado en SIGHNO, 
                    el sistema lo traerá automáticamente. 
                </li>
                <li>
                    <a href="#" data-toggle="tooltip" title="Tipos de usuarios: Alojamientos, consecionario, empleado, invitado, proveedor, usuario con sanción, evento, particular." id="vehtipusu">
                    <strong>Tipo de usuario</strong></a> <em>(TIPOUSUA)</em>: define el tipo de usuario registrado en el sistema
                </li>
                <li>
                    <a href="#" data-toggle="tooltip" title="Tipos de vehiculos: Carro, moto" id="vehtipveh">
                    <strong>Tipo de vehículo</strong></a> <em>(TIPOVEHI)</em>: define el tipo de vehículo registrado en el sistema
                </li>
                <li>
                    <strong>Número de identidad</strong> <em>(CANUMDOCUM)</em>: donde se registra el número de identidad del propietario del vehículo
                </li>
                <li>
                    <a href="#" data-toggle="tooltip" title="Tipos de vehiculos: Blanco, amarillo, rojo, azul, negro, verde, vinotinto, morado y gris" id="vehtipcol">
                    <strong>Color</strong></a> <em>(COLOR)</em>: define el color del vehículo
                </li>
                <li>
                    <a href="#" data-toggle="tooltip" title="Estado de vehículo: En condiciones óptimas, regular" id="vehestveh">
                    <strong>Estado del vehículo</strong></a> <em>(ESTAVEHI)</em>: define el estado de ingreso del vehículo, es un estado por defecto.
                </li>
                <li>
                    <strong>Auditoria</strong> <em>(ESTADO, USUARIO, FECHA, HORA, EQUIPO, DIRIP)</em>: Son los campos
                    de auditoria por defecto para todas las tablas.
                </li>
            </ul>
        </div>
        <div class="tab-pane" id="parqueade">
            <h3>
                Parqueadero
            </h3>
            <p>
                Contiene el listado de los parqueadero creados en el sistema, se pueden crear nuevos y editar los
                existentes. Los campos que contiene la tabla son:
            </p>
            <pre>select id, nombre, cupocar, cupomot, ascodarea, cacodcenat, tiempo  from parqueadero</pre>
            <ul>
                <li>
                    <strong>Id</strong> <em>(ID)</em>: Es el campo llave de la tabla, autoincremento y no permite ser modificado
                </li>
                <li>
                    <strong>Nombre</strong> <em>(NOMBRE)</em>: Es la descripcion o nombre del parqueadero, campo alfanumerico de 
                    45 caracteres.
                </li>
                <li>
                    <strong>Cupo para carros</strong> <em>(CUPOCAR)</em>: es la cantidad de cupos disponibles en el
                    parqueadero los para los vehiculos de tipo carro, el campo es decimal de 5 caracteres.
                </li>
                <li>
                    <strong>Cupo para motos</strong> <em>(CUPOMOT)</em>: es la cantidad de cupos disponibles en el
                    parqueadero los para los vehiculos de tipo moto, el campo es decimal de 5 caracteres.
                </li>
                <li>
                    <strong>Area</strong> <em>(ASCODAREA)</em>: Hace referencia al area de servicio del parqueadero, 
                    esta informacion viene de la base de datos de MUF.<br/>
                    <em>SELECT ASCODAREA, ASDESAREA FROM BDMUF.TBBDARESER WHERE ASCODAREA IN ('0', '3', 'G') AND ASCODEST = 'A'</em>
                </li>
                <li>
                    <strong>Centro de atencion</strong> <em>(CACODCENAT)</em>: Hace referencia al proceso de servicio del parqueadero, 
                    esta informacion viene de la base de datos de MUF.<br/>
                    <em>SELECT CACODCENAT, CADESCENAT FROM BDMUF.TBBDCENATE WHERE CACODCENAT IN ('PQ1', 'PQ2', '301') AND CACODESTAD = 'A'</em>
                </li>
                <li>
                    <strong>Tiempo</strong> (duracion) <em>(TIEMPO)</em>: Es el tiempo que tiene disponible el vehiculo para 
                    salir del parqueadero, despues de realizar el pago en caja.
                </li>
                <li>
                    <strong>Auditoria</strong> <em>(ESTADO, USUARIO, FECHA, HORA, EQUIPO, DIRIP)</em>: Son los campos
                    de auditoria por defecto para todas las tablas.
                </li>
            </ul>
            
            <hr/>
            <h3>
                Horas de ingreso y salida por parqueadero
            </h3>
            <p>
                Contiene la informacion de las horas de atencion en cada parqueadero, con esta informacion el sistema 
                sabra si cobra o no el parqueo.
            </p>
            <p>
                <em>select id, parqueadero, dia, inicio, fin from parquehora</em>
            </p>
            <ul>
                <li>
                    <strong>Id</strong> <em>(ID)</em>: Es el campo llave de la tabla, autoincremento y no permite ser modificado
                </li>
                <li>
                    <strong>Parqueadero</strong> <em>(PARQUEADERO)</em>: Es la llave foranea de la tabla PARQUEADERO-ID.
                </li>
                <li>
                    <strong>Dia de la semana</strong> <em>(DIA)</em>: Es el dia que se va a registrar, empieza desde el 
                    domingo 0 hasta el sabado 6, siendo el dia festivo el numero 7
                </li>
                <li>
                    <strong>Inicio de jornada</strong> <em>(INICIO)</em>: Es la hora de inicio de la jornada de trabajo
                    del dia seleccionado.
                </li>
                <li>
                    <strong>Fin de jornada</strong> <em>(FIN)</em>: Es la hora de finalizacion de la jornada de trabajo
                    del dia seleccionado.
                </li>
                <li>
                    <strong>Auditoria</strong> <em>(ESTADO, USUARIO, FECHA, HORA, EQUIPO, DIRIP)</em>: Son los campos
                    de auditoria por defecto para todas las tablas.
                </li>
            </ul>

        </div>
        <div class="tab-pane" id="ponal">
            <h3>
                Solicitud Policia Nacional
            </h3>
            <p>
                Contiene el listado de los vehículo que han sido reportador por la Policia Nacional y se requiere
                aviso al mismo en el momento que ingresen al sistema.
            </p>
            <pre>
select PLACA,CANUMDOCUM,NOMBRE,TIPOVEHI,COLOR,ESTADO,USUARIO,FECHA,HORA,EQUIPO,DIRIP 
from vehiponal</pre>
            <ul>
                <li>
                    <strong>Placa</strong> <em>(PLACA)</em>: campo único, es el identificador de la tabla.
                </li>
                <li>
                    <strong>Número de identidad</strong> <em>(CANUMDOCUM)</em>: donde se registra el número de identidad del propietario del vehículo
                </li>
                <li>
                    <strong>Nombre</strong> <em>(NOMBRE)</em>: del dueño del vehículo. Si está registrado en SIGHNO, 
                    el sistema lo traerá automáticamente. 
                </li>
                <li>
                    <a href="#" data-toggle="tooltip" title="Tipos de vehiculos: Carro, moto" id="pontipveh">
                    <strong>Tipo de vehículo</strong></a> <em>(TIPOVEHI)</em>: define el tipo de vehículo registrado en el sistema
                </li>
                <li>
                    <a href="#" data-toggle="tooltip" title="Tipos de vehiculos: Blanco, amarillo, rojo, azul, negro, verde, vinotinto, morado y gris" id="pontipcol">
                    <strong>Color</strong></a> <em>(COLOR)</em>: define el color del vehículo
                </li>
                <li>
                    <strong>Auditoria</strong> <em>(ESTADO, USUARIO, FECHA, HORA, EQUIPO, DIRIP)</em>: Son los campos
                    de auditoria por defecto para todas las tablas.
                </li>
            </ul>
        </div>
        <div class="tab-pane" id="tipovehi">
            <h3>
                Tipo de vehiculo
            </h3>
            <p>
                Esta informaci&oacute;n se almacena en la tabla <em>MOTIVO</em>, y se debe tener en cuenta el campo
                <em>TABLA igual a 'tipovehi'</em>. La tabla contiene los siguientes campos:
            </p>
            <pre>select id, nombre, criterio from motivo where tabla = 'tipovehi'</pre>
            <ul>
                <li>
                    <strong>Id</strong> <em>(ID)</em>: Es el campo llave de la tabla, autoincremento y no permite ser modificado
                </li>
                <li>
                    <strong>Tabla referencia</strong> <em>(TABLA)</em>: Es el campo con el cual se hace la relacion.
                    Este campo no se muestra en el mantenimiento. Dese ser siempre <em>tipovehi</em>
                </li>
                <li>
                    <strong>Nombre</strong> <em>(NOMBRE)</em>: Es la descripcion o nombre del motivo, campo alfanumerico de 
                    45 caracteres.
                </li>
                <li>
                    <strong>Criterio</strong> <em>(CRITERIO)</em>: Es algo asi como el campo llave de la tabla, no se 
                    debe repetir, es el valor que guarda en las tablas que tienen este campo como foraneo. Es un 
                    campo alfanumerico de 45 caracteres.
                </li>
                <li>
                    <strong>Auditoria</strong> <em>(ESTADO, USUARIO, FECHA, HORA, EQUIPO, DIRIP)</em>: Son los campos
                    de auditoria por defecto para todas las tablas.
                </li>
            </ul>
        </div>
        <div class="tab-pane" id="tipousua">
            <h3>
                Tipo de usuario
            </h3>
            <p>
                Esta informaci&oacute;n se almacena en la tabla <em>MOTIVO</em>, y se debe tener en cuenta el campo
                <em>TABLA igual a 'tipousua'</em>. La tabla contiene los siguientes campos:
            </p>
            <pre>select id, nombre, criterio from motivo where tabla = 'tipousua'</pre>
            <ul>
                <li>
                    <strong>Id</strong> <em>(ID)</em>: Es el campo llave de la tabla, autoincremento y no permite ser modificado
                </li>
                <li>
                    <strong>Tabla referencia</strong> <em>(TABLA)</em>: Es el campo con el cual se hace la relacion.
                    Este campo no se muestra en el mantenimiento. Dese ser siempre <em>tipousua</em>
                </li>
                <li>
                    <strong>Nombre</strong> <em>(NOMBRE)</em>: Es la descripcion o nombre del motivo, campo alfanumerico de 
                    45 caracteres.
                </li>
                <li>
                    <strong>Criterio</strong> <em>(CRITERIO)</em>: Es algo asi como el campo llave de la tabla, no se 
                    debe repetir, es el valor que guarda en las tablas que tienen este campo como foraneo. Es un 
                    campo alfanumerico de 45 caracteres.
                </li>
                <li>
                    <strong>Auditoria</strong> <em>(ESTADO, USUARIO, FECHA, HORA, EQUIPO, DIRIP)</em>: Son los campos
                    de auditoria por defecto para todas las tablas.
                </li>
            </ul>
        </div>
        <div class="tab-pane" id="estavehi">
            <h3>
                Estado del vehiculo
            </h3>
            <p>
                Esta informaci&oacute;n se almacena en la tabla <em>MOTIVO</em>, y se debe tener en cuenta el campo
                <em>TABLA igual a 'estavehi'</em>. La tabla contiene los siguientes campos:
            </p>
            <pre>select id, nombre, criterio from motivo where tabla = 'estavehi'</pre>
            <ul>
                <li>
                    <strong>Id</strong> <em>(ID)</em>: Es el campo llave de la tabla, autoincremento y no permite ser modificado
                </li>
                <li>
                    <strong>Tabla referencia</strong> <em>(TABLA)</em>: Es el campo con el cual se hace la relacion.
                    Este campo no se muestra en el mantenimiento. Dese ser siempre <em>estavehi</em>
                </li>
                <li>
                    <strong>Nombre</strong> <em>(NOMBRE)</em>: Es la descripcion o nombre del motivo, campo alfanumerico de 
                    45 caracteres.
                </li>
                <li>
                    <strong>Criterio</strong> <em>(CRITERIO)</em>: Es algo asi como el campo llave de la tabla, no se 
                    debe repetir, es el valor que guarda en las tablas que tienen este campo como foraneo. Es un 
                    campo alfanumerico de 45 caracteres.
                </li>
                <li>
                    <strong>Auditoria</strong> <em>(ESTADO, USUARIO, FECHA, HORA, EQUIPO, DIRIP)</em>: Son los campos
                    de auditoria por defecto para todas las tablas.
                </li>
            </ul>
        </div>
        <div class="tab-pane" id="colorveh">
            <h3>
                Colores
            </h3>
            <p>
                Esta informaci&oacute;n se almacena en la tabla <em>MOTIVO</em>, y se debe tener en cuenta el campo
                <em>TABLA igual a 'color'</em>. La tabla contiene los siguientes campos:
            </p>
            <pre>select id, nombre, criterio from motivo where tabla = 'color'</pre>
            <ul>
                <li>
                    <strong>Id</strong> <em>(ID)</em>: Es el campo llave de la tabla, autoincremento y no permite ser modificado
                </li>
                <li>
                    <strong>Tabla referencia</strong> <em>(TABLA)</em>: Es el campo con el cual se hace la relacion.
                    Este campo no se muestra en el mantenimiento. Dese ser siempre <em>color</em>
                </li>
                <li>
                    <strong>Nombre</strong> <em>(NOMBRE)</em>: Es la descripcion o nombre del motivo, campo alfanumerico de 
                    45 caracteres.
                </li>
                <li>
                    <strong>Criterio</strong> <em>(CRITERIO)</em>: Es el color que queremos dar a esta descripcion, la
                    pagina de mantenimiento tiene un SelectorColorPicker (Ayuda en JavaScript) para seleccionar un color.
                    La informacion almacenada es el valor hexadecimal del color. Es un campo alfanumerico de 45 caracteres.
                </li>
                <li>
                    <strong>Auditoria</strong> <em>(ESTADO, USUARIO, FECHA, HORA, EQUIPO, DIRIP)</em>: Son los campos
                    de auditoria por defecto para todas las tablas.
                </li>
            </ul>
        </div>
        <div class="tab-pane" id="festivos">
            <h3>
                D&iacute;as festivos
            </h3>
            <p>
                Esta informaci&oacute;n se almacena en la tabla <em>FESTIVO</em>, y se debe tener en cuenta el campo
                <em>FECFES</em> que hace referencia a la fecha festiva. La tabla contiene los siguientes campos:
            </p>
            <pre>select fecfes from festivo</pre>
            <ul>
                <li>
                    <strong>Fecha festiva</strong> <em>(FECFES)</em>: Es el campo llave de la tabla, es la fecha que queremos
                    ver como festiva.
                </li>
                <li>
                    <strong>Auditoria</strong> <em>(ESTADO, USUARIO, FECHA, HORA, EQUIPO, DIRIP)</em>: Son los campos
                    de auditoria por defecto para todas las tablas.
                </li>
            </ul>
        </div>
    </div>
</div>
<script>
    $('#myTab a').click(function(e) {
        e.preventDefault();
        $(this).tab('show');
    });
    $(function () {
        $('#vehtipusu').tooltip();
        $('#vehtipveh').tooltip();
        $('#vehtipcol').tooltip();
        $('#vehestveh').tooltip();
        $('#pontipveh').tooltip();
        $('#pontipcol').tooltip();
    });
</script>