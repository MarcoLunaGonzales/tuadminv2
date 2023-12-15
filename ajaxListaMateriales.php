<html>
<body>
<table align='center' class="texto">
<tr>
<th>Marca</th><th>Procedencia</th><th>Producto</th><th>Stock</th><th>Precio</th>
</tr>
<?php
require("conexion.inc");
require("funciones.php");

$codMarca=$_GET['codMarca'];
$nombreItem=$_GET['nombreItem'];
$codInterno=$_GET['codInterno'];
$globalAlmacen=$_COOKIE['global_almacen'];
$itemsNoUtilizar=$_GET['arrayItemsUtilizados'];
$globalAgencia=$_COOKIE["global_agencia"];

$soloStock = $_GET["stock"];


	$sql="SELECT m.codigo_material, 
		m.descripcion_material,
		(select concat(p.nombre_proveedor,' ',pl.abreviatura_linea_proveedor) from proveedores p, proveedores_lineas pl where p.cod_proveedor=pl.cod_proveedor and pl.cod_linea_proveedor=m.cod_linea_proveedor), 
		m.codigo_anterior, 
		pp.nombre as procedencia  
	FROM material_apoyo m 
	LEFT JOIN pais_procedencia pp ON pp.codigo = m.cod_pais_procedencia
	WHERE m.estado=1 
	AND m.codigo_material not in ($itemsNoUtilizar)";
	if($nombreItem!=""){
		$sql=$sql. " and descripcion_material like '%$nombreItem%'";
	}
	if($codMarca!=0){
		$sql=$sql. " and m.cod_linea_proveedor='$codMarca' ";
	}
	if($codInterno!=""){
		$sql=$sql. " and m.codigo_anterior like '%$codInterno%' ";
	}
	$sql=$sql." order by 2";
	//echo $sql;
	$resp=mysqli_query($enlaceCon,$sql);

	$numFilas=mysqli_num_rows($resp);
	if($numFilas>0){
		while($dat=mysqli_fetch_array($resp)){
			$codigo=$dat[0];
			$nombre=$dat[1];
			$nombre=addslashes($nombre);
			$linea=$dat[2];
			$codigoInterno=$dat[3];

			$nombreProcedencia=$dat[4];
			
			// Obtiene precios de la tabla PRECIOS
			$arrayPrecios = [];
			$sqlPrecio = "SELECT p.cod_tipoventa, p.cantidad_inicio, p.cantidad_final, p.precio
							FROM precios p
							WHERE p.codigo_material = '$codigo'";
			$respPrecios = mysqli_query($enlaceCon,$sqlPrecio);
			while($dataPrecio=mysqli_fetch_array($respPrecios)){
				$arrayPrecios[] = [$dataPrecio['cod_tipoventa'], $dataPrecio['cantidad_inicio'], $dataPrecio['cantidad_final'], $dataPrecio['precio']];
			}
			$arrayPrecios = json_encode($arrayPrecios);
			// Reemplazar comillas dobles por comillas simples
			$jsonPrecios = str_replace('"', "'", $arrayPrecios);
			
			$nombreCompletoProducto=$linea."-".$nombre;
			$nombreCompletoProducto=substr($nombreCompletoProducto,0,90);

			$stockProducto=stockProducto($globalAlmacen, $codigo);
			
			// Verifica si se muestra fila
			$mostrarFila = 1;
			if($soloStock && $stockProducto <= 0){
				$mostrarFila = 0;
			}
			
			if($mostrarFila == 1){
				$consulta="select p.`precio` from precios p where p.`codigo_material`='$codigo' and p.`cod_precio`='1' and cod_ciudad='$globalAgencia'";
				$rs=mysqli_query($enlaceCon,$consulta);
				$registro=mysqli_fetch_array($rs);
				$precioProducto=empty($registro[0]) ? '' : $registro[0];
				if($precioProducto=="")
				{   $precioProducto=0;
				}
				$precioProducto=redondear2($precioProducto);
				
				echo "<tr>
				<td>$linea</td>
				<td>$nombreProcedencia</td>
				<td><div class='textomedianonegro'><a href='javascript:setMateriales(form1, $codigo, \"$nombreCompletoProducto\", \"" . htmlspecialchars($jsonPrecios, ENT_QUOTES, 'UTF-8') . "\", $stockProducto, $precioProducto)'>$nombre</a></div></td>
				<td>$stockProducto</td>
				<td>$precioProducto</td>
				</tr>";
			}
		}
	}else{
		echo "<tr><td colspan='3'>Sin Resultados en la busqueda.</td></tr>";
	}
	
?>
</table>

</body>
</html>