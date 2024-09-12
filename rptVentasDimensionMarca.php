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

// Cantidad de Registros
$cantidad_registros = obtenerValorConfiguracion(12);
?>


<div class="content text-center">
	<h3 class="font-weight-bold text-primary">Reporte de Ventas por Marca</h3>
	<h6 class="font-weight-bold text-secondary">De: <?=$fecha_iniconsulta;?></h6>
	<h6 class="font-weight-bold text-secondary">A: <?=$fecha_finconsulta;?></h6>
	<h6 class="font-weight-bold text-secondary">Almacenes: <?=$nombre_territorio;?></h6>
</div>


<div class="content">
	<div class="container-fluid">
   	 	<div class="row">
      		<div class="card">
        		<div class="card-header card-header-success card-header-icon">
                  	<div class="card-icon">
                      	<i class="material-icons">bar_chart</i> Ventas por Marca
                  	</div>
        		</div>

    			<div class="row mb-4">
					<div class="col-md-4">
						<div class="card mb-0">
							<div class="card-header card-header-info card-header-icon">
								<div class="card-body p-0">
									<div class="table-responsive">
										<table class="table table-striped table-bordered">
											<thead>
												<tr>
													<th class="text-center">#</th>
													<th class="text-center">Marca</th>
													<th class="text-center">Cantidad</th>
													<th class="text-center">Monto</th>
												</tr>
											</thead>
											<tbody>
												<?php
												$sql = "SELECT pl.cod_linea_proveedor as codigo, COALESCE(CONCAT(pl.nombre_linea_proveedor,' ',pp.nombre), '-') as nombre,
													(SUM(sd.monto_unitario) - SUM(sd.descuento_unitario)) AS montoVenta, 
													SUM(sd.cantidad_unitaria) AS cantidadventa, 
													SUM(((sd.monto_unitario - sd.descuento_unitario) / s.monto_total) * s.descuento) AS descuentocabecera
													FROM salida_almacenes s 
													INNER JOIN salida_detalle_almacenes sd ON s.cod_salida_almacenes = sd.cod_salida_almacen 
													INNER JOIN material_apoyo m ON sd.cod_material = m.codigo_material
													INNER JOIN almacenes a ON a.cod_almacen = s.cod_almacen
													LEFT JOIN proveedores_lineas pl ON pl.cod_linea_proveedor=m.cod_linea_proveedor
													LEFT JOIN pais_procedencia pp ON pp.codigo=m.cod_pais_procedencia
													WHERE s.fecha BETWEEN '$fecha_iniconsulta' AND '$fecha_finconsulta'
													AND s.salida_anulada = 0 
													AND s.cod_tiposalida = 1001 
													AND a.cod_ciudad IN ($rptTerritorio)
													GROUP BY pl.cod_linea_proveedor, pp.nombre ";

												if ($rptOrdenar == 1) {
													$sql .= " ORDER BY montoVenta DESC ";
												} elseif ($rptOrdenar == 2) {
													$sql .= " ORDER BY cantidadventa DESC ";
												}
												$sql .= " LIMIT $cantidad_registros;";

												//echo $sql;

												$resp = mysqli_query($enlaceCon, $sql);
												$indice = 1;
												while ($dat = mysqli_fetch_array($resp)) {
													$codigo = $dat[0];
													$nombre = $dat[1];
													$monto = $dat[2];
													$cantidad = $dat[3];
													$descuento = $dat[4];
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
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>

					<!-- Grafico 1 -->
      				<div class="col-md-4">
        				<div class="card h-100">
							<div class="card-header card-header-primary card-header-icon">
								<div class="card-icon">
									<i class="material-icons">bar_chart</i>
								</div>
							</div>
          					<div class="card-body">
            					<div id="chart1"></div>
          					</div>
        				</div>
      				</div>

					<!-- Grafico 2 -->
      				<div class="col-md-4">
        				<div class="card h-100">
							<div class="card-header card-header-warning card-header-icon">
								<div class="card-icon">
									<i class="material-icons">bar_chart</i>
								</div>
							</div>
          					<div class="card-body">
            					<div id="chart2"></div>
          					</div>
        				</div>
      				</div>
				</div>     
				<div class="row mt-4">
					<!-- Grafico 3 -->
					<div class="col-md-12 pr-4">
						<div class="card">
							<div class="card-header card-header-success card-header-icon">
								<div class="card-icon">
									<i class="material-icons">bar_chart</i>
								</div>
							</div>
							<div class="card-body">
								<div id="chart3"></div>
							</div>
						</div>
					</div>
    			</div>      
      		</div>
    	</div>  
  	</div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/apexcharts/3.37.2/apexcharts.min.js"></script>
<script>
	// Colores 
	var colors = [
    '#0056A0',
    '#009688',
    '#FF5722',
    '#673AB7',
    '#FFC107',
    '#4CAF50',
    '#3F51B5',
    '#FF9800',
    '#E91E63',
    '#9E9E9E'
];




	// Función de formateo de números
	function formatNumber(num) {
		return num.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
	}

	// Prepara Graficos Iniciales
	function graficoVentaDimension() {
		var colorsInitial = [
			'#FF6F61',
			'#FF8C00',
			'#00BFFF',
			'#FF1493',
			'#FFC300',
			'#DAF7A6',
			'#FF5733',
			'#33FF57',
			'#3357FF',
			'#FF33A6'
		];

		$.ajax({
			url: 'backendGrafico/rptVentaDimensionMarca.php',
			type: 'POST',
			data: {
				fecha_inicio: <?= "'".$fecha_iniconsulta."'" ?>,
				fecha_fin: <?= "'".$fecha_finconsulta."'" ?>,
				territorios: <?= "'".$rptTerritorio."'" ?>,
			},
			success: function(data) {
				/**************************
				 * Grafico 1 - Monto Venta 
				 **************************/
				var categories = [];
				var seriesData = [];
				
				data.forEach(function(item, index) {
					categories.push(item.nombre);
					seriesData.push(parseFloat(item.montoVenta));
				});

				// * Configuración del Grafico 1
				var options = {
					chart: {
						type: 'bar',
						height: 350,
					},
					plotOptions: {
						bar: {
							barHeight: '100%',
							distributed: true,
							horizontal: true,
							dataLabels: {
								position: 'bottom'
							},
						}
					},
					series: [{
						name: 'Monto de Venta',
						data: seriesData
					}],
					xaxis: {
						categories: categories
					},
					title: {
						text: 'Monto Venta x Marca',
						align: 'center'
					},
					yaxis: {
						title: {
							text: 'Monto de Venta'
						},
						labels: {
							show: false,
							formatter: function(value) {
								return formatNumber(value);
							}
						}
					},
					colors: colorsInitial,
					dataLabels: {
						enabled: true,
						textAnchor: 'start',
						style: {
							colors: ['#000']
						},
						formatter: function (val, opt) {
							return opt.w.globals.labels[opt.dataPointIndex] + ":  " + val
						},
						offsetX: 0,
						dropShadow: {
							enabled: false,
						}
					},
					stroke: {
						width: 1,
						colors: ['#fff']
					},
				}
				
				// ! Renderizar el Gráfico 1
				var chart = new ApexCharts(document.querySelector("#chart1"), options);
				chart.render();

				/****************************
				 * Grafico 2 - Cantidad Venta 
				 ****************************/
				var categories 	 = [];
				var quantityData = [];
				
				data.forEach(function(item, index) {
					categories.push(item.nombre);
					quantityData.push(parseFloat(item.cantidadventa));
				});
				
				// Configuración del gráfico de dona
				var options = {
					chart: {
						type: 'pie',
						height: 350,
					},
					series: quantityData,
					labels: categories,
					title: {
						text: 'Cantidad Venta x Marca',
						align: 'center'
					},
					colors: colors,
					plotOptions: {
						pie: {
							donut: {
								size: '65%'
							}
						}
					},
					dataLabels: {
						enabled: true,
						formatter: function(val, opts) {
							return formatNumber(val);
						},
					},
				}
				
				// ! Renderizar el gráfico 2
				var chart = new ApexCharts(document.querySelector("#chart2"), options);
				chart.render();
			},
			error: function(err) {
				console.error('Error al obtener los datos:', err);
			}
		});
	}

	// Prepara Grafico Evolutivo
	function graficoVentaDimensionMes() {
		$.ajax({
			url: 'backendGrafico/rptVentaDimensionMarcaMes.php',
			type: 'POST',
			data: {
				fecha_inicio: <?= "'".$fecha_iniconsulta."'" ?>,
				fecha_fin: <?= "'".$fecha_finconsulta."'" ?>,
				territorios: <?= "'".$rptTerritorio."'" ?>,
			},
			success: function(response) {
				let categories = response.data.categories;
				let series 	   = response.data.series;
				var options = {
					chart: {
						type: 'area',
						height: 400,
					},
					series: series,
					xaxis: {
						categories: categories
					},
					title: {
						text: 'Monto de Venta por Marca y Mes'
					},
					yaxis: {
						title: {
							text: 'Monto de Venta'
						},
						labels: {
							show: false,
							formatter: function(value) {
								return formatNumber(value)+" Bs.";
							}
						}
					},
					markers: {
						size: 5,
						colors: ['#000'],
						strokeColors: '#fff',
						strokeWidth: 2
					},
					grid: {
						row: {
							colors: ['#f3f3f3', 'transparent'],
							opacity: 0.5
						},
					},
					colors: colors,
					legend: {
						show: true,
						position: 'bottom',
						horizontalAlign: 'center',
						floating: false,
						offsetY: 0,
						labels: {
							colors: '#000',
							useSeriesColor: true 
						}
					}
				};
				
				// ! Renderizar el gráfico 3
				var chart = new ApexCharts(document.querySelector("#chart3"), options);
				chart.render();
			},
			error: function(err) {
				console.error('Error al obtener los datos:', err);
			}
		});
	}

	$(document).ready(function() {
		// Graficos Iniciales
		graficoVentaDimension();
		// Grafico General - Evolutivo
		graficoVentaDimensionMes();
	});
</script>