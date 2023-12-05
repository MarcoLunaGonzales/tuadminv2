<?php

require("conexion.inc");

// Obtén los valores enviados desde el frontend
$codigo             = $_POST['codigo'];
$cod_proveedor      = $_POST['cod_proveedor'];
$observaciones      = $_POST['observaciones'];
$fecha_factura_proveedor = $_POST['fecha_factura_proveedor'];
$nro_documento      = $_POST['nro_documento'];
$monto_orden        = $_POST['monto_orden'];
$dias_credito       = $_POST['dias_credito'];

// Actualizar el registro en la tabla ordenes_compra
$sql = "UPDATE ordenes_compra
        SET cod_proveedor = '$cod_proveedor',
            observaciones = '$observaciones',
            fecha_factura_proveedor = '$fecha_factura_proveedor',
            nro_documento = '$nro_documento',
            monto_orden = '$monto_orden',
            dias_credito = '$dias_credito'
        WHERE codigo = '$codigo'";

$sql_actualizar=mysqli_query($enlaceCon,$sql);

if ($sql_actualizar) {
    $response = array(
        "message" => "Registro actualizado correctamente",
        "status"  => true
    );
} else {
    $response = array(
        "message" => "Ocurrió un error en la actualización",
        "status"  => false
    );
}
ob_clean();
header('Content-Type: application/json');
echo json_encode($response);
exit;