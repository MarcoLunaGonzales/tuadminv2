<?php
require('function_formatofecha.php');
require('funcion_nombres.php');
require('conexion.inc');
require('estilos_reportes_almacencentral.php');


$fecha_ini=$_GET['fecha_ini'];
$fecha_fin=$_GET['fecha_fin'];
$codTipoDoc=$_GET['codTipoDoc'];


//desde esta parte viene el reporte en si
$fecha_iniconsulta=($fecha_ini);
$fecha_finconsulta=($fecha_fin);


$rpt_territorio=$_GET['rpt_territorio'];

$fecha_reporte=date("d/m/Y");

$nombre_territorio=nombreTerritorio($rpt_territorio);

echo "<h1>Reporte Ventas x Cliente (Rutas)</h1>
	<h2>Territorio: $nombre_territorio <br> De: $fecha_ini A: $fecha_fin
	<br>Fecha Reporte: $fecha_reporte</h2>";

$sql="SELECT c.cod_cliente, c.nombre_cliente, sum((sd.cantidad_unitaria*sd.precio_unitario)-sd.descuento_unitario) from salida_almacenes s
INNER JOIN salida_detalle_almacenes sd ON s.cod_salida_almacenes=sd.cod_salida_almacen
LEFT JOIN clientes c ON sd.cod_cliente=c.cod_cliente
where s.fecha BETWEEN '$fecha_iniconsulta' and '$fecha_finconsulta' and s.cod_tipo_doc in ($codTipoDoc)
and s.salida_anulada=0 and s.`cod_almacen` in (select a.`cod_almacen` from `almacenes` a where a.`cod_ciudad`='$rpt_territorio') 
GROUP BY c.cod_cliente, c.nombre_cliente order by 2";

//echo $sql;

$resp=mysqli_query($enlaceCon,$sql);

echo "<br><table align='center' class='texto' width='70%'>
<tr>
<th>-</th>
<th>Cliente (Ruta)</th>
<th>Venta</th>
</tr>";

$totalVenta=0;
while($datos=mysqli_fetch_array($resp)){	
	$codCliente=$datos[0];
	$nombreCliente=$datos[1];
	$montoVenta=$datos[2];

	$totalVenta=$totalVenta+$montoVenta;
	
	$montoVentaFormat=number_format($montoVenta,2,".",",");
	echo "<tr>
	<td>$codCliente</td>
	<td>$nombreCliente</td>
	<td align='right'>$montoVentaFormat</td>
	</tr>";
}
$totalVentaFormat=number_format($totalVenta,2,".",",");
echo "<tr>
	<td>&nbsp;</td>
	<td>Total:</td>
	<td align='right'>$totalVentaFormat</td>
<tr>";

echo "</table></br>";
?>