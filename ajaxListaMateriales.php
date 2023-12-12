<html>
<body>
<table align='center' class="texto">
<tr>
<th>Linea</th><th>CodInterno</th><th>Producto</th><th>Stock</th><th>Precio</th>
</tr>
<?php
require("conexion.inc");
require("funciones.php");

$codTipo=$_GET['codTipo'];
$nombreItem=$_GET['nombreItem'];
$codInterno=$_GET['codInterno'];
$globalAlmacen=$_COOKIE['global_almacen'];
$itemsNoUtilizar=$_GET['arrayItemsUtilizados'];
$globalAgencia=$_COOKIE["global_agencia"];

$soloStock = $_GET["stock"];


	$sql="select m.codigo_material, m.descripcion_material,
	(select concat(p.nombre_proveedor,' ',pl.abreviatura_linea_proveedor) from proveedores p, proveedores_lineas pl where p.cod_proveedor=pl.cod_proveedor and pl.cod_linea_proveedor=m.cod_linea_proveedor) 
	, m.codigo_anterior  from material_apoyo m where estado=1 and m.codigo_material not in ($itemsNoUtilizar)";
	if($nombreItem!=""){
		$sql=$sql. " and descripcion_material like '%$nombreItem%'";
	}
	if($codTipo!=0){
		$sql=$sql. " and m.cod_grupo='$codTipo' ";
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
				<td>$codigoInterno</td>
				<td><div class='textomedianonegro'><a href='javascript:setMateriales(form1, $codigo, \"$nombreCompletoProducto\")'>$nombre</a></div></td>
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