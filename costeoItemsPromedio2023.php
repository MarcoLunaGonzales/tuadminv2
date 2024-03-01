<?php
require("conexion.inc");
require("funcionRecalculoCostos2023.php");

set_time_limit(0);


$inicioTime = microtime(true);

//$rptAlmacen=1000;//16 de Julio

//$rptAlmacen=1003;//av. bolivia

$rptAlmacen=$_GET["rpt_almacen"];

$sqlItems="SELECT distinct(sd.cod_material) from salida_almacenes s, salida_detalle_almacenes sd
where s.cod_salida_almacenes=sd.cod_salida_almacen and s.cod_almacen='$rptAlmacen' and s.fecha BETWEEN '2023-01-01' and '2023-12-31' and s.salida_anulada=0 order by sd.cod_material";
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