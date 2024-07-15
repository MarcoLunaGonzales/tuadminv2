<html>
<head>
	<meta charset="utf-8" />
	<title>Minka Software</title>

	<link type="text/css" rel="stylesheet" href="menuLibs/css/demo.css" />
	<link type="text/css" rel="stylesheet" href="menuLibs/dist/jquery.mmenu.css" />

	<script type="text/javascript" src="menuLibs/jquery-3.2.1.min.js"></script>
	<script type="text/javascript" src="menuLibs/dist/jquery.mmenu.js"></script>
	<script type="text/javascript">
		$(function() {
			$('nav#menu').mmenu();
		});
		
</script> 
	</script>
		
</head>
<body>
<?
include("datosUsuario.php");
?>
<div id="page">
	<div class="header">
		<a href="#menu"><span></span></a>
		TuAdmin - <?=$nombreEmpresa;?>
		<div style="position:absolute; width:95%; height:50px; text-align:right; top:0px; font-size: 9px; font-weight: bold; color: #fff;">
			[<? echo $fechaSistemaSesion?>][<? echo $horaSistemaSesion;?>]			
		<div>
		<div style="position:absolute; width:95%; height:50px; text-align:left; top:0px; font-size: 12px; font-weight: bold; color: #ffff00;">
			[<? echo $nombreUsuarioSesion?>]&nbsp;&nbsp;&nbsp;[<? echo $nombreAlmacenSesion;?>]
		<div>
	</div>
	
	
	<div class="content">
		<iframe src="inicio_almacenes.php" name="contenedorPrincipal" id="mainFrame" border="1"></iframe>
	</div>
	
	
	<nav id="menu">
		<ul>
			<li><span>Datos Generales</span>
				<ul>
					<li><a href="programas/proveedores/inicioProveedores.php" target="contenedorPrincipal">Proveedores</a></li>
					<li><span>Gestion de Productos</span>
						<ul>
							<li><a href="navegador_tiposmaterial.php" target="contenedorPrincipal">Tipos de Producto</a></li>
							<li><a href="navegador_grupos.php" target="contenedorPrincipal">Grupos</a></li>
							<li><a href="navegador_material.php?vista=0&vista_ordenar=0&grupo=0" target="contenedorPrincipal">Productos</a></li>
							<li><a href="navegador_precios.php?orden=1" target="contenedorPrincipal">Precios (Orden Alfabetico)</a></li>
							<li><a href="navegador_precios.php?orden=2" target="contenedorPrincipal">Precios (Por Linea Proveedor)</a></li>			
							<li><a href="navegador_precios.php?orden=3" target="contenedorPrincipal">Precios (Por Grupo)</a></li>			
						</ul>
					</li>
					<li><a href="navegador_ajustarpreciostock.php" target="contenedorPrincipal">Ajustar Precio/Stock **</a></li>
					<li><a href="navegador_funcionarios1.php" target="contenedorPrincipal">Funcionarios</a></li>
					<li><a href="programas/clientes/inicioClientes.php" target="contenedorPrincipal">Clientes</a></li>
					<li><a href="navegador_dosificaciones.php" target="contenedorPrincipal">Dosificaciones de Facturas</a></li>
					<!--li><a href="navegador_vehiculos.php" target="contenedorPrincipal">Vehiculos</a></li-->
					<li><span>Gestion de Almacenes</span>
						<ul>
							<li><a href="navegador_almacenes.php" target="contenedorPrincipal">Almacenes</a></li>
							<li><a href="navegador_tiposingreso.php" target="contenedorPrincipal">Tipos de Ingreso</a></li>
							<li><a href="navegador_tipossalida.php" target="contenedorPrincipal">Tipos de Salida</a></li>
							
						</ul>	
					</li>					
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
					<li><a href="navegador_ingresomateriales.php" target="contenedorPrincipal">Ingreso de Materiales</a></li>
					<li><a href="navegador_ingresotransito.php" target="contenedorPrincipal">Ingreso de Materiales en Transito</a></li>
					<li><a href="navegadorLiquidacionIngresos.php" target="contenedorPrincipal">Liquidacion de Ingresos</a></li>
				</ul>	
			</li>
			<li><span>Salidas</span>
				<ul>
					<li><a href="navegador_salidamateriales.php" target="contenedorPrincipal">Listado de Traspasos</a></li>
					<li><a href="navegadorVentas.php" target="contenedorPrincipal">Listado de Ventas</a></li>
				</ul>	
			</li>
			<li><span>Cobranzas</span>
				<ul>
					<li><a href="cobranzas/navegadorCobranzas.php" target="contenedorPrincipal">Listado de Cobranzas</a></li>
					<li><a href="cobranzas/rptOpCobranzas.php" target="contenedorPrincipal">Reporte de Cobros</a></li>
					<li><a href="cobranzas/rptOpCuentasCobrar.php" target="contenedorPrincipal">Reporte Cuentas x Cobrar</a></li>
				</ul>	
			</li>
			<!--li><span>Listado de Cobranzas</span>
				<ul>
					<li><a href="navegadorCobranzas.php" target="contenedorPrincipal">Listado de Cobranzas</a></li>
				</ul>	
			</li-->
			<!--li><span>Configuracion</span>
				<ul>
					<li><a href="navegadorDolar.php" target="contenedorPrincipal">Cambiar Cotizacion de Dolar</a></li>
				</ul>	
			</li-->
			
			<li>
				<span>Obligaciones</span>
				<ul>
					<li><a href="obligaciones/navegadorObligaciones.php" target="contenedorPrincipal">Listado de Obligaciones</a></li>
					<li><a href="obligaciones/rptOpObligaciones.php" target="contenedorPrincipal">Reporte de Pagos</a></li>
					<li><a href="obligaciones/rptOpObligacionesPagar.php" target="contenedorPrincipal">Reporte Obligaciones x Pagar</a></li>
				</ul>	
			</li>
						<li><a href="registrar_salidaventas.php" target='_blank'>Vender / Facturar</a></li>
						<li><a href="cambiarSucursalSesion.php" target="contenedorPrincipal">Cambiar Sucursal</a></li>
						<li><span>Gastos</span>
							<ul>
								<li><a href="navegador_gastos.php" target="contenedorPrincipal">Gastos</a></li>			
								<li><a href="rptOpGastos.php" target="contenedorPrincipal">Reporte Detallado de Gastos</a></li>			
							</ul>
						</li>

						<!--li><a href="navegador_costosimp.php" target="contenedorPrincipal">Items de Importacion</a></li-->
						<li><a href="rptOpVentasDocumento.php" target="contenedorPrincipal">Reporte Ventas x Documento</a></li>
						<li><a href="rptOpArqueoDiario.php?variableAdmin=1" target="contenedorPrincipal" >Arqueo de Caja</a></li>
						

			<li><span>Reportes</span>
				<ul>
					<li><span>Productos</span>
						<ul>
							<li><a href="rptOpProductos.php" target="contenedorPrincipal">Productos</a></li>
							<li><a href="rptOpPrecios.php" target="contenedorPrincipal">Precios</a></li>
							<li><a href="rptProductosIZI.php" target="_blank">Migrado</a></li>
						</ul>
					</li>	
					<li><span>Movimiento de Almacen</span>
						<ul>
							<li><a href="rpt_op_inv_kardex.php" target="contenedorPrincipal">Kardex de Movimiento</a></li>
							<li><a href="rpt_op_inv_existencias.php" target="contenedorPrincipal">Existencias</a></li>
							<li><a href="rptOpInvExistenciasLote.php" target="contenedorPrincipal">Existencias Por Lote</a></li>
							<li><a href="rptOpExistenciasCostos.php" target="contenedorPrincipal">Existencias con Precio de Venta</a></li>
							<li><a href="rpt_op_inv_ingresos.php" target="contenedorPrincipal">Ingresos</a></li>
							<li><a href="rpt_op_inv_salidas.php" target="contenedorPrincipal">Salidas</a></li>
							<li><a href="rpt_op_inv_traspasos.php" target="contenedorPrincipal">Seguimiento de Traspasos</a></li>
							<!--li><a href="rptOCPagar.php" target="contenedorPrincipal">OC por Pagar</a></li-->
						</ul>
					</li>	
					<li><span>Costos</span>
						<ul>
							<li><a href="rptOpKardexCostos.php" target="contenedorPrincipal">Kardex de Movimiento</a></li>
							<!--li><a href="rptOpKardexCostosPEPS.php" target="contenedorPrincipal">Kardex de Movimiento PEPS</a></li>
							<li><a href="rptOpKardexCostosUEPS.php" target="contenedorPrincipal">Kardex de Movimiento UEPS</a></li-->							
							<li><a href="rptOpExistenciasCostos.php" target="contenedorPrincipal">Existencias</a></li>							
						</ul>
					</li>
					<li><span>Ventas Nuevo</span>
						<ul>
							
							<li><a href="rptOpKardexCliente.php" target="contenedorPrincipal">Kardex x Cliente</a></li>
						</ul>	
					</li>
					<li><span>Ventas</span>
						<ul>
							<li><a href="rptOpVentasDocumento.php" target="contenedorPrincipal">Ventas x Documento</a></li>
							<li><a href="rptOpVentasxItem.php" target="contenedorPrincipal">Ranking de Ventas x Item</a></li>
							<li><a href="rptOpVentasGeneral.php" target="contenedorPrincipal">Ventas x Documento e Item</a></li>
							<li><a href="rptOpVentasxPersona.php" target="contenedorPrincipal">Ventas x Vendedor Resumido</a></li>
							<li><a href="rptOpVentasxPersonaDetalle.php" target="contenedorPrincipal">Ventas x Vendedor Detallado</a></li>
							<li><a href="rptOpKardexCliente.php" target="contenedorPrincipal">Kardex x Cliente</a></li>
							<li><a href="rptOpVentasPorClientes.php" target="contenedorPrincipal">Ventas x Cliente</a></li>
							<li><a href="rptOpVentasDetallado.php" target="contenedorPrincipal">Detallado por Item y Linea ** </a></li>
						</ul>	
					</li>

					<li><span>Reportes Contables</span>
						<ul>
							<li><a href="rptOpLibroVentas.php" target="contenedorPrincipal">Libro de Ventas</a></li>
							<!--li><a href="" target="contenedorPrincipal">Libro de Compras</a></li-->
							<!--li><a href="rptOpKardexCliente.php" target="contenedorPrincipal">Kardex x Cliente</a></li-->
						</ul>	
					</li>
					<li><span>Utilidades</span>
						<ul>
							<li><a href="rptOpUtilidadesDocumento.php" target="contenedorPrincipal">Utilidades x Documento</a></li>
							<li><a href="rptOpUtilidadesxItem.php" target="contenedorPrincipal">Ranking de Utilidades x Linea e Item</a></li>
							<li><a href="rptOpUtilidadesDocItem.php" target="contenedorPrincipal">Utilidades x Documento e Item</a></li>
						</ul>	
					</li>
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
				</ul>
			</li>
			<!-- Nueva opción Despacho a Vendedores -->
			<li><a href="navegador_despachoalmacenes.php" target="contenedorPrincipal" >Despacho de Productos</a></li>		
	</nav>
</div>
	</body>
</html>