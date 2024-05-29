<?php
require("../conexionmysqli.inc");
require("../funciones.php");

ob_clean();
$sql = "SELECT t.codigo, t.nombre
        FROM transportadoras t
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
    $response['message'] = "Se obtuvo la lista de transportistas correctamente";
    $response['html'] = $html;
} else {
    $response['success'] = false;
    $response['message'] = "Error al obtener transportistas";
}

// Limpia el buffer de salida
ob_clean();

// Devuelve la respuesta en formato JSON
echo json_encode($response);

?>