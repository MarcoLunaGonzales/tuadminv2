<?php
require('estilos_reportes_almacencentral.php');
require('function_formatofecha.php');
require('conexion.inc');
require('funcion_nombres.php');
require('funciones.php');

$fecha_ini=$_GET['fecha_ini'];
$fecha_fin=$_GET['fecha_fin'];
$rpt_territorio=$_GET['rpt_territorio'];

$variableAdmin=$_GET["variableAdmin"];
if($variableAdmin!=1){
	$variableAdmin=0;
}

//desde esta parte viene el reporte en si
$fecha_iniconsulta=$fecha_ini;
$fecha_finconsulta=$fecha_fin;

$fecha_reporte=date("d/m/Y");

echo "<h1>Reporte Detallado de Gastos</h1>
	<h2>Fecha Inicio: $fecha_ini Fecha Final: $fecha_fin &nbsp;&nbsp;&nbsp; Fecha Reporte: $fecha_reporte</h2>";

echo "<br><center><table class='textomediano'>";
echo "<tr><th colspan='4'>Detalle de Gastos</th></tr>";
echo "<tr><th>Fecha</th><th>Tipo</th>
	<th>Descripcion</th><th>Monto [Bs]</th></tr>";

$consulta = "select g.cod_gasto, g.descripcion_gasto, 
	(select nombre_tipogasto from tipos_gasto where cod_tipogasto=g.cod_tipogasto)tipogasto, 
	DATE_FORMAT(g.fecha_gasto, '%d/%m/%Y'), monto, estado from gastos g, tipos_gasto tg where g.cod_tipogasto=tg.cod_tipogasto and fecha_gasto between '$fecha_iniconsulta' and '$fecha_finconsulta' and g.estado=1 and tg.tipo in (1,2) and g.cod_ciudad='$rpt_territorio' order by g.cod_gasto";
//echo $consulta;

$resp = mysql_query($consulta);
$totalGastos=0;
while ($dat = mysql_fetch_array($resp)) {
	$codGasto = $dat[0];
	$descripcionGasto= $dat[1];
	$tipoGasto=$dat[2];
	$fechaGasto = $dat[3];
	$montoGasto = $dat[4];
	$totalGastos=$totalGastos+$montoGasto;
	$codEstado=$dat[5];	
	$montoGasto=redondear2($montoGasto);

	echo "<tr>
	<td align='center'>$fechaGasto</td>
	<td align='center'>$tipoGasto</td>
	<td align='center'>$descripcionGasto</td>
	<td align='right'>$montoGasto</td>
	</tr>";
}
$totalGastos=redondear2($totalGastos);
echo "<tr>
<td align='center'>-</td>
<td align='center'>-</td>
<th>Total Gastos</th>
<th align='right'>$totalGastos</th>
</tr>";
echo "</table></center><br>";


include("imprimirInc.php");
?>