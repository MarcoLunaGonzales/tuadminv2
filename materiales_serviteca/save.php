<?php
require("../conexion.inc");
require("../estilos2.inc");
require("configModule.php");

$sql="insert into $table (nombre, numero, peso, precio, cod_estado) values('$nombre','$numero','$peso','$precio','1')";
//echo $sql;
$sql_inserta=mysql_query($sql);

echo "<script language='Javascript'>
			alert('Los datos fueron insertados correctamente.');
			location.href='$urlList2';
			</script>";

?>