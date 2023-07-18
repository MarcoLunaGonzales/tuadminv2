<?php
require('conexion.inc');
require('funciones.php');
require('funciones_inventarios.php');

$codAlmacen="1002";
$codGrupos="1000, 1001, 1002, 1003, 1004, 1005, 1006, 1007, 1008, 1009, 1010, 1011, 1012, 1013, 1014, 1015, 1016, 1017, 1018, 1019, 1020, 1021, 1022, 1024, 1025, 1026, 1027, 1028, 1029, 1030, 1031, 1032, 1033, 1034, 1035, 1036, 1037, 1038, 1039, 1040, 1041, 1042, 1043, 1044, 1045, 1046, 1047, 1048, 1050, 1051, 1052, 1053, 1054, 1055, 1056, 1056, 1056";
$codIngreso="3600";
$codSalida="16213";

$fechaCorte="2020-12-21";


$sql="select ma.codigo_material, ma.descripcion_material from material_apoyo ma where ma.cod_grupo in ($codGrupos)";
$resp=mysqli_query($enlaceCon,$sql);
$i=0;
while($dat=mysqli_fetch_array($resp)){
	$codMaterial=$dat[0];
	$nombreMaterial=$dat[1];
	
	
	$arrayCantidadCorte=obtenerSaldoKardexAnterior($codAlmacen, $codMaterial, $fechaCorte);
	$cantidadCorte=$arrayCantidadCorte[0];
	$valorCorte=$arrayCantidadCorte[1];
	$valorCorte=redondear2($valorCorte);
	
	$costoPromedio=0;
	if($cantidadCorte>0){
		$costoPromedio=$valorCorte/$cantidadCorte;
	}
	
	//INSERTAMOS EL DETALLE DE LA SALIDA
	$respuesta=descontar_inventarios($codSalida,$codAlmacen,$codMaterial,$cantidadCorte,0,0,0,$i);
	
	
	//INSERTAMOS EL INGRESO
	$sqlInsert="insert into ingreso_detalle_almacenes(cod_ingreso_almacen, cod_material, cantidad_unitaria, cantidad_restante, lote, fecha_vencimiento, 
	precio_bruto, costo_almacen, costo_actualizado, costo_actualizado_final, costo_promedio, precio_neto) 
	values('$codIngreso','$codMaterial','$cantidadCorte','$cantidadCorte','0','','$costoPromedio','$costoPromedio','$costoPromedio','$costoPromedio','$costoPromedio','$costoPromedio')";
	$sql_inserta2 = mysqli_query($enlaceCon,$sqlInsert);
	
	echo $codMaterial." ".$nombreMaterial." ".$cantidadCorte." ".$valorCorte." ".$costoPromedio."<br>";
	
	$i++;
}


?>