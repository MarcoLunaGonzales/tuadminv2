<?php
require('estilos_reportes_almacencentral.php');
require('function_formatofecha.php');
require('conexion.inc');
require('funcion_nombres.php');
require('funciones.php');

$fecha_ini=$_POST['exafinicial'];
$fecha_fin=$_POST['exaffinal'];
$rpt_ver=$_POST['rpt_ver'];

//desde esta parte viene el reporte en si
$fecha_iniconsulta=$fecha_ini;
$fecha_finconsulta=$fecha_fin;


$rpt_territorio=$_POST['rpt_territorio'];

$fecha_reporte=date("d/m/Y");

$nombre_territorio=nombreTerritorio($rpt_territorio);

echo "<h1>Utilidad Neta x Periodo</h1>
	<h2>Almacen: $nombre_territorio <br> De: $fecha_ini A: $fecha_fin
	<br>Fecha Reporte: $fecha_reporte</h2>";
	
$sql="select (select p.nombre_proveedor from proveedores p, proveedores_lineas pl where p.cod_proveedor=pl.cod_proveedor and pl.cod_linea_proveedor=m.cod_linea_proveedor)as proveedor, sum( (sd.monto_unitario-sd.descuento_unitario) - (((sd.monto_unitario-sd.descuento_unitario)/s.monto_total)*s.descuento))montoVenta, sum(sd.cantidad_unitaria), sum(sd.cantidad_unitaria*sd.costo_almacen)costo, s.descuento, s.monto_total, ((sum(sd.monto_unitario)-sum(sd.descuento_unitario))-sum(sd.cantidad_unitaria*sd.costo_almacen))as utilidad 
	from `salida_almacenes` s, `salida_detalle_almacenes` sd, `material_apoyo` m 
	where s.`cod_salida_almacenes`=sd.`cod_salida_almacen` and s.`fecha` BETWEEN '$fecha_iniconsulta' and '$fecha_finconsulta' 
	and s.`salida_anulada`=0 and s.`cod_tiposalida`=1001 and sd.`cod_material`=m.`codigo_material` and
	s.`cod_almacen` in (select a.`cod_almacen` from `almacenes` a where a.`cod_ciudad`='$rpt_territorio')";
	$sql.="	 group by proveedor order by proveedor, utilidad desc;";

//echo $sql;	
	
$resp=mysqli_query($enlaceCon,$sql);

echo "<center><table class='texto' style='border: gray 0.5px solid;' width='80%'>
<tr>
<td colspan='5' align='center'>Ingresos, Costo de Venta y Utilidad Bruta</td>
</tr>
<tr>
<th>Linea</th>
<th>Monto Venta</th>
<th>Costo Venta</th>
<th>Utilidad Bruta</th>
<th>% Utilidad Bruta</th>
</tr>";

$totalVenta=0;
$totalCosto=0;

$subTotalVenta=0;
$subTotalCosto=0;

while($datos=mysqli_fetch_array($resp)){	
	$nombreProveedor=$datos[0];
	$montoVenta=$datos[1];
	$cantidad=$datos[2];
	$montoCosto=$datos[3];
	
	$descuentoVenta=$datos[4];
	$montoNota=$datos[5];
	
	$montoPtr=number_format($montoVenta,2,".",",");
	$cantidadFormat=number_format($cantidad,2,".",",");
	
	$totalCosto=$totalCosto+$montoCosto;
	$montoCostoFormat=number_format($montoCosto,2,".",",");
	
	$utilidad=$montoVenta-$montoCosto;
	$utilidadFormat=number_format($utilidad,2,".",",");
	$totalVenta=$totalVenta+$montoVenta;
	
	$porcentajeUtilidad=($utilidad/$montoVenta)*100;
	$porcentajeUtilidadF=number_format($porcentajeUtilidad,2,".",",");
	$subTotalCosto=0;
	$subTotalVenta=0;
	$lineaPivote=$nombreLinea;
	
	$subTotalCosto=$subTotalCosto+$montoCosto;
	$subTotalVenta=$subTotalVenta+$montoVenta;
	

	$subTotalUtilidad=$subTotalVenta-$subTotalCosto;
	$subTotalUtilidadF=number_format($subTotalUtilidad,2,".",",");
	$subTotalVentaF=number_format($subTotalVenta,2,".",",");
	$subTotalCostoF=number_format($subTotalCosto,2,".",",");
	$porcentajeUtilidadLinea=($subTotalUtilidad/$subTotalVenta)*100;
	$porcentajeUtilidadLineaF=number_format($porcentajeUtilidadLinea,2,".",",");

	echo "<tr>
	<td>$nombreProveedor</td>
	<td align='right' class='textopequenonegro'>$subTotalVentaF</td>
	<td align='right' class='textopequenonegro'>$subTotalCostoF</td>
	<td align='right' class='textopequenonegro'>$subTotalUtilidadF</td>
	<td align='right' class='textopequenonegro'>$porcentajeUtilidadLineaF %</td>
	</tr>";	
}				

$totalPtr=number_format($totalVenta,2,".",",");
$totalCostoFormat=number_format($totalCosto,2,".",",");
$totalUtilidad=$totalVenta-$totalCosto;
$totalUtilidadFormat=number_format($totalUtilidad,2,".",",");
$porcentajeUtilidadTotal=($totalUtilidad/$totalVenta)*100;
$porcentajeUtilidadTotalF=number_format($porcentajeUtilidadTotal,2,".",",");

echo "<tr>
	<td>-</td>
	<td align='right' class='textosmallnegro'>$totalPtr</td>
	<td align='right' class='textosmallnegro'>$totalCostoFormat</td>
	<td align='right' class='textosmallnegro'>$totalUtilidadFormat</td>
	<td align='right' class='textosmallnegro'>$porcentajeUtilidadTotalF %</td>
<tr>";
echo "</table>";






echo "<br><center><table class='texto' style='border: gray 0.5px solid;' width='80%'>";
echo "<tr><td colspan='4'>Detalle de Gastos</td></tr>";
echo "<tr>
	<th align='center'>&nbsp;</th>
	<th align='center'>Tipo Gasto</th>
	<th align='center'>Monto [Bs]</th></tr>";

$consulta = "SELECT g.cod_gasto, (select nombre_tipogasto from tipos_gasto where cod_tipogasto=g.cod_tipogasto)tipogasto, sum(monto)monto, estado from gastos g where fecha_gasto between '$fecha_iniconsulta' and '$fecha_finconsulta' and g.estado=1 and g.cod_ciudad='$rpt_territorio' GROUP BY tipogasto";

//echo $consulta;

$resp = mysqli_query($enlaceCon,$consulta);
$totalGastos=0;
$indice=1;
while ($dat = mysqli_fetch_array($resp)) {
	$codGasto = $dat[0];
	$tipoGasto=$dat[1];
	$montoGasto = $dat[2];
	$totalGastos=$totalGastos+$montoGasto;
	$codEstado=$dat[3];	
	$montoGasto=redondear2($montoGasto);

	echo "<tr>
	<td align='center'>$indice</td>
	<td align='center'>$tipoGasto</td>
	<td align='right'>$montoGasto</td>
	</tr>";
	$indice++;
}
$totalGastosF=formatonumeroDec($totalGastos);
echo "<tr>
<td align='center'>-</td>
<td align='center' class='textosmallnegro'>Total Gastos</td>
<td align='right' class='textosmallnegro'>$totalGastosF</td>
</tr>";
echo "</table></center><br>";


$utilidadNetaPeriodo=$totalUtilidad-$totalGastos;
$utilidadNetaPeriodoF=formatonumeroDec($utilidadNetaPeriodo);


echo "<br><center><table class='texto' style='border: gray 0.5px solid;' width='80%'>";
echo "<tr><td class='textogranderojo'>Utilidad Neta = $utilidadNetaPeriodoF</td></tr>";
echo "</table>";

?>