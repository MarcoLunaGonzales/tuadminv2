<?php
echo "<script language='Javascript'>
	function validar(f)
	{
		if(f.contrasena.value=='')
		{
		  	alert('El campo Contrase�a esta vacio.');
			f.contrasena.focus();
			return(false);
		}
		else
		{
			 if(f.contrasena.value.length < 6)
			 {
			 	alert('La contrase�a debe tener al menos 6 caracteres.');
				return(false);  
			 }
			  
		}
		f.submit();
	}
	</script>";
require("conexion.inc");
require("estilos.inc");
	$sql_cab=mysql_query("select paterno, materno, nombres from funcionarios where codigo_funcionario='$codigo_funcionario'");
	$dat_cab=mysql_fetch_array($sql_cab);
	$nombre_funcionario="$dat_cab[2] $dat_cab[0] $dat_cab[1]";
echo "<form action='guarda_alta_sistema.php' method='get'>";
echo "<h1>Alta en Sistema<br>Funcionario: $nombre_funcionario</h1>";

echo "<center><table class='texto'>";
echo "<tr><th>Usuario</th><th>Clave</th></tr>";
echo "<tr><th>$codigo_funcionario</th><td align='center'><input type='text' class='texto' name='contrasena' size='40'></td></tr>";
echo "<input type='hidden' name='codigo_funcionario' value='$codigo_funcionario'>";
echo "<input type='hidden' name='cod_territorio' value='$cod_territorio'>";
echo "</table></center>";

echo "<div class='divBotones'>
<input type='button' class='boton' value='Guardar' onClick='validar(this.form)'>
<input type='button' class='boton2' value='Cancelar' onClick='location.href=\"navegador_funcionarios.php?cod_ciudad=$cod_territorio\"'>
</div>";

echo "</form>";
echo "<center><table border='0' width='40%'><tr><th>Nota: La contrase�a debe tener al menos 6 caracteres.</th></tr></table></center>";
?>