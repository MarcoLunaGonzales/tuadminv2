<?php
require("conexion.inc");
require("funcionRecalculoCostosOficial2022.php");

set_time_limit(0);


$inicioTime = microtime(true);

//$rptAlmacen=1000;//16 de Julio

$rptAlmacen=1003;//av. bolivia

$sqlItems="SELECT DISTINCT(id.cod_material) from ingreso_almacenes i, ingreso_detalle_almacenes id 
where i.cod_ingreso_almacen=id.cod_ingreso_almacen and i.ingreso_anulado=0 and 
i.fecha BETWEEN '2000-01-01' and '2022-12-31' and i.cod_almacen='$rptAlmacen' ORDER BY id.cod_material ";
$respItems=mysql_query($sqlItems);

$indice=1;
while($dat=mysql_fetch_array($respItems)){
	$codigoItem=$dat[0];

	$inicioTimeProducto = microtime(true);
	
	recalculaCostos($codigoItem, $rptAlmacen);

	$finTimeProducto = microtime(true);
	$tiempoEjecucionProducto = $finTimeProducto - $inicioTimeProducto;

	echo $indice." ************** ".$codigoItem." *********tiempoEjecucion: ".$tiempoEjecucionProducto."<br>";

	$indice++;
}

$finTime = microtime(true);
$tiempoEjecucionTodo = $finTime - $inicioTime;

echo "Tiempo Total Ejecucion: ".$tiempoEjecucionTodo;

?>