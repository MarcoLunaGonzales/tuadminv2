<?php

require("conexion.inc");


$sql = "SELECT IFNULL(MAX(nro_correlativo)+1,1) from ordenes_compra where cod_estado = 1 order by codigo desc";
$resp = mysqli_query($enlaceCon,$sql);
$nro_correlativo=mysqli_result($resp,0,0);

// Obtén los valores enviados desde el frontend
$cod_proveedor      = $_POST['cod_proveedor'];
$nro_correlativo    = $nro_correlativo;
$fecha              = date('Y-m-d');
$observaciones      = $_POST['observaciones'];
$cod_estado         = 1;
$cod_tipopago       = 4;
$nro_documento      = $_POST['nro_documento'];
$monto_orden        = $_POST['monto_orden'];
$monto_cancelado    = 0;
$dias_credito       = $_POST['dias_credito'];
$fecha_vencimiento  = '';
$fecha_factura_proveedor = $_POST['fecha_factura_proveedor'];

// Inserta el registro en la tabla ordenes_compra
$sql = "INSERT INTO ordenes_compra (cod_proveedor, nro_correlativo, fecha, observaciones, cod_estado, cod_tipopago, nro_documento, monto_orden, monto_cancelado, dias_credito, fecha_vencimiento, fecha_factura_proveedor) VALUES ('$cod_proveedor', '$nro_correlativo', '$fecha', '$observaciones', '$cod_estado', '$cod_tipopago', '$nro_documento', '$monto_orden', '$monto_cancelado', '$dias_credito', '$fecha_vencimiento', '$fecha_factura_proveedor')";

$sql_inserta=mysqli_query($enlaceCon,$sql);

if ($sql_inserta) {
    $response = array(
        "message" => "Registro correcto",
        "status"  => true
    );
} else {
    $response = array(
        "message" => "Ocurrió un error en el registro",
        "status"  => false
    );
}
ob_clean();
header('Content-Type: application/json');
echo json_encode($response);
exit;