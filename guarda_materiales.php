<?php
require("conexion.inc");
require("estilos_administracion.inc");
$sql="select cod_material from materiales order by cod_material desc";
$resp=mysqli_query($enlaceCon,$sql);
$dat=mysqli_fetch_array($resp);
$num_filas=mysqli_num_rows($resp);
if($num_filas==0)
{	$codigo=1000;
}
else
{	$codigo=$dat[0];
	$codigo++;
}
echo "insert into materiales values($codigo,'$tipo_material','$nombre_material','$producto','$forma_farmaceutica','$presentacion',1)";
$sql_inserta=mysqli_query($enlaceCon,"insert into materiales values($codigo,'$tipo_material','$nombre_material','$producto','$forma_farmaceutica','$presentacion',1)");
echo "<script language='Javascript'>
			alert('Los datos fueron insertados correctamente.');
			location.href='navegador_materiales.php';
			</script>";
?>