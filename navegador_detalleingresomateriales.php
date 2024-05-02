<?php
	require("conexionmysqli2.inc");
	//require('estilos_almacenes_central_sincab.php');
	require("funciones.php");
	require("funcion_nombres.php");
	
	error_reporting(E_ALL);
	ini_set('display_errors', '1');

	$global_almacen=$_COOKIE["global_almacen"];
	$codigo_ingreso=$_GET["codigo_ingreso"];

	echo "<form method='post' action=''>";
	$sql="select i.cod_ingreso_almacen, i.fecha, ti.nombre_tipoingreso, i.observaciones, i.nro_correlativo, i.cod_almacen
	FROM ingreso_almacenes i, tipos_ingreso ti
	where i.cod_tipoingreso=ti.cod_tipoingreso and i.cod_almacen='$global_almacen' and i.cod_ingreso_almacen='$codigo_ingreso'";
	//echo $sql;	
	$resp=mysqli_query($enlaceCon,$sql);
	echo "<h2>Detalle de Ingreso</h2><br>";
	
	echo "<table border='0' class='texto' align='center'>";
	echo "<tr><th>Nro. de Ingreso</th><th>Sucursal</th><th>Fecha</th><th>Tipo de Ingreso</th><th>Observaciones</th></tr>";
	$dat=mysqli_fetch_array($resp);
	$codigo=$dat[0];
	$fecha_ingreso=$dat[1];
	$fecha_ingreso_mostrar="$fecha_ingreso[8]$fecha_ingreso[9]-$fecha_ingreso[5]$fecha_ingreso[6]-$fecha_ingreso[0]$fecha_ingreso[1]$fecha_ingreso[2]$fecha_ingreso[3]";
	$nombre_tipoingreso=$dat[2];
	$obs_ingreso=$dat[3];
	$nro_correlativo=$dat[4];
	$codAlmacenIngreso=$dat[5];
	$nombreAlmacen=nombreAlmacen($codAlmacenIngreso);

	echo "<tr><td align='center'>$nro_correlativo</td><td align='center'>$nombreAlmacen</td><td align='center'>$fecha_ingreso_mostrar</td><td>$nombre_tipoingreso</td><td>&nbsp;$obs_ingreso</td></tr>";
	echo "</table>";
	$sql_detalle="select m.codigo_anterior, i.cantidad_unitaria, i.precio_neto, i.lote, DATE_FORMAT(i.fecha_vencimiento, '%d/%m/%Y'), m.descripcion_material, m.codigo_material	from ingreso_detalle_almacenes i, material_apoyo m
	where i.cod_ingreso_almacen='$codigo' and m.codigo_material=i.cod_material order by m.descripcion_material";
	$resp_detalle=mysqli_query($enlaceCon,$sql_detalle);

	echo "<br><table border=0 class='texto' align='center'>";
	echo "<tr><th>&nbsp;</th><th>Codigo</th><th>Producto</th><th>Cantidad</th><th>Lote</th><th>Costo (Bs.)</th><th>Total Costo(Bs.)</th></tr>";
	$indice=1;
	while($dat_detalle=mysqli_fetch_array($resp_detalle))
	{	$cod_material=$dat_detalle[0];
		$cantidad_unitaria=$dat_detalle[1];
		$precioNeto=redondear2($dat_detalle[2]);
		$loteProducto=$dat_detalle[3];
		$fechaVenc=$dat_detalle[4];
		$nombre_material=$dat_detalle[5];
		
		$totalValorItem=$cantidad_unitaria*$precioNeto;
		
		$cantidad_unitaria=redondear2($cantidad_unitaria);
		echo "<tr><td align='center'>$indice</td>
		<td>$cod_material</td>
		<td align='left'>$nombre_material</td>
		<td align='center'>$cantidad_unitaria</td>
		<td align='center'>$loteProducto</td>
		<td align='center'>$precioNeto</td><td align='center'>$totalValorItem</td></tr>";
		$indice++;
	}
	echo "</table>";
	
	echo "<center><a href='javascript:window.print();'><IMG border='no'
	 src='imagenes/print.jpg' width='40'></a></center>";
	
?>