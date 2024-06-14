<?php
    require("../conexionmysqli.inc");
    require("../estilos_almacenes_central_sincab.php");
    require("../funciones.php");
    require("../funcion_nombres.php");

    // Obtener datos de la empresa
    $sqlEmpresa = "SELECT nombre, nit, direccion FROM datos_empresa";
    $respEmpresa = mysqli_query($enlaceCon, $sqlEmpresa);
    $nombreEmpresa = "";
    $nitEmpresa = 0;
    $direccionEmpresa = "";
    while ($datEmpresa = mysqli_fetch_assoc($respEmpresa)) {
        $nombreEmpresa 	  = $datEmpresa['nombre'];
        $nitEmpresa 	  = $datEmpresa['nit'];
        $direccionEmpresa = $datEmpresa['direccion'];
    }

    $global_almacen = $_COOKIE["global_almacen"];


	$codCobro = $_GET['codCobro'];
	$sqlDatos="SELECT c.cod_cobro, 
					c.fecha_cobro,
					c.observaciones,
					c.monto_cobro,
					(SELECT concat(cl.nombre_cliente,' ',cl.paterno) FROM clientes cl WHERE c.cod_cliente = cl.cod_cliente), 
					c.nro_cobro, 
					(select g.nombre_gestion from gestiones g where g.cod_gestion=c.cod_gestion) 
				FROM cobros_cab c 
				WHERE c.cod_cobro = '$codCobro' 
				ORDER BY c.cod_cobro DESC";
	$respDatos=mysqli_query($enlaceCon, $sqlDatos);
	while($datDatos=mysqli_fetch_array($respDatos)){
		$fechaCobro		= $datDatos[1];
		$obsNota		= $datDatos[2];
		$montoCobro		= $datDatos[3];
		$nombreCliente	= $datDatos[4];			
		$nroCobro		= $datDatos[5];
		$gestion		= $datDatos[6];
	}
?>

<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f9f9f9;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .texto {
            font-family: 'Arial', sans-serif;
        }
        .bordeNegroTdMod {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }
        table {
            width: 90%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: #fff;
        }
        th, td {
            padding: 10px;
            text-align: center;
            border: 1px solid #ccc;
        }
        th {
            background-color: #e6e6e6;
            color: #333;
			font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        .header {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 20px;
            text-align: center;
        }
        .footer {
            width: 100%;
            text-align: center;
            padding: 10px;
            margin-top: 20px;
        }
        .footer table {
            width: 80%;
            border: none;
        }
        .footer td {
            border: none;
            padding: 20px;
        }
        .footer .bordeNegroTdMod {
            border: 1px solid #333;
            padding: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <?php echo $nombreEmpresa; ?> - <span style="color: #E07A01">DETALLE DE COBRANZA</span>
    </div>
    <table class="texto">
        <tr>
            <td align="left" width="30%"><b>Nro. de Cobranza:</b> <?php echo $nroCobro; ?></td>
            <td align="center" width="30%"><b>Cliente:</b> <?php echo $nombreCliente; ?></td>
            <td align="right" width="30%"><b>Fecha:</b> <?php echo $fechaCobro; ?></td>
        </tr>
        <tr>
            <td colspan="3" class="bordeNegroTdMod"><b>Observaciones:</b> <?php echo $obsNota; ?></td>
        </tr>
    </table>
    <br>
    <table class="texto" cellspacing="0">
        <tr>
            <th width="15%">Nro. Doc. Pago</th>
            <th width="20%">Venta</th>
            <th width="20%">Tipo Pago</th>
            <th width="25%">Referencia</th>
            <th width="10%">Monto</th>
            <th width="10%">Archivo</th>
        </tr>
        <?php
            // Obtener detalles de la salida
			$sql_detalle="SELECT cd.nro_doc, 
								cd.monto_detalle, 
								td.abreviatura, 
								s.nro_correlativo, 
								s.razon_social, 
								(select tp.abreviatura from tipos_pago tp where tp.cod_tipopago=cd.cod_tipopago) as tipopago, 
								cd.referencia, 
								cd.archivo
						FROM cobros_cab c, cobros_detalle cd, salida_almacenes s, tipos_docs td
						WHERE c.cod_cobro = cd.cod_cobro 
						AND cd.cod_venta = s.cod_salida_almacenes 
						AND c.cod_cobro = '$codCobro' 
						AND td.codigo = s.cod_tipo_doc";
			$resp_detalle=mysqli_query($enlaceCon, $sql_detalle);
			// echo $sql_detalle;
			$montoTotal=0;
            while ($dat_detalle = mysqli_fetch_assoc($resp_detalle)) {
				$nroDoc			 = $dat_detalle['nro_doc'];
				$montoDet		 = $dat_detalle['monto_detalle'];
				$nroVenta		 = $dat_detalle['abreviatura']."-".$dat_detalle['nro_correlativo'];
				$razonSocial	 = $dat_detalle['razon_social'];
				$tipoPagoDetalle = $dat_detalle['tipopago'];
				$referencia		 = $dat_detalle['referencia'];
				$archivo		 = $dat_detalle['archivo'];
			
				
				$montoTotal		 = $montoTotal + $montoDet;
				$montoDet		 = redondear2($montoDet);
        ?>
        <tr>
            <td class="bordeNegroTdMod"><?php echo $nroDoc; ?></td>
            <td class="bordeNegroTdMod"><?php echo $nroVenta; ?></td>
            <td class="bordeNegroTdMod"><?php echo $tipoPagoDetalle; ?></td>
            <td class="bordeNegroTdMod"><?php echo $referencia; ?></td>
            <td class="bordeNegroTdMod" style="text-align: right;"><?php echo number_format($montoDet, 2, ".",","); ?></td>
			<td class="bordeNegroTdMod" align="center" style="text-align: center;">
				<?php if (!empty($archivo)) : ?>
					<div style="display: inline-block;">
						<button type="button" 
								class="btn btn-primary abrirArchivo"
								data-archivo="<?= $archivo; ?>"
								style="border: none; border-radius: 50%; background: none; padding: 0; margin: 0;"
								title="Ver Archivo">
							<img src="../imagenes/ver_archivo.png" alt="Ver Archivo" width="30" height="30">
						</button>
					</div>
				<?php else : ?>
					<div style="display: inline-block;">
						No disponible
					</div>
				<?php endif; ?>
			</td>
        </tr>
        <?php
            }
        ?>
        <tr>
            <th colspan="3"></th>
            <th style="text-align: right;">Total Cobrado</th>
            <th style="text-align: right;"><?php echo number_format($montoTotal, 2, ".",","); ?></th>
			<th></th>
        </tr>
    </table>
    <br><br><br><br><br>
    <div class="footer">
        <table>
            <tr>
                <td class="bordeNegroTdMod" width="33%" align="center">Entregue Conforme</td>
                <td class="bordeNegroTdMod" width="33%" align="center">Recibi Conforme</td>
            </tr>
        </table>
    </div>
	<!-- Modal -->
	<div class="modal fade" id="fileModal" tabindex="-1" role="dialog" aria-labelledby="fileModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-xl" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="fileModalLabel"><b style="font-weight: bold; color: #4E4EDB; text-align: center;">VISOR DE ARCHIVO ADJUNTO</b></h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div id="fileViewer">
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
				</div>
			</div>
		</div>
	</div>
	<style>
		#fileViewer {
			display: flex;
			justify-content: center;
			align-items: center;
			height: 100%;
		}
	</style>
	<script>
		$(document).ready(function() {
			$('body').on('click', '.abrirArchivo', function() {
				var file = $(this).data('archivo');
				var fileType = getFileType(file);
				var fileUrl = 'archivos_cobro/' + file;

				if (fileType === 'image') {
					// Mostrar imagen en un <img>
					$('#fileViewer').html('<img src="' + fileUrl + '" class="img-fluid" alt="Imagen" style="max-width: 100%; max-height: 80vh;" >');
				} else {
					// Mostrar documento en un <iframe>
					$('#fileViewer').html('<iframe id="fileFrame" src="' + fileUrl + '" width="100%" height="400px"></iframe>');
				}

				$('#fileModal').modal('show');
			});

			// Función para determinar el tipo de archivo basado en su extensión
			function getFileType(fileName) {
				var ext = fileName.split('.').pop().toLowerCase();
				if (ext === 'jpg' || ext === 'jpeg' || ext === 'png' || ext === 'gif') {
					return 'image';
				} else {
					return 'document';
				}
			}
		});
	</script>
</body>
</html>
