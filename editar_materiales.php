<?php
echo "<script language='Javascript'>
	function validar(f)
	{
		if(f.nombre_material.value=='')
		{	alert('El campo Nombre de Producto esta vacio.');
			f.nombre_material.focus();
			return(false);
		}
		if(f.presentacion.value=='')
		{	alert('El campo Presentaci�n esta vacio.');
			f.presentacion.focus();
			return(false);
		}
		f.submit();
	}
	</script>";
require("conexion.inc");
require("estilos_administracion.inc");
$sql=mysqli_query($enlaceCon,"select * from materiales where cod_material=$codigo_registro");
$dat=mysqli_fetch_array($sql);
$codtipomaterial=$dat[1];
$nombrematerial=$dat[2];
$codproducto=$dat[3];
$codforma=$dat[4];
$presentacion=$dat[5];
echo "<form action='guarda_modi_materiales.php' method='post'>";
echo "<center><table border='0' class='textotit'><tr><td>Editar Productos<td></tr></table></center><br>";
echo "<center><table border='1' class='texto' cellspacing='0'>";
echo "<input type='hidden' name='codigo' value='$codigo_registro'>";
echo "<tr><th align='left'>Tipo de Producto</th>";
$sql1="select * from tipos_material order by nombre_tipomaterial";
$resp1=mysqli_query($enlaceCon,$sql1);
echo "<td><select name='tipo_material' class='texto'>";
while($dat1=mysqli_fetch_array($resp1))
{	$cod_tipomaterial=$dat1[0];
	$nombre_tipomaterial=$dat1[1];
	if($codtipomaterial==$cod_tipomaterial)
	{	echo "<option value='$cod_tipomaterial' selected>$nombre_tipomaterial</option>";
	}
	else
	{	echo "<option value='$cod_tipomaterial'>$nombre_tipomaterial</option>";
	}
}
echo "</select></td>";
echo "</tr>";
echo "<tr><th align='left'>Nombre de Producto</th>";
echo "<td align='center'><input type='text' class='texto' name='nombre_material' value='$nombrematerial' size='40' onKeyUp='javascript:this.value=this.value.toUpperCase();'></td>";
echo "</tr>";
echo "<tr><th align='left'>Producto</th>";
$sql2="select cod_producto, descripcion from productos order by descripcion";
$resp2=mysqli_query($enlaceCon,$sql2);
echo "<td><select name='producto' class='texto'>";
while($dat2=mysqli_fetch_array($resp2))
{	$cod_producto=$dat2[0];
	$nombre_producto=$dat2[1];
	if($cod_producto==$codproducto)
	{	echo "<option value='$cod_producto' selected>$nombre_producto</option>";
	}
	else
	{	echo "<option value='$cod_producto'>$nombre_producto</option>";
	}
}
echo "</select></td>";
echo "</tr>";
echo "<tr><th align='left'>Forma Farmaceutica</th>";
$sql3="select cod_forma, nombre_forma from formas_farmaceuticas order by nombre_forma";
$resp3=mysqli_query($enlaceCon,$sql3);
echo "<td><select name='forma_farmaceutica' class='texto'>";
while($dat3=mysqli_fetch_array($resp3))
{	$cod_forma=$dat3[0];
	$nombre_forma=$dat3[1];
	if($cod_forma==$codforma)
	{	echo "<option value='$cod_forma' selected>$nombre_forma</option>";
	}
	else
	{	echo "<option value='$cod_forma'>$nombre_forma</option>";
	}
}
echo "</select></td>";
echo "</tr>";
echo "</tr>";
echo "<tr><th align='left'>Presentaci�n</th>";
echo "<td align='center'><input type='text' class='texto' name='presentacion' value='$presentacion' size='40' onKeyUp='javascript:this.value=this.value.toUpperCase();'></td>";
echo "</tr>";
echo "</table><br>";
echo"\n<table align='center'><tr><td><a href='navegador_materiales.php'><img  border='0'src='imagenes/volver.gif' width='15' height='8'>Volver Atras</a></td></tr></table>";
echo "<input type='button' class='boton' value='Guardar' onClick='validar(this.form)'></center>";
echo "</form>";
?>