<?php

require("conexion.inc");
require("funciones.php");

 error_reporting(E_ALL);
 ini_set('display_errors', '1');

$usuario = $_POST["usuario"];
$contrasena = $_POST["contrasena"];
$contrasena = str_replace("'", "''", $contrasena);

$sql = "
    SELECT f.cod_cargo, f.cod_ciudad
    FROM funcionarios f, usuarios_sistema u
    WHERE u.codigo_funcionario=f.codigo_funcionario AND u.codigo_funcionario='$usuario' AND u.contrasena='$contrasena' ";

//echo $sql;

$resp = mysqli_query($enlaceCon,$sql);
$num_filas = mysqli_num_rows($resp);
if ($num_filas != 0) {
    $dat = mysqli_fetch_array($resp);
    $cod_cargo = $dat[0];
    $cod_ciudad = $dat[1];
    // $cod_empresa = $dat[2];
    setcookie("global_usuario", $usuario);
    setcookie("global_agencia", $cod_ciudad);
	/**
	 * Datos generales de la Empresa
	 */
	$sql_emp = "SELECT e.cod_empresa, e.nombre, e.nit
				FROM datos_empresa e limit 0,1";
	//			echo $sql_emp;
	$resp_emp 	   = mysqli_query($enlaceCon,$sql_emp);
	$num_filas_emp = mysqli_num_rows($resp_emp);
	if ($num_filas_emp != 0) {
		$data = mysqli_fetch_array($resp_emp);
		$cod_empresa = $data[0]; // Valor por defecto
		$empresa_nombre = $data[1];
		$empresa_nit    = $data[2];
		
	    setcookie("global_cod_empresa", $cod_empresa);
		setcookie("global_empresa_nombre", $empresa_nombre);
		setcookie("global_empresa_nit", $empresa_nit);
	}
	//sacamos la gestion activa
	$sqlGestion="select cod_gestion, nombre_gestion from gestiones where estado=1";
	$respGestion=mysqli_query($enlaceCon,$sqlGestion);
	$globalGestion=mysqli_result($respGestion,0,0);
	$nombreG=mysqli_result($respGestion, 0, 1);
	
	//almacen
	$sql_almacen="select cod_almacen, nombre_almacen from almacenes where cod_ciudad='$cod_ciudad'";
	//echo $sql_almacen;
	$resp_almacen=mysqli_query($enlaceCon,$sql_almacen);
	$dat_almacen=mysqli_fetch_array($resp_almacen);
	$global_almacen=$dat_almacen[0];
	$global_almacen_nombre=$dat_almacen[1];

	setcookie("global_almacen",$global_almacen);
	setcookie("global_almacen_nombre",$global_almacen_nombre);
	setcookie("globalGestion", $globalGestion);
	
	if($cod_cargo==1000){//ADMINISTRADORES
		header("location:indexGerencia.php");
	}elseif($cod_cargo==1001){
		header("location:indexSupervision.php");
	}
	elseif($cod_cargo==1002){
		header("location:indexAlmacenSup.php");
	}elseif($cod_cargo==1019){//CAJA
		header("location:indexAlmacenReg.php");
	}elseif($cod_cargo==1016){//VENDEDOR
		header("location:indexAlmacenCaja.php");
	}elseif ($cod_cargo==1020){
		header("location:indexServiteca.php");
	}


	$stringGlobalAdmins=obtenerValorConfiguracion(0);
	$posBuscada = strpos($stringGlobalAdmins, $usuario);
	if ($posBuscada === true) {
		setcookie("global_admin_cargo", 0);	    
	}else{
		setcookie("global_admin_cargo", 1);		
	}
} else {
    echo "<link href='stilos.css' rel='stylesheet' type='text/css'>
        <form action='problemas_ingreso.php' method='post' name='formulario'>
        <h1>Sus datos de acceso no son correctos.</h1>
        </form>";
}
?>