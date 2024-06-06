<?php
require("../conexionmysqli.inc");
require("../funciones.php");

ob_clean();
/**
 * LISTA DE CHOFERES
 */

$cod_transportadora = $_GET['cod_transportadora'];

$sql = "SELECT v.codigo, v.nombre, v.placa
        FROM vehiculos v
        WHERE v.estado = 1
        AND v.cod_transportadora = '$cod_transportadora'
        ORDER BY v.nombre ASC";
$resp = mysqli_query($enlaceCon, $sql);

$html = '<option value="0">Ninguno</option>';
while ($dat = mysqli_fetch_array($resp)) {
    $codigo = $dat['codigo'];
    $nombre = $dat['nombre'];
    $placa  = $dat['placa'];
    $html .= "<option value='$codigo'>$placa $nombre</option>";
}

if ($resp) {
    $response['success'] = true;
    $response['message'] = "Se obtuvo la lista de vehículos correctamente";
    $response['html'] = $html;
} else {
    $response['success'] = false;
    $response['message'] = "Error al obtener la vehículos";
}

// Limpia el buffer de salida
ob_clean();

// Devuelve la respuesta en formato JSON
echo json_encode($response);

?>