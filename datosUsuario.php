<?php
	require_once 'conexion.inc';
	
	$sql = "select paterno, materno, nombres, cod_ciudad from funcionarios where codigo_funcionario=$global_usuario";
	//echo $sql;
	$resp = mysqli_query($enlaceCon, $sql );
	$dat = mysqli_fetch_array( $resp );
	$paterno = $dat[ 0 ];
	$materno = $dat[ 1 ];
	$nombre = $dat[ 2 ];	
	$nombreUsuarioSesion = "$paterno $nombre";

	$sql = "select descripcion from ciudades where cod_ciudad=$global_agencia";
	$resp = mysqli_query($enlaceCon, $sql );
	$dat = mysqli_fetch_array( $resp );
	$nombreAgenciaSesion = $dat[ 0 ];
	
	$sql_almacen="select cod_almacen, nombre_almacen from almacenes where cod_ciudad='$global_agencia'";
	$resp_almacen=mysqli_query($enlaceCon,$sql_almacen);
	$dat_almacen=mysqli_fetch_array($resp_almacen);
	$nombreAlmacenSesion=$dat_almacen[1];

	date_default_timezone_set('America/La_Paz');
	$fechaSistemaSesion = date( "d-m-Y" );
	$horaSistemaSesion = date( "H:i" );
?>
