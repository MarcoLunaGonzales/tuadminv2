<?php
require("conexion.inc");

$globalAgencia = $_COOKIE['global_agencia'];
$fechaInicio   = $_GET['fechaInicio'];
$fechaFin 	   = $_GET['fechaFin'];

$sql="SELECT MIN(s.nro_correlativo), MAX(s.nro_correlativo)
FROM salida_almacenes s WHERE s.cod_tiposalida = 1001
AND s.cod_almacen in (select a.cod_almacen from almacenes a where a.cod_ciudad='$globalAgencia')
AND s.salida_anulada = 0
AND s.fecha BETWEEN '$fechaInicio' and '$fechaFin'";
$resp=mysql_query($sql);

$inicio = 0;
$fin 	= 0;
while($dat=mysql_fetch_array($resp)){
	$inicio = $dat[0];
	$fin 	= $dat[1];
}

echo json_encode(array(
	'inicio' => $inicio,
	'fin' 	 => $fin
));
?>
