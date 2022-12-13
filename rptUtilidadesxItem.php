<?php
require('estilos_reportes_almacencentral.php');
require('function_formatofecha.php');
require('conexion.inc');
require('funcion_nombres.php');

$fecha_ini=$_POST['exafinicial'];
$fecha_fin=$_POST['exaffinal'];
$rpt_ver=$_POST['rpt_ver'];

//desde esta parte viene el reporte en si
$fecha_iniconsulta=$fecha_ini;
$fecha_finconsulta=$fecha_fin;


$rpt_territorio=$_POST['rpt_territorio'];
$rpt_grupo=$_POST['rpt_grupo'];
$rpt_ver=$_POST['rpt_ver'];

$arrayRptGrupo=implode(',',$rpt_grupo);
//echo $arrayRptGrupo;

$fecha_reporte=date("d/m/Y");

$nombre_territorio=nombreTerritorio($rpt_territorio);

echo "<h1>Ranking de Utilidades x Linea e Item</h1>
	<h2>Territorio: $nombre_territorio <br> De: $fecha_ini A: $fecha_fin
	<br>Fecha Reporte: $fecha_reporte</h2>";
	
$sql="select m.`codigo_material`, m.`descripcion_material`, 
	(sum(sd.monto_unitario)-sum(sd.descuento_unitario))montoVenta, sum(sd.cantidad_unitaria), sum(sd.cantidad_unitaria*sd.costo_almacen)costo, 
	s.descuento, s.monto_total, ((sum(sd.monto_unitario)-sum(sd.descuento_unitario))-sum(sd.cantidad_unitaria*sd.costo_almacen))as utilidad,
	(select g.nombre_grupo from grupos g where g.cod_grupo=m.cod_grupo)as nombregrupo,
	(select pl.nombre_linea_proveedor from proveedores_lineas pl where pl.cod_linea_proveedor=m.cod_linea_proveedor)as nombrelinea
	from `salida_almacenes` s, `salida_detalle_almacenes` sd, `material_apoyo` m 
	where s.`cod_salida_almacenes`=sd.`cod_salida_almacen` and s.`fecha` BETWEEN '$fecha_iniconsulta' and '$fecha_finconsulta' 
	and m.cod_grupo in ($arrayRptGrupo)
	and s.`salida_anulada`=0 and s.`cod_tiposalida`=1001 and sd.`cod_material`=m.`codigo_material` and
	s.`cod_almacen` in (select a.`cod_almacen` from `almacenes` a where a.`cod_ciudad`='$rpt_territorio')";
	
if($rpt_ver==0){
	$sql.="group by m.`codigo_material` order by 8 desc;";
}
if($rpt_ver==1 || $rpt_ver==2){
	$sql.="	group by m.`codigo_material` order by nombrelinea, utilidad desc;";
}
	
	
$resp=mysql_query($sql);

echo "<center><table class='texto'>
<tr>
<th>Codigo</th>
<th>Linea</th>
<th>Item</th>
<th>Cantidad</th>
<th>Monto Venta</th>
<th>Costo</th>
<th>Utilidades</th>
<th>%</th>
</tr>";

$totalVenta=0;
$totalCosto=0;

$subTotalVenta=0;
$subTotalCosto=0;

$lineaPivote="XXX";

while($datos=mysql_fetch_array($resp)){	
	$codItem=$datos[0];
	$nombreItem=$datos[1];
	$montoVenta=$datos[2];
	$cantidad=$datos[3];
	$montoCosto=$datos[4];
	
	$descuentoVenta=$datos[5];
	$montoNota=$datos[6];
	$nombreGrupo=$datos[8];
	$nombreLinea=$datos[9];
	
	if($descuentoVenta>0){
		$porcentajeVentaProd=($montoVenta/$montoNota);
		$descuentoAdiProducto=($descuentoVenta*$porcentajeVentaProd);
		$montoVenta=$montoVenta-$descuentoAdiProducto;
	}
	
	$montoPtr=number_format($montoVenta,2,".",",");
	$cantidadFormat=number_format($cantidad,2,".",",");
	
	$totalCosto=$totalCosto+$montoCosto;
	$montoCostoFormat=number_format($montoCosto,2,".",",");
	
	$utilidad=$montoVenta-$montoCosto;
	$utilidadFormat=number_format($utilidad,2,".",",");
	$totalVenta=$totalVenta+$montoVenta;
	
	$porcentajeUtilidad=($utilidad/$montoVenta)*100;
	$porcentajeUtilidadF=number_format($porcentajeUtilidad,2,".",",");
	
	
	if($lineaPivote!=$nombreLinea && $nombreLinea!="XXX" && ($rpt_ver==1 || $rpt_ver==2) ){
		$subTotalUtilidad=$subTotalVenta-$subTotalCosto;
		$subTotalUtilidadF=number_format($subTotalUtilidad,2,".",",");
		$subTotalVentaF=number_format($subTotalVenta,2,".",",");
		$subTotalCostoF=number_format($subTotalCosto,2,".",",");
		if($subTotalVenta>0){
			$porcentajeUtilidadLinea=($subTotalUtilidad/$subTotalVenta)*100;
		}else{
			$porcentajeUtilidad=0;
		}
		$porcentajeUtilidadLineaF=number_format($porcentajeUtilidadLinea,2,".",",");

		if($lineaPivote!="XXX"){
			echo "<tr>
			<td>-</td>
			<td class='textomedianorojo'>TOTAL $lineaPivote</td>
			<td>-</td>
			<td></td>
			<td align='right' class='textomedianorojo'>$subTotalVentaF</td>
			<td align='right' class='textomedianorojo'>$subTotalCostoF</td>
			<td align='right' class='textomedianorojo'>$subTotalUtilidadF</td>
			<td align='right' class='textomedianorojo'>$porcentajeUtilidadLineaF %</td>
			</tr>";			
		}
		$subTotalCosto=0;
		$subTotalVenta=0;
		$lineaPivote=$nombreLinea;
	}
	
	$subTotalCosto=$subTotalCosto+$montoCosto;
	$subTotalVenta=$subTotalVenta+$montoVenta;
	
	if($rpt_ver!=2){
		echo "<tr>
		<td>$codItem</td>
		<td>$nombreLinea</td>
		<td>$nombreItem</td>
		<td align='right'>$cantidadFormat</td>
		<td align='right'>$montoPtr</td>
		<td align='right'>$montoCostoFormat</td>
		<td align='right'>$utilidadFormat</td>
		<td align='right'>$porcentajeUtilidadF %</td>
		</tr>";		
	}
}
	
	if($rpt_ver==1 || $rpt_ver==2){
		$subTotalUtilidad=$subTotalVenta-$subTotalCosto;
		$subTotalUtilidadF=number_format($subTotalUtilidad,2,".",",");
		$subTotalVentaF=number_format($subTotalVenta,2,".",",");
		$subTotalCostoF=number_format($subTotalCosto,2,".",",");
		$porcentajeUtilidadLinea=($subTotalUtilidad/$subTotalVenta)*100;
		$porcentajeUtilidadLineaF=number_format($porcentajeUtilidadLinea,2,".",",");

			echo "<tr>
			<td>-</td>
			<td class='textomedianorojo'>TOTAL $lineaPivote</td>
			<td>-</td>
			<td></td>
			<td align='right' class='textomedianorojo'>$subTotalVentaF</td>
			<td align='right' class='textomedianorojo'>$subTotalCostoF</td>
			<td align='right' class='textomedianorojo'>$subTotalUtilidadF</td>
			<td align='right' class='textomedianorojo'>$porcentajeUtilidadLineaF %</td>
			</tr>";					
	}


$totalPtr=number_format($totalVenta,2,".",",");
$totalCostoFormat=number_format($totalCosto,2,".",",");
$totalUtilidad=$totalVenta-$totalCosto;
$totalUtilidadFormat=number_format($totalUtilidad,2,".",",");
$porcentajeUtilidadTotal=($totalUtilidad/$totalVenta)*100;
$porcentajeUtilidadTotalF=number_format($porcentajeUtilidadTotal,2,".",",");

echo "<tr>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td class='textosmallnegro'>Total:</td>
	<td>-</td>
	<td align='right' class='textosmallnegro'>$totalPtr</td>
	<td align='right' class='textosmallnegro'>$totalCostoFormat</td>
	<td align='right' class='textosmallnegro'>$totalUtilidadFormat</td>
	<td align='right' class='textosmallnegro'>$porcentajeUtilidadTotalF %</td>
<tr>";

echo "</table>";
include("imprimirInc.php");
?>