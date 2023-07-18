<?php
require("conexion.inc");
require("estilos_almacenes.inc");

$vector=explode(",",$_POST['datos_itemsImp']);

//echo $_POST['datos_itemsImp'];

$global_almacen=$_COOKIE["global_almacen"];

$sql1="delete from costos_importacion_ingreso where cod_ingreso_almacen=".$_POST['codIngreso']." and cod_almacen='".$global_almacen."'";
	mysqli_query($enlaceCon,$sql1);
	$n=sizeof($vector);
	for($i=0;$i<$n;$i++){
		$sql="insert into costos_importacion_ingreso (cod_ingreso_almacen,cod_almacen,cod_costoimp,tipo_calculo,monto)
		values(".$_POST['codIngreso'].",'".$global_almacen."','".$vector[$i]."','".$_POST['tipoCalculo'.$vector[$i]]."','".$_POST['monto'.$vector[$i]]."')";
		//echo $sql."<br>";
		mysqli_query($enlaceCon,$sql);
		
	}


?>
<script type='text/javascript' language='javascript'>
  alert('Los Items fueron asociados correctamente al Ingreso.');
   location.href='navegador_ingresomateriales.php';
</script>





