<?php
require("conexion.inc");
require("estilos_administracion.inc");
$sql="select cod_tipoingreso, nombre_tipoingreso from tipos_ingreso order by cod_tipoingreso desc";
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
$sql_inserta=mysqli_query($enlaceCon,"insert into tipos_ingreso values($codigo,'$tipo_ingreso','$obs_tipo_ingreso','$tipo_almacen')");
echo "<script language='Javascript'>
			alert('Los datos fueron insertados correctamente.');
			location.href='navegador_tiposingreso.php';
			</script>";
?>