<?php
	require("../conexion.inc");
	require("../estilos2.inc");
	require("configModule.php");

	$vector=explode(",",$datos);
	$n=sizeof($vector);
	for($i=0;$i<$n;$i++)
	{
		$sql="update $table set cod_estado=2 where codigo=$vector[$i]";
		$resp=mysql_query($sql);
	}
	echo "<script language='Javascript'>
			alert('Los datos fueron eliminados.');
			location.href='$urlList2';
			</script>";

?>