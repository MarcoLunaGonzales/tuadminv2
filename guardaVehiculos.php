<?php
require("conexion.inc");
require("estilos.inc");
$sql="select codigo from vehiculos order by codigo desc";
$resp=mysqli_query($enlaceCon,$sql);
$dat=mysqli_fetch_array($resp);
$num_filas=mysqli_num_rows($resp);
if($num_filas==0)
{	$codigo=1;
}
else
{	$codigo=$dat[0];
	$codigo++;
}

$placa=$_POST['placa'];
$nombre=$_POST['nombre'];
$peso=$_POST['peso'];

$sql_inserta="insert into vehiculos values($codigo,'$placa','$nombre','$peso')";
$resp_inserta=mysqli_query($enlaceCon,$sql_inserta);
echo "<script language='Javascript'>
			alert('Los datos fueron insertados correctamente.');
			location.href='navegador_vehiculos.php';
			</script>";
?>