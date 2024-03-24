<?php
session_start();
require("../conexionmysqli.php");
require("../funciones.php");
require("../estilos.inc");

$fechaHoraActual=date("Y-m-d H:i:s");

//$cantReg=contarTipoCambio($codigo,$fi,$ff);
$cantFilas=$_POST["cantidad_filas"];
$codigo=$_POST['codigo_tipo_cambio'];
//guardar las ediciones
for ($i=0;$i<$cantFilas;$i++){
	$valor=$_POST["valor".($i+1)];
	if($valor!=0 || $valor!=""){
		$fecha=$_POST["fecha".($i+1)]; 
	 
		$sql="INSERT INTO tipo_cambiomonedas (cod_moneda,fecha,valor)VALUES ('$codigo','$fecha','$valor')";
		$insertar = mysqli_query($enlaceCon, $sql);
	}
} 

if($insertar==true){
	echo "<script language='Javascript'>
				alert('Los datos fueron insertados correctamente.');
				setTimeout(function() {
					location.href='list.php';
				}, 2000);
				</script>";
}else{
	echo "<script language='Javascript'>
				alert('Hubo un error con el registro.');
				setTimeout(function() {
					location.href='list.php';
				}, 2000);
				</script>";
}


?>