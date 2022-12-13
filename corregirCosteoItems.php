<?php
require("conexion.inc");
require("funcionRecalculoCostos.php");

$rpt_almacen=1003;
$codItems="89,90,91,92,93,103,94,104,95,96,97,1159,98,99,100,101,102";

$sql="select m.codigo_material from material_apoyo m where m.codigo_material in ($codItems)";
$resp=mysql_query($sql);

while($dat=mysql_fetch_array($resp)){
	$codigoItem=$dat[0];
	recalculaCostos($codigoItem, $rpt_almacen);
	echo "ok ".$codigoItem."<br>";
}

?>