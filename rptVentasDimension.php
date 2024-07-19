<?php
require('estilos_reportes_almacencentral.php');
require('function_formatofecha.php');
require('conexion.inc');
require('funcion_nombres.php');
require('funciones.php');


$fecha_ini=$_POST['fecha_ini'];
$fecha_fin=$_POST['fecha_fin'];
$rptOrdenar=$_POST['rpt_ordenar'];
$rpt_territorio=$_POST['rpt_territorio'];

$rptTerritorio=implode(",",$rpt_territorio);

//desde esta parte viene el reporte en si
$fecha_iniconsulta=$fecha_ini;
$fecha_finconsulta=$fecha_fin;

$fecha_reporte=date("d/m/Y");

$nombre_territorio=nombreTerritorio($rptTerritorio);
?>

<h4 class="card-title" align="center">Reporte de Ventas por Dimension</h4>
<h6 class="card-title">De: <?=$fecha_iniconsulta;?></h6>
<h6 class="card-title">A: <?=$fecha_finconsulta;?></h6>
<h6 class="card-title">Almacenes: <?=$nombreTerritorio;?></h6>




<div class="content">
	<div class="container-fluid">
   	 	<div class="row">
      		<div class="card">
        		<div class="card-header card-header-success card-header-icon">
                  	<div class="card-icon">
                      	<i class="material-icons">bar_chart</i> Ventas por Pais de Procedencia
                  	</div>
        		</div>

    			<div class="row">
      				<div class="col-md-4">
        				<div class="card">
          					<div class="card-header card-header-info card-header-icon">
								<table width="100%">
					              <tr>
					                <th class="text-center">-</th>
					                <th class="text-center">Nombre</th>
					                <th class="text-center">Cantidad</th>
					                <th class="text-center">Monto</th>
					              </tr>
            				<?php
							$sql="SELECT p.codigo, p.nombre,
								(sum(sd.monto_unitario)-sum(sd.descuento_unitario))montoVenta, sum(sd.cantidad_unitaria)cantidadventa, sum(((sd.monto_unitario-sd.descuento_unitario)/s.monto_total)*s.descuento)as descuentocabecera
								from salida_almacenes s 
								INNER JOIN salida_detalle_almacenes sd ON s.cod_salida_almacenes=sd.cod_salida_almacen 
								INNER JOIN material_apoyo m ON sd.cod_material=m.codigo_material
								INNER JOIN almacenes a ON a.cod_almacen=s.cod_almacen
								LEFT JOIN pais_procedencia p ON p.codigo=m.cod_pais_procedencia
								where s.fecha BETWEEN '$fecha_iniconsulta' and '$fecha_finconsulta'
								and s.salida_anulada=0 and s.cod_tiposalida=1001 and a.cod_ciudad in ($rptTerritorio)
								group by p.codigo ";
								
								if($rptOrdenar==1){
									$sql=$sql." order by montoVenta desc;";
								}elseif($rptOrdenar==2){
									$sql=$sql." order by cantidadventa desc;";
								}	
							$resp=mysqli_query($enlaceCon,$sql);
							$indice=1;
							while($dat=mysqli_fetch_array($resp)){
								$codigo=$dat[0];
								$nombre=$dat[1];
								$monto=$dat[2];
								$cantidad=$dat[3];
								$descuento=$dat[4];
							?>
								<tr>
					                <td class="text-center"><?=$indice;?></td>
					                <td class="text-left"><?=$nombre;?></td>
					                <td class="text-right"><?=formatonumeroDec($cantidad);?></td>
					                <td class="text-right"><?=formatonumeroDec($monto);?></td>
				              	</tr>
							<?php	
								$indice++;
							}
            				?>	
					            </table>
          					</div>
        				</div>
      				</div>

      				<div class="col-md-4">
        				<div class="card">
          					<div class="card-header card-header-info card-header-icon">
            					Grafico 1
          					</div>
        				</div>
      				</div>

      				<div class="col-md-4">
        				<div class="card">
          					<div class="card-header card-header-info card-header-icon">
            					Grafico 2
          					</div>
        				</div>
      				</div>
    			</div>      
      		</div>
    	</div>  
  	</div>
</div>
