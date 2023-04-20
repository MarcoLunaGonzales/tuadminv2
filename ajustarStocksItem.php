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
	require("conexionmysqli.inc");
	require('estilos.inc');
	require('funciones.php');

	$codigoProveedor=$_POST["cod_proveedor"];
	$nombreProducto=$_POST["nombre_producto"];
	$codigoBarras=$_POST["codigo_barras"];
	
	$globalAlmacen=$_COOKIE['global_almacen'];
	
	echo "<h1>Ajuste de Stocks</h1>";

	echo "<form method='post' action='guardarAjusteStocks.php'>";

	echo "<input type='hidden' name='id_linea_proveedor' id='id_linea_proveedor' value='0'>";
	
	$sql="select m.codigo_material, m.descripcion_material, m.estado, 
		(select pl.nombre_linea_proveedor from proveedores p, proveedores_lineas pl where p.cod_proveedor=pl.cod_proveedor and pl.cod_linea_proveedor=m.cod_linea_proveedor),
		m.cantidad_presentacion 
		from material_apoyo m where m.estado='1' ";
	if($codigoProveedor>0){
		$sql.=" and m.cod_linea_proveedor in (select cod_linea_proveedor from proveedores_lineas p where p.cod_proveedor='$codigoProveedor')";
	}
	if($nombreProducto!=""){
		$sql.=" and m.descripcion_material like '%$nombreProducto%' ";		
	}
	if($codigoBarras!=""){
		$sql.=" and m.codigo_barras like '%$codigoBarras%' ";			
	}

	$sql.=" order by m.descripcion_material";	
	
	//echo $sql;
	
	$resp=mysqli_query($enlaceCon,$sql);
	
	echo "</th></tr></table><br>";
		
	echo "<center><table class='texto' width='90%'>";
	echo "<tr><th width='5%'>#</th><th width='30%'>Nombre Producto</th>
		<th width='5%'>CP</th><th width='15%'>Linea</th>";
	$sqlSucursales="select cod_ciudad, descripcion from ciudades order by 1";
	$respSucursales=mysqli_query($enlaceCon,$sqlSucursales);
	while($datSucursales=mysqli_fetch_array($respSucursales)){
		$codCiudadPrecio=$datSucursales[0];
		$nombreCiudadPrecio=$datSucursales[1];
		echo "<th align='center' width='6%'><small>$nombreCiudadPrecio</small></th>";
	}
	echo "<th width='7.5%'>Stock</th><th width='10%'>Stock Ajustado</th></tr>";
	
	$indice_tabla=1;
	while($dat=mysqli_fetch_array($resp))
	{
		$codigo=$dat[0];
		$nombreProd=$dat[1];
		$estado=$dat[2];
		$nombreLinea=$dat[3];
		
		$cantPresentacion=$dat[4];
		

		$stockProducto=stockProducto($globalAlmacen, $codigo);
		$valorStockProducto=$stockProducto;

		if($stockProducto>0){
			$stockProducto="<b class='textomedianorojo' style='color:#C70039'>".$stockProducto."</b>";
		}
		$cadenaPrecios="";
		$sqlSucursales="select cod_ciudad, descripcion from ciudades order by 1";
		$respSucursales=mysqli_query($enlaceCon,$sqlSucursales);
		while($datSucursales=mysqli_fetch_array($respSucursales)){
			$codCiudadPrecio=$datSucursales[0];
			$nombreCiudadPrecio=$datSucursales[1];
			$sqlPrecios="select precio from precios where cod_precio=1 and cod_ciudad='$codCiudadPrecio' and codigo_material='$codigo'";
			//echo $sqlPrecios;
			$respPrecios=mysqli_query($enlaceCon,$sqlPrecios);
			if( $datPrecios = mysqli_fetch_array($respPrecios) ){
				$precio1 = $datPrecios[0];
			}

			if($precio1==0){
				$precio1="-";
			}else{
				$precio1=formatonumeroDec($precio1);
			}
			
			$cadenaPrecios.="<th align='center'>$precio1</th>";
		}
		
		echo "<tr><td align='center'>$indice_tabla</td>
		<td><a href='editar_material_apoyo.php?cod_material=$codigo&pagina_retorno=2'><div class='textopequenorojo2'>$nombreProd</div></a></td>
		<td align='center'>$cantPresentacion</td>
		<td><small>$nombreLinea</small></td>";
		echo $cadenaPrecios;
		echo "<td>$stockProducto</td>
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
