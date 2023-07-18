<?php
require("conexion.inc");
require("estilos.inc");

$codMes=$_POST['codMes'];
$codAnio=$_POST['codAnio'];
$codAlmacen=$_POST['codAlmacen'];

$codMesAux=$codMes+1;
$codAnioAux=$codAnio;
if($codMesAux==13){
	$codMesAux=1;
	$codAnioAux=$codAnioAux+1;
}

$fecha="$codAnioAux-$codMesAux-01";

$fechaIni="$codAnio-$codMes-01";
$fechaFin="$codAnio-$codMes-30";


$sql="select codigo_material from material_apoyo";
$resp=mysqli_query($enlaceCon,$sql);

while($dat=mysqli_fetch_array($resp)){
	$codigoMat=$dat[0];
	$costoUnit=$_POST["$codigoMat"];
	
	$sqlDel="delete from costos_mes_pueps where cod_material='$codigoMat' and mes='$codMes' and anio='$codAnio'";
	$respDel=mysqli_query($enlaceCon,$sqlDel);
	
	//sacamos las existencias del item en el almacen
	$sql_ingresos="select sum(id.cantidad_unitaria) from ingreso_almacenes i, ingreso_detalle_almacenes id
			where i.cod_ingreso_almacen=id.cod_ingreso_almacen and i.fecha<'$fecha' and i.cod_almacen='$codAlmacen'
			and id.cod_material='$codigoMat' and i.ingreso_anulado=0";
	$resp_ingresos=mysqli_query($enlaceCon,$sql_ingresos);
	$dat_ingresos=mysqli_fetch_array($resp_ingresos);
	$cant_ingresos=$dat_ingresos[0];
	$sql_salidas="select sum(sd.cantidad_unitaria) from salida_almacenes s, salida_detalle_almacenes sd
			where s.cod_salida_almacenes=sd.cod_salida_almacen and s.fecha<'$fecha' and s.cod_almacen='$codAlmacen'
			and sd.cod_material='$codigoMat' and s.salida_anulada=0";
	$resp_salidas=mysqli_query($enlaceCon,$sql_salidas);
	$dat_salidas=mysqli_fetch_array($resp_salidas);
	$cant_salidas=$dat_salidas[0];
	$cantExistencia=$cant_ingresos-$cant_salidas;
	//fin sacar existencias
	$valorUnitario=$costoUnit*$cantExistencia;
	
	//sacamos el ultimo ingreso del mes
	
	$sqlUltIng="select i.`cod_ingreso_almacen`, i.`fecha` from `ingreso_almacenes` i, `ingreso_detalle_almacenes` id
		where i.`cod_ingreso_almacen`=id.`cod_ingreso_almacen` and 
		i.`ingreso_anulado`=0 and id.`cod_material`='$codigoMat' and 
		i.`fecha` BETWEEN '$fechaIni' and '$fechaFin' order by i.`fecha` desc";
	$respUltIng=mysqli_query($enlaceCon,$sqlUltIng);
	$nroFilasUlt=mysqli_num_rows($respUltIng);
	if($nroFilasUlt>0){
		$codIngreso=mysqli_result($respUltIng,0,0);
		$fechaIngreso=mysqli_result($respUltIng,0,1);
		
		$sqlInsert="insert into costos_mes_pueps 
			values('$codAnio', '$codMes', '$codAlmacen', '$codigoMat', '$codIngreso', '$fechaIngreso', 
			'$cantExistencia', '$cantExistencia', '$costoUnit')";
		$respInsert=mysqli_query($enlaceCon,$sqlInsert);
	}
	
	//fin ultimo ingreso	
		
}
echo "<script language='Javascript'>
			alert('Los datos fueron modificados correctamente.');
			location.href='navegadorCostosMesAlmacen.php';
			</script>";


?>