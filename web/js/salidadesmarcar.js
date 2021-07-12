function marcarDesmarcar(parqueadero, placa) {
    $.post('../clases/salidadesmarcar.php', {'parqueadero':parqueadero, 'placa':placa});
}
