<?php

require("conexion.inc");
require("estilos_almacenes.inc");

$sql = "select cod_orden from orden_compra order by cod_orden desc";
$resp = mysql_query($sql);
$dat = mysql_fetch_array($resp);
$num_filas = mysql_num_rows($resp);
if ($num_filas == 0) {
    $codigo = 1;
} else {
    $codigo = $dat[0];
    $codigo++;
}

$sql = "select nro_orden from orden_compra order by 1 desc";
$resp = mysql_query($sql);
$dat = mysql_fetch_array($resp);
$num_filas = mysql_num_rows($resp);
if ($num_filas == 0) {
    $nro_correlativo = 1;
} else {
    $nro_correlativo = $dat[0];
    $nro_correlativo++;
}
$hora_sistema = date("H:i:s");



$tipo_pago=$_POST['tipo_pago'];
$proveedor=$_POST['proveedor'];
$nro_factura=$_POST['nro_factura'];
$observaciones=$_POST['observaciones'];
$totalVenta=$_POST['totalOC'];

$fecha_real=date("Y-m-d");


$consulta="insert into orden_compra 
	values($codigo,$nro_correlativo,$proveedor,'$fecha_real','$observaciones',1,$tipo_pago,$nro_factura,'$totalOC', '0',1,'',0,0,0)";

$sql_inserta = mysql_query($consulta);
echo "$sql_inserta";

if($sql_inserta==1){
	echo "entro detalle";
	for ($i = 1; $i <= $cantidad_material; $i++) {
		$cod_material = $_POST["material$i"];
		$cantidad=$_POST["cantidad_unitaria$i"];
		$precioBruto=$_POST["precio$i"];
		$precioNeto=$_POST["neto$i"];
		$loteProd=$_POST["lote$i"];
		$fechaVencProd=$_POST["fechaVenc$i"];
		
		$consulta="insert into orden_compra_detalle (cod_orden, cod_material, observaciones, cantidad, precio_unitario, lote, fecha_vencimiento) 
			values($codigo,'$cod_material',' ', $cantidad,$precioBruto,'$loteProd','$fechaVencProd')";
		
		//echo "bbb:$consulta";
		
		$sql_inserta2 = mysql_query($consulta);
	}
}

echo "<script language='Javascript'>
    alert('Los datos fueron insertados correctamente.');
    location.href='navegador_ordenCompra.php';
    </script>";
	
?>