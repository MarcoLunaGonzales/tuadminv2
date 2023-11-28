<?php
require('estilos_reportes_almacencentral.php');
require('function_formatofecha.php');
require('conexion.inc');
require('funcion_nombres.php');
require('funciones.php');

 // error_reporting(E_ALL);
 // ini_set('display_errors', '1');


$fecha_ini=$_GET['fecha_ini'];
$fecha_fin=$_GET['fecha_fin'];
$rpt_ver=$_GET['rpt_ver'];

//desde esta parte viene el reporte en si
$fecha_iniconsulta=$fecha_ini;
$fecha_finconsulta=$fecha_fin;


$rpt_territorio=$_GET['rpt_territorio'];
$rpt_persona=$_GET['rpt_persona'];

$fecha_reporte=date("d/m/Y");

$nombre_territorio=nombreTerritorio($rpt_territorio);

echo "<table align='center' class='textotit' width='100%'><tr><td align='center'>Ventas por Vendedor
	<br>Territorio: $nombre_territorio <br> De: $fecha_ini A: $fecha_fin
	<br>Fecha Reporte: $fecha_reporte</tr></table>";
	
$sql="SELECT 
(select t.abreviatura from tipos_docs t where t.codigo=s.cod_tipo_doc)as tipodoc, 
 s.nro_correlativo, s.fecha, s.hora_salida, m.cod_linea_proveedor,
 (select pl.abreviatura_linea_proveedor from proveedores_lineas pl where pl.cod_linea_proveedor=m.cod_linea_proveedor) as lineaproveedor,
 m.codigo_anterior, m.descripcion_material, 
 sd.cantidad_unitaria, sd.precio_unitario, sd.descuento_unitario, sd.monto_unitario,
 (select pre.precio from precios pre where pre.cod_precio=1 and pre.cod_ciudad=1 and pre.codigo_material=m.codigo_material)as precio_registrado,
 (select concat(f.paterno,' ',f.nombres) from funcionarios f where f.codigo_funcionario=s.cod_chofer)as vendedor, s.descuento, ((sd.monto_unitario/s.monto_total)*s.descuento)as descuentocabecera
from salida_almacenes s, salida_detalle_almacenes sd, material_apoyo m
where s.cod_salida_almacenes=sd.cod_salida_almacen and  sd.cod_material=m.codigo_material and 
s.cod_almacen in (select a.cod_almacen from almacenes a where a.cod_ciudad='$rpt_territorio') and s.fecha BETWEEN '$fecha_iniconsulta' and '$fecha_finconsulta' and s.salida_anulada=0  and s.cod_chofer in ($rpt_persona)
and s.cod_tiposalida=1001 order by vendedor, lineaproveedor, s.fecha, m.descripcion_material;";

$resp=mysql_query($sql);

if($rpt_ver==1){
	echo "<br><table align='center' class='texto' width='100%'>
	<tr>
	<th>-</th>
	<th colspan='7'>Vendedor</th>
	<th>-</th>
	<th>-</th>
	<th>Dif.(Bs.)</th>
	<th>Monto Bruto</th>
	<th>Desc.<br>Producto</th>
	<th>Desc.<br>Nota</th>
	<th>Monto Venta</th>
	</tr>";
}else{
	echo "<br><table align='center' class='texto' width='100%'>
	<tr>
	<th>Documento</th>
	<th>Fecha</th>
	<th>Vendedor</th>
	<th>Linea</th>
	<th>Codigo<br>Interno</th>
	<th>Item</th>
	<th>Cantidad</th>
	<th>Precio<br>Sistema</th>
	<th>Precio<br>Venta</th>
	<th>Dif.%</th>
	<th>Dif.(Bs.)</th>
	<th>Monto Bruto</th>
	<th>Desc.<br>Producto</th>
	<th>Desc.<br>Nota</th>
	<th>Monto Venta</th>
	</tr>";
}

$totalVentaBruta=0;
$totalVenta=0;
$totalDescuentos=0;

$subTotalVenta=0;
$subTotalDescuentos=0;
$subTotalDescuentosCab=0;
$subTotalDiferenciaPrecios=0;

$sumaDiferenciaPreciosCabecera=0;

$vendedorPivote="XXX";

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
	
	$nombreVendedor=$datos[13];

	$descuentoCabecera=$datos[14];
	$descuentoCabeceraAplicadoProducto=$datos[15];
	$descuentoCabeceraAplicadoProductoF=formatonumeroDec($descuentoCabeceraAplicadoProducto);

	$montoItem2=$montoItem-$descuentoCabeceraAplicadoProducto;
	
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

	$montoItem=$totalItem-$descuentoVenta-$descuentoCabeceraAplicadoProducto;
	$montoItemF=formatonumeroDec($montoItem);


	$subTotalDescuentos=$subTotalDescuentos+$descuentoVenta;
	$subTotalDescuentosCab=$subTotalDescuentosCab+$descuentoCabeceraAplicadoProducto;
	$subTotalVentaX=$subTotalVenta-$subTotalDescuentos-$subTotalDescuentosCab;

	$totalVenta=$totalVenta+$totalItem;

	$totalDescuentos=$totalDescuentos+$descuentoVenta;
	$totalDescuentosCab=$totalDescuentosCab+$descuentoCabeceraAplicadoProducto;
	$totalVentaMenosDescuentos=$totalVenta-$totalDescuentos-$totalDescuentosCab;

	if($vendedorPivote!=$nombreVendedor && $nombreVendedor!="XXX" ){
		$subTotalUtilidad=$subTotalVenta-$subTotalCosto;
		
		if($diferenciaBsPrecio<0){
			$subTotalDiferenciaPrecios=$subTotalDiferenciaPrecios+$diferenciaBsPrecio;
		}
		$subTotalDiferenciaPreciosF="<span style='color:red; font-size:16px;'>".number_format($subTotalDiferenciaPrecios,2,".",",")."</span>";	
		
		$subTotalVentaXF=number_format($subTotalVentaX,2,".",",");
		if($vendedorPivote!="XXX"){
			$subTotalUtilidadF=number_format($subTotalUtilidad,2,".",",");
			$subTotalVentaF=number_format($subTotalVenta,2,".",",");
			$subTotalDescuentosF=number_format($subTotalDescuentos,2,".",",");
			$subTotalDescuentosCabF=number_format($subTotalDescuentosCab,2,".",",");		
			echo "<tr>
			<td>-</td>
			<td class='textomedianorojo' colspan='7'>$vendedorPivote</td>
			<td>-</td>
			<td>-</td>
			<td>$subTotalDiferenciaPreciosF</td>
			<td align='right' class='textomedianorojo'>$subTotalVentaF</td>
			<td align='right' class='textomedianorojo'>$subTotalDescuentosF</td>
			<td align='right' class='textomedianorojo'>$subTotalDescuentosCabF</td>
			<td align='right' class='textomedianorojo'>$subTotalVentaXF</td>
			</tr>";			
		}
			$subTotalDescuentos=0;
			$subTotalVenta=0;
			$subTotalDiferenciaPrecios=0;
			$subTotalDescuentosCab=0;
			$vendedorPivote=$nombreVendedor;			
	}

	//$subTotalDescuentos=$subTotalDescuentos+$descuentoVenta;
	$subTotalVenta=$subTotalVenta+$totalItem;
	if($diferenciaBsPrecio<0){
		$subTotalDiferenciaPrecios=$subTotalDiferenciaPrecios+$diferenciaBsPrecio;
	}

	if($rpt_ver==2){
		echo "<tr>
		<td>$numeroDoc</td>
		<td>$fechaHora</td>
		<td><small><b>$nombreVendedor</b></small></td>
		<td>$lineaProveedor</td>
		<td>$codInterno</td>
		<td><small><b>$nombreItem</b></small></td>
		<td>$cantidadItemF</td>
		<td align='right'><p style='color:red'>$precioRegistradoF</p></td>
		<td align='right'>$precioItemF</td>
		<td align='right'>$txtDiferenciaPorcentualPrecio</td>
		<td align='right'>$txtDiferenciaBsPrecio</td>
		<td align='right'>$totalItemF</td>
		<td align='right'>$descuentoVentaF</td>
		<td align='right'>$descuentoCabeceraAplicadoProductoF</td>
		<td align='right'>$montoItemF</td>
		</tr>";
	}
}

$subTotalVentaF=number_format($subTotalVenta,2,".",",");
$subTotalDescuentosF=number_format($subTotalDescuentos,2,".",",");
$subTotalDescuentosCabF=number_format($subTotalDescuentosCab,2,".",",");
$subTotalDiferenciaPreciosF="<span style='color:red; font-size:16px;'>".number_format($subTotalDiferenciaPrecios,2,".",",")."</span>";	
$subTotalVentaX=$subTotalVenta-$subTotalDescuentos-$subTotalDescuentosCab;
$subTotalVentaXF=number_format($subTotalVentaX,2,".",",");

if($vendedorPivote!="XXX"){
	echo "<tr>
	<td>-</td>
	<td class='textomedianorojo' colspan='7'>$vendedorPivote</td>
	<td>-</td>
	<td>-</td>
	<td>$subTotalDiferenciaPreciosF</td>
	<td align='right' class='textomedianorojo'>$subTotalVentaF</td>
	<td align='right' class='textomedianorojo'>$subTotalDescuentosF</td>
	<td align='right' class='textomedianorojo'>$subTotalDescuentosCabF</td>
	<td align='right' class='textomedianorojo'>$subTotalVentaXF</td>
	</tr>";			
}

$totalVentaF=formatonumeroDec($totalVenta);
$totalDescuentosF=formatonumeroDec($totalDescuentos);
$totalDescuentosCabF=formatonumeroDec($totalDescuentosCab);

$sumaDiferenciaPreciosCabeceraF="<span style='color:red; font-size:16px;'>$sumaDiferenciaPreciosCabecera</span>";

$totalVentaMenosDescuentosF=formatonumeroDec($totalVentaMenosDescuentos);
echo "<tr>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td class='textograndeazul'>Total:</td>
	<td>&nbsp;</td>
	<td align='right' class='textograndeazul'>$totalVentaF</td>
	<td align='right' class='textograndeazul'>$totalDescuentosF</td>
	<td align='right' class='textograndeazul'>$totalDescuentosCabF</td>
	<td align='right' class='textograndeazul'>$totalVentaMenosDescuentosF</td>
<tr>";

echo "</table>";
include("imprimirInc.php");
?>