<?php
require("../conexionmysqli.inc");
require("../funciones.php");

ob_clean();
$sql = "SELECT t.codigo, t.nombre, t.nro_licencia
        FROM transportistas t
        WHERE t.estado = 1
        ORDER BY t.nombre ASC";
$resp = mysqli_query($enlaceCon, $sql);

$html = '<option value="0">Ninguno</option>';
while ($dat = mysqli_fetch_array($resp)) {
    $codigo = $dat['codigo'];
    $nombre = $dat['nombre'];
    $html .= "<option value='$codigo'>$nombre</option>";
}

if ($resp) {
    $response['success'] = true;
    $response['message'] = "Se obtuvo la lista de choferes correctamente";
    $response['html'] = $html;
} else {
    $response['success'] = false;
    $response['message'] = "Error al obtener la choferes";
}

// Limpia el buffer de salida
ob_clean();

// Devuelve la respuesta en formato JSON
echo json_encode($response);

?>