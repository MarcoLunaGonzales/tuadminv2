<?php
require("conexion.inc");
require("estilos_almacenes.inc");
require("funciones.php");


/*$fecha=date("Y-m-d");
$hora=date("H:i:s");*/



$fecha=$_POST["fecha"];
$fecha=formateaFechaVista($fecha);
$hora=date("H:i:s");

$cliente=$_POST["cliente"];
$nroFilas=$_POST["nroFilas"];
$observaciones=$_POST["observaciones"];




$sql="SELECT cod_cobro FROM cobros_cab ORDER BY cod_cobro DESC";
echo $sql;
$resp=mysql_query($sql);
$dat=mysql_fetch_array($resp);
$num_filas=mysql_num_rows($resp);
if($num_filas==0)
{   $codigo=1;
}
else
{   $codigo=$dat[0];
	$codigo++;
}

$sqlNro="select max(nro_cobro) from cobros_cab where cod_gestion in (select cod_gestion from gestiones where estado=1)";
$nroCobranza=obtenerCodigo($sqlNro);

$montoTotal=0;

echo "monto=0";
$globalGestion=1;

for($i=1;$i<=$nroFilas;$i++)
{   		
	
	echo $i." iii";

	$codVenta=$_POST["codCobro$i"];	
	$montoPago=$_POST["montoPago$i"];
	$nroDoc=$_POST["nroDoc$i"];
	
	$montoTotal=$montoTotal+$montoPago;
	if($montoPago>0){
		$sql_inserta="INSERT INTO `cobros_detalle` (`cod_cobro`,`cod_venta`,`monto_detalle`,`nro_doc`) 
			VALUE ('$codigo','$codVenta','$montoPago','$nroDoc')";
		echo $sql_inserta;
		$sql_inserta=mysql_query($sql_inserta);
	}
	
	//actualizamos la tabla ordenes de compra
	$sqlUpd="update salida_almacenes set monto_cancelado=monto_cancelado+$montoPago where cod_salida_almacenes='$codVenta'";
	$respUpd=mysql_query($sqlUpd);

	//echo $i;
}

$sqlInsertC="INSERT INTO `cobros_cab` (`cod_cobro`,`fecha_cobro`,`monto_cobro`,`observaciones`,`cod_cliente`,`cod_estado`,`cod_gestion`,`nro_cobro`) 
	VALUE ('$codigo','$fecha','$montoTotal','$observaciones','$cliente','1','$globalGestion','$nroCobranza')";

echo $sqlInsertC;

$respInsertC=mysql_query($sqlInsertC);

echo "<script type='text/javascript' language='javascript'>";
echo "    alert('Los datos fueron insertados correctamente.');";
echo "    location.href='navegadorCobranzas.php';";
echo "</script>";
?>



