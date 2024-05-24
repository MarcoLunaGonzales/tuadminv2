<?php
require("conexionmysqlipdf.inc");
require("funcion_nombres.php");

$codProductoCambiar=$_GET['codProducto'];

$precio1=$_GET['precio1'];
$precio2=$_GET['precio2'];
$precio3=$_GET['precio3'];


$nombreProducto=obtenerNombreProductoSimple($enlaceCon, $codProductoCambiar);



//verificamos el otro producto
$sqlVerificacion="select m.codigo_material from material_apoyo m where 
    m.descripcion_material like '%$nombreProducto%' and m.codigo_material<>'$codProductoCambiar' and estado=1";
$respVerificacion=mysqli_query($enlaceCon, $sqlVerificacion);
$codigoProductoOriginal=0;
if($datVerificacion=mysqli_fetch_array($respVerificacion)){
    $codigoProductoOriginal=$datVerificacion[0];
}

$sqlDelPrecios="delete from precios where codigo_material='$codigoProductoOriginal'";
$respDelPrecios=mysqli_query($enlaceCon, $sqlDelPrecios);


$sql_inserta_precio = "INSERT INTO precios (codigo_material, cod_precio, precio, cod_ciudad, cod_tipoventa, cantidad_inicio, cantidad_final, cod_moneda)
    SELECT $codigoProductoOriginal, cod_precio, precio, cod_ciudad, cod_tipoventa, cantidad_inicio, cantidad_final, cod_moneda
	from precios where codigo_material=$codProductoCambiar";
$stmt_precio = mysqli_query($enlaceCon, $sql_inserta_precio);


echo "Precio a: ".$codigoProductoOriginal;

?>
