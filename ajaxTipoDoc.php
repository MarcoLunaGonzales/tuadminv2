<?php
require("conexion.inc");
$codTipoSalida=$_GET['codTipoSalida'];

if($codTipoSalida==1001){
	$sql="select codigo, nombre, abreviatura from tipos_docs where codigo in (1,2) order by 2 desc";
}else{
	$sql="select codigo, nombre, abreviatura from tipos_docs where codigo in (3) order by 2 desc";
}
$resp=mysql_query($sql);

echo "<select name='tipoDoc' class='texto' id='tipoDoc' onChange='ajaxNroDoc(form1)' required>";
echo "<option value=''>---</option>";
while($dat=mysql_fetch_array($resp)){
	$codigo=$dat[0];
	$nombre=$dat[1];

	echo "<option value='$codigo'>$nombre</option>";
}
echo "</select>";

?>
