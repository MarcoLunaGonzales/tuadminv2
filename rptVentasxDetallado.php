<?php
require('estilos_reportes_almacencentral.php');
require('function_formatofecha.php');
require('conexion.inc');
require('funcion_nombres.php');

 error_reporting(E_ALL);
 ini_set('display_errors', '1');

$fecha_ini=$_GET['fecha_ini'];
$fecha_fin=$_GET['fecha_fin'];
$rpt_ver=$_GET['rpt_ver'];

//desde esta parte viene el reporte en si
$fecha_iniconsulta=cambia_formatofecha($fecha_ini);
$fecha_finconsulta=cambia_formatofecha($fecha_fin);


$rpt_territorio=$_GET['rpt_territorio'];

$fecha_reporte=date("d/m/Y");

$nombre_territorio=nombreTerritorio($rpt_territorio);

echo "<table align='center' class='textotit' width='100%'><tr><td align='center'>Detallado de Ventas por Linea e Item
	<br>Territorio: $nombre_territorio <br> De: $fecha_ini A: $fecha_fin
	<br>Fecha Reporte: $fecha_reporte</tr></table>";
	
$sql="SELECT 
(select t.abreviatura from tipos_docs t where t.codigo=s.cod_tipo_doc)as tipodoc, 
 s.nro_correlativo, s.fecha, s.hora_salida, m.cod_linea_proveedor,
 (select pl.abreviatura_linea_proveedor from proveedores_lineas pl where pl.cod_linea_proveedor=m.cod_linea_proveedor) as lineaproveedor,
 m.codigo_anterior, m.descripcion_material, 
 sd.cantidad_unitaria, sd.precio_unitario, sd.descuento_unitario, sd.monto_unitario, s.descuento, ((sd.monto_unitario/s.monto_total)*s.descuento)as descuentocabecera
from salida_almacenes s, salida_detalle_almacenes sd, material_apoyo m
where s.cod_salida_almacenes=sd.cod_salida_almacen and  sd.cod_material=m.codigo_material and 
s.cod_almacen in (select a.cod_almacen from almacenes a where a.cod_ciudad='$rpt_territorio') and s.fecha BETWEEN '$fecha_iniconsulta' and '$fecha_finconsulta' and s.salida_anulada=0 
order by lineaproveedor, s.fecha, m.descripcion_material;";
	
$resp=mysql_query($sql);

echo "<br><table align='center' class='texto' width='100%'>
<tr>
<th>Documento</th>
<th>Linea</th>
<th>CodigoInterno</th>
<th>Item</th>
<th>Cantidad</th>
<th>Precio</th>
<th>Monto Venta</th>
<th>Precio</th>
<th>Subtotal</th>
<th>DescuentoCabecera</th>
<th>MontoProducto</th>
</tr>";

$totalVenta=0;
$lineaProveedorPivot="";
while($datos=mysql_fetch_array($resp)){	
	$numeroDoc=$datos[0]."-".$datos[1];	
	$fechaHora=$datos[2]." ".$datos[3];
	$lineaProveedor=$datos[5];
	$codInterno=$datos[6];
	$nombreItem=$datos[7];
	$cantidadItem=$datos[8];
	$precioItem=$datos[9];
	$descuentoVenta=$datos[10];
	$montoItem=$datos[11];
	$descuentoCabecera=$datos[12];
	$descuentoCabeceraAplicadoProducto=$datos[13];
	
	$montoItem2=$montoItem-$descuentoCabeceraAplicadoProducto;

	$cantidadFormat=number_format($cantidad,0,".",",");
	
	$totalVenta=$totalVenta+$montoItem2;
	echo "<tr>
	<td>$numeroDoc</td>
	<td>$fechaHora</td>
	<td>$lineaProveedor</td>
	<td>$codInterno</td>
	<td>$nombreItem</td>
	<td>$cantidadItem</td>
	<td>$precioItem</td>
	<td>$descuentoVenta</td>
	<td>$montoItem</td>
	<td>$descuentoCabeceraAplicadoProducto</td>
	<td>$montoItem2</td>	
	</tr>";
}
echo "<tr>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>Total:</td>
	<td>$totalVenta</td>
<tr>";

echo "</table>";
include("imprimirInc.php");
?>