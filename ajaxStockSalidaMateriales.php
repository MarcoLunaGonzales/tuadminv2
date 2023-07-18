<?php
require("funciones.php");

$codMaterial = $_GET["codmat"];
$codAlmacen = $_GET["codalm"];
$indice = $_GET["indice"];

//
require("conexion.inc");
//SACAMOS LA CONFIGURACION PARA LA  VALIDACION DE STOCKS
$sqlConf="select valor_configuracion from configuraciones where id_configuracion=4";
$respConf=mysqli_query($enlaceCon,$sqlConf);
$banderaValidacionStock=mysqli_result($respConf,0,0);

$stockProducto=stockProducto($codAlmacen, $codMaterial);

if($banderaValidacionStock!=1){
	echo "<input type='text' id='stock$indice' name='stock$indice' value='-' readonly size='4'>
	<span style='color:red'>S:$stockProducto</span>";
}else{
	echo "<input type='text' id='stock$indice' name='stock$indice' value='$stockProducto' readonly size='4'>";
}

?>
