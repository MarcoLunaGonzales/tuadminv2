<?php
	require("conexion.inc");
	require("estilos.inc");
	$codRegistro=$_GET['codigo_registro'];
	
	
		$sqlUpd="select cod_sucursal from dosificaciones where cod_dosificacion=$codRegistro";
		//echo $sqlUpd;
		$respUpd=mysqli_query($enlaceCon,$sqlUpd);
		$codSucursal=mysqli_result($respUpd,0,0);
		
		$sqlUpd1="update dosificaciones set cod_estado='3' where cod_sucursal in ($codSucursal) and cod_estado in (1,2)";
		$respUpd1=mysqli_query($enlaceCon,$sqlUpd1);
		
		$sql="update dosificaciones set cod_estado='1' where cod_dosificacion=$codRegistro";
		$resp=mysqli_query($enlaceCon,$sql);


		echo "<script language='Javascript'>
			alert('Activado!');
			location.href='navegador_dosificaciones.php';
			</script>";


?>