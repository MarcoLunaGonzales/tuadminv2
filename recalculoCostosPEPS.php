<?php
set_time_limit(0);

require('conexion.inc');
require('funcionRecalculoCostos.php');

/*$fecha_reporte=date("d/m/Y");
$rptLote=$_GET["rptLote"];*/

$rpt_almacen=1000;

	$sql_nombre_almacen="select nombre_almacen from almacenes where cod_almacen='$rpt_almacen'";
	$resp_nombre_almacen=mysql_query($sql_nombre_almacen);
	$dat_almacen=mysql_fetch_array($resp_nombre_almacen);
	$nombre_almacen=$dat_almacen[0];
	echo "$nombre_territorio  Almacen: <strong>$nombre_almacen</strong> Fecha inicio: <strong>$fecha_ini</strong> Fecha final: 
	<strong>$fecha_fin</strong>";


$sqlLotes="select distinct(id.`cod_material`) from `ingreso_almacenes` i, `ingreso_detalle_almacenes` id
	where i.`cod_ingreso_almacen`=id.`cod_ingreso_almacen` and 
	i.`cod_almacen`=$rpt_almacen and i.`ingreso_anulado`=0 and id.cod_material between 1500 and 2500 order by 1";
$respLotes=mysql_query($sqlLotes);

while($datLotes=mysql_fetch_array($respLotes)){	
	$rpt_item=$datLotes[0];
	
	recalculaCostos($rpt_item, $rpt_almacen);
	
	echo $rpt_item."<br>";
}	


?>