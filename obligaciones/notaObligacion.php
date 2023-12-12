<?php
ob_start();
require('../fpdf.php');
require('../conexion.inc');
require('../funciones.php');
require('../NumeroALetras.php');

$globalAgencia=$_COOKIE['global_agencia'];

$codPago=$_GET['codPago'];

//consulta cuantos items tiene el detalle
$tamanoLargo=300;

$pdf=new FPDF('P','mm',array(74,$tamanoLargo));
//$pdf=new FPDF('P','mm',array(100,$tamanoLargo));
$pdf->SetMargins(0,0,0);
$pdf->AddPage(); 
$pdf->SetFont('Arial','',10);

$y=0;
$incremento=3;

/*error_reporting(E_ALL);
ini_set('display_errors', '1');*/
//desde aca
$sqlConf="SELECT id, valor FROM configuracion_facturas WHERE id=1 AND cod_ciudad = '$globalAgencia'";
$respConf=mysqli_query($enlaceCon,$sqlConf);
$nombreEmpresa=mysqli_result($respConf,0,1);

$sqlConf="SELECT id, valor FROM configuracion_facturas WHERE id=2 AND cod_ciudad = '$globalAgencia'";
$respConf=mysqli_query($enlaceCon,$sqlConf);
$ciudadEmpresa=mysqli_result($respConf,0,1);

$sqlConf="SELECT id, valor FROM configuracion_facturas WHERE id=3 AND cod_ciudad = '$globalAgencia'";
$respConf=mysqli_query($enlaceCon,$sqlConf);
$direccionEmpresa=mysqli_result($respConf,0,1);

$sqlConf="SELECT id, valor FROM configuracion_facturas WHERE id=9 AND cod_ciudad = '$globalAgencia'";
$respConf=mysqli_query($enlaceCon,$sqlConf);
$nitEmpresa=mysqli_result($respConf,0,1);


//datos documento				
$sqlDatos="SELECT pc.`cod_pago`, pc.`fecha`, pc.`observaciones`, pc.`monto_pago`, 
			(SELECT p.`nombre_proveedor`
			FROM proveedores p 
			WHERE pc.`cod_proveedor` = p.`cod_proveedor`) AS nombre_proveedor, 
			pc.nro_pago,
			DATE_FORMAT(pc.fecha, '%Y') AS nombre_gestion 
			FROM `pagos_proveedor_cab` pc  
			WHERE pc.cod_pago = '$codPago' 
			ORDER BY pc.`cod_pago` DESC";
$respDatos=mysqli_query($enlaceCon, $sqlDatos);
while($datDatos=mysqli_fetch_array($respDatos)){
	$fechaPago		 = $datDatos['fecha'];
	$obsNota		 = $datDatos['observaciones'];
	$montoPago		 = $datDatos['monto_pago'];
	$nombreProveedor = $datDatos['nombre_proveedor'];			
	$nroPago		 = $datDatos['nro_pago'];
	$gestion		 = $datDatos['nombre_gestion'];
}


$pdf->SetXY(0,$y+9);		$pdf->Cell(0,0,"Pago Nro. $nroPago", 0,0,"C");
$pdf->SetXY(0,$y+12);		$pdf->Cell(0,0,"-------------------------------------------------------------------------------", 0,0,"C");


$pdf->SetXY(0,$y+15);		$pdf->Cell(0,0,"FECHA: $fechaPago",0,0,"C");
$pdf->SetXY(0,$y+20);		$pdf->Cell(0,0,"Proveedor: $nombreProveedor",0,0,"C");
$pdf->SetXY(0,$y+25);		$pdf->Cell(0,0,"Obs: $obsNota",0,0,"C");


$y=$y-15;

$pdf->SetXY(0,$y+45);		$pdf->Cell(0,0,"=================================================================================",0,0,"C");
$pdf->SetXY(7,$y+48);		$pdf->Cell(0,0,"#");
$pdf->SetXY(25,$y+48);		$pdf->Cell(0,0,"Nro.Correlativo"); // Ingreso Almacen
$pdf->SetXY(50,$y+48);		$pdf->Cell(0,0,"Monto");
$pdf->SetXY(0,$y+52);		$pdf->Cell(0,0,"=================================================================================",0,0,"C");


$yyy=55;

$sql_detalle="SELECT pd.`monto_detalle`, ia.`nro_correlativo`
			FROM `pagos_proveedor_cab` pc
			JOIN `pagos_proveedor_detalle` pd ON pc.`cod_pago` = pd.`cod_pago`
			JOIN `ingreso_almacenes` ia ON pd.`cod_ingreso_almacen` = ia.`cod_ingreso_almacen`
			WHERE pc.`cod_pago` = '$codPago'";		
// echo $sql_detalle;
$resp_detalle=mysqli_query($enlaceCon, $sql_detalle);
$montoTotal=0;
$indice=1;
while($dat_detalle=mysqli_fetch_array($resp_detalle)){
	$montoDet = $dat_detalle['monto_detalle'];
	$nroVenta = $dat_detalle['nro_correlativo'];
	
	$montoTotal=$montoTotal+$montoDet;
	$montoDet=redondear2($montoDet);
	
	$pdf->SetFont('Arial','',9);
	$pdf->SetXY(7,$y+$yyy);		$pdf->Cell(10,5,"$indice",0,0,"L");	
	$pdf->SetXY(30,$y+$yyy);		$pdf->Cell(10,5,"$nroVenta",0,0,"C");
	$pdf->SetXY(40,$y+$yyy);		$pdf->Cell(20,5,"$montoDet",0,0,"R");	
	$indice++;
	$yyy+=5;
}

$pdf->SetXY(0,$y+$yyy+2);		$pdf->Cell(0,0,"=================================================================================",0,0,"C");

$y=$y+5;

$pdf->SetXY(25,$y+$yyy);		$pdf->Cell(25,5,"Total Pago:",0,0,"R");
$pdf->SetXY(40,$y+$yyy);		$pdf->Cell(20,5,$montoTotal,0,0,"R");

$yyy=$yyy+5;
ob_end_clean();
$pdf->Output();

?>