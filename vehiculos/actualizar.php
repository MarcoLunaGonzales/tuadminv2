<?php
require("../conexionmysqli.inc");

// Recibe los datos de la transportadora
$nombre = $_POST['nombre'];
$placa  = $_POST['placa'];
$peso_maximo = $_POST['peso_maximo'];
$codigo = isset($_POST['codigo']) ? $_POST['codigo'] : null;
// Aquí puedes realizar las validaciones necesarias antes de guardar o actualizar los datos
if (empty($nombre)) {
    $response = array(
        'success' => false,
        'message' => 'Todos los campos son obligatorios.'
    );
    ob_clean();
    echo json_encode($response);
    exit;
}

// Actualización
$sql = "UPDATE vehiculos SET 
			nombre = '$nombre',
			placa = '$placa',
			peso_maximo = '$peso_maximo' 
			WHERE codigo = '$codigo'";
$result = mysqli_query($enlaceCon, $sql);

if ($result) {
	// Verifica si al menos una fila fue afectada
	if (mysqli_affected_rows($enlaceCon) > 0) {
		// La actualización fue exitosa
		$response = array(
			'success' => true,
			'message' => 'Los datos de la transportadora se actualizaron correctamente.',
			'codigo'  => "$codigo"
		);
	} else {
		// No se actualizó ninguna fila, probablemente debido a un error
		$response = array(
			'success' => false,
			'message' => 'Error al actualizar los datos de la transportadora: No se actualizaron filas.'
		);
	}
} else {
	// Si hubo un error en la ejecución de la consulta, devuelve un mensaje de error
	$response = array(
		'success' => false,
		'message' => 'Error al ejecutar la consulta: ' . mysqli_error($enlaceCon)
	);
}

ob_clean();
echo json_encode($response);
?>
