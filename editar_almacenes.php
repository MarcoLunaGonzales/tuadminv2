<?php
echo "<script language='Javascript'>
	function validar(f)
	{
		if(f.nombre_almacen.value=='')
		{	alert('El campo Nombre de Almacen esta vacio.');
			f.nombre_almacen.focus();
			return(false);
		}
		f.submit();
	}
	</script>";
require("conexion.inc");
require("estilos.inc");
$sql=mysql_query("select * from almacenes where cod_almacen=$codigo_registro");
$dat=mysql_fetch_array($sql);
$ciudad=$dat[1];
$nombre_almacen=$dat[2];
$codigo_responsable=$dat[3];

echo "<form action='guarda_modi_almacenes.php' method='post'>";
echo "<center><table border='0' class='textotit'><tr><td>Editar Almacenes<td></tr></table></center><br>";
echo "<center><table border='1' class='texto' cellspacing='0'>";
echo "<tr><th>Nombre Almacen</th><th>Territorio</th><th>Responsable</th></tr>";
echo "<input type='hidden' name='codigo' value='$codigo_registro'>";
echo "<tr><td align='center'><input type='text' class='texto' name='nombre_almacen' value='$nombre_almacen' size='40' onKeyUp='javascript:this.value=this.value.toUpperCase();'></td>";
$sql1="select * from ciudades order by descripcion";
$resp1=mysql_query($sql1);
echo "<td><select name='territorio' class='texto'>";
while($dat1=mysql_fetch_array($resp1))
{	$cod_ciudad=$dat1[0];
	$nombre_ciudad=$dat1[1];
	if($cod_ciudad==$ciudad)
	{	echo "<option value='$cod_ciudad' selected>$nombre_ciudad</option>";
	}
	else
	{	echo "<option value='$cod_ciudad'>$nombre_ciudad</option>";
	}
}
echo "</select></td>";
$sql2="select codigo_funcionario, paterno, materno, nombres from funcionarios where cod_cargo=1016 order by paterno, materno";
$resp2=mysql_query($sql2);
echo "<td><select name='responsable' class='texto'>";
while($dat2=mysql_fetch_array($resp2))
{	$cod_funcionario=$dat2[0];
	$nombre_funcionario="$dat2[1] $dat2[2] $dat2[3]";
	if($cod_funcionario==$codigo_responsable)
	{	echo "<option value='$cod_funcionario' selected>$nombre_funcionario</option>";
	}
	else
	{	echo "<option value='$cod_funcionario'>$nombre_funcionario</option>";
	}
}
echo "</select></td>";
echo "</table><br>";
echo"\n<table align='center'><tr><td><a href='navegador_almacenes.php'><img  border='0'src='imagenes/volver.gif' width='15' height='8'>Volver Atras</a></td></tr></table>";
echo "<input type='button' class='boton' value='Guardar' onClick='validar(this.form)'></center>";
echo "</form>";
?>