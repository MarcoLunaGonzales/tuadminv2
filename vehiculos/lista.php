<?php
require("../conexionmysqli.inc");
require("../funciones.php");

$sql = "SELECT v.codigo, v.nombre, v.placa
        FROM vehiculos v
        WHERE v.estado = 1
        ORDER BY v.nombre ASC";

$resp = mysqli_query($enlaceCon, $sql);

$lista = array();
while ($row = mysqli_fetch_assoc($resp)) {
    $lista[] = $row;
}

$response = array();

if ($resp) {
    $response['success'] = true;
    $response['message'] = "Se obtuvo la lista de choferes correctamente";
    $response['lista'] = $lista;
} else {
    $response['success'] = false;
    $response['message'] = "Error al obtener la choferes";
}

// Limpia el buffer de salida
ob_clean();

// Devuelve la respuesta en formato JSON
echo json_encode($response);

?>