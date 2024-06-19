<?php
require_once 'conexionmysqli.inc';
require_once 'funciones.php';
include 'assets/php-barcode-master/barcode.php';

// error_reporting(E_ALL);
// ini_set('display_errors', '1');

// Codigo de CIUDAD
$global_agencia = $_COOKIE["global_agencia"];

// Lista de Codigos Seleccionados
$codigos = empty($_GET['codigos']) ? '' : $_GET['codigos'];
// Modigo de Material 
$cod_ingreso_almacen = empty($_GET['cod_ingreso_almacen']) ? '' : $_GET['cod_ingreso_almacen'];

$query_fil = empty($codigos) 
                ? "AND ida.cod_ingreso_almacen = '$cod_ingreso_almacen'" 
                : "AND CONCAT(ida.cod_ingreso_almacen, '_', ma.codigo_material, '_', ida.lote) IN ($codigos)";

$sqlConf="SELECT ma.codigo_material, ma.descripcion_material, ROUND(p.precio, 2) as precio, ida.lote, ida.cod_ingreso_almacen, ida.costo_promedio as costo
            FROM material_apoyo ma 
            LEFT JOIN ingreso_detalle_almacenes ida ON ida.cod_material = ma.codigo_material
            LEFT JOIN precios p ON p.codigo_material = ma.codigo_material
            WHERE p.cod_ciudad = '$global_agencia'
            AND p.cod_precio = 1
            $query_fil";
$respConf=mysqli_query($enlaceCon,$sqlConf);
// $registro = mysqli_fetch_array($respConf);

while ($registro = mysqli_fetch_assoc($respConf)) {
    /******************************************************************************/
    $detalle_codigo_material     = $registro['codigo_material'];
    $detalle_cod_ingreso_almacen = $registro['cod_ingreso_almacen'];
    $detalle_lote                = $registro['lote'];

    $nombre_barra = $detalle_cod_ingreso_almacen.'_'.$detalle_codigo_material.'_'.$detalle_lote;
    $codigo_barra = $detalle_cod_ingreso_almacen.'|'.$detalle_codigo_material.'|'.$detalle_lote;
    
    barcode("codigo_barra/$nombre_barra.png", $codigo_barra, 5, 'horizontal', 'code128', false);
}
//Redirecciona a otro archivo PHP pasando los datos en la URL como parámetros
if(empty($codigos)){
    header('Location: ticketMaterialPrint_detalle.php?cod_ingreso_almacen=' . urlencode($cod_ingreso_almacen) );
}else{
    header('Location: ticketMaterialPrint_detalle.php?codigos=' . urlencode($codigos) );
}
        // '&cod_ingreso_almacen=' . urlencode($cod_ingreso_almacen) . 
        // '&precio=' . urlencode($precio) . 
        // '&costo=' . urlencode($precio_costo) );

exit;
?>
