<?php

	require("conexion.inc");
	//require('estilos_inicio_adm.inc');
	$vector=explode(",",$datos);
	$n=sizeof($vector);
	for($i=0;$i<$n;$i++)
	{
		$sql="update material_apoyo set estado=0 where codigo_material=$vector[$i]";
		$resp=mysql_query($sql);
	}
	echo "<script language='Javascript'>
			alert('Los datos fueron eliminados.');
			location.href='navegador_material.php';
			</script>";


?>