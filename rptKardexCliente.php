<?php
require('function_formatofecha.php');
require('conexion.inc');
require('funcion_nombres.php');
require('funciones.php');
require('estilos_almacenes.inc');

$fecha_ini=$_POST['exafinicial'];
$fecha_fin=$_POST['exaffinal'];
$rpt_cliente=$_POST['rpt_cliente'];

//desde esta parte viene el reporte en si
$fecha_iniconsulta=($fecha_ini);
$fecha_finconsulta=($fecha_fin);

$fecha_reporte=date("d/m/Y");

$nombre_cliente=nombreCliente($rpt_cliente);

$sql="select s.`cod_salida_almacenes`, s.`nro_correlativo`, s.`fecha`,
 c.`nombre_cliente`, s.`monto_final`,
       (
         select COALESCE(sum(cbd.monto_detalle), 0)
         from `cobros_cab` cb, `cobros_detalle` cbd
         where cb.cod_cobro=cbd.cod_cobro and cbd.cod_venta=s.`cod_salida_almacenes`
         and cb.cod_estado<>2 and cb.fecha_cobro <'$fecha_iniconsulta' and cb.cod_cliente='$rpt_cliente'
       ) cobrado
from `salida_almacenes` s, clientes c where s.`monto_final` >
      (
        select COALESCE(sum(cbd.monto_detalle), 0)
         from `cobros_cab` cb, `cobros_detalle` cbd
         where cb.cod_cobro=cbd.cod_cobro and cbd.cod_venta=s.`cod_salida_almacenes`
         and cb.cod_estado<>2 and cb.fecha_cobro < '$fecha_iniconsulta' and cb.cod_cliente='$rpt_cliente'
      ) and s.`cod_cliente` = c.`cod_cliente` and c.cod_cliente='$rpt_cliente' and 
      s.`salida_anulada` = 0 and
      s.`fecha` < '$fecha_iniconsulta'";	

      
$resp=mysqli_query($enlaceCon,$sql);
$totalAntCli=0;
while($dat=mysqli_fetch_array($resp)){
	$cuentaxCobrarCli=$dat[4]-$dat[5];
	$totalAntCli=$totalAntCli+$cuentaxCobrarCli;
} 
// Nombre de Cliente
$nombreCliente = '';
if($rpt_cliente > 0){
	$sqlCliente = "SELECT cod_cliente, CONCAT(nombre_cliente,' ',paterno) as nombre_completo FROM clientes WHERE cod_cliente = '$rpt_cliente'";
    $respCliente = mysqli_query($enlaceCon, $sqlCliente);

    if ($respCliente) {
        $registro = mysqli_fetch_assoc($respCliente);
        $nombre_cliente = $registro ? $registro['nombre_completo'] : '';
    }
}

echo "<h1>Reporte Kardex x Cliente</h1>
	<h2>Cliente: $nombre_cliente <br> De: $fecha_ini A: $fecha_fin   --    Fecha Impresion: $fecha_reporte
	<br>Cuenta x Cobrar a fecha Inicio: $totalAntCli
	</h2>";

echo "<center><table class='texto' width='80%'>
<tr>
<th>Fecha</th>
<th>Transaccion</th>
<th>Nro. Venta</th>
<th>Monto Venta</th>
<th>Nro. Cobranza</th>
<th>Monto Cobro</th>
<th>Total</th>
</tr>";

$sqlFechas="(select (s.`fecha`) as fecha from `salida_almacenes` s 
	where s.`cod_cliente`=$rpt_cliente and s.`salida_anulada`=0 and s.fecha BETWEEN '$fecha_iniconsulta' and '$fecha_finconsulta')
	union
	(select (c.`fecha_cobro`) as fecha from `cobros_cab` c where c.`cod_cliente`=$rpt_cliente and c.`cod_estado`<>2
	and c.fecha_cobro BETWEEN '$fecha_iniconsulta' and '$fecha_finconsulta')
	 order by fecha";
$respFechas=mysqli_query($enlaceCon,$sqlFechas);

$saldoNuevo=$totalAntCli;

while($datFechas=mysqli_fetch_array($respFechas)){
	$fechaTr=$datFechas[0];
	
	$sqlVenta=" select concat(t.`abreviatura`,' ',s.`nro_correlativo`), s.`monto_final` from `salida_almacenes` s, `tipos_docs` t 
		 where s.`cod_cliente`=$rpt_cliente and s.`fecha`='$fechaTr' and s.`salida_anulada`=0 
		and s.`cod_tipo_doc`=t.`codigo`";
	
	$respVenta=mysqli_query($enlaceCon,$sqlVenta);
	while($datVenta=mysqli_fetch_array($respVenta)){
		$docVenta=$datVenta[0];
		$montoVenta=$datVenta[1];
		$saldoNuevo=$saldoNuevo+$montoVenta;
		
		$montoVentaF=formatonumeroDec($montoVenta);
		$saldoNuevoF=formatonumeroDec($saldoNuevo);
		
		echo "<tr>
			<td align='center'>$fechaTr</td><td>Venta</td><td>$docVenta</td><td>$montoVentaF</td><td>-</td><td>-</td><td>$saldoNuevoF</td></tr>";
	}
	
	$sqlCobros=" select c.`nro_cobro`,
				cd.`monto_detalle`, concat(t.abreviatura,' ', s.nro_correlativo)
		 from `cobros_cab` c,
			  `cobros_detalle` cd,
			  `salida_almacenes` s,
			  `tipos_docs` t
		 where c.`cod_cobro` = cd.`cod_cobro` and
			   cd.`cod_venta` = s.`cod_salida_almacenes` and
			   s.`cod_tipo_doc` = t.`codigo` and c.`cod_cliente` = $rpt_cliente and
			   c.`cod_estado` <> 2 and
			   c.`fecha_cobro` = '$fechaTr';";
	$respCobros=mysqli_query($enlaceCon,$sqlCobros);
	while($datCobros=mysqli_fetch_array($respCobros)){
		$nroCobro=$datCobros[0];
		$montoCobro=$datCobros[1];
		$ventaAsociada=$datCobros[2];
		
		$saldoNuevo=$saldoNuevo-$montoCobro;
		
		$montoCobroF=formatonumeroDec($montoCobro);

		$saldoNuevoF=formatonumeroDec($saldoNuevo);
		echo "<tr>
			<td align='center'>$fechaTr</td><td>Cobranza</td><td>-</td><td>-</td><td>$nroCobro ($ventaAsociada)</td><td>$montoCobroF</td><td>$saldoNuevoF</td></tr>";
	}
	
}

echo "</table></center>";

?>