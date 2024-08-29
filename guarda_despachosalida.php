<?php
require("conexion.inc");
date_default_timezone_set('America/La_Paz');


// Crear un array para almacenar la respuesta JSON
$response = array();

// Recibir datos JSON del cliente y decodificarlos
$data = $_POST['data'];

// Extraer datos del JSON para la cabecera
$codigo            = $data['codigo'];           // En caso de actualización

$observaciones     = $data['observaciones'];
$global_agencia    = $_COOKIE["global_agencia"]; // codigo ciudad
$fecha_entrega     = date('Y-m-d H:i:s');
$fecha_recepcion   = date('Y-m-d H:i:s');
$codigoFuncionario = $data['codigo_funcionario'];
$created_by        = $_COOKIE["global_usuario"];
$created_at        = date('Y-m-d H:i:s');

// Insertar en la tabla de cabecera
if(empty($codigo)){
    // Nuevo
    $consulta = "INSERT INTO despacho_productos (cod_ciudad, fecha_entrega, cod_funcionario, observaciones, created_by, created_at) 
                VALUES ('$global_agencia', '$fecha_entrega', '$codigoFuncionario', '$observaciones', '$created_by', '$created_at')";
    $sql_cabecera = mysqli_query($enlaceCon, $consulta);
}else{
    // Editar
    $consulta_actualizar_cabecera = "UPDATE despacho_productos
                                    SET fecha_recepcion = '$fecha_recepcion'
                                    WHERE codigo = '$codigo'";
    $sql_cabecera = mysqli_query($enlaceCon, $consulta_actualizar_cabecera);
}

if ($sql_cabecera) {

    // Obtener el ID del registro recién insertado
    $cod_despachoproducto = empty($codigo) ? mysqli_insert_id() : $codigo;

    $cantidad_venta      = 0;
    $cantidad_devolucion = 0;
    $monto_venta         = 0;
    $precio_producto     = 0;
    if(!empty($codigo) && $codigo > 0){
        // Editar
        $consulta_eliminar_detalle = "DELETE FROM despacho_productosdetalle WHERE cod_despachoproducto = '$codigo'";
        $sql_eliminar_detalle = mysqli_query($enlaceCon, $consulta_eliminar_detalle);
    }
    
    // Insertar en la tabla de detalle
    foreach ($data['items'] as $item) {
        $cod_material        = $item['codigo_material'];
        $cantidad_entrega    = $item['cantidad_entrega'];
        
        if(!empty($codigo)){
            // Editar
            $cantidad_venta      = $item['cantidad_venta'];
            $cantidad_devolucion = $cantidad_entrega - $cantidad_venta;
            $monto_venta         = $item['monto_venta'];
            $precio_producto     = $precio_producto = ($cantidad_venta != 0) ? ($monto_venta / $cantidad_venta) : 0;
        }
        $consulta_detalle = "INSERT INTO despacho_productosdetalle (cod_despachoproducto, cod_material, cantidad_entrega, cantidad_venta, cantidad_devolucion, monto_venta, precio_producto) 
                        VALUES ('$cod_despachoproducto', '$cod_material', '$cantidad_entrega', '$cantidad_venta', '$cantidad_devolucion', '$monto_venta', '$precio_producto')";
        $sql_inserta_detalle = mysqli_query($enlaceCon, $consulta_detalle);
    }

    if ($sql_inserta_detalle) {
        // Tanto la inserción en la cabecera como en el detalle fueron exitosas
        $response['status']  = true;
        $response['message'] = "Los datos fueron insertados correctamente.";
    } else {
        // Error en la inserción del detalle
        $response['status']  = false;
        $response['message'] = "No se pudo insertar el detalle.";
    }
} else {
    // Error en la inserción de la cabecera
    $response['status']  = false;
    $response['message'] = "No se pudo insertar la cabecera.";
}

// Convertir el array de respuesta en formato JSON y mostrarlo
echo json_encode($response);
?>
