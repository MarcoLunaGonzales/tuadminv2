<?php
require("conexion.inc");
require("estilos.inc");
    $sqlCambioUsd="select valor from cotizaciondolar order by 1 desc limit 1";
	$respUsd=mysql_query($sqlCambioUsd);
	$tipoCambio=1;
	while($filaUSD=mysql_fetch_array($respUsd)){
		$tipoCambio=$filaUSD[0];	
	}

echo "<form action='guarda_almacen_cookie.php' method='post'>";

echo "<h1>Cambiar Almacen Trabajo</h1>";

echo "<center><table class='texto'>";
echo "<tr><th align='left'>Almacen</th>";
echo "<td align='center'>";

$sql1="select cod_almacen, nombre_almacen from almacenes order by nombre_almacen";
$resp1=mysql_query($sql1);
echo "<td align='center'><select name='almacen' id='almacen' class='texto'>";
while($dat1=mysql_fetch_array($resp1))
{   
	$cod_almacen=$dat1[0];
    $nombre_almacen=$dat1[1];
    if($cod_almacen==$_COOKIE['global_almacen']){
      echo "<option value='$cod_almacen' selected>$nombre_almacen</option>";
    }else{
      echo "<option value='$cod_almacen'>$nombre_almacen</option>";	
    }    
}
echo "</select>";

echo	"</td></tr>";
echo "</table></center>";

echo "<div class='divBotones'>
<input type='submit' class='boton' value='Guardar'>
";
echo "</form>";
?>