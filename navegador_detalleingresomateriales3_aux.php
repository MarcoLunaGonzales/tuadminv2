<?php
	require("conexion.inc");
	require('estilos_almacenes_central_sincab.php');
	require("funciones.php");
	$global_almacen=$_COOKIE['global_almacen'];
	$codIngreso=$_GET['codigo_ingreso'];
?>	
<form method='post' action=''>
<?php
	$sql="select i.cod_ingreso_almacen, i.fecha, ti.nombre_tipoingreso, i.observaciones, i.nro_correlativo 
	FROM ingreso_almacenes i, tipos_ingreso ti
	where i.cod_tipoingreso=ti.cod_tipoingreso and i.cod_almacen='".$global_almacen."' and i.cod_ingreso_almacen='".$codIngreso."'";
	
	//echo $sql;
	
	$resp=mysql_query($sql);
?>	
	<center><table border='0' class='textotit'><tr><th>Detalle de Ingreso y Costos de Importacion</th></tr></table></center><br>
	
	<table border='0' class='texto' align='center'>
	<tr><th>Nro. de Ingreso</th><th>Fecha</th><th>Tipo de Ingreso</th><th>Observaciones</th></tr>
	
<?php	
	$dat=mysql_fetch_array($resp);
	$codigo=$dat[0];
	$fecha_ingreso=$dat[1];
	$fecha_ingreso_mostrar="$fecha_ingreso[8]$fecha_ingreso[9]-$fecha_ingreso[5]$fecha_ingreso[6]-$fecha_ingreso[0]$fecha_ingreso[1]$fecha_ingreso[2]$fecha_ingreso[3]";
	$nombre_tipoingreso=$dat[2];
	$obs_ingreso=$dat[3];
	$nro_correlativo=$dat[4];
?>	
	<tr><td align='center'><?=$nro_correlativo;?></td><td align='center'><?=$fecha_ingreso_mostrar;?></td><td><?=$nombre_tipoingreso;?></td><td>&nbsp;<?=$obs_ingreso;?></td></tr>
	</table>
<?php	

	$sql2="select count(*) nroItems, sum(i.cantidad_unitaria*i.precio_bruto)total, sum(metros_cubicos)m3 from ingreso_detalle_almacenes i where i.cod_ingreso_almacen='".$codIngreso."'"; 
	//echo $sql2;
	$resp2=mysql_query($sql2);
	$nroItems=0;
	$total=0;
	$totalCubicaje=0;
	while($dat2=mysql_fetch_array($resp2)){
		$nroItems=$dat2[0];
		$total=$dat2[1];
		$totalCubicaje=$dat2[2];
	}

	//echo  "nroItems:".$nroItems." total".$total;
	$sql_detalle="select m.codigo_anterior, i.cantidad_unitaria, i.costo_almacen, i.lote, DATE_FORMAT(i.fecha_vencimiento, '%d/%m/%Y'),
	m.descripcion_material, m.codigo_material, i.metros_cubicos from ingreso_detalle_almacenes i, material_apoyo m
	where i.cod_ingreso_almacen='".$codIngreso."' 
	and m.codigo_material=i.cod_material order by m.descripcion_material";
	//echo $sql_detalle;
	$resp_detalle=mysql_query($sql_detalle);
?>
	<br><table border=0 class='texto' align='center'>
	<tr><th>&nbsp;</th><th>Codigo</th><th>CodigoInterno</th><th>Producto</th><th>Cantidad</th><th>Costo</th><th>ValorTotal</th><th>CantAnterior</th><th>CostoAnterior</th><th>ValorAnterior</th><th>ValorTotal</th><th>CantidadTotal</th><th>CostoPromedio</th>		
	</tr>
<?php	
	$indice=1;
	$costoImpProd=0;
	$totalCostoImpProd=0;
	$totalMetrosCubicos=0;
	while($dat_detalle=mysql_fetch_array($resp_detalle))
	{	$cod_material=$dat_detalle[0];
		$cantidad_unitaria=$dat_detalle[1];
		$precioNeto=redondear2($dat_detalle[2]);
		$loteProducto=$dat_detalle[3];
		$fechaVenc=$dat_detalle[4];
		$nombre_material=$dat_detalle[5];
		$codigoSistema=$dat_detalle[6];

		$metrosCubicos=$dat_detalle[7];
		
		$totalValorItem=$cantidad_unitaria*$precioNeto;
		$totalMetrosCubicos=$totalMetrosCubicos+$metrosCubicos;
		$cantidad_unitaria=redondear2($cantidad_unitaria);
		$particionProd=0;
		$particionProdFormato=0;
		$participacionCubicaje=0;

		$stockAnterior=stockProductoAFecha(1001, $codigoSistema, "2023-03-09");
		if($stockAnterior<0){
			$stockAnterior=0;
		}

		$precioCostoAnterior=precioVentaCosto($codigoSistema,1);
		$valorAnterior=$stockAnterior*$precioCostoAnterior;

		$valorTotalProducto=$totalValorItem+$valorAnterior;
		$totalCantidadProducto=$cantidad_unitaria+$stockAnterior;

		$precioPromedioProducto=$valorTotalProducto/$totalCantidadProducto;
		
?>
<tr>
<td align='center'><?=$indice;?></td>
	<td><?=$codigoSistema;?></td>
	<td><?=$cod_material;?></td>
	<td><?=$nombre_material;?></td>
	<td align='center'><?=$cantidad_unitaria;?></td>
	<td align='right'><?=$precioNeto;?></td>
	<td align="right"><?=$totalValorItem;?></td>		
	<td align="right"><?=$stockAnterior;?></td>		
	<td align="right"><?=$precioCostoAnterior;?></td>		
	<td align="right"><?=$valorAnterior;?></td>		
	<td align="right"><?=$valorTotalProducto;?></td>		
	<td align="right"><?=$totalCantidadProducto;?></td>		
	<td align="right"><?=$precioPromedioProducto;?></td>		
</tr>
<?php
	}
?>
</table>
		
?>