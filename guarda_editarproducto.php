<?php
require("conexion.inc");
require("estilos.inc");

//recogemos variables
$globalAgencia=$_COOKIE['global_agencia'];
$codProducto=$_POST['codProducto'];
$nombreProducto=$_POST['material'];
$nombreProducto = strtoupper($nombreProducto);

$codLinea=$_POST['codLinea'];
$codGrupo=$_POST['cod_grupo'];
$codTipo=$_POST['cod_tipo'];
$precioProducto=$_POST['precio_producto'];


// $costoProducto=$_POST['costo_producto'];
$costoProducto=0;
// $observaciones=$_POST['observaciones'];
$observaciones='';
// $codUnidad=$_POST['cod_unidad'];
$codUnidad=1;

$modelo = $_POST['modelo'];
$medida = $_POST['medida'];
$capacidad_carga_velocidad = $_POST['capacidad_carga_velocidad'];
$cod_pais_procedencia = $_POST['cod_pais_procedencia'];
$stock_minimo = $_POST['stock_minimo'];

$sql_inserta="UPDATE material_apoyo set descripcion_material='$nombreProducto', cod_linea_proveedor='$codLinea', 
cod_grupo='$codGrupo', cod_tipomaterial='$codTipo', observaciones='$observaciones', 
cod_unidad='$codUnidad', modelo='$modelo', medida='$medida', capacidad_carga_velocidad='$capacidad_carga_velocidad', cod_pais_procedencia='$cod_pais_procedencia', stock_minimo='$stock_minimo'
WHERE codigo_material='$codProducto'";
$resp_inserta=mysqli_query($enlaceCon,$sql_inserta);

//insertamos los precios
$sqlDel="delete from precios where codigo_material=$codProducto";
$respDel=mysqli_query($enlaceCon,$sqlDel);

// $sqlInsertPrecio="insert into precios values($codProducto, 0,$costoProducto,'$globalAgencia')";
// $respInsertPrecio=mysqli_query($enlaceCon,$sqlInsertPrecio);

// $sqlInsertPrecio="insert into precios values($codProducto, 1,$precioProducto,'$globalAgencia')";
// $respInsertPrecio=mysqli_query($enlaceCon,$sqlInsertPrecio);



/**
 * ALMACENA LOS PRECIOS
 */
for ($i = 0; $i < count($_POST['precio']); $i++) {
    $codigo_material = $codProducto;
    $cod_precio 	 = $_POST['cod_precio'][$i];
    $precio 		 = empty($_POST['precio'][$i]) ? 0 : $_POST['precio'][$i];
    $cod_ciudad 	 = 1;
    $cod_tipoventa 	 = $_POST['cod_tipoventa'][$i];
    $cantidad_inicio = $_POST['cantidad_inicio'][$i];
    $cantidad_final  = $_POST['cantidad_final'][$i];

    $sql_inserta_precio = "INSERT INTO precios (codigo_material, cod_precio, precio, cod_ciudad, cod_tipoventa, cantidad_inicio, cantidad_final) 
							VALUES ('$codigo_material','$cod_precio','$precio','$cod_ciudad','$cod_tipoventa','$cantidad_inicio','$cantidad_final')";
    mysqli_query($enlaceCon, $sql_inserta_precio);
}

if($resp_inserta){
		echo "<script language='Javascript'>
			alert('Los datos fueron guardados correctamente.');
			location.href='navegador_material.php';
			</script>";
}else{
	echo "<script language='Javascript'>
			alert('ERROR EN LA TRANSACCION. COMUNIQUESE CON EL ADMIN.');
			history.back();
			</script>";
}
	

?>