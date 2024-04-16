<?php
require("conexion.inc");
require("estilos.inc");
require('funciones.php');

// Configuración | Tipo de Moneda => 1:Bs 2:$us
// $cod_moneda = obtenerValorConfiguracion(10);
$cod_moneda = $_POST['cod_moneda'];

//recogemos variables
$globalAgencia=$_COOKIE['global_agencia'];

$nombreProducto=$_POST['material'];
$nombreProducto = strtoupper($nombreProducto);
$codLinea=$_POST['codLinea'];
$codGrupo=$_POST['cod_grupo'];
$codTipoAro=$_POST['cod_tipoaro'];
$cod_tipopliegue=$_POST['cod_tipopliegue'];
$codTipo=$_POST['cod_tipo'];
$precioProducto=$_POST['precio_producto']??'';
$codigoBarras=$_POST['codigo_barras'];

// $costoProducto=$_POST['costo_producto'];
$costoProducto=0;
// $observaciones=$_POST['observaciones'];
$observaciones='';
// $codUnidad=$_POST['cod_unidad'];
$codUnidad=1;
// $codigoInterno=$_POST['codigo_interno'];
$codigoInterno='';

$modelo = $_POST['modelo'];
$medida = $_POST['medida'];
$capacidad_carga_velocidad = $_POST['capacidad_carga_velocidad'];
$cod_pais_procedencia = $_POST['cod_pais_procedencia'];
$stock_minimo = $_POST['stock_minimo'];


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

$sql_inserta="INSERT INTO material_apoyo(codigo_material, descripcion_material, estado, cod_linea_proveedor, cod_grupo, cod_tipomaterial,
cantidad_presentacion, observaciones, imagen, cod_unidad, codigo_anterior, codigo_barras, modelo, medida, capacidad_carga_velocidad, cod_pais_procedencia,stock_minimo, cod_tipoaro, cod_tipopliegue) values ($codigo,'$nombreProducto','1','$codLinea','$codGrupo','$codTipo','1','$observaciones','$archivoName','$codUnidad','$codigoInterno','$codigoBarras','$modelo','$medida','$capacidad_carga_velocidad', '$cod_pais_procedencia', '$stock_minimo', '$codTipoAro', '$cod_tipopliegue')";

//echo $sql_inserta;

$resp_inserta=mysqli_query($enlaceCon,$sql_inserta);


/**
 * ALMACENA LOS PRECIOS
 */
for ($i = 0; $i < count($_POST['precio']); $i++) {
    $codigo_material = $codigo;
    $cod_precio 	 = $_POST['cod_precio'][$i];
    $precio 		 = empty($_POST['precio'][$i]) ? 0 : $_POST['precio'][$i];
    $cod_ciudad 	 = 1;
    $cod_tipoventa 	 = $_POST['cod_tipoventa'][$i];
    $cantidad_inicio = $_POST['cantidad_inicio'][$i];
    $cantidad_final  = $_POST['cantidad_final'][$i];

    $sql_inserta_precio = "INSERT INTO precios (codigo_material, cod_precio, precio, cod_ciudad, cod_tipoventa, cantidad_inicio, cantidad_final, cod_moneda) 
							VALUES ('$codigo_material','$cod_precio','$precio','$cod_ciudad','$cod_tipoventa','$cantidad_inicio','$cantidad_final', '$cod_moneda')";
    $stmt_precio = mysqli_query($enlaceCon, $sql_inserta_precio);
}

//insertamos los precios
// $sqlDel="delete from precios where codigo_material=$codigo";
// $respDel=mysqli_query($enlaceCon,$sqlDel);
// $sqlInsertPrecio="insert into precios values($codigo, 0,$costoProducto,'$globalAgencia')";
// $respInsertPrecio=mysqli_query($enlaceCon,$sqlInsertPrecio);
// $sqlInsertPrecio="insert into precios values($codigo, 1,$precioProducto,'$globalAgencia')";
// $respInsertPrecio=mysqli_query($enlaceCon,$sqlInsertPrecio);

if($resp_inserta){
		echo "<script language='Javascript'>
			alert('Los datos fueron insertados correctamente.');
			location.href='navegador_material.php';
			</script>";
}else{
	echo "<script language='Javascript'>
			alert('ERROR EN LA TRANSACCION. COMUNIQUESE CON EL ADMIN.');
			history.back();
			</script>";
}
	
?>