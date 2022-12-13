<?php
require("fpdf.php");
require("conexion.inc");
require("funciones.php");

date_default_timezone_set('America/La_Paz');


class PDF extends FPDF
{ 	
	
	function Header()
	{
		$codigoVenta=$_GET['codVenta'];
		$sqlEmp="select cod_empresa, nombre, nit, direccion, ciudad from datos_empresa";
		$respEmp=mysql_query($sqlEmp);

		$nombreEmpresa=mysql_result($respEmp,0,1);
		$nitEmpresa=mysql_result($respEmp,0,2);
		$direccionEmpresa=mysql_result($respEmp,0,3);
		$ciudadEmpresa=mysql_result($respEmp,0,4);
	
		//datos documento				
		$sqlDatosVenta="select concat((DATE_FORMAT(s.fecha, '%d/%m/%Y')),' ',s.hora_salida) as fecha, t.`abreviatura`, 
			(select cl.nombre_cliente from clientes cl where s.cod_cliente=cl.cod_cliente) as nombre_cliente,
			(select cl.telf1_cliente from clientes cl where s.cod_cliente=cl.cod_cliente) as telefonoCli, 			
			s.`nro_correlativo`, s.razon_social, s.nit, s.observaciones, 
			(select concat(f.paterno, ' ', f.nombres) from funcionarios f where codigo_funcionario=s.cod_chofer) as chofer,
			(select celular from funcionarios f where codigo_funcionario=s.cod_chofer) as celular,
			(select v.placa from vehiculos v where v.codigo=s.cod_vehiculo) as placa
			from `salida_almacenes` s, `tipos_docs` t
				where s.`cod_salida_almacenes`='$codigoVenta' and s.`cod_tipo_doc`=t.`codigo`";
		$respDatosVenta=mysql_query($sqlDatosVenta);
		while($datDatosVenta=mysql_fetch_array($respDatosVenta)){
			$fechaVenta=$datDatosVenta[0];
			$nombreTipoDoc=$datDatosVenta[1];
			$nombreCliente=$datDatosVenta[2];
			$telfCliente=$datDatosVenta[3];
			$nroDocVenta=$datDatosVenta[4];
			$razonSocial=$datDatosVenta[5];
			$nitVenta=$datDatosVenta[6];
			$obsNota=$datDatosVenta[7];
			$nombreChofer=$datDatosVenta[8];
			$celularChofer=$datDatosVenta[9];
			$placa=$datDatosVenta[10];
		}
		
		$this->SetFont('Arial','B',13);
		$this->SetXY(95,10);		$this->Cell(0,0,$nombreTipoDoc." ".$nroDocVenta,0,0);

		$this->SetFont('Arial','',10);
		$this->SetXY(10,10);		$this->Cell(0,0,"Cliente: ".$nombreCliente,0,0);
		$this->SetXY(150,10);		$this->Cell(0,0,"Fecha: ".$fechaVenta,0,0);
		
		$this->SetXY(10,15);		$this->Cell(0,0,"R.Social: ".$razonSocial,0,0);
		
		$this->SetXY(90,20);		$this->Cell(0,0,"Observaciones: ".$obsNota,0,0);
		$this->SetXY(10,20);		$this->Cell(0,0,"NIT: $nitVenta",0,0);
		
		
		$this->SetXY(10,25);		$this->Cell(0,0,"Vendedor: ".$nombreChofer,0,0);
		$this->SetXY(90,25);		$this->Cell(0,0,".",0,0);
		$this->SetXY(165,25);		$this->Cell(0,0,"",0,0);
		
		
		$this->Line(5, 30, 210,30);
		
		$this->SetXY(10,33);		$this->Cell(0,0,"Cant.",0,0);
		$this->SetXY(40,33);		$this->Cell(0,0,"Item",0,0);
		$this->SetXY(135,33);		$this->Cell(0,0,"PrecioUnitario",0,0);
		$this->SetXY(165,33);		$this->Cell(0,0,"Desc.",0,0);
		$this->SetXY(190,33);		$this->Cell(0,0,"Monto",0,0);
		
		$this->Line(5, 35, 210,35);
		
		$this->ln(10);
		
 
	}
	
	function Footer()
	{
		global $montoTotal;
		global $descuentoFinal;
		global $pesoTotal;
		global $pesoTotalqq;
		
		$this->Line(5, 115, 210,115);
		
		$this->SetY(-20);
		$this->SetX(150);		$this->Cell(0,0,"Monto Total",0,0);
		$this->SetX(190);		$this->Cell(15,0,$montoTotal,0,0,"R");
				
		
		$this->SetY(-15);

		$this->SetX(150);		$this->Cell(0,0,"Descuento Final",0,0);
		$this->SetX(190);		$this->Cell(15,0,$descuentoFinal,0,0,"R");
		
		$this->SetY(-10);
		$this->SetX(150);		$this->Cell(0,0,"Monto Final",0,0);
		$this->SetX(190);		$this->Cell(15,0,$montoTotal-$descuentoFinal,0,0,"R");
		
		$this->SetY(-10);
		// Arial italic 8
		$this->SetFont('Arial','',10);
		// N�mero de p�gina
		//$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
	}
}


$pdf=new PDF('L','mm',array(214,140));

//$pdf=new PDF('P','mm',array(140,214));
$pdf->AliasNbPages();
$pdf->AddPage();
	
$pdf->SetFont('Arial','',10);
			
//AQUI EMPEZAMOS CON EL DETALLE
$codigoVenta=$_GET['codVenta'];

$sql_detalle="select s.cod_material, m.descripcion_material, s.lote, s.fecha_vencimiento,
	sum(s.cantidad_unitaria), s.precio_unitario, sum(s.`descuento_unitario`), sum(s.`monto_unitario`), sum(ss.`descuento`)
	from salida_detalle_almacenes s, material_apoyo m, `salida_almacenes` ss
	where s.cod_salida_almacen='$codigoVenta' and s.cod_material=m.codigo_material and ss.`cod_salida_almacenes`=s.`cod_salida_almacen` 
	group by s.cod_material
		order by s.orden_detalle";
	
$resp_detalle=mysql_query($sql_detalle);
$montoTotal=0;
$pesoTotal=0;
$pesoTotalqq=0;
while($dat_detalle=mysql_fetch_array($resp_detalle))
{	$cod_material=$dat_detalle[0];
	$nombre_material=$dat_detalle[1];
	$codigoInterno=$dat_detalle[2];
	$peso=$dat_detalle[3];
	$peso=redondear2($peso);
	$cantidad_unitaria=$dat_detalle[4];
	$cantidad_unitaria=redondear2($cantidad_unitaria);
	$precioUnitario=$dat_detalle[5];
	$precioUnitario=redondear2($precioUnitario);
	$descuentoUnitarioAF=$dat_detalle[6];
	$descuentoUnitario=redondear2($descuentoUnitarioAF);
	$montoUnitario=$dat_detalle[7];
	$montoUnitario=$montoUnitario-$descuentoUnitarioAF;
	$descuentoFinal=$dat_detalle[8];
	$descuentoFinal=redondear2($descuentoFinal);
	$montoUnitario=redondear2($montoUnitario);
	$pesoItem=$peso*$cantidad_unitaria;
	$pesoItem=redondear2($pesoItem);
	$pesoTotal=$pesoTotal+$pesoItem;
	$pesoTotal=redondear2($pesoTotal);
	
	
	$montoTotal=$montoTotal+$montoUnitario;
	$montoTotal=redondear2($montoTotal);
	
	$pdf->Cell(0,0,$cantidad_unitaria,0,0);
	$pdf->SetX(25);
	$pdf->Cell(0,0,$nombre_material,0,0);
	$pdf->SetX(140);
	$pdf->Cell(15,0,$precioUnitario,0,0,"R");
	$pdf->SetX(165);
	$pdf->Cell(15,0,$descuentoUnitario,0,0,"R");
	$pdf->SetX(190);
	$pdf->Cell(15,0,$montoUnitario,0,0,"R");
	
	$pdf->ln(4);
	
}

//FIN DETALLE

$pdf->Output();


?>