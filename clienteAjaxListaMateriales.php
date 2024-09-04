<html>
<body>
<table align='center' class="texto">
<tr><th>Codigo</th><th>Producto</th><th>Stock</th><th>Precio</th></tr>
<?php

require("conexionmysqli2.inc");
require("funciones.php");

$codigoMat=0;
$nomAccion="";
$nomPrincipio="";

$codigoCiudadGlobal=$_COOKIE["global_agencia"];

if(isset($_GET['codigoMat'])){
	$codigoMat=$_GET['codigoMat'];
}
$nombreItem=$_GET['nombreItem'];
$globalAlmacen=$_COOKIE['global_almacen'];
$itemsNoUtilizar=$_GET['arrayItemsUtilizados'];

$fechaActual=date("Y-m-d");

$indexFila=0;

	$sql="select m.codigo_material, m.descripcion_material,
	(select concat(p.nombre_proveedor,'-',pl.nombre_linea_proveedor)as nombre_proveedor
	from proveedores p, proveedores_lineas pl where p.cod_proveedor=pl.cod_proveedor and pl.cod_linea_proveedor=m.cod_linea_proveedor) 
	from material_apoyo m where estado=1 and m.codigo_material not in ($itemsNoUtilizar)";
	if($codigoMat!=""){
		$sql=$sql. " and codigo_material='$codigoMat'";
	}
	if($nombreItem!=""){
		$sql=$sql. " and descripcion_material like '%$nombreItem%' ";
	}
	$sql=$sql." order by 2";
	
	echo $sql;
	
	$resp=mysqli_query($enlaceCon,$sql);

	$numFilas=mysqli_num_rows($resp);
	if($numFilas>0){
		$cont=0;
		while($dat=mysqli_fetch_array($resp)){
			$codigo=$dat[0];
			$nombre=$dat[1];
			$linea=$dat[2];
			
			$nombre=addslashes($nombre);
			$linea=addslashes($linea);
			
			$stockProducto=stockProducto($enlaceCon,$globalAlmacen, $codigo);
			
			$datosProd=$codigo."|".$nombre."|".$linea;		

			$consulta="select p.`precio` from precios p where p.`codigo_material`='$codigo' and p.`cod_precio`='1' and 
					cod_ciudad='$codigoCiudadGlobal'";
					
			$rs=mysqli_query($enlaceCon,$consulta);
			$registro=mysqli_fetch_array($rs);
			$precioProducto=$registro[0];
			if($precioProducto=="")
			{   $precioProducto=0;
			}
			$precioProducto=redondear2($precioProducto);
			$mostrarFila=1;
			if(isset($_GET["stock"])){
				 if($_GET["stock"]==1&&$stockProducto<=0){
                    $mostrarFila=0;
				 }  	              
			}
			if($mostrarFila==1){
				$indexFila++;

			  	if($stockProducto>0){
					$stockProducto="<b class='textograndenegro' style='color:#C70039'>".$stockProducto."</b>";
			  	}
				echo "<tr><td>$codigo</td><td><div class='textograndenegro'><a href='javascript:setMateriales(form1, $codigo, \"$nombre - $linea ($codigo)\", $precioProducto)'>$nombre</a></div></td>
				<td>$linea</td>
				<td>$stockProducto</td>
				<td>$precioProducto</td>
				</tr>";
				$cont++;
			}
		}
	}else{
		echo "<tr><td colspan='3'>Sin Resultados en la busqueda.</td></tr>";
	}
	
?>
</table>

</body>
</html>