<?php

echo "<script language='Javascript'>
		function enviar_nav()
		{	location.href='registrar_tiposingreso.php';
		}
		function eliminar_nav(f)
		{
			var i;
			var j=0;
			datos=new Array();
			for(i=0;i<=f.length-1;i++)
			{
				if(f.elements[i].type=='checkbox')
				{	if(f.elements[i].checked==true)
					{	datos[j]=f.elements[i].value;
						j=j+1;
					}
				}
			}
			if(j==0)
			{	alert('Debe seleccionar al menos un Tipo de Ingreso para proceder a su eliminaci�n.');
			}
			else
			{
				if(confirm('Esta seguro de eliminar los datos.'))
				{
					location.href='eliminar_tiposingreso.php?datos='+datos+'';
				}
				else
				{
					return(false);
				}
			}
		}

		function editar_nav(f)
		{
			var i;
			var j=0;
			var j_cod_registro;
			for(i=0;i<=f.length-1;i++)
			{
				if(f.elements[i].type=='checkbox')
				{	if(f.elements[i].checked==true)
					{	j_cod_registro=f.elements[i].value;
						j=j+1;
					}
				}
			}
			if(j>1)
			{	alert('Debe seleccionar solamente un Tipo de Ingreso para editar sus datos.');
			}
			else
			{
				if(j==0)
				{
					alert('Debe seleccionar un Tipo de Ingreso para editar sus datos.');
				}
				else
				{
					location.href='editar_tiposingreso.php?codigo_registro='+j_cod_registro+'';
				}
			}
		}
		</script>";
	require("conexion.inc");
	require("estilos_administracion.inc");
	echo "<form method='post' action=''>";
	$sql="select cod_tipoingreso, nombre_tipoingreso, obs_tipoingreso, tipo_almacen from tipos_ingreso order by nombre_tipoingreso";
	$resp=mysql_query($sql);

	echo "<h1>Registro de Tipos de Ingreso</h1>";
	
	echo "<div class='divBotones'>
	<input type='button' value='Adicionar' name='adicionar' class='boton' onclick='enviar_nav()'>
	<input type='button' value='Editar' name='Editar' class='boton' onclick='editar_nav(this.form)'>
	<input type='button' value='Eliminar' name='eliminar' class='boton2' onclick='eliminar_nav(this.form)'>
	</div>";
	
	echo "<center><table class='texto'>";
	echo "<tr><th>&nbsp;</th><th>Nombre de Tipo de Ingreso</th><th>Definicion de Tipo de Ingreso</th><th>Tipo de Almacen</th></tr>";
	while($dat=mysql_fetch_array($resp))
	{
		$codigo=$dat[0];
		$tipo_ingreso=$dat[1];
		$obs_ingreso=$dat[2];
		$tipo_almacen=$dat[3];
		if($tipo_almacen==1)
		{	$desc_tipoalmacen="Almacen Central";
		}
		else
		{	$desc_tipoalmacen="Almacen Regional";
		}
		echo "<tr><td><input type='checkbox' name='codigo' value='$codigo'></td><td>$tipo_ingreso</td><td>&nbsp;$obs_ingreso</td><td>&nbsp;$desc_tipoalmacen</td></tr>";
	}
	echo "</table></center><br>";
	
	echo "<div class='divBotones'>
	<input type='button' value='Adicionar' name='adicionar' class='boton' onclick='enviar_nav()'>
	<input type='button' value='Editar' name='Editar' class='boton' onclick='editar_nav(this.form)'>
	<input type='button' value='Eliminar' name='eliminar' class='boton2' onclick='eliminar_nav(this.form)'>
	</div>";
	
	echo "</form>";
?>