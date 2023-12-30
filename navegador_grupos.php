<?php

echo "<script language='Javascript'>
		function enviar_nav()
		{	location.href='registrar_grupos.php';
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
			{	alert('Debe seleccionar al menos un Grupo para proceder a su eliminaci�n.');
			}
			else
			{
				if(confirm('Esta seguro de eliminar los datos.'))
				{
					location.href='eliminar_grupos.php?datos='+datos+'';
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
			{	alert('Debe seleccionar solamente un Grupo para editar sus datos.');
			}
			else
			{
				if(j==0)
				{
					alert('Debe seleccionar un Grupo para editar sus datos.');
				}
				else
				{
					location.href='editar_grupo.php?codigo_registro='+j_cod_registro+'';
				}
			}
		}
		</script>";
	require("conexion.inc");
	require("estilos.inc");
	
	echo "<form method='post' action=''>";
	$sql="SELECT g.cod_grupo, g.nombre_grupo, g.estado, tm.nombre_tipomaterial 
		FROM grupos g
		LEFT JOIN tipos_material tm ON tm.cod_tipomaterial = g.cod_tipomaterial 
		WHERE g.estado=1 order by 2";
	$resp=mysqli_query($enlaceCon,$sql);
	echo "<h1>Registro de Grupos</h1>";
	
	echo "<div class='divBotones'>
	<input type='button' value='Adicionar' name='adicionar' class='boton' onclick='enviar_nav()'>
	<input type='button' value='Editar' name='Editar' class='boton' onclick='editar_nav(this.form)'>
	<input type='button' value='Eliminar' name='eliminar' class='boton2' onclick='eliminar_nav(this.form)'>
	</div>";
	
	
	echo "<center><table class='texto'>";
	echo "<tr><th>&nbsp;</th><th>Grupo</th><th>Tipo Material</th></tr>";
	while($dat=mysqli_fetch_array($resp))
	{
		$codigo 		 	= $dat[0];
		$material 		 	= $dat[1];
		$nombreTipoMaterial = $dat[3];
		echo "<tr><td><input type='checkbox' name='codigo' value='$codigo'></td><td>$material</td><td>$nombreTipoMaterial</td></tr>";
	}
	echo "</table></center><br>";
	
	echo "<div class='divBotones'>
	<input type='button' value='Adicionar' name='adicionar' class='boton' onclick='enviar_nav()'>
	<input type='button' value='Editar' name='Editar' class='boton' onclick='editar_nav(this.form)'>
	<input type='button' value='Eliminar' name='eliminar' class='boton2' onclick='eliminar_nav(this.form)'>
	</div>";
	
	echo "</form>";
?>