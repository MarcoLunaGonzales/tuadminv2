<?php
require_once 'conexionmysqli.inc';
// require_once 'functions.php';
include 'assets/php-barcode-master/barcode.php';

error_reporting(E_ALL);
ini_set('display_errors', '1');

// Codigo de CIUDAD
$global_agencia = $_COOKIE["global_agencia"];

// Modigo de Material
$cod_material = $_GET['cod_material'];

$sqlConf="SELECT ma.codigo_material, ma.descripcion_material, ROUND(p.precio, 2) as precio
            FROM material_apoyo ma 
            LEFT JOIN precios p ON p.codigo_material = ma.codigo_material
            WHERE ma.codigo_material = '$cod_material'
            AND p.cod_ciudad = '$global_agencia'
            AND p.cod_precio = 1
            LIMIT 1";
$respConf=mysqli_query($enlaceCon,$sqlConf);
$registro = mysqli_fetch_array($respConf);

$nombre_producto = $registro['descripcion_material'];
// $nombre_producto = '';
$codigo          = $registro['codigo_material'];
$precio          = empty($registro['precio']) ? '0.00' : $registro['precio'];

// barcode('codigo_barra/'.$codigo.'.png', $codigo, 10, 'horizontal', 'code128', true);

//Aumentar la altura del código de barras a 50 unidades:
//barcode('codigo_barra/'.$codigo.'.png', $codigo, 10, 'horizontal', 'code128', true);
//Utilizar un tipo de código de barras diferente, como Code39:
//barcode('codigo_barra/'.$codigo.'.png', $codigo, 40, 'horizontal', 'code39', true);
//Cambiar la orientación del código de barras a vertical:
//barcode('codigo_barra/'.$codigo.'.png', $codigo, 10, 'vertical', 'code128', true);
//Deshabilitar el checksum:
 barcode('codigo_barra/'.$codigo.'.png', $codigo, 5, 'horizontal', 'code128', false);

//Redirecciona a otro archivo PHP pasando los datos en la URL como parámetros
header('Location: ticketMaterialPrint.php?codigo=' . urlencode($codigo) . '&nombre=' . urlencode($nombre_producto) . '&precio=' . urlencode($precio) . '&margen_x=' . urlencode($margen_x) . '&margen_y=' . urlencode($margen_y) . '&margen_x2=' . urlencode($margen_x2) . '&margen_y2=' . urlencode($margen_y2) . '&card_width=' . urlencode($card_width) . '&card_height=' . urlencode($card_height));


function obtenerConfiguracion($id_configuracion){
	$sqlConf="SELECT c.valor_configuracion
                FROM configuraciones c
                WHERE c.id_configuracion='$id_configuracion'
                LIMIT 1";
    $respConf=mysqli_query($enlaceCon,$sqlConf);
    $registro = mysqli_fetch_array($respConf);
    return $registro['valor_configuracion'];
}

exit;
?>
