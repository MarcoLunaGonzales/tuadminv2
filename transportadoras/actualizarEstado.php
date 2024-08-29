<?php
require("../conexionmysqli.inc");

// Recibe los datos del transportadora
$codigo = $_POST['codigo'];

// Aquí puedes realizar las validaciones necesarias antes de actualizar el estado
if (empty($codigo)) {
    $response = array(
        'success' => false,
        'message' => 'El CODIGO de la transportadora es requerido.'
    );
    ob_clean();
    echo json_encode($response);
    exit;
}

// Obtiene el estado actual de la transportadora
$sql = "SELECT estado FROM transportadoras WHERE codigo = '$codigo'";
$result = mysqli_query($enlaceCon, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $currentEstado = $row['estado'];

    // Determina el nuevo estado
    $nuevoEstado = ($currentEstado == 1) ? 2 : 1;

    // Actualiza el estado de la transportadora en la base de datos
    $sqlUpdate = "UPDATE transportadoras SET estado = '$nuevoEstado' WHERE codigo = '$codigo'";
    $updateResult = mysqli_query($enlaceCon, $sqlUpdate);

    if ($updateResult) {
        if (mysqli_affected_rows($enlaceCon) > 0) {
            $response = array(
                'success' => true,
                'message' => 'El estado de la transportadora se actualizó correctamente.',
                'nuevo_estado' => $nuevoEstado
            );
        } else {
            $response = array(
                'success' => true,
                'message' => 'No se actualizaron filas.'
            );
        }
    } else {
        // Si hubo un error en la ejecución de la consulta, devuelve un mensaje de error
        $response = array(
            'success' => false,
            'message' => 'Error al ejecutar la consulta: ' . mysqli_error($enlaceCon)
        );
    }
} else {
    // Si no se encontró la transportadora, devuelve un mensaje de error
    $response = array(
        'success' => false,
        'message' => 'No se encontró la transportadora con el ID proporcionado.'
    );
}

ob_clean();
echo json_encode($response);
?>