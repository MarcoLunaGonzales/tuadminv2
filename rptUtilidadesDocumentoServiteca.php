<?php
require('estilos_reportes_almacencentral.php');
require('function_formatofecha.php');
require('conexion.inc');
require('funcion_nombres.php');
require('funciones.php');

$fecha_ini=$_GET['fecha_ini'];
$fecha_fin=$_GET['fecha_fin'];
$rpt_ver=$_GET['rpt_ver'];
$codTipoDoc=$_GET['codTipoDoc'];


//desde esta parte viene el reporte en si

// $fecha_iniconsulta=cambia_formatofecha($fecha_ini);
// $fecha_finconsulta=cambia_formatofecha($fecha_fin);

$fecha_iniconsulta=$fecha_ini;
$fecha_finconsulta=$fecha_fin;

$rpt_territorio=$_GET['rpt_territorio'];

$fecha_reporte=date("d/m/Y");

$nombre_territorio=nombreTerritorio($rpt_territorio);

echo "<h1>Reporte Utilidades x Documento Serviteca</h1>
	<h2>Territorio: $nombre_territorio <br> De: $fecha_ini A: $fecha_fin
	<br>Fecha Reporte: $fecha_reporte</h2>";

$sql="select s.`fecha`,  
	(select c.nombre_cliente from clientes c where c.`cod_cliente`=s.cod_cliente) as cliente, 
	s.`razon_social`, s.`observaciones`, 
	(select t.`abreviatura` from `tipos_docs` t where t.`codigo`=s.cod_tipo_doc),
	s.`nro_correlativo`, s.`monto_final`, s.cod_salida_almacenes
	from `salida_almacenes` s where s.`cod_tiposalida`=1001 and s.salida_anulada=0 and
	s.`cod_almacen` in (select a.`cod_almacen` from `almacenes` a where a.`cod_ciudad`='$rpt_territorio')
	and s.`fecha` BETWEEN '$fecha_iniconsulta' and '$fecha_finconsulta' and 
	s.cod_tipo_doc in ($codTipoDoc)";

if($rpt_ver==1){
	$sql.=" and s.estado_salida=4 ";
}

$sql.=" order by s.fecha, s.nro_correlativo";
	
$resp=mysql_query($sql);

echo "<center><table class='texto'>
<tr>
<th>Fecha</th>
<th>Cliente</th>
<th>Razon Social</th>
<th>Observaciones</th>
<th>Documento</th>
<th>Monto</th>
<th>Gastos Directos</th>
<th>Utilidad</th>
</tr>";

$totalVenta=0;
$totalCosto=0;
while($datos=mysql_fetch_array($resp)){	
	$fechaVenta=$datos[0];
	$nombreCliente=$datos[1];
	$razonSocial=$datos[2];
	$obsVenta=$datos[3];
	$datosDoc=$datos[4]."-".$datos[5];
	$montoVenta=$datos[6];
	$totalVenta=$totalVenta+$montoVenta;
	$montoVentaFormat=number_format($montoVenta,2,".",",");

	$codSalidaAlmacen=$datos[7];

	$sqlCostos="select sum(v.cantidad*v.precio) from ventas_materialesserviteca v where v.cod_venta='$codSalidaAlmacen'";
	//echo $sqlCostos;
	$respCostos=mysql_query($sqlCostos);
	$montoCosto=0;
	if($datCostos=mysql_fetch_array($respCostos)){
		$montoCosto=$datCostos[0];
	}
	$totalCosto=$totalCosto+$montoCosto;
	$montoCostoFormat=number_format($montoCosto,2,".",",");
	
	$utilidad=$montoVenta-$montoCosto;
	$utilidadFormat=number_format($utilidad,2,".",",");
	
	echo "<tr>
	<td>$fechaVenta</td>
	<td>$nombreCliente</td>
	<td>$razonSocial</td>
	<td>$obsVenta</td>
	<td>$datosDoc</td>
	<td>$montoVentaFormat</td>
	<td><a href='materiales_serviteca/materialesServitecaDetalle.php?codVenta=$codSalidaAlmacen' target='_BLANK'>$montoCostoFormat</a></td>
	<td>$utilidadFormat</td>
	</tr>";
}
$totalVentaFormat=number_format($totalVenta,2,".",",");
$totalCostoFormat=number_format($totalCosto,2,".",",");
$totalUtilidad=$totalVenta-$totalCosto;
$totalUtilidadFormat=number_format($totalUtilidad,2,".",",");
echo "<tr>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>Total:</td>
	<td>$totalVentaFormat</td>
	<td>$totalCostoFormat</td>
	<td>$totalUtilidadFormat</td>
<tr>";
echo "</table></br>";



echo "<br><center><table class='texto' style='border: gray 0.5px solid;' width='80%'>";
echo "<tr><td colspan='4'>Detalle de Gastos</td></tr>";
echo "<tr>
	<th align='center'>&nbsp;</th>
	<th align='center'>Tipo Gasto</th>
	<th align='center'>Monto [Bs]</th></tr>";

$consulta = "SELECT g.cod_gasto, (select nombre_tipogasto from tipos_gasto where cod_tipogasto=g.cod_tipogasto)tipogasto, sum(monto)monto, estado from gastos g where fecha_gasto between '$fecha_iniconsulta' and '$fecha_finconsulta' and g.estado=1 and g.cod_ciudad='$rpt_territorio' GROUP BY tipogasto";

//echo $consulta;

$resp = mysql_query($consulta);
$totalGastos=0;
$indice=1;
while ($dat = mysql_fetch_array($resp)) {
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