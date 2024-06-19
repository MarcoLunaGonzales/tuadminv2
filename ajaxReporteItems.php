<?php
require("conexionmysqlipdf.inc");

 error_reporting(E_ALL);
 ini_set('display_errors', '1');


$codGrupo=$_GET['codGrupo'];

	echo "<select name='rpt_item' class='texto'>";
	
	$sql_item="select codigo_material, descripcion_material from material_apoyo where cod_grupo='$codGrupo' and codigo_material<>0 order by descripcion_material";
	
	$resp=mysqli_query($enlaceCon, $sql_item);
	echo "<option value=''></option>";
	while($dat=mysqli_fetch_array($resp))
	{	$codigo_item=$dat[0];
		$nombre_item=$dat[1];

		echo "<option value='$codigo_item'>$nombre_item</option>";
	}
	echo "</select>";

?>
