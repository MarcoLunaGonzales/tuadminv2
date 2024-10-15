<?php
function saca_nombre_muestra($codigo){	
	require("conexionmysqlipdf.inc");
	$sql="select descripcion from muestras_medicas where codigo='$codigo'";
	$resp=mysqli_query($enlaceCon,$sql);
	$dat=mysqli_fetch_array($resp);
	$nombre_muestra=$dat[0];
	return($nombre_muestra);
}
function nombreProducto($codigo){	
	require("conexionmysqlipdf.inc");
	$sql="select concat(descripcion, ' ',presentacion) from muestras_medicas where codigo='$codigo'";
	$resp=mysqli_query($enlaceCon,$sql);
	$dat=mysqli_fetch_array($resp);
	$nombre_muestra=$dat[0];
	return($nombre_muestra);
}

function nombreGestion($codigo){	
	require("conexionmysqlipdf.inc");
	$sql="select g.`nombre_gestion` from `gestiones` g where g.`codigo_gestion`='$codigo'";
	$resp=mysqli_query($enlaceCon,$sql);
	$nombre=mysqli_result($resp,0,0);
	return($nombre);
}

function nombreLinea($codigo){	
	require("conexionmysqlipdf.inc");
	$sql="select nombre_linea from lineas where codigo_linea='$codigo'";
	$resp=mysqli_query($enlaceCon,$sql);
	$nombre=mysqli_result($resp,0,0);
	return($nombre);
}

function nombreVisitador($codigo){	
	require("conexionmysqlipdf.inc");
	$sql="select concat(paterno,' ',nombres) from funcionarios where codigo_funcionario='$codigo'";
	$resp=mysqli_query($enlaceCon,$sql);
	$nombre=mysqli_result($resp,0,0);
	return($nombre);
}

function nombreTerritorio($codigo){	
	require("conexionmysqlipdf.inc");
	$sql="select descripcion from ciudades where cod_ciudad in ($codigo) ";
	$resp=mysqli_query($enlaceCon,$sql);
	$nombre="";
	while($dat=mysqli_fetch_array($resp)){
		$nombre.=$dat[0]."-";
	}
	return($nombre);
}

function nombreMedico($codigo){	
	require("conexionmysqlipdf.inc");
	$sql="select concat(ap_pat_med,' ', nom_med) from Clientes where cod_med='$codigo'";
	$resp=mysqli_query($enlaceCon,$sql);
	$nombre=mysqli_result($resp,0,0);
	return($nombre);
}

function nombreDia($codigo){	
	require("conexionmysqlipdf.inc");
	$sql="select dia_contacto from orden_dias where id='$codigo'";
	$resp=mysqli_query($enlaceCon,$sql);
	$nombre=mysqli_result($resp,0,0);
	return($nombre);
}


function nombreRutero($codigo){
	require("conexionmysqlipdf.inc");
	$sql="select nombre_rutero from rutero_maestro_cab where cod_rutero='$codigo'";
	$resp=mysqli_query($enlaceCon,$sql);
	$nombre=mysqli_result($resp,0,0);
	return($nombre);
}

function nombreZona($codigo){
	require("conexionmysqlipdf.inc");
	$sql="select zona from zonas where cod_zona='$codigo'";
	$resp=mysqli_query($enlaceCon,$sql);
	$nombre=mysqli_result($resp,0,0);
	return($nombre);
}

function nombreCategoria($codigo, $link){
	require("conexionmysqlipdf.inc");
	$sql="select nombre_categoria from categorias_producto where cod_categoria='$codigo'";
	$resp=mysqli_query($enlaceCon,$sql, $link);
	$nombre=mysqli_result($resp,0,0);
	return($nombre);
}

function nombreCliente($codigo){
	require("conexionmysqlipdf.inc");
	$sql="select nombre_cliente from clientes where cod_cliente='$codigo'";
	$resp=mysqli_query($enlaceCon,$sql);
	$nombre=mysqli_result($resp,0,0);
	return($nombre);
}

function nombreProveedor($codigo){
	require("conexionmysqlipdf.inc");
	$sql="select nombre_proveedor from proveedores where cod_proveedor='$codigo'";
	$resp=mysqli_query($enlaceCon,$sql);
	$nombre=mysqli_result($resp,0,0);
	return($nombre);
}

function nombreAlmacen($codigo){
	require("conexionmysqlipdf.inc");
	$sql="select nombre_almacen from almacenes where cod_almacen='$codigo'";
	//echo $sql;
	$resp=mysqli_query($enlaceCon,$sql);
	if($dat=mysqli_fetch_array($resp)){
		$nombre=$dat[0];		
	}else{
		$nombre="-";
	}
	return($nombre);
}

function nombreGrupo($codigo){
	require("conexionmysqlipdf.inc");
	$sql="select nombre_grupo from grupos where cod_grupo in ($codigo)";
	$resp=mysqli_query($enlaceCon,$sql);
	$nombre="";
	while($dat=mysqli_fetch_array($resp)){
		$nombre.=$dat[0]."-";
	}
	$nombre=substr($nombre,0,100);
	$nombre=$nombre."...";
	return($nombre);
}
function nombreLineaProveedor($codigo){
	require("conexionmysqlipdf.inc");
	$sql="select nombre_linea_proveedor from proveedores_lineas where cod_linea_proveedor='$codigo'";
	$resp=mysqli_query($enlaceCon,$sql);
	$dat=mysqli_fetch_array($resp);
	$nombre=$dat[0];
	//$nombre=mysqli_result($resp,0,0);
	return($nombre);
}

function obtenerNombreProductoSimple($enlaceCon, $codigo){
	require("conexionmysqlipdf.inc");
	$sql="select descripcion_material from material_apoyo where codigo_material=$codigo";
	$resp=mysqli_query($enlaceCon,$sql);
	$nombre="";
	while($dat=mysqli_fetch_array($resp)){
		$nombre=$dat[0];
	}
	return($nombre);
}

function obtenerFuncionariosAsignados($enlaceCon, $codigo){
	require("conexionmysqlipdf.inc");
	$sql="SELECT f.codigo_funcionario, concat(f.paterno,' ',f.materno,' ',f.nombres) from funcionarios f, funcionarios_clientes fc 
		where fc.cod_funcionario=f.codigo_funcionario and fc.cod_cliente='$codigo'";
	$resp=mysqli_query($enlaceCon,$sql);
	$nombre="";
	while($dat=mysqli_fetch_array($resp)){
		$nombre.=$dat[1]."<br>";
	}
	return($nombre);
}


?>