<?php
require("conexion.inc");
require("estilos.inc");

//recogemos variables
$globalAgencia=$_COOKIE['global_agencia'];

$nombreProducto=$_POST['material'];
$nombreProducto = strtoupper($nombreProducto);
$codLinea=$_POST['codLinea'];
$codGrupo=$_POST['cod_grupo'];
$codTipo=$_POST['cod_tipo'];
$observaciones=$_POST['observaciones'];
$codUnidad=$_POST['cod_unidad'];
$precioProducto=$_POST['precio_producto'];
$costoProducto=$_POST['costo_producto'];
$codigoInterno=$_POST['codigo_interno'];
$codigoBarras=$_POST['codigo_barras'];


$fechahora=date("dmy.Hi");
$archivoName=$fechahora.$_FILES['archivo']['name'];
if ($_FILES['archivo']["error"] > 0){
	echo "Error: " . $_FILES['archivo']['error'] . "<br>";
	$archivoName='default.png';
}else{
	move_uploaded_file($_FILES['archivo']['tmp_name'], "imagenesprod/".$archivoName);		
}


$sql="select IFNULL((max(codigo_material)+1),1) as codigo from material_apoyo m";
$resp=mysqli_query($enlaceCon,$sql);
$codigo=mysqli_result($resp,0,0);

$sql_inserta="insert into material_apoyo(codigo_material, descripcion_material, estado, cod_linea_proveedor, cod_grupo, cod_tipomaterial,
cantidad_presentacion, observaciones, imagen, cod_unidad, codigo_anterior, codigo_barras) values ($codigo,'$nombreProducto','1','$codLinea','$codGrupo','$codTipo','1','$observaciones','$archivoName','$codUnidad','$codigoInterno','$codigoBarras')";

//echo $sql_inserta;

$resp_inserta=mysqli_query($enlaceCon,$sql_inserta);

//insertamos los precios
$sqlDel="delete from precios where codigo_material=$codigo";
$respDel=mysqli_query($enlaceCon,$sqlDel);
$sqlInsertPrecio="insert into precios values($codigo, 0,$costoProducto,'$globalAgencia')";
$respInsertPrecio=mysqli_query($enlaceCon,$sqlInsertPrecio);
$sqlInsertPrecio="insert into precios values($codigo, 1,$precioProducto,'$globalAgencia')";
$respInsertPrecio=mysqli_query($enlaceCon,$sqlInsertPrecio);


if($resp_inserta){
		echo "<script language='Javascript'>
			alert('Los datos fueron insertados correctamente.');
			location.href='navegador_material.php?tipo_material=$codTipo';
			</script>";
}else{
	echo "<script language='Javascript'>
			alert('ERROR EN LA TRANSACCION. COMUNIQUESE CON EL ADMIN.');
			history.back();
			</script>";
}
	
?>