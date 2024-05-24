<?php
require("conexionmysqlipdf.inc");
require("funcion_nombres.php");

$codProductoCambiar=$_GET['codProducto'];
$nombreProducto=obtenerNombreProductoSimple($enlaceCon, $codProductoCambiar);


//verificamos el otro producto
$sqlVerificacion="select m.codigo_material from material_apoyo m where 
    m.descripcion_material like '%$nombreProducto%' and m.codigo_material<>'$codProductoCambiar' and estado=1";
$respVerificacion=mysqli_query($enlaceCon, $sqlVerificacion);
$codigoProductoOriginal=0;
if($datVerificacion=mysqli_fetch_array($respVerificacion)){
    $codigoProductoOriginal=$datVerificacion[0];
}

$sqlUpd1="UPDATE ingreso_detalle_almacenes set cod_material='$codigoProductoOriginal' where cod_material='$codProductoCambiar'";
$sqlUpd2="UPDATE salida_detalle_almacenes set cod_material='$codigoProductoOriginal' where cod_material='$codProductoCambiar'";
$sqlUpd3="UPDATE salida_detalle_ingreso set material='$codigoProductoOriginal' where material='$codProductoCambiar'";

$respUpd1=mysqli_query($enlaceCon, $sqlUpd1);
$respUpd2=mysqli_query($enlaceCon, $sqlUpd2);
$respUpd3=mysqli_query($enlaceCon, $sqlUpd3);

$sqlUpdProd="UPDATE material_apoyo set estado=0 where codigo_material='$codProductoCambiar'";
$respUpdProd=mysqli_query($enlaceCon, $sqlUpdProd);

echo "ProductoCambiado a: ".$codigoProductoOriginal;

?>
