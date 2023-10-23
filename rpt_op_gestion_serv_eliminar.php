<?php

require("conexion.inc");

// Obtiene registro para eliminar
$codigo = $_POST['codigo'];

// Inserta el registro en la tabla ordenes_compra
$sql = "UPDATE ordenes_compra
        SET cod_estado = CASE 
            WHEN cod_estado = 1 THEN 2
            WHEN cod_estado = 2 THEN 1
            ELSE cod_estado
        END
        WHERE codigo = '$codigo'";

$sql_inserta=mysqli_query($enlaceCon,$sql);

if ($sql_inserta) {
    $response = array(
        "message" => "Se eliminó registro correctamente",
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