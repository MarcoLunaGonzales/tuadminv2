
<?php
require('../function_formatofecha.php');
require('../conexionmysqli.inc');
require('../funcion_nombres.php');
require('../funciones.php');
require('../estilos.inc');

//require("../estilos_almacenes.inc");
// error_reporting(E_ALL);
// ini_set('display_errors', '1');


$fecha_fin=$_GET['fecha_fin'];
$fecha_ini=$_GET['fecha_ini'];

$globalAlmacen=$_COOKIE["global_almacen"];


//desde esta parte viene el reporte en si
$fecha_iniconsulta=$fecha_ini;
$fecha_finconsulta=$fecha_fin;

$rpt_territorio=$_GET['rpt_territorio'];

$rptVer=$_GET['rpt_ver'];

$fecha_reporte=date("d/m/Y");

$nombre_territorio=nombreTerritorio($rpt_territorio);

echo "<table align='center' class='textotit' width='80%'><tr><td align='center'>Reporte de Cuentas x Cobrar
	<br>Territorio: $nombre_territorio <br> De: $fecha_ini A: $fecha_fin
	<br>Fecha Reporte: $fecha_reporte</tr></table>";

$sql="select s.`cod_salida_almacenes`, s.`nro_correlativo`, min(s.`fecha`)as fecha, c.`nombre_cliente`, sum(s.`monto_final`),
       (
         select COALESCE(sum(cbd.monto_detalle), 0)
         from `cobros_cab` cb, `cobros_detalle` cbd
         where cb.cod_cobro=cbd.cod_cobro and cbd.cod_venta=s.`cod_salida_almacenes`
         and cb.cod_estado<>2 and cb.fecha_cobro between '$fecha_iniconsulta' and
               '$fecha_finconsulta'
       ) cobrado, c.cod_cliente
from `salida_almacenes` s, clientes c where s.`monto_final` >
      (
        select COALESCE(sum(cbd.monto_detalle), 0)
         from `cobros_cab` cb, `cobros_detalle` cbd
         where cb.cod_cobro=cbd.cod_cobro and cbd.cod_venta=s.`cod_salida_almacenes`
         and cb.cod_estado<>2 and cb.fecha_cobro between '$fecha_iniconsulta' and
               '$fecha_finconsulta'
      ) and s.`cod_cliente` = c.`cod_cliente` and
      s.`salida_anulada` = 0 and s.cod_almacen='$globalAlmacen' and s.cod_tiposalida=1001 and s.cod_tipopago=4 and 
      s.`fecha` between '$fecha_iniconsulta' and
      '$fecha_finconsulta'";

	$sql.=" GROUP BY s.cod_salida_almacenes, s.nro_correlativo, s.fecha, c.nombre_cliente order by c.nombre_cliente, s.fecha";


$resp=mysqli_query($enlaceCon, $sql);

echo "<br><table cellspacing='0' border=1 align='center' class='texto' width='80%'>
<tr>
<th>N.R.</th>
<th>Fecha</th>
<th>Cliente</th>
<th>MontoVenta</th>
<th>A Cuenta</th>
<th>Saldo</th>
</tr>";

$totalVentas=0;
$totalCobros=0;
$totalxCobrar=0;

$subTotalVentasCliente=0;
$subTotalCobrosCliente=0;
$subTotalXCobrarCliente=0;

$idPivoteCliente=0;
$nombrePivoteCliente="";
$contador=0;
while($datos=mysqli_fetch_array($resp)){	
	$codSalida=$datos[0];
	$nroVenta=$datos[1];
	$fechaVenta=$datos[2];
	$nombreCliente=$datos[3];
	$montoVenta=$datos[4];
	$montoCobro=$datos[5];
	$idCliente=$datos[6];

	if($contador==0){
		$idPivoteCliente=$idCliente;
		$nombrePivoteCliente=$nombreCliente;
	}
	$contador++;

	$montoxCobrar=$montoVenta-$montoCobro;
	
	
	$montoCobro=redondear2($montoCobro);
	$montoxCobrar=redondear2($montoxCobrar);
	$montoVenta=redondear2($montoVenta);
	
	$montoCobroF=formatonumeroDec($montoCobro);
	$montoxCobrarF=formatonumeroDec($montoxCobrar);
	$montoVentaF=formatonumeroDec($montoVenta);

	if($montoxCobrar>1){
		if($idCliente!=$idPivoteCliente){
			$subTotalVentasClienteF=formatonumeroDec($subTotalVentasCliente);
			$subTotalCobrosClienteF=formatonumeroDec($subTotalCobrosCliente);
			$subTotalXCobrarClienteF=formatonumeroDec($subTotalXCobrarCliente);

			echo "<tr style='color:blue;'>
				<td align='center' colspan='2'>SubTotal Cliente:</td>
				<td align='right'>$nombrePivoteCliente</td>
				<td align='right'>$subTotalVentasClienteF</td>
				<td align='right'>$subTotalCobrosClienteF</td>
				<td align='right'>$subTotalXCobrarClienteF</td>
				</tr>";				

			$subTotalVentasCliente=0;
			$subTotalCobrosCliente=0;
			$subTotalXCobrarCliente=0;
			$idPivoteCliente=$idCliente;
			$nombrePivoteCliente=$nombreCliente;
		}

		$totalxCobrar=$totalxCobrar+$montoxCobrar;
		$totalVentas=$totalVentas+$montoVenta;
		$totalCobros=$totalCobros+$montoCobro;

		$subTotalVentasCliente=$subTotalVentasCliente+$montoVenta;
		$subTotalCobrosCliente=$subTotalCobrosCliente+$montoCobro;
		$subTotalXCobrarCliente=$subTotalXCobrarCliente+$montoxCobrar;

		if($rptVer==0){
			echo "<tr>
			<td align='center'>$nroVenta</td>
			<td align='center'>$fechaVenta</td>
			<td>$nombreCliente</td>
			<td align='right'>$montoVentaF</td>
			<td align='right'>$montoCobroF</td>
			<td align='right'>$montoxCobrarF</td>
			</tr>";
		}

	}
}


$subTotalVentasClienteF=formatonumeroDec($subTotalVentasCliente);
$subTotalCobrosClienteF=formatonumeroDec($subTotalCobrosCliente);
$subTotalXCobrarClienteF=formatonumeroDec($subTotalXCobrarCliente);
echo "<tr style='color:blue;'>
		<td align='center' colspan='2'>SubTotal Cliente:</td>
		<td align='right'>$nombreCliente</td>
		<td align='right'>$subTotalVentasClienteF</td>
		<td align='right'>$subTotalCobrosClienteF</td>
		<td align='right'>$subTotalXCobrarClienteF</td>
		</tr>";

$totalVentas=formatonumeroDec($totalVentas);
$totalCobros=formatonumeroDec($totalCobros);
$totalxCobrar=formatonumeroDec($totalxCobrar);

echo "<tr style='color:red;font-size:16pt'>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td align='right'>$totalVentas</td>
	<td align='right'>$totalCobros</td>
	<td align='right'>$totalxCobrar</td>
</tr>";

echo "</table>";
?>