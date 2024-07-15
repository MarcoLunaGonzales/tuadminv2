<?php
require('estilos_reportes_almacencentral.php');
require('function_formatofecha.php');
require('conexion.inc');
require('funcion_nombres.php');
require('funciones.php');


$fecha_ini=$_POST['fecha_ini'];
$fecha_fin=$_POST['fecha_fin'];
$rptOrdenar=$_POST['rpt_ordenar'];
$rpt_territorio=$_POST['rpt_territorio'];

$rptTerritorio=implode(",",$rpt_territorio);

//desde esta parte viene el reporte en si
$fecha_iniconsulta=$fecha_ini;
$fecha_finconsulta=$fecha_fin;

$fecha_reporte=date("d/m/Y");

$nombre_territorio=nombreTerritorio($rptTerritorio);

echo "<table align='center' class='textotit' width='100%'><tr><td align='center'>Ranking de Ventas x Producto
	<br>Territorio: $nombre_territorio <br> De: $fecha_ini A: $fecha_fin
	<br>Fecha Reporte: $fecha_reporte</tr></table>";
	
$sql="SELECT m.`codigo_material`, m.codigo_anterior, m.`descripcion_material`, 
	(sum(sd.monto_unitario)-sum(sd.descuento_unitario))montoVenta, sum(sd.cantidad_unitaria)cantidadventa, sum(((sd.monto_unitario-sd.descuento_unitario)/s.monto_total)*s.descuento)as descuentocabecera
	from `salida_almacenes` s, `salida_detalle_almacenes` sd, `material_apoyo` m 
	where s.`cod_salida_almacenes`=sd.`cod_salida_almacen` and s.`fecha` BETWEEN '$fecha_iniconsulta' and '$fecha_finconsulta'
	and s.`salida_anulada`=0 and sd.`cod_material`=m.`codigo_material` and s.`cod_tiposalida`=1001 and  
	s.`cod_almacen` in (select a.`cod_almacen` from `almacenes` a where a.`cod_ciudad` in ($rptTerritorio) )
	group by m.`codigo_material` ";

if($rptOrdenar==0){
	$sql=$sql." order by m.descripcion_material ;";
}elseif($rptOrdenar==1){
	$sql=$sql." order by montoVenta desc;";
}elseif($rptOrdenar==2){
	$sql=$sql." order by cantidadventa desc;";
}
	
$resp=mysqli_query($enlaceCon,$sql);

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
$totalVentaProducto=0;
while($datos=mysqli_fetch_array($resp)){	
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
	<td align='right'>$cantidadFormat</td>
	<td align='right'>$montoVentaF</td>
	<td align='right'>$descuentoCabeceraF</td>
	<td align='right'>$montoVentaProductoF</td>
	
	</tr>";
}
$totalVentaProductoF=number_format($totalVentaProducto,2,".",",");
$totalPtr=number_format($totalVenta,2,".",",");
echo "<tr>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>Total:</td>
	<td align='right'><b>$totalVentaProductoF</b></td>
	<td>&nbsp;</td>
	<td align='right'><b>$totalPtr</b></td>
<tr>";

echo "</table>";

?>