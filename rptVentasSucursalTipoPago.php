<br><br>
<?php
set_time_limit(0);

require('function_formatofecha.php');
require('funcion_nombres.php');
require('funciones.php');
require('estilos_reportes_almacencentral.php');
require('conexionmysqli.php');

//  error_reporting(E_ALL);
//  ini_set('display_errors', '1');

$fecha_ini=$_POST['fecha_ini'];
$fecha_fin=$_POST['fecha_fin'];
$codSucursales=$_POST['rpt_sucursal'];

$stringSucursales=implode(',',$codSucursales);

//desde esta parte viene el reporte en si
$fecha_iniconsulta=$fecha_ini;
$fecha_finconsulta=$fecha_fin;

$fecha_reporte=date("d/m/Y");

?><style type="text/css"> 
        thead tr th { 
            position: sticky;
            top: 0;
            z-index: 10;
            background-color: #ffffff;
        }
    
        .table-responsive { 
            height:200px;
            overflow:scroll;
        }
    </style>
<table style='margin-top:-90 !important' align='center' class='textotit' width='70%'>
  <tr><td align='center'>Reporte Ventas x Sucursal y Tipo de Pago
	<br> De: <?=$fecha_ini?> A: <?=$fecha_fin?>
	<br>Fecha Reporte: <?=$fecha_reporte?></tr></table>
<br>
<center><table align='center' class='texto' width='70%' id='ventasSucursal'>
	<thead>
    <tr>
      <th>-</th><th>Tipo de Pago</th>
<?php
  $sql="select c.cod_ciudad, c.descripcion from ciudades c where c.cod_ciudad in ($stringSucursales) order by 2;";
  $resp=mysqli_query($enlaceCon,$sql);
  while($dat=mysqli_fetch_array($resp)){ 
    $codigoCiudad=$dat[0];
    $nombreCiudad=$dat[1];
    echo "<th>$nombreCiudad</th>"; 
  }
?>
  <th>Totales</th>
</tr>
</thead>
<tbody>
<?php

$sqlTiposPago="select t.cod_tipopago, t.nombre_tipopago from tipos_pago t;";
$respTiposPago=mysqli_query($enlaceCon,$sqlTiposPago);
$index=0;

while($datosTiposPago=mysqli_fetch_array($respTiposPago)){
  $totalesHorizontal=0;
  $index++;
	$codTipoPago=$datosTiposPago[0];
	$nombreTipoPago=$datosTiposPago[1];
	?>
  <tr>
    <td><?=$index;?></td>
    <td><?=$nombreTipoPago;?></td>
  <?php    
    //echo $dateInicio."...".$dateFin."<br>";
    $montoVenta=0;
    $sql="select c.cod_ciudad, c.descripcion from ciudades c where c.cod_ciudad in ($stringSucursales) order by 2;";
    $resp=mysqli_query($enlaceCon,$sql);
    while($dat=mysqli_fetch_array($resp)){ 
      $codigoCiudad=$dat[0];
      $nombreCiudad=$dat[1];

      /*monto de venta*/
      $sqlMontoVenta="SELECT sum(s.monto_final) from salida_almacenes s where s.salida_anulada=0 and s.fecha BETWEEN '$fecha_iniconsulta' and '$fecha_finconsulta' and s.cod_tipopago='$codTipoPago' and s.cod_tiposalida=1001 and s.cod_almacen in (select a.cod_almacen from almacenes a where a.cod_ciudad in ($codigoCiudad))";
      //echo $sqlMontoVenta;
      $respMontoVenta=mysqli_query($enlaceCon, $sqlMontoVenta);
      if($datMontoVenta=mysqli_fetch_array($respMontoVenta)){
        $montoVenta=$datMontoVenta[0];
      }
      $montoVentaF=formatonumeroDec($montoVenta);
      echo "<td align='right'>$montoVentaF</td>"; 
      $totalesHorizontal+=$montoVenta;
    }
    $totalesHorizontalF=formatonumeroDec($totalesHorizontal);
  ?>
    <td class="text-right"><?=$totalesHorizontalF;?></td>
  </tr> 
<?php
}
?>
</tbody>
<tfoot>
  <tr>
  </tr>
</tfoot>
</table>
</center>

<script type="text/javascript">
  totalesTablaVertical('ventasSucursal',2,1);
</script>
