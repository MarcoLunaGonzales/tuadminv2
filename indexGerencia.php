<html>
<head>
	<meta charset="utf-8" />
	<title>MinkaSoftware</title> 
	    <link rel="shortcut icon" href="imagenes/icon_farma.ico" type="image/x-icon">
	<link type="text/css" rel="stylesheet" href="menuLibs/css/demo.css" />
	<script type="text/javascript" src="http://code.jquery.com/jquery-3.2.1.min.js"></script>
	<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"/>
	<style>  
	.boton-rojo
{
    text-decoration: none !important;
    padding: 10px !important;
    font-weight: 600 !important;
    font-size: 12px !important;
    color: #ffffff !important;
    background-color: #E73024 !important;
    border-radius: 3px !important;
    border: 2px solid #E73024 !important;
}
.boton-rojo:hover{
    color: #000000 !important;
    background-color: #ffffff !important;
  }
   .boton-plomo
{
    text-decoration: none !important;
    font-weight: 0 !important;
    font-size: 12spx !important;
    color: #ffffFF !important;
    background-color: #88898A !important;
    border-radius: 3px !important;
    border: 2px solid #88898A !important;
}
.boton-plomo:hover{
    color: #000000 !important;
    background-color: #ffffff !important;
  }


	/**
	* Estilos para el boton de notificaciones
	**/
	.notification-btn {
		background-color: transparent;
		border: none;
		position: relative;
		right: 0px;
		cursor: pointer;
		font-size: 16px;
		color: #fff;
		z-index: 99999;
	}

	.notification-icon {
		border-radius: 50%;
		font-size: 24px;
		color: #fff;
		position: relative;
		transition: transform 0.8s ease;
		text-shadow: 
			1px 1px 0 white, /* Right */
			-1px 1px 0 white, /* Left */
			1px -1px 0 white, /* Top */
			-1px -1px 0 white; /* Bottom */
	}


	.notification-icon.active {
		animation: pulse 3s infinite;
	}

	@keyframes pulse {
		0% {
			box-shadow: 0 0 0 0 rgba(255, 223, 0, 0.7);
		}
		50% {
			box-shadow: 0 0 0 20px rgba(255, 223, 0, 0);
		}
		100% {
			box-shadow: 0 0 0 0 rgba(255, 223, 0, 0);
		}
	}


	.notification-badge {
		position: absolute;
		top: 0;
		right: 0;
		width: 10px;
		height: 10px;
		background-color: red;
		border-radius: 50%;
		border: 2px solid white;
		pointer-events: none;
	}


</style>
     <link rel="stylesheet" href="dist/css/demo.css" />
     <link rel="stylesheet" href="dist/mmenu.css" />
	 <link rel="stylesheet" href="dist/demo.css" />
</head>

<body>
<?php
require_once 'datosUsuario.php';
require_once 'funciones.php';

$serverSIAT=obtenerValorConfiguracion(7);

/**
 * Verificación notificación
 */
require('conexion.inc');
$fecha_actual = date('Y-m-d');
$sqlVeri = "SELECT ns.codigo, ns.cod_producto, ns.stock_minimo, ns.stock, ns.fecha_registro
			FROM notificaciones_stocks ns
			WHERE ns.fecha_registro = '$fecha_actual'";
$respVeri = mysqli_query($enlaceCon, $sqlVeri);
$cant_notif = 0;
if ($respVeri) {
    $cant_notif = mysqli_num_rows($respVeri);
}

?>
<div id="page">
	<!-- Modal -->
	<div class="modal fade" id="notificationModal" tabindex="-1" aria-labelledby="notificationModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="notificationModalLabel">Notificación de Inventario</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary" data-bs-dismiss="modal">Entendido</button>
					<a href="/ruta-al-reporte" class="btn btn-warning">Ver Reporte</a>
				</div>
			</div>
		</div>
	</div>
	<div class="header">
		<a href="#menu"><span></span></a>
		<p style="font-size: 25px;"><?=$_COOKIE["global_empresa_nombre"];?></p>
		<div style="position:absolute; width:95%; height:50px; text-align:right; top:0px; font-size: 9px; font-weight: bold; color: #fff;">
			<?php
				if($cant_notif > 0){
			?>
			<!-- Botón de Notificaciones -->
			<button ttype="button" class="notification-btn" title="Tienes notificaciones pendientes" id="abreModalNotificacion" onclick="window.contenedorPrincipal.location.href='notificacionesStocks.php'">
				<i class="material-icons notification-icon active text-warning" style="font-size:17px;">notifications</i>
				<span class="notification-badge"></span>
			</button>
			<?php
				}else{
			?>
			<!-- Botón de Notificaciones -->
			<button ttype="button" class="notification-btn" title="No tienes notificaciones">
				<i class="material-icons notification-icon" style="font-size:17px;">notifications</i>
			</button>
			<?php
				}
			?>
			[<?=$fechaSistemaSesion;?>][<?=$horaSistemaSesion;?>]		<button onclick="location.href='salir.php'" style="position:relative;z-index:99999;right:0px;" class="boton-rojo" title="Salir">
				<i class="material-icons" style="font-size: 16px">logout</i>
			</button>
		<div>
		<div style="position:absolute; width:95%; height:50px; text-align:left; top:0px; font-size: 12px; font-weight: bold; color: #ffff00;">
			
			[<?=$nombreUsuarioSesion; ?>]&nbsp;&nbsp;&nbsp;[<?=$nombreAlmacenSesion;?>]
			<button onclick="window.contenedorPrincipal.location.href='cambiarSucursalSesion.php'" style="position:relative;z-index:99999;right:0px;" class="boton-rojo" title="Cambiar Sucursal" formtarget="contenedorPrincipal">
				<i class="material-icons" style="font-size: 16px">swap_horiz</i>
			</button>
			<button onclick="window.contenedorPrincipal.location.href='editPerfil.php'" style="position:relative;z-index:99999;right:0px;" class="boton-rojo" title="Cambiar Clave de Acceso" formtarget="contenedorPrincipal">
				<i class="material-icons" style="font-size: 16px">person</i>
			</button>
		<div>
	</div>
	
	
	<div class="content">
		<iframe src="inicio_almacenes.php" name="contenedorPrincipal" id="mainFrame" border="1"></iframe>
	</div>
	
	
	<nav id="menu">

		<div id="panel-menu">
		
		<ul>
			<li><span>Datos Generales</span>
				<ul>
					<li><a href="programas/proveedores/inicioProveedores.php" target="contenedorPrincipal">Fabricantes</a></li>

					<!--li><a href="materiales_serviteca/list.php" target="contenedorPrincipal">Productos Serviteca</a></li-->

					<li><span>Gestion de Productos</span>
						<ul>
							<li><a href="navegador_tiposmaterial.php" target="contenedorPrincipal">Tipos de Producto</a></li>
							<li><a href="navegador_grupos.php" target="contenedorPrincipal">Grupos</a></li>
							<li><a href="pais_procedencia/list.php" target="contenedorPrincipal">Paises de Origen</a></li>
							<li><a href="navegador_material.php?vista=0&vista_ordenar=0&grupo=0" target="contenedorPrincipal">Productos</a></li>
							<!--li><a href="navegador_precios.php?orden=1" target="contenedorPrincipal">Precios (Orden Alfabetico)</a></li>
							<li><a href="navegador_precios.php?orden=2" target="contenedorPrincipal">Precios (Por Linea Proveedor)</a></li>			
							<li><a href="navegador_precios.php?orden=3" target="contenedorPrincipal">Precios (Por Grupo)</a></li-->			
						</ul>
					</li>
					<li><a href="navegador_funcionarios1.php" target="contenedorPrincipal">Funcionarios</a></li>
					<li><a href="programas/clientes/inicioClientes.php" target="contenedorPrincipal">Clientes</a></li>
					<li><a href="navegador_costosimp.php" target="contenedorPrincipal">Items de Importacion</a></li>
					<li><span>Gestion de Almacenes</span>
						<ul>
							<li><a href="navegador_almacenes.php" target="contenedorPrincipal">Almacenes</a></li>
							<li><a href="navegador_tiposingreso.php" target="contenedorPrincipal">Tipos de Ingreso</a></li>
							<li><a href="navegador_tipossalida.php" target="contenedorPrincipal">Tipos de Salida</a></li>
							
						</ul>	
					</li>	
					<li><a href="tipos_cambios/list.php" target="contenedorPrincipal">Tipo de Cambio</a></li>	
					<li><a href="transportadoras/generalLista.php" target="contenedorPrincipal">Transportadoras</a></li>				
				</ul>	
			</li>

			<!--li><span>Ordenes de Compra</span>
				<ul>
					<li><a href="navegador_ordenCompra.php" target="contenedorPrincipal">Registro de O.C.</a></li>
					<li><a href="registrarOCTerceros.php" target="contenedorPrincipal">Registro de O.C. de Terceros</a></li>
					<li><a href="navegadorIngresosOC.php" target="contenedorPrincipal">Generar OC a traves de Ingreso</a></li>
					<li><a href="navegador_pagos.php" target="contenedorPrincipal">Registro de Pagos</a></li>
				</ul>	
			</li-->
			<li><span>Ingresos</span>
				<ul>
					<li><a href="navegador_ingresomateriales.php"  target='_blank'>Ingreso de Productos</a></li>
					<li><a href="navegador_ingresotransito.php" target="contenedorPrincipal">Ingreso de Productos en Transito</a></li>
					<!--li><a href="navegadorLiquidacionIngresos.php" target="contenedorPrincipal">Liquidacion de Ingresos</a></li-->
				</ul>	
			</li>
			<li><span>Salidas</span>
				<ul>
					<li><a href="navegador_salidamateriales.php" target="_blank">Listado de Traspasos</a></li>
					<li><a href="navegadorVentas.php" target="_blank">Listado de Ventas</a></li>
					<!--li><a href="navegadorVentasServiteca.php" target="contenedorPrincipal">Listado de Ventas Serviteca</a></li-->
				</ul>	
			</li>

			<li><span>Gastos</span>
				<ul>
					<li><a href="navegador_gastos.php" target="contenedorPrincipal">Gastos</a></li>			
					<li><a href="rptOpGastos.php" target="contenedorPrincipal">Reporte detallado de Gastos</a></li>
				</ul>	
			</li>

			<li><span>Cobranzas</span>
				<ul>
					<li><a href="cobranzas/navegadorCobranzas.php" target="contenedorPrincipal">Listado de Cobranzas</a></li>
					<li><a href="cobranzas/rptOpCobranzas.php" target="contenedorPrincipal">Reporte de Cobros</a></li>
					<li><a href="cobranzas/rptOpCuentasCobrar.php" target="contenedorPrincipal">Reporte Cuentas x Cobrar</a></li>
				</ul>	
			</li>

			<li><a href="registrar_salidaventas.php" target='_blank'>Vender / Facturar</a></li>
			<li><a href="navegadorCotizaciones.php" target='_blank'>Cotizaciones</a></li>			
			<li><a href="listadoProductosStock.php" target='_blank'>Stock Actual **</a></li>
			<li><a href="rptOpArqueoDiario.php?variableAdmin=1" target="contenedorPrincipal" >Arqueo de Caja</a></li>
			
  			<!-- Nueva Sección de Pagos de Servicio por Pagar -->
			<!-- <li><span>Obligaciones</span>
				<ul>
					<li><a href="obligaciones/navegadorObligaciones.php" target="contenedorPrincipal">Listado de Obligaciones</a></li>
					<li><a href="obligaciones/rptOpObligaciones.php" target="contenedorPrincipal">Reporte de Pagos</a></li>
					<li><a href="obligaciones/rptOpObligacionesPagar.php" target="contenedorPrincipal">Reporte Obligaciones x Pagar</a></li>
				</ul>	
			</li> -->
				
			<li><span>Obligaciones</span>
				<ul>	
					<li><span>Obligaciones</span>
						<ul>
							<li><a href="obligaciones/navegadorObligaciones.php" target="contenedorPrincipal">Listado de Obligaciones</a></li>
							<li><a href="obligaciones/rptOpObligaciones.php" target="contenedorPrincipal">Reporte de Pagos</a></li>
							<li><a href="obligaciones/rptOpObligacionesPagar.php" target="contenedorPrincipal">Reporte Obligaciones x Pagar</a></li>
						</ul>	
					</li>
				</ul>	
			</li>

			<!--li><span>Adicionales</span>
				<ul>
					<li><span>SIAT</span>
					<ul>
						<li><a href="<?=$serverSIAT;?>siat_folder/siat_facturacion_offline/facturas_sincafc_list.php" target="contenedorPrincipal">Facturas Off-line</a></li>
						<li><a href="<?=$serverSIAT;?>siat_folder/siat_sincronizacion/index.php" target="contenedorPrincipal">Sincronización</a></li>
						<li><a href="<?=$serverSIAT;?>siat_folder/siat_cuis_cufd/index.php" target="contenedorPrincipal">Generación CUIS y CUFD</a></li>
					</ul>	
					</li>					
				</ul>								
			</li-->

			<li><span>Reportes</span>
				<ul>
					<li><span>Productos</span>
						<ul>
							<!--li><a href="rptOpProductos.php" target="contenedorPrincipal">Productos</a></li-->
							<li><a href="rptOpPrecios.php" target="contenedorPrincipal">Precios</a></li>
						</ul>
					</li>	
					<li><span>Movimiento de Almacen</span>
						<ul>
							<li><a href="rpt_op_inv_kardex.php" target="contenedorPrincipal">Kardex de Movimiento</a></li>
							<li><a href="rpt_op_inv_existencias.php" target="contenedorPrincipal">Existencias</a></li>
							<!--li><a href="rptOpExistenciasCostos.php" target="contenedorPrincipal">Existencias con Precio de Venta</a></li-->
							<li><a href="rpt_op_inv_ingresos.php" target="contenedorPrincipal">Ingresos</a></li>
							<li><a href="rpt_op_inv_salidas.php" target="contenedorPrincipal">Salidas</a></li>
							
							<!--li><a href="rpt_op_inv_traspasos.php" target="contenedorPrincipal">Seguimiento Traspasos</a></li>
							<li><a href="rpt_op_inv_ingresossalidas.php" target="contenedorPrincipal">Ingresos Vs. Salidas</a></li-->
							
							<!--li><a href="rptOCPagar.php" target="contenedorPrincipal">OC por Pagar</a></li-->
						</ul>
					</li>	
					<!--li><span>Costos</span>
						<ul>
							<li><a href="rptOpKardexCostos.php" target="contenedorPrincipal">Kardex de Movimiento Precio Promedio</a></li>			
							<li><a href="rptOpExistenciasCostos.php" target="contenedorPrincipal">Existencias</a></li>							
						</ul>
					</li-->
					<li><span>Ventas</span>
						<ul>
							<li><a href="rptOpVentasDocumento.php" target="contenedorPrincipal">Ventas x Documento</a></li>
							<!--li><a href="rptOpVentasDetallado.php" target="contenedorPrincipal">Detallado por Item y Linea</a></li-->
							<li><a href="rptOpVentasxItem.php" target="contenedorPrincipal">Ranking de Ventas x Producto</a></li>

							<!--li><a href="rptOpVentasGeneral.php" target="contenedorPrincipal">Ventas x Documento e Item</a></li-->
							<li><a href="rptOpVentasxPersonaDetalle.php" target="contenedorPrincipal">Ventas x Vendedor</a></li>
							<li><a href="rptOpVentasSucursalTipoPago.php" target="contenedorPrincipal">Ventas x Sucursal y Tipo de Pago</a></li>
							<li><a href="rptOpVentasDimension.php" target="contenedorPrincipal">Estadisticos de Venta</a></li>
							<li><a href="rptOpKardexCliente.php" target="contenedorPrincipal">Kardex x Cliente</a></li>
						</ul>	
					</li>
					<li><span>Reportes Contables</span>
						<ul>
							<li><a href="rptOpLibroVentas.php" target="contenedorPrincipal">Libro de Ventas</a></li>
							<!--li><a href="" target="contenedorPrincipal">Libro de Compras</a></li-->
							<!--li><a href="rptOpKardexCliente.php" target="contenedorPrincipal">Kardex x Cliente</a></li-->
						</ul>	
					</li>
					<!--li><span>Utilidades</span>
						<ul>
							<li><a href="rptOpUtilidadesDocumento.php" target="contenedorPrincipal">Utilidades x Documento</a></li>
							<li><a href="rptOpUtilidadesDocumentoServiteca.php" target="contenedorPrincipal">Utilidades x Documento Serviteca</a></li>
							<li><a href="rptOpUtilidadesxItem.php" target="contenedorPrincipal">Ranking de Utilidades x Linea e Item</a></li>
							<li><a href="rptOpUtilidadesDocItem.php" target="contenedorPrincipal">Utilidades x Documento e Item</a></li>
							<li><a href="rptOpUtilidadesNetas.php" target="contenedorPrincipal">Utilidad Neta x Periodo</a></li>
						</ul>	
					</li-->
					<!--li><span>Cobranzas</span>
						<ul>
							<li><a href="rptOpCobranzas.php" target="contenedorPrincipal">Cobranzas</a></li>
							<li><a href="rptOpCuentasCobrar.php" target="contenedorPrincipal">Cuentas por Cobrar</a></li>
						</ul>	
					</li-->
				</ul>
			</li>		
			<li><span>Utilitarios</span>
				<ul>
					<li><a href="configuraciones_sistema.php" target="contenedorPrincipal">Configuraciones</a></li>
					<!--li><a href="reprocesarcostos.php" target="_blank">Reprocesar Costos</a></li-->
				</ul>
			</li>	
		</div>	
	</nav>
</div>

<script src="dist/mmenu.polyfills.js"></script>
<script src="dist/mmenu.js"></script>
<script src="dist/demo.js"></script>
	</body>
</html>