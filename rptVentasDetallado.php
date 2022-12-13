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

echo "<table align='center' class='textotit' width='100%'><tr><td align='center'>Detallado de Ventas por Linea e Item
	<br>Territorio: $nombre_territorio <br> De: $fecha_ini A: $fecha_fin
	<br>Fecha Reporte: $fecha_reporte</tr></table>";
	
$sql="SELECT 
(select t.abreviatura from tipos_docs t where t.codigo=s.cod_tipo_doc)as tipodoc, 
 s.nro_correlativo, s.fecha, s.hora_salida, m.cod_linea_proveedor,
 (select pl.abreviatura_linea_proveedor from proveedores_lineas pl where pl.cod_linea_proveedor=m.cod_linea_proveedor) as lineaproveedor,
 m.codigo_anterior, m.descripcion_material, 
 sd.cantidad_unitaria, sd.precio_unitario, sd.descuento_unitario, sd.monto_unitario,
 (select pre.precio from precios pre where pre.cod_precio=1 and pre.cod_ciudad=1 and pre.codigo_material=m.codigo_material)as precio_registrado
from salida_almacenes s, salida_detalle_almacenes sd, material_apoyo m
where s.cod_salida_almacenes=sd.cod_salida_almacen and  sd.cod_material=m.codigo_material and 
s.cod_almacen in (select a.cod_almacen from almacenes a where a.cod_ciudad='$rpt_territorio') and s.fecha BETWEEN '$fecha_iniconsulta' and '$fecha_finconsulta' and s.salida_anulada=0 
and s.cod_tiposalida=1001 order by lineaproveedor, s.fecha, m.descripcion_material;";
	
$resp=mysql_query($sql);

echo "<br><table align='center' class='texto' width='100%'>
<tr>
<th>Documento</th>
<th>Fecha</th>
<th>Linea</th>
<th>CodigoInterno</th>
<th>Item</th>
<th>Cantidad</th>
<th>PrecioRegistrado</th>
<th>PrecioVenta</th>
<th>Dif.%</th>
<th>Dif.(Bs.)</th>
<th>Monto Venta</th>
<th>Descuento</th>
<th>Subtotal</th>
</tr>";

$totalVentaBruta=0;
$totalVenta=0;
$totalDescuentos=0;

$sumaDiferenciaPreciosCabecera=0;
while($datos=mysql_fetch_array($resp)){	
	$numeroDoc=$datos[0]."-".$datos[1];	
	$fechaHora=$datos[2]." ".$datos[3];
	$lineaProveedor=$datos[5];
	$codInterno=$datos[6];
	$nombreItem=$datos[7];
	$cantidadItem=$datos[8];
	$cantidadItemF=formatonumero($cantidadItem);
	$precioItem=$datos[9];
	$precioItemF=formatonumeroDec($precioItem);
	$descuentoVenta=$datos[10];
	$descuentoVentaF=formatonumeroDec($descuentoVenta);
	
	$txtDiferenciaPorcentualPrecio="-";
	$txtDiferenciaBsPrecio="-";
	$diferenciaBsPrecio=0;
	
	$precioRegistrado=$datos[12];
	$precioRegistradoF=formatonumeroDec($precioRegistrado);
	
	$diferenciaPorcentualPrecio=(($precioItem-$precioRegistrado)/$precioRegistrado)*100;
	$diferenciaPorcentualPrecioF=formatonumeroDec($diferenciaPorcentualPrecio);
	$diferenciaBsPrecio=$precioItem-$precioRegistrado;
	$diferenciaBsPrecioF=formatonumeroDec($diferenciaBsPrecio);
	
	if($diferenciaPorcentualPrecio<0){
		$txtDiferenciaPorcentualPrecio="<span style='color:red; font-size:16px;'>$diferenciaPorcentualPrecioF %</span>";
		$txtDiferenciaBsPrecio="<span style='color:red; font-size:15px;'>$diferenciaBsPrecioF</span>";
		$sumaDiferenciaPreciosCabecera=$sumaDiferenciaPreciosCabecera+$diferenciaBsPrecio;
	}
	
	$totalItem=$cantidadItem*$precioItem;
	$totalItemF=formatonumeroDec($totalItem);

	$montoItem=$totalItem-$descuentoVenta;
	$montoItemF=formatonumeroDec($montoItem);

	$totalVenta=$totalVenta+$montoItem;
	$totalVentaBruta=$totalVentaBruta+$totalItem;
	$totalDescuentos=$totalDescuentos+$descuentoVenta;

	echo "<tr>
	<td>$numeroDoc</td>
	<td>$fechaHora</td>
	<td>$lineaProveedor</td>
	<td>$codInterno</td>
	<td>$nombreItem</td>
	<td>$cantidadItemF</td>
	<td align='right'><p style='color:red'>$precioRegistradoF</p></td>
	<td align='right'>$precioItemF</td>
	<td align='right'>$txtDiferenciaPorcentualPrecio</td>
	<td align='right'>$txtDiferenciaBsPrecio</td>
	<td align='right'>$totalItemF</td>
	<td align='right'>$descuentoVentaF</td>
	<td align='right'>$montoItemF</td>
	
	</tr>";
}
$totalVentaF=formatonumeroDec($totalVenta);
$totalVentaBrutaF=formatonumeroDec($totalVentaBruta);
$totalDescuentosF=formatonumeroDec($totalDescuentos);

$sumaDiferenciaPreciosCabeceraF="<span style='color:red; font-size:16px;'>$sumaDiferenciaPreciosCabecera</span>";

echo "<tr>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>Total:</td>
	<td align='right'>$sumaDiferenciaPreciosCabeceraF</td>
	<td align='right'>$totalVentaBrutaF</td>
	<td align='right'>$totalDescuentosF</td>
	<td align='right'>$totalVentaF</td>
<tr>";

echo "</table>";
include("imprimirInc.php");
?>