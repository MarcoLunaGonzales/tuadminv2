<?php
require('function_formatofecha.php');
require('funcion_nombres.php');
require('conexion.inc');
require('estilos_reportes_almacencentral.php');

$fecha_ini=$_GET['fecha_ini'];
$fecha_fin=$_GET['fecha_fin'];


//desde esta parte viene el reporte en si
$fecha_iniconsulta=($fecha_ini);
$fecha_finconsulta=($fecha_fin);

$rpt_territorio=$_GET['rpt_territorio'];

$fecha_reporte=date("d/m/Y");

$nombre_territorio=nombreTerritorio($rpt_territorio);

echo "<h1>Reporte Ventas x Documento y Producto
	<br>Almacen: $nombre_territorio <br> De: $fecha_ini A: $fecha_fin
	<br>Fecha Reporte: $fecha_reporte</h1>";

$sql="select s.`fecha`,  
	(select c.nombre_cliente from clientes c where c.`cod_cliente`=s.cod_cliente) as cliente, 
	s.`razon_social`, s.`observaciones`, 
	(select t.`abreviatura` from `tipos_docs` t where t.`codigo`=s.cod_tipo_doc),
	s.`nro_correlativo`, s.`monto_final`, s.cod_salida_almacenes
	from `salida_almacenes` s where s.`cod_tiposalida`=1001 and s.salida_anulada=0 and
	s.`cod_almacen` in (select a.`cod_almacen` from `almacenes` a where a.`cod_ciudad`='$rpt_territorio')
	and s.`fecha` BETWEEN '$fecha_iniconsulta' and '$fecha_finconsulta' ";

$sql.=" order by s.fecha, s.nro_correlativo";

$resp=mysqli_query($enlaceCon,$sql);

echo "<br><table align='center' class='texto' width='70%'>
<tr>
<th>Fecha</th>
<th>Cliente</th>
<th>Razon Social</th>
<th>Nro.Documento</th>
<th>Monto</th>
<th>
	<table width='100%'>
	<tr>
		<th width='50%'>Producto</th>
		<th width='25%'>Cantidad</th>
		<th width='25%'>Monto</th>
	</tr>
	</table>
</th>
</tr>";

$totalVenta=0;
while($datos=mysqli_fetch_array($resp)){	
	$fechaVenta=$datos[0];
	$nombreCliente=$datos[1];
	$razonSocial=$datos[2];
	$obsVenta=$datos[3];
	$datosDoc=$datos[4]."-".$datos[5];
	$montoVenta=$datos[6];
	$codSalida=$datos[7];
	$montoVentaFormat=number_format($montoVenta,2,".",",");
	
	$totalVenta=$totalVenta+$montoVenta;
	
	$sqlX="select m.`codigo_material`, m.`descripcion_material`, 
	(sum(sd.monto_unitario)-sum(sd.descuento_unitario))montoVenta, sum(sd.cantidad_unitaria), s.descuento, s.monto_total
	from `salida_almacenes` s, `salida_detalle_almacenes` sd, `material_apoyo` m 
	where s.`cod_salida_almacenes`=sd.`cod_salida_almacen` and s.`fecha` BETWEEN '$fecha_iniconsulta' and '$fecha_finconsulta'
	and s.`salida_anulada`=0 and sd.`cod_material`=m.`codigo_material` and
	s.`cod_almacen` in (select a.`cod_almacen` from `almacenes` a where a.`cod_ciudad`='$rpt_territorio') and 
	s.cod_salida_almacenes='$codSalida'
	group by m.`codigo_material` order by 2 desc;";
	
	$respX=mysqli_query($enlaceCon,$sqlX);

	$tablaDetalle="<table width='100%'>";
	
	$totalVentaX=0;
	while($datosX=mysqli_fetch_array($respX)){	
		$codItem=$datosX[0];
		$nombreItem=$datosX[1];
		$montoVenta=$datosX[2];
		$cantidad=$datosX[3];
		
		$descuentoVenta=$datosX[4];
		$montoNota=$datosX[5];
		
		if($descuentoVenta>0){
			$porcentajeVentaProd=($montoVenta/$montoNota);
			$descuentoAdiProducto=($descuentoVenta*$porcentajeVentaProd);
			$montoVenta=$montoVenta-$descuentoAdiProducto;
		}
		
		$montoPtr=number_format($montoVenta,2,".",",");
		$cantidadFormat=number_format($cantidad,0,".",",");
		
		$totalVentaX=$totalVentaX+$montoVenta;
		$tablaDetalle.="<tr>
		<td width='50%'>$nombreItem</td>
		<td width='25%' align='right'>$cantidadFormat</td>
		<td width='25%' align='right'>$montoPtr</td>		
		</tr>";
	}
	$totalPtr=number_format($totalVentaX,2,".",",");
	if($montoVenta-$totalVentaX>0 || $montoVenta-$totalVentaX<0){
		$colorObs="#ff0000";
	}else{
		$colorObs="#ffffff";
	}
	$tablaDetalle.="<tr>
		<td>&nbsp;</td>
		<td><b>Total:</b></td>
		<td align='right'><b>$totalPtr</b></td>
	<tr></table>";

	
	echo "<tr>
	<td>$fechaVenta</td>
	<td>$nombreCliente</td>
	<td>$razonSocial</td>
	<td>$datosDoc</td>
	<td align='right'>$montoVentaFormat</td>
	<td>$tablaDetalle</td>
	</tr>";
}
$totalVentaFormat=number_format($totalVenta,2,".",",");
echo "<tr>
	<td>-</td>
	<td>-</td>
	<td>-</td>
	<td>-</td>
	<td><b>Total Reporte</b></td>
	<td align='right'><b>$totalVentaFormat</b></th>
</tr>";
echo "</table></br>";


include("imprimirInc.php");
?>