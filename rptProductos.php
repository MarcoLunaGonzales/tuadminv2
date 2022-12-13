<?php

	require("conexion.inc");
	require('estilos.inc');
	require("funciones.php");
	require("funcion_nombres.php");
	
	$globalAgencia=$_GET['rpt_territorio'];
	$rpt_grupo=$_GET['rpt_grupo'];
	$nombreAgencia=nombreTerritorio($globalAgencia);
	
	echo "<h1>Reporte de Productos</h1>";
	echo "<h2>Agencia: $nombreAgencia</h2>";

	$sql="select m.codigo_material, m.descripcion_material, m.estado, 
		(select e.nombre_grupo from grupos e where e.cod_grupo=m.cod_grupo), 
		(select t.nombre_tipomaterial from tipos_material t where t.cod_tipomaterial=m.cod_tipomaterial), 
		(select pl.nombre_linea_proveedor from proveedores p, proveedores_lineas pl where p.cod_proveedor=pl.cod_proveedor and pl.cod_linea_proveedor=m.cod_linea_proveedor),
		m.observaciones, imagen
		from material_apoyo m
		where m.estado='1' and m.cod_tipomaterial in (1,2) and m.cod_grupo in ($rpt_grupo) order by 4,2";
	
	$resp=mysql_query($sql);
			
	echo "<center><table class='texto'>";
	echo "<tr><th>Indice</th><th>Nombre Producto</th><th>Unidad</th>
		<th>Grupo</th><th>Proveedor</th><th>Costo[Bs]</th><th>PrecioVenta[Bs]</th><th>&nbsp;</th></tr>";
	
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
		$imagen=$dat[7];
		$precioVenta=precioVenta($codigo,$globalAgencia);
		$precioVenta=$precioVenta;
		
		$unidadMedida=unidadMedida($codigo);
		
		$costoVenta=costoVenta($codigo, $globalAgencia);
		
		if($imagen=='default.png'){
			$tamanioImagen=80;
		}else{
			$tamanioImagen=200;
		}
		echo "<tr><td align='center'>$indice_tabla</td>
		<td>$nombreProd</td>
		<td>$unidadMedida</td>
		<td>$grupo</td>
		<td>$nombreLinea</td>
		<td align='center'>$costoVenta</td>
		<td align='center'>$precioVenta</td>
		<td align='center'><img src='imagenesprod/$imagen' width='$tamanioImagen'></td>
		</tr>";
		$indice_tabla++;
	}
	echo "</table></center>";
?>
