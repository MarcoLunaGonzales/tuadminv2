<?php
require('estilos_reportes_almacencentral.php');
require('function_formatofecha.php');
require('conexion.inc');
require('funcion_nombres.php');
require('funciones.php');

$fecha_ini=$_GET['fecha_ini'];
$fecha_fin=$_GET['fecha_fin'];
$rpt_ver=$_GET['rpt_ver'];

//desde esta parte viene el reporte en si
$fecha_iniconsulta=cambia_formatofecha($fecha_ini);
$fecha_finconsulta=cambia_formatofecha($fecha_fin);


$rpt_territorio=$_GET['rpt_territorio'];

$fecha_reporte=date("d/m/Y");

$nombre_territorio=nombreTerritorio($rpt_territorio);

echo "<table align='center' class='textotit' width='100%'><tr><td align='center'>Ranking de Ventas x Item
	<br>Territorio: $nombre_territorio <br> De: $fecha_ini A: $fecha_fin
	<br>Fecha Reporte: $fecha_reporte</tr></table>";
	
$sql="select m.`codigo_material`, m.codigo_anterior, m.`descripcion_material`, 
	(sum(sd.monto_unitario)-sum(sd.descuento_unitario))montoVenta, sum(sd.cantidad_unitaria), sum(((sd.monto_unitario-sd.descuento_unitario)/s.monto_total)*s.descuento)as descuentocabecera
	from `salida_almacenes` s, `salida_detalle_almacenes` sd, `material_apoyo` m 
	where s.`cod_salida_almacenes`=sd.`cod_salida_almacen` and s.`fecha` BETWEEN '$fecha_iniconsulta' and '$fecha_finconsulta'
	and s.`salida_anulada`=0 and sd.`cod_material`=m.`codigo_material` and s.`cod_tiposalida`=1001 and  
	s.`cod_almacen` in (select a.`cod_almacen` from `almacenes` a where a.`cod_ciudad`='$rpt_territorio')
	group by m.`codigo_material` order by 4 desc;";
	
$resp=mysql_query($sql);

echo "<br><table align='center' class='texto' width='100%'>
<tr>
<th>Codigo</th>
<th>CodigoInterno</th>
<th>Item</th>
<th>Cantidad</th>
<th>Monto Producto</th>
<th>Descuento Cabecera</th>
<th>Monto Producto Final</th>
</tr>";

$totalVenta=0;
while($datos=mysql_fetch_array($resp)){	
	$codItem=$datos[0];
	$codInterno=$datos[1];
	$nombreItem=$datos[2];
	$montoVenta=$datos[3];
	$cantidad=$datos[4];

	$descuentoCabecera=$datos[5];

	$montoVentaF=number_format($montoVenta,2,".",",");
	
	$montoVentaProducto=$montoVenta-$descuentoCabecera;

	
	$montoVentaProductoF=number_format($montoVentaProducto,2,".",",");
	$cantidadFormat=number_format($cantidad,0,".",",");

	$descuentoCabeceraF=formatonumeroDec($descuentoCabecera);
	
	$totalVentaProducto=$totalVentaProducto+$montoVenta;
	$totalVenta=$totalVenta+$montoVentaProducto;
	echo "<tr>
	<td>$codItem</td>
	<td>$codInterno</td>
	<td align='left'>$nombreItem</td>
	<td>$cantidadFormat</td>
	<td>$montoVentaF</td>
	<td>$descuentoCabeceraF</td>
	<td>$montoVentaProductoF</td>
	
	</tr>";
}
$totalVentaProductoF=number_format($totalVentaProducto,2,".",",");
$totalPtr=number_format($totalVenta,2,".",",");
echo "<tr>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>Total:</td>
	<td>$totalVentaProductoF</td>
	<td>&nbsp;</td>
	<td>$totalPtr</td>
<tr>";

echo "</table>";
include("imprimirInc.php");
?>