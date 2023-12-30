<script>
	function validar(f){
		if(confirm("Esta seguro de proceder. No podra revertir la transaccion!")){
			return true;
		}else{
			return false;
		}
	}
</script>
<?php	
	require("conexion.inc");
	require('estilos.inc');
	require('funciones.php');
	
	$globalAlmacen=$_COOKIE['global_almacen'];
	$lineaDistribuidor=$_GET['linea'];
	
	echo "<h1>Ajuste de Stocks x Linea/Marca de Proveedor</h1>";

	echo "<form method='post' action='guardarAjusteStocks.php'>";

	echo "<input type='hidden' name='id_linea_proveedor' id='id_linea_proveedor' value='$lineaDistribuidor'>";
	
	$sql="select m.codigo_material, m.descripcion_material, m.estado, 
		(select pl.nombre_linea_proveedor from proveedores p, proveedores_lineas pl where p.cod_proveedor=pl.cod_proveedor and pl.cod_linea_proveedor=m.cod_linea_proveedor),
		m.cantidad_presentacion, m.codigo_anterior
		from material_apoyo m
		where m.estado='1' and cod_linea_proveedor=$lineaDistribuidor order by m.descripcion_material";	
	//echo $sql;
	$resp=mysqli_query($enlaceCon,$sql);
	
	echo "</th></tr></table><br>";
		
	echo "<center><table class='texto'>";
	echo "<tr><th>Indice</th><th>&nbsp;</th><th>Codigo Interno</th><th>Nombre Producto</th>
		<th>Cant.Presentacion</th><th>Linea/Marca Proveedor</th><th>Stock</th><th>Stock Ajustado</th></tr>";
	
	$indice_tabla=1;
	while($dat=mysqli_fetch_array($resp))
	{
		$codigo=$dat[0];
		$nombreProd=$dat[1];
		$estado=$dat[2];
		$nombreLinea=$dat[3];
		$cantPresentacion=$dat[4];
		$codigoInterno=$dat[5];
		
		$stockProducto=stockProducto($globalAlmacen, $codigo);
		$valorStockProducto=$stockProducto;

		if($stockProducto>0){
			$stockProducto="<b class='textograndenegro' style='color:#C70039'>".$stockProducto."</b>";
		}
				
		echo "<tr><td align='center'>$indice_tabla</td><td align='center'>
		<input type='checkbox' name='codigo' value='$codigo'></td>
		<td>$codigoInterno</td>
		<td>$nombreProd</td>
		<td>$cantPresentacion</td>
		<td>$nombreLinea</td>
		<td>$stockProducto</td>
		<input type='hidden' name='stock|$codigo' id='stock|$codigo' value='$valorStockProducto' >
		<td><input type='number' step='1' name='producto|$codigo' id='producto|$codigo' value='' style='width: 5em;' class='textogranderojo'></td>
		</tr>";
		$indice_tabla++;
	}
	echo "</table></center><br>";
	echo "<div class='divBotones2'>
	        <input type='submit' class='boton' value='Guardar Ajuste' id='btsubmit' name='btsubmit' onClick='return validar(this.form)'>
        </div>";
						

	echo "</form>";
?>
