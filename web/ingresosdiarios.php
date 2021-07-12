<script>
    $(document).ready(function() {
        $('#registros').dataTable({
            "bPaginate": true,
            "bJQueryUI": true,
            "iDisplayLength": 10,
            "aaSorting": [],
            "bStateSave": true
        });
    });
</script>
<?php
if ( isset ( $_POST['informe'] ) ) :
    $fecha_ingreso = $_POST['fecha_ingreso'];
else:
    $fecha_ingreso = $fecha;
endif;
?>

<form action="" method="POST" class="span12">
    <table>
        <tr>
            <td>Fecha ingreso</td>
            <td><script>DateInput('fecha_ingreso', true, 'YYYYMMDD', '<?php echo $fecha_ingreso; ?>')</script></td>
            <td>
                <input type="submit" class="btn btn-primary" name="informe" value="Buscar" />
            </td>
        </tr>
    </table>
</form>
<h3>
    Ingresos consolidado por cajero y centro
</h3>
<?php
    $sql = "SELECT M.PLACA, TU.NOMBRE AS TIPOUSUA, M.MRCODCONS, M.TIPOVEHI, 
            M.USUARIO, M.FECING, M.HORING, M.USUFAC, M.FECFAC, M.HORFAC, M.USUSAL, M.FECSAL, M.HORSAL
        FROM PARQUEAW.MOVIMIENTO M
            INNER JOIN MOTIVO TU ON M.TIPOUSUA = TU.CRITERIO AND TU.TABLA = 'tipousua'
        WHERE M.PARQUEADERO = 1 AND M.TIPOUSUA IN ('X', 'S', 'A', 'V') AND M.FECING = $fecha_ingreso AND MRCODCONS < 0 
            AND M.HORING > 60000 AND M.HORSAL < 210000 AND (M.HORSAL - M.HORING ) > 800 ";
    $encabezados = "Placa,Usuario,Recibo,Vehiculo,Usuario ingresa,Fecha ingreso,Hora ingreso,Usuario factura,Fecha factura,Hora factura,Usuario salida,Fecha salida,Hora salida";
    $parqueo->tabla($sql, $encabezados, '', 'registros');
