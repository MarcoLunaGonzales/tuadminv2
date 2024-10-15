<?php

	require("conexion.inc");
	require("estilos_almacenes.inc");
	require("funciones.php");
	require("funcion_nombres.php");
	
	
	$rptGrupo=$_POST["rpt_grupo"];
	$rptGrupo=implode(",",$rptGrupo);
	

	$rptMarca=$_POST["rpt_marca"];
	$rptMarca=implode(",",$rptMarca);
	

	echo "<h1>Reporte de Precios</h1>";
	
	
	echo "<div id='divCuerpo'>";
	$sql="select codigo_material, descripcion_material, (select g.nombre_grupo from grupos g where g.cod_grupo=ma.cod_grupo)grupo, pl.nombre_linea_proveedor 
		from material_apoyo ma
		INNER JOIN proveedores_lineas pl ON pl.cod_linea_proveedor=ma.cod_linea_proveedor
		INNER JOIN proveedores p ON p.cod_proveedor=pl.cod_proveedor
		WHERE ma.estado=1 and ma.cod_grupo in ($rptGrupo) and ma.cod_linea_proveedor in ($rptMarca) order by 3,2";
//	echo $sql;

	$resp=mysqli_query($enlaceCon,$sql);
	
	echo "<center><table class='texto'>";
	echo "<tr>
	<th>-</th>
	<th>Producto</th>
	<th>Marca</th>
	<th>Grupo</th>
	<th>Precio -10</th>
	<th>Precio Contado > 10</th>
	<th>Precio Credito > 10</th>
	</tr>";
	$indice=1;
	while($dat=mysqli_fetch_array($resp))
	{
		$codigo=$dat[0];
		$nombreMaterial=$dat[1];
		$nombreGrupo=$dat[2];
		$nombreMarca=$dat[3];
		
		// //sacamos existencias
		// $rpt_fecha=date("Y-m-d");
		// $sql_ingresos="select sum(id.cantidad_unitaria) from ingreso_almacenes i, ingreso_detalle_almacenes id
		// where i.cod_ingreso_almacen=id.cod_ingreso_almacen and i.fecha<='$rpt_fecha' and i.cod_almacen='$global_almacen'
		// and id.cod_material='$codigo' and i.ingreso_anulado=0";
		// $resp_ingresos=mysqli_query($enlaceCon,$sql_ingresos);
		// $dat_ingresos=mysqli_fetch_array($resp_ingresos);
		// $cant_ingresos=$dat_ingresos[0];
		// $sql_salidas="select sum(sd.cantidad_unitaria) from salida_almacenes s, salida_detalle_almacenes sd
		// where s.cod_salida_almacenes=sd.cod_salida_almacen and s.fecha<='$rpt_fecha' and s.cod_almacen='$global_almacen'
		// and sd.cod_material='$codigo' and s.salida_anulada=0";
		// $resp_salidas=mysqli_query($enlaceCon,$sql_salidas);
		// $dat_salidas=mysqli_fetch_array($resp_salidas);
		// $cant_salidas=$dat_salidas[0];
		// $stock2=$cant_ingresos-$cant_salidas;

		
		echo "<tr>
		<td align='center'>$indice</td>
		<td>$nombreMaterial </td>
		<td>$nombreMarca</td>
		<td>$nombreGrupo</td>";
	
		$sqlPrecio="select p.`precio` from `precios` p where p.`cod_precio`=0 and p.`codigo_material`=$codigo and p.cod_ciudad='$global_agencia'";
		$respPrecio=mysqli_query($enlaceCon,$sqlPrecio);
		$numFilas=mysqli_num_rows($respPrecio);
		if($numFilas==1){
			$precio0=mysqli_result($respPrecio,0,0);
			$precio0=formatonumeroDec($precio0);
		}else{
			$precio0=0;
			$precio0=formatonumeroDec($precio0);
		}
		
		$sqlPrecio="select p.`precio` from `precios` p where p.`cod_precio`=1 and p.`codigo_material`=$codigo and p.cod_ciudad='1'";
		$respPrecio=mysqli_query($enlaceCon,$sqlPrecio);
		$numFilas=mysqli_num_rows($respPrecio);
		if($numFilas==1){
			$precio1=mysqli_result($respPrecio,0,0);
			$precio1=formatonumeroDec($precio1);
		}else{
			$precio1=0;
			$precio1=formatonumeroDec($precio1);
		}

		$sqlPrecio="select p.`precio` from `precios` p where p.`cod_precio`=2 and p.`codigo_material`=$codigo  and p.cod_ciudad='1'";
		$respPrecio=mysqli_query($enlaceCon,$sqlPrecio);
		$numFilas=mysqli_num_rows($respPrecio);
		if($numFilas==1){
			$precio2=mysqli_result($respPrecio,0,0);
			$precio2=formatonumeroDec($precio2);
		}else{
			$precio2=0;
			$precio2=formatonumeroDec($precio2);
		}

		$sqlPrecio="select p.`precio` from `precios` p where p.`cod_precio`=3 and p.`codigo_material`=$codigo  and p.cod_ciudad='1'";
		$respPrecio=mysqli_query($enlaceCon,$sqlPrecio);
		$numFilas=mysqli_num_rows($respPrecio);
		if($numFilas==1){
			$precio3=mysqli_result($respPrecio,0,0);
			$precio3=formatonumeroDec($precio3);
		}else{
			$precio3=0;
			$precio3=formatonumeroDec($precio3);
		}

		$sqlPrecio="select p.`precio` from `precios` p where p.`cod_precio`=4 and p.`codigo_material`=$codigo  and p.cod_ciudad='1'";
		$respPrecio=mysqli_query($enlaceCon,$sqlPrecio);
		$numFilas=mysqli_num_rows($respPrecio);
		if($numFilas==1){
			$precio4=mysqli_result($respPrecio,0,0);
			$precio4=formatonumeroDec($precio4);
		}else{
			$precio4=0;
			$precio4=formatonumeroDec($precio4);
		}

		$indice++;
		echo "<td align='center'>$precio1</td>";
		echo "<td align='center'>$precio2</td>";
		echo "<td align='center'>$precio3</td>";
		echo "</tr>";
	}
	echo "</table></center><br>";
	echo "</div>";
	
?>
</form>

