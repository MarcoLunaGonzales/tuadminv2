<?php
require("../../conexion.inc");

$codigoIngreso=$_GET["codigo"];
$tipoIngreso = $_GET["combotipoingreso"];
$proveedor = $_GET["comboproveedor"];

$sql="update ingreso_almacenes set cod_tipoingreso='$tipoIngreso', cod_proveedor='$proveedor' where cod_ingreso_almacen=$codigo";
$resp=mysql_query($sql);


if($resp){
    echo "OK";
}else{
	echo "ERROR";
}

?>
