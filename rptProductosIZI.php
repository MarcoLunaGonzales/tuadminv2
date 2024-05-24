<?php

	require("conexion.inc");
	require('estilos.inc');
	require("funciones.php");
	require("funcion_nombres.php");
	
	$globalAgencia=1;
	$nombreAgencia=nombreTerritorio($globalAgencia);
	
	echo "<h1>Reporte de Productos</h1>";
	echo "<h2>Agencia: $nombreAgencia</h2>";

	$sql="select m.codigo_material, m.descripcion_material, m.estado, 
		(select e.nombre_grupo from grupos e where e.cod_grupo=m.cod_grupo), 
		(select t.nombre_tipomaterial from tipos_material t where t.cod_tipomaterial=m.cod_tipomaterial), 
		(select pl.nombre_linea_proveedor from proveedores p, proveedores_lineas pl where p.cod_proveedor=pl.cod_proveedor and pl.cod_linea_proveedor=m.cod_linea_proveedor),
		m.observaciones
		from material_apoyo m
		where m.estado='1' and m.cod_tipomaterial in (1,2) order by 4,2";
	
	$resp=mysql_query($sql);
			
	echo "<center><table class='texto'>";
	echo "<tr><th>Codigo</th><th>Nombre Producto</th><th>Unidad</th>
		<th>Categoria</th><th>SubCategoria</th>
		<th>Minimo</th><th>Maximo</th>
		<th>PrecioVenta</th><th>PrecioCompra</th><th>Stock</th></tr>";
	
	$indice_tabla=1;
	while($dat=mysql_fetch_array($resp))
	{
		$codigo=$dat[0];
		$nombreProd=$dat[1];
		$estado=$dat[2];
		$grupo=$dat[3];
		$tipoMaterial=$dat[4];
		$nombreLinea=$dat[5];
		$observaciones=$dat[6];

		$precioVenta=precioVenta($codigo,$globalAgencia);
		$precioVenta=$precioVenta;
		
		$unidadMedida=unidadMedida($codigo);
		
		$costoVenta=costoVenta($codigo, $globalAgencia);

		$stockProducto=stockProducto(1000, $codigo);
		
		echo "<tr><td align='center'>$codigo</td>
		<td>&nbsp;$nombreProd&nbsp;</td>
		<td>&nbsp;$unidadMedida&nbsp;</td>
		<td>&nbsp;$grupo&nbsp;</td>
		<td>&nbsp;$nombreLinea&nbsp;</td>
		<td>-</td>
		<td>-</td>
		<td align='center'>&nbsp;$precioVenta&nbsp;</td>
		<td align='center'>&nbsp;$costoVenta&nbsp;</td>
		<td align='center'>&nbsp;$stockProducto&nbsp;</td>
		</tr>";
		$indice_tabla++;
	}
	echo "</table></center>";
?>
