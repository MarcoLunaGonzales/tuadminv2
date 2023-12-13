<?php

require("conexion.inc");

// Obtén los valores enviados desde el frontend
$cod_tipoventa = ($_POST['tipo_venta'] == 4) ? 2 : 1;
$cod_material  = $_POST['cod_material'];
$cantidad      = $_POST['cantidad'];

$sql_select = "SELECT p.precio
                    FROM precios p
                    WHERE p.codigo_material = '$cod_material'
                    AND '$cantidad' BETWEEN p.cantidad_inicio AND p.cantidad_final
                    AND p.cod_tipoventa = '$cod_tipoventa'
                UNION
                SELECT p.precio
                    FROM precios p
                    WHERE p.codigo_material = '$cod_material'
                    AND '$cantidad' BETWEEN p.cantidad_inicio AND p.cantidad_final
                    AND p.cod_tipoventa = 0
                LIMIT 1";
// echo $sql_select;
$resultado = mysqli_query($enlaceCon, $sql_select);

$precio = 0;
if ($resultado) {
    $fila_actualizada = mysqli_fetch_assoc($resultado);
    $precio = $fila_actualizada['precio'];
}


if ($sql_actualizar) {
    $response = array(
        "precio"  => $precio,
        "message" => "Registro actualizado correctamente",
        "status"  => true
    );
} else {
    $response = array(
        "precio"  => $precio,
        "message" => "Ocurrió un error en la actualización",
        "status"  => false
    );
}
ob_clean();
header('Content-Type: application/json');
echo json_encode($response);
exit;