<?php
require("../conexionmysqli.inc");
require("../funciones.php");

$sql = "SELECT t.codigo, t.nombre, t.nro_licencia
        FROM transportistas t
        WHERE t.estado = 1
        ORDER BY t.nombre ASC";

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