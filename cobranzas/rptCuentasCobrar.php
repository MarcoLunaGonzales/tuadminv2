
<?php
require('../function_formatofecha.php');
require('../conexionmysqli.inc');
require('../funcion_nombres.php');
require('../funciones.php');
require("../estilos_almacenes.inc");

error_reporting(E_ALL);
ini_set('display_errors', '1');


$fecha_fin=$_GET['fecha_fin'];
$fecha_ini=$_GET['fecha_ini'];

$globalAlmacen=$_COOKIE["global_almacen"];


//desde esta parte viene el reporte en si
$fecha_iniconsulta=$fecha_ini;
$fecha_finconsulta=$fecha_fin;

$rpt_cod_vendedor = $_GET['cod_vendedor'];
$rpt_territorio=$_GET['rpt_territorio'];

$fecha_reporte=date("d/m/Y");

$nombre_territorio=nombreTerritorio($rpt_territorio);

// Nombre de Vendedor
$nombreCompletoV = 'TODOS';
if($rpt_cod_vendedor > 0){
	$sql = "SELECT CONCAT(f.nombres, ' ', f.paterno) AS nombre_completo FROM funcionarios f WHERE f.codigo_funcionario = '$rpt_cod_vendedor'";
    $resp = mysqli_query($enlaceCon, $sql);

    if ($resp) {
        $registro = mysqli_fetch_assoc($resp);
        $nombreCompletoV = $registro ? $registro['nombre_completo'] : '';
    }
}

echo "<table align='center' class='textotit' width='90%'><tr><td align='center'>Reporte de Cuentas x Cobrar
	<br>Almacén: $nombre_territorio <br> De: $fecha_ini A: $fecha_fin
	<br>Fecha Reporte: $fecha_reporte
	<br>Vendedor: $nombreCompletoV</tr></table>";

$sql="SELECT s.cod_salida_almacenes, 
		s.nro_correlativo, 
		s.fecha, 
		concat(c.nombre_cliente,' ',c.paterno), 
		s.monto_final,
		(select COALESCE(sum(cbd.monto_detalle), 0)
		from cobros_cab cb, cobros_detalle cbd
		where cb.cod_cobro = cbd.cod_cobro 
		and cbd.cod_venta = s.cod_salida_almacenes
		and cb.cod_estado <> 2) cobrado, 
		DATEDIFF(DATE_ADD(s.fecha, INTERVAL s.dias_credito DAY), CURDATE()) AS dias_credito_faltante,
		(SELECT CONCAT(f.nombres, ' ', f.paterno) FROM funcionarios f WHERE f.codigo_funcionario = s.cod_chofer) as funcionario
	from salida_almacenes s, clientes c 
	where s.monto_final > (
			select COALESCE(sum(cbd.monto_detalle), 0)
			from cobros_cab cb, cobros_detalle cbd
			where cb.cod_cobro = cbd.cod_cobro and cbd.cod_venta = s.cod_salida_almacenes
			and cb.cod_estado <> 2) 
	and s.cod_cliente = c.cod_cliente 
	and s.salida_anulada = 0 
	and s.cod_almacen in (SELECT alm.cod_almacen from almacenes alm where alm.cod_ciudad='$rpt_territorio')
	and s.cod_tiposalida = 1001 
	and s.cod_tipopago = 4 
	and s.fecha between '$fecha_iniconsulta' and '$fecha_finconsulta' ";
if($rpt_cod_vendedor > 0){
	$sql .= " and s.cod_chofer = '$rpt_cod_vendedor' ";
}
$sql .= " ORDER BY c.nombre_cliente,
         s.fecha";	  

// echo $sql;

$resp=mysqli_query($enlaceCon, $sql);

echo "<br><table cellspacing='0' border=1 align='center' class='texto' width='90%'>
<tr>
<th>N.R.</th>
<th>Vendedor</th>
<th>Fecha Registro</th>
<th width='10%' style='text-align: center;'>Días Restantes de Crédito</th>
<th>Cliente</th>
<th>MontoVenta</th>
<th>A Cuenta</th>
<th>Saldo</th>
</tr>";

$totalxCobrar=0;
while($datos=mysqli_fetch_array($resp)){	
	$codSalida		   = $datos[0];
	$nroVenta		   = $datos[1];
	$fechaVenta		   = $datos[2];
	$nombreCliente	   = $datos[3];
	$montoVenta		   = $datos[4];
	$montoCobro		   = $datos[5];
	$funcionarioCobrar = $datos[7];
	$montoxCobrar	   = $montoVenta-$montoCobro;
	$dias_credito_faltante = $datos['dias_credito_faltante'];
	
	
	// $montoCobro=redondear2($montoCobro);
	// $montoxCobrar=redondear2($montoxCobrar);
	// $montoVenta=redondear2($montoVenta);
	
	$montoCobroF=formatonumeroDec($montoCobro);
	$montoxCobrarF=formatonumeroDec($montoxCobrar);
	$montoVentaF=formatonumeroDec($montoVenta);

	if($montoxCobrar>1){
		$totalxCobrar=$totalxCobrar+$montoxCobrar;
		echo "<tr>
		<td align='center'>$nroVenta</td>
		<td>$funcionarioCobrar</td>
		<td align='center' ".($dias_credito_faltante < 0 ? "style='background-color: #ffcccc;color: red; font-weight: bold; font-size: 16px;'" : "").">$fechaVenta</td>
		<td style='text-align: center;'>$dias_credito_faltante</td>
		<td>$nombreCliente</td>
		<td align='right'>$montoVentaF</td>
		<td align='right'>$montoCobroF</td>
		<td align='right'>$montoxCobrarF</td>
		</tr>";
	}
}
$totalxCobrarF=formatonumeroDec($totalxCobrar);

echo "<tr>
		<td colspan='6'>&nbsp;</td>
		<td align='right'><b>Total:</b></td>
		<td align='right'>$totalxCobrarF</td>
	</tr>";

echo "</table>";
?>