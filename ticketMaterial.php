<?php
require_once 'conexionmysqli.inc';
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
$codigo          = $registro['codigo_material'];
$precio          = empty($registro['precio']) ? '0.00' : $registro['precio'];

barcode('codigo_barra/'.$codigo.'.png', $codigo, 20, 'horizontal', 'code128', true);

// Redirecciona a otro archivo PHP pasando los datos en la URL como parámetros
header('Location: ticketMaterialPrint.php?codigo=' . urlencode($codigo) . '&nombre=' . urlencode($nombre_producto) . '&precio=' . urlencode($precio));
exit;
?>