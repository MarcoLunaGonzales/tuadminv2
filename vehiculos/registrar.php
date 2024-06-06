<?php
require("../conexionmysqli.inc");
require("../funciones.php");

// Recibe los datos del vehiculo
$nombre = $_POST['nombre'];
$placa  = $_POST['placa'];
$ch_cod_transportadora = $_POST['cod_transportadora'];

// Aquí puedes realizar las validaciones necesarias antes de guardar los datos, por ejemplo:
if (empty($nombre) || empty($placa) || empty($ch_cod_transportadora)) {
    $response = array(
        'success' => false,
        'message' => 'Todos los campos son obligatorios.'
    );
	ob_clean();
    echo json_encode($response);
    exit;
}

// Guarda los datos del vehiculo en la base de datos
$sql = "INSERT INTO vehiculos (nombre, placa, cod_transportadora, estado) 
        VALUES ('$nombre', '$placa', '$ch_cod_transportadora', 1)";
$result = mysqli_query($enlaceCon, $sql);

if ($result) {
    // Verifica si al menos una fila fue afectada
    if (mysqli_affected_rows($enlaceCon) > 0) {
        // La inserción fue exitosa
        // Obtiene el ID primario generado por la inserción
        $codigo = mysqli_insert_id($enlaceCon);
        $response = array(
            'success' => true,
            'message' => 'Los datos del vehiculo se guardaron correctamente.',
            'codigo'  => "$codigo"
        );
        echo json_encode($response);
    } else {
        // No se insertó ninguna fila, probablemente debido a un error
        $response = array(
            'success' => false,
            'message' => 'Error al guardar los datos del vehiculo: No se insertaron filas.'
        );
        echo json_encode($response);
    }
} else {
    // Si hubo un error en la ejecución de la consulta, devuelve un mensaje de error
    $response = array(
        'success' => false,
        'message' => 'Error al ejecutar la consulta: ' . mysqli_error($enlaceCon)
    );
    echo json_encode($response);
}
ob_clean();
echo json_encode($response);


?>