<?php
	require("conexion.inc");
	require('estilos.inc');
	require('funciones.php');
	require('funcion_nombres.php');
	
	$fechaActual=date("Y-m-d");

	$sqlDelete="delete from notificaciones_stocks where fecha_registro='$fechaActual'";
	$respInsert=mysqli_query($enlaceCon, $sqlDelete);

	echo "<h1>Proceso de Stocks Minimos</h1>";
				
	echo "<form method='post' action=''>";
	$sql="SELECT m.codigo_material, m.descripcion_material, m.estado, m.stock_minimo
		from material_apoyo m
		where m.estado='1' and m.stock_minimo>0";

	$resp=mysqli_query($enlaceCon,$sql);

	echo "<center class='scroll-container'><table class='texto' id='myTable'>";
	echo "<thead>";
	echo "<tr><th>-</th>
	<th>Codigo</th>
	<th>Nombre Producto</th>
	<th>Stock Minimo</th>";

	$sqlAlmacenes="SELECT a.cod_almacen, a.nombre_almacen from almacenes a order by 2 asc";
	$respAlmacenes=mysqli_query($enlaceCon, $sqlAlmacenes);
	while($datAlmacenes=mysqli_fetch_array($respAlmacenes)){
		$codAlmacenX=$datAlmacenes[0];
		$nombreAlmacenX=$datAlmacenes[1];
		echo "<th>$nombreAlmacenX</th>";	
	}
	echo "</th>
	<th>Stock Total</th>
	</tr>";
	echo "</thead>";
	
	echo "<tbody>";

	$indice_tabla=1;

	$sw_color_fila = 1;
	while($dat=mysqli_fetch_array($resp))
	{
		$codigo=$dat[0];
		$nombreProd=$dat[1];
		$nombreProd=utf8_decode($nombreProd);
		$estado=$dat[2];	
		$stockMinimo=$dat[3];	

			
		$sqlAlmacenes="SELECT a.cod_almacen, a.nombre_almacen from almacenes a order by 2 asc";
		$respAlmacenes=mysqli_query($enlaceCon, $sqlAlmacenes);
		$stockTotal=0;
		
		$txtDetalleFila="";
		while($datAlmacenes=mysqli_fetch_array($respAlmacenes)){
			$codAlmacenX=$datAlmacenes[0];
			$nombreAlmacenX=$datAlmacenes[1];
			$stockProductoX=stockProducto($codAlmacenX,$codigo);
			if($stockProductoX==0){
				$stockProductoXF="-";
			}else{
				$stockProductoXF=formatonumero($stockProductoX);
				$stockTotal+=$stockProductoX;
			}
			$txtDetalleFila.="<td align='center'>$stockProductoXF</td>";	
		}
		$txtDetalleFila.="<td align='center'><span style='color:blue;font-size:20px;'>$stockTotal</span></td>";	

			echo "<tr $color_fila><td align='center'>$indice_tabla</td>
			<td align='center'>$codigo</td>
			<td><div class='textograndeazul'>$nombreProd</div></td>
			<td><div class='textograndeazul'>$stockMinimo</div></td>";

			echo $txtDetalleFila;

			echo "
			</tr>";			
			
		/*INSERTAR EN LA TABLA DE MINIMOS*/
		if($stockTotal<=$stockMinimo){
			$sqlInsert="insert into notificaciones_stocks (cod_producto, stock_minimo, stock, fecha_registro) values 
			('$codigo', '$stockMinimo', '$stockTotal', '$fechaActual')";
			$respInsert=mysqli_query($enlaceCon, $sqlInsert);
		}


		$indice_tabla++;
	}


	echo "</tbody>";
	echo "</table></center><br>";   
?>
