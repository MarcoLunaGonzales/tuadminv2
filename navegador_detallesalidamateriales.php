<?php

	require("conexion.inc");
	require('estilos_almacenes_central_sincab.php');
	require("funciones.php");
	require("funcion_nombres.php");

	$sqlEmpresa="select nombre, nit, direccion from datos_empresa";
	$respEmpresa=mysql_query($sqlEmpresa);
	$nombreEmpresa=mysql_result($respEmpresa,0,0);
	$nitEmpresa=mysql_result($respEmpresa,0,1);
	$direccionEmpresa=mysql_result($respEmpresa,0,2);
	
	
	$sql="select s.cod_salida_almacenes, s.fecha, ts.nombre_tiposalida, s.observaciones,
	s.nro_correlativo, s.territorio_destino, s.almacen_destino, (select c.nombre_cliente from clientes c where c.cod_cliente=s.cod_cliente),
	(select c.dir_cliente from clientes c where c.cod_cliente=s.cod_cliente),
	s.monto_total, s.descuento, s.monto_final
	FROM salida_almacenes s, tipos_salida ts
	where s.cod_tiposalida=ts.cod_tiposalida and s.cod_almacen='$global_almacen' and s.cod_salida_almacenes='$codigo_salida'";
	$resp=mysql_query($sql);
	$dat=mysql_fetch_array($resp);
	$codigo=$dat[0];
	$fecha_salida=$dat[1];
	$fecha_salida_mostrar="$fecha_salida[8]$fecha_salida[9]-$fecha_salida[5]$fecha_salida[6]-$fecha_salida[0]$fecha_salida[1]$fecha_salida[2]$fecha_salida[3]";
	$nombre_tiposalida=$dat[2];
	$obs_salida=$dat[3];
	$nro_correlativo=$dat[4];
	$territorio_destino=$dat[5];
	$almacen_destino=$dat[6];
	$nombreAlmacenDestino=nombreAlmacen($almacen_destino);

	$nombreCliente=$dat[7];
	$direccionCliente=$dat[8];
	$montoNota=$dat[9];
	$montoNota=redondear2($montoNota);
	$descuentoNota=$dat[10];
	$descuentoNota=redondear2($descuentoNota);
	$montoFinal=$dat[11];
	$montoFinal=redondear2($montoFinal);

		
	echo "<table class='texto' align='center'>";
	echo "<tr><th colspan='3' align='center'>Nota de Remision</th></tr>";
	echo "<tr><th align='left' width='30%'>$nombreEmpresa</th>
	<th align='center' width='30%'>Nro. $nro_correlativo</th>
	<th align='right' width='30%'>Fecha: $fecha_salida_mostrar</th>
	</tr>";
	
	echo "<tr><th align='left' class='bordeNegroTdMod'>Tipo de Salida: $nombre_tiposalida</th>
	<th align='center' class='bordeNegroTdMod'>Almacen Destino: $nombreAlmacenDestino</th><th align='right'>Observaciones: $obs_salida</th></tr>";
			
	echo "</table><br>";

	echo "<table border='0' class='texto' cellspacing='0' width='90%' align='center'>";
	
	echo "<tr><th>Codigo</th><th>Producto</th><th>Cantidad</th><th>CostoUnitario</th><th>CostoItem</th></tr>";
	
	echo "<form method='post' action=''>";
	
	$sql_detalle="select s.cod_material, m.descripcion_material, s.lote, s.fecha_vencimiento, 
		sum(s.cantidad_unitaria), avg(s.costo_almacen)
		from salida_detalle_almacenes s, material_apoyo m
		where s.cod_salida_almacen='$codigo' and s.cod_material=m.codigo_material group by s.cod_material, m.descripcion_material 
		order by m.descripcion_material";
	
	$resp_detalle=mysql_query($sql_detalle);
	$indice=0;
	$montoTotal=0;
	$pesoTotal=0;
	
	$costoTotal=0;

	while($dat_detalle=mysql_fetch_array($resp_detalle))
	{	$cod_material=$dat_detalle[0];
		$nombre_material=$dat_detalle[1];
		$loteProducto=$dat_detalle[2];
		$fechaVencimiento=$dat_detalle[3];
		$cantidad_unitaria=$dat_detalle[4];
		$costoUnitario=$dat_detalle[5];
		$costoItem=$cantidad_unitaria*$costoUnitario;
		
		$costoTotal+=$costoItem;
		
		$cantidadF=redondear2($cantidad_unitaria);
		$costoUnitF=redondear2($costoUnitario);
		$costoItemF=redondear2($costoItem);
		
		echo "<tr>
			<td class='bordeNegroTdMod'>$cod_material</td>
			<td class='bordeNegroTdMod'>$nombre_material</td>
			<td class='bordeNegroTdMod'>$cantidadF</td>
			<td class='bordeNegroTdMod'>$costoUnitF</td>
			<td class='bordeNegroTdMod'>$costoItemF</td>
			</tr>";
		$indice++;
		$montoTotal=$montoTotal+$montoUnitario;
		$montoTotal=redondear2($montoTotal);	
	}
	$costoTotalF=redondear2($costoTotal);
	
	echo "<tr><th>-</th><th>-</th><th>Costo Total</th><th>$costoTotalF</th></tr>";
	echo "</table><br><br><br><br><br>";
	echo "<div><table width='90%'>
	<tr class='bordeNegroTdMod'><td width='33%' align='center'>Despachado</td><td width='33%' align='center'>Entregue Conforme</td><td width='33%' align='center'>Recibi Conforme</td></tr>
	</table></div>";
?>