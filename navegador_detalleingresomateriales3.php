<?php
	require("conexion.inc");
	require('estilos_almacenes_central_sincab.php');
	require("funciones.php");
	$global_almacen=$_COOKIE['global_almacen'];
	$codIngreso=$_GET['codigo_ingreso'];
?>	
<form method='post' action=''>
<?php
	$sql="select i.cod_ingreso_almacen, i.fecha, ti.nombre_tipoingreso, i.observaciones, i.nro_correlativo 
	FROM ingreso_almacenes i, tipos_ingreso ti
	where i.cod_tipoingreso=ti.cod_tipoingreso and i.cod_almacen='".$global_almacen."' and i.cod_ingreso_almacen='".$codIngreso."'";
	
	//echo $sql;
	
	$resp=mysql_query($sql);
?>	
	<center><table border='0' class='textotit'><tr><th>Detalle de Ingreso y Costos de Importacion</th></tr></table></center><br>
	
	<table border='0' class='texto' align='center'>
	<tr><th>Nro. de Ingreso</th><th>Fecha</th><th>Tipo de Ingreso</th><th>Observaciones</th></tr>
	
<?php	
	$dat=mysql_fetch_array($resp);
	$codigo=$dat[0];
	$fecha_ingreso=$dat[1];
	$fecha_ingreso_mostrar="$fecha_ingreso[8]$fecha_ingreso[9]-$fecha_ingreso[5]$fecha_ingreso[6]-$fecha_ingreso[0]$fecha_ingreso[1]$fecha_ingreso[2]$fecha_ingreso[3]";
	$nombre_tipoingreso=$dat[2];
	$obs_ingreso=$dat[3];
	$nro_correlativo=$dat[4];
?>	
	<tr><td align='center'><?=$nro_correlativo;?></td><td align='center'><?=$fecha_ingreso_mostrar;?></td><td><?=$nombre_tipoingreso;?></td><td>&nbsp;<?=$obs_ingreso;?></td></tr>
	</table>
<?php	

	$sql2="select count(*) nroItems, sum(i.cantidad_unitaria*i.costo_almacen)total, sum(metros_cubicos)m3 from ingreso_detalle_almacenes i where i.cod_ingreso_almacen='".$codIngreso."'"; 
	//echo $sql2;
	$resp2=mysql_query($sql2);
	$nroItems=0;
	$total=0;
	$totalCubicaje=0;
	while($dat2=mysql_fetch_array($resp2)){
		$nroItems=$dat2[0];
		$total=$dat2[1];
		$totalCubicaje=$dat2[2];
	}

	//echo  "nroItems:".$nroItems." total".$total;
	$sql_detalle="select m.codigo_anterior, i.cantidad_unitaria, i.costo_almacen, i.lote, DATE_FORMAT(i.fecha_vencimiento, '%d/%m/%Y'),
	m.descripcion_material, m.codigo_material, i.metros_cubicos from ingreso_detalle_almacenes i, material_apoyo m
	where i.cod_ingreso_almacen='".$codIngreso."' 
	and m.codigo_material=i.cod_material order by m.descripcion_material";
	//echo $sql_detalle;
	$resp_detalle=mysql_query($sql_detalle);
?>
	<br><table border=0 class='texto' align='center'>
	<tr><th>&nbsp;</th><th>Codigo</th><th>Producto</th><th>Cantidad</th><th>MetrosCubicos/<br>Peso[Kg]</th><th>Precio<br>Compra U.(Bs.)</th><th>Precio<br>Compra(Bs.)</th>
	<!--th>Participacion %</th-->
<?php	
	$sqlCii="select cii.cod_costoimp,ci.nombre_costoimp,tipo_calculo,monto from costos_importacion_ingreso  cii
	left join costos_importacion ci on( cii.cod_costoimp=ci.cod_costoimp)
	where cii.cod_almacen='".$global_almacen."' and cii.cod_ingreso_almacen='".$codIngreso."'
	order by ci.nombre_costoimp";
	$respCii=mysql_query($sqlCii);
	$tipoCalculo="";
	$montoCiiFormato=0;
	while($datCii=mysql_fetch_array($respCii)){
		if($datCii['tipo_calculo']==1){
			$tipoCalculo="Nro de Items";
		}
		if($datCii['tipo_calculo']==2){
			$tipoCalculo="Participacion";
		}
		if($datCii['tipo_calculo']==3){
			$tipoCalculo="Cubicaje";
		}
		$montoCiiFormato=redondear2($datCii['monto'],2);
		
?>		
		<th><?=$datCii['nombre_costoimp'];?><br>M:<?=$montoCiiFormato;?>[Bs]<br>(<?=$tipoCalculo;?>)</th>
		
<?php
	}
	
	?>
	<th>Costo Total<br>Adicional</th>
	<th>Costo Total</th>
	<th>Costo Total<br>Unitario</th>
	</tr>
<?php	
	$indice=1;
	$costoImpProd=0;
	$totalCostoImpProd=0;
	$totalMetrosCubicos=0;
	while($dat_detalle=mysql_fetch_array($resp_detalle))
	{	$cod_material=$dat_detalle[0];
		$cantidad_unitaria=$dat_detalle[1];
		$precioNeto=redondear2($dat_detalle[2]);
		$loteProducto=$dat_detalle[3];
		$fechaVenc=$dat_detalle[4];
		$nombre_material=$dat_detalle[5];
		$metrosCubicos=$dat_detalle[7];
		
		$totalValorItem=$cantidad_unitaria*$precioNeto;
		$totalMetrosCubicos=$totalMetrosCubicos+$metrosCubicos;
		$cantidad_unitaria=redondear2($cantidad_unitaria);
		$particionProd=0;
		$particionProdFormato=0;
		$participacionCubicaje=0;
		if($total!=0){
			$particionProd=($totalValorItem*100)/$total;
			$particionProdFormato=redondear2($particionProd);
		}
		if($totalCubicaje>0){
			$participacionCubicaje=($metrosCubicos*100)/$totalCubicaje;
		}
		
?>
<tr>
<td align='center'><?=$indice;?></td>
	<td><?=$cod_material;?></td>
	<td><?=$nombre_material;?></td>
	<td align='center'><?=$cantidad_unitaria;?></td>
	<td align='center'><?=$metrosCubicos;?></td>
	<td align='right'><?=$precioNeto;?></td>
	<td align="right"><?=$totalValorItem;?></td>
	<!--td align="right"><?=$particionProdFormato;?></td-->
<?php		
		$sqlCii2="select cii.cod_costoimp,ci.nombre_costoimp,tipo_calculo,monto from costos_importacion_ingreso  cii
		left join costos_importacion ci on( cii.cod_costoimp=ci.cod_costoimp)
		where cii.cod_almacen='".$global_almacen."' and cii.cod_ingreso_almacen='".$codIngreso."'
		order by ci.nombre_costoimp";
		$respCii2=mysql_query($sqlCii2);	
		$tipo_calculo=0;
		$montoCii=0;
		$costoImpProd=0;
		$costoProdTotal=0;
		$costoUnitarioTotal=0;
		while($datCii2=mysql_fetch_array($respCii2)){
			$tipo_calculo=$datCii2['tipo_calculo'];
			$montoCii=$datCii2['monto'];

			if($tipo_calculo==1){
				$auxCostoImpProd= $montoCii/$nroItems;
			}
			if($tipo_calculo==2){
				$auxCostoImpProd=($particionProdFormato*$montoCii)/100;
			}
			if($tipo_calculo==3){
				$auxCostoImpProd=($participacionCubicaje*$montoCii)/100;
			}
			$costoImpProd=$costoImpProd+$auxCostoImpProd;
			$costoProdTotal=$costoImpProd+$totalValorItem;
			$costoUnitarioTotal=$costoProdTotal/$cantidad_unitaria;

			$auxCostoImpProdF=formatonumeroDec($auxCostoImpProd);

?>
			<td align="right"><?=$auxCostoImpProdF;?></td>
<?php			
		}
		$totalCostoImpProd=$totalCostoImpProd+$costoImpProd;

		$costoImpProdF=formatonumeroDec($costoImpProd);
		$costoProdTotalF=formatonumeroDec($costoProdTotal);
		$costoUnitarioTotalF=formatonumeroDec($costoUnitarioTotal);
		
?>		
			<td align="right"><?=$costoImpProdF;?></td>
			<td align="right"><?=$costoProdTotalF;?></td>
			<td align="right"><b><?=$costoUnitarioTotalF;?></b></td>
		</tr>
<?php		
		$indice++;
	}
	$totalFormato=redondear2($total);
	$totalCostoImpProdFormato=redondear2($totalCostoImpProd);
?>	
<tr>
<td colspan="4">&nbsp;</td>
<td align="right" ><?=$totalMetrosCubicos;?></td>
<td align="right" >&nbsp;</td>
<td align="right" ><?=$totalFormato;?></td>
<?php
$sqlCii="select cii.cod_costoimp,ci.nombre_costoimp,tipo_calculo,monto from costos_importacion_ingreso  cii
	left join costos_importacion ci on( cii.cod_costoimp=ci.cod_costoimp)
	where cii.cod_almacen='".$global_almacen."' and cii.cod_ingreso_almacen='".$codIngreso."'
	order by ci.nombre_costoimp";
	$respCii=mysql_query($sqlCii);
	$tipoCalculo="";
	$montoCiiFormato=0;
	while($datCii=mysql_fetch_array($respCii)){
		if($datCii['tipo_calculo']==1){
			$tipoCalculo="Nro de Items";
		}
		if($datCii['tipo_calculo']==2){
			$tipoCalculo="Participacion";
		}
		if($datCii['tipo_calculo']==3){
			$tipoCalculo="Cubicaje";
		}
		$montoCiiFormato=redondear2($datCii['monto']);
		
?>
<td align='right'><?=$montoCiiFormato;?></td>
<?php
	}
?>
<td align='right'><?=$totalCostoImpProdFormato;?></td>
</tr>
	</table>
	
	<center><a href='javascript:window.print();'><IMG border='no'
	 src='imagenes/print.jpg' width='40'></a></center>
	
?>