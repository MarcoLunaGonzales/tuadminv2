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

?>
<div id="page">
	<div class="header">
		<a href="#menu"><span></span></a>
		<?=$_COOKIE["global_empresa_nombre"];?>
		<div style="position:absolute; width:95%; height:50px; text-align:right; top:0px; font-size: 9px; font-weight: bold; color: #fff;">
			[<?=$fechaSistemaSesion;?>][<?=$horaSistemaSesion;?>]			
		<div>
		<div style="position:absolute; width:95%; height:50px; text-align:left; top:0px; font-size: 12px; font-weight: bold; color: #ffff00;">
			[<?=$nombreUsuarioSesion; ?>]&nbsp;&nbsp;&nbsp;[<?=$nombreAlmacenSesion;?>]
		<div>
	</div>
	
	
	<div class="content">
		<iframe src="inicio_almacenes.php" name="contenedorPrincipal" id="mainFrame" border="1"></iframe>
	</div>
	
	
	<nav id="menu">

		<div id="panel-menu">
		
		<ul>
			<!--li><span>Datos Generales</span>
				<ul>
					<li><a href="programas/proveedores/inicioProveedores.php" target="contenedorPrincipal">Proveedores</a></li>

					<li><a href="materiales_serviteca/list.php" target="contenedorPrincipal">Materiales Serviteca</a></li>

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
					<li><a href="navegador_funcionarios1.php" target="contenedorPrincipal">Funcionarios</a></li>
					<li><a href="programas/clientes/inicioClientes.php" target="contenedorPrincipal">Clientes</a></li>
					<li><span>Gestion de Almacenes</span>
						<ul>
							<li><a href="navegador_almacenes.php" target="contenedorPrincipal">Almacenes</a></li>
							<li><a href="navegador_tiposingreso.php" target="contenedorPrincipal">Tipos de Ingreso</a></li>
							<li><a href="navegador_tipossalida.php" target="contenedorPrincipal">Tipos de Salida</a></li>
							
						</ul>	
					</li>					
				</ul>	
			</li-->

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
					<li><a href="navegadorVentasServiteca.php" target="contenedorPrincipal">Listado de Ventas Serviteca</a></li>
				</ul>	
			</li>
			<li><span>SIAT</span>
				<ul>
					<li><a href="<?=$serverSIAT;?>siat_folder/siat_facturacion_offline/facturas_sincafc_list.php" target="contenedorPrincipal">Facturas Off-line</a></li>
					<li><a href="<?=$serverSIAT;?>siat_folder/siat_sincronizacion/index.php" target="contenedorPrincipal">Sincronización</a></li>
					<li><a href="<?=$serverSIAT;?>siat_folder/siat_cuis_cufd/index.php" target="contenedorPrincipal">Generación CUIS y CUFD</a></li>
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
						<li><a href="registrar_salidaventas.php" target='_blank'>Vender / Facturar</a></li>
						<li><a href="cambiarSucursalSesion.php" target="contenedorPrincipal">Cambiar Sucursal</a></li>
						<li><a href="navegador_gastos.php" target="contenedorPrincipal">Gastos</a></li>			
						<li><a href="navegador_costosimp.php" target="contenedorPrincipal">Items de Importacion</a></li>
						<li><a href="rptOpVentasDocumento.php" target="contenedorPrincipal">Reporte Ventas x Documento</a></li>
						<li><a href="rptOpGastos.php" target="contenedorPrincipal">Reporte detallado de Gastos</a></li>
						<li><a href="rptOpArqueoDiario.php?variableAdmin=1" target="contenedorPrincipal" >Arqueo de Caja</a></li>
						<li><a href="rptOpArqueoDiarioServiteca.php?variableAdmin=1" target="contenedorPrincipal" >Arqueo de Caja Serviteca</a></li>
						

			<li><span>Reportes</span>
				<ul>
					<li><span>Productos</span>
						<ul>
							<li><a href="rptOpProductos.php" target="contenedorPrincipal">Productos</a></li>
							<li><a href="rptOpPrecios.php" target="contenedorPrincipal">Precios</a></li>
						</ul>
					</li>	
					<li><span>Movimiento de Almacen</span>
						<ul>
							<li><a href="rpt_op_inv_kardex.php" target="contenedorPrincipal">Kardex de Movimiento</a></li>
							<li><a href="rpt_op_inv_existencias.php" target="contenedorPrincipal">Existencias</a></li>
							<li><a href="rptOpExistenciasCostos.php" target="contenedorPrincipal">Existencias con Precio de Venta</a></li>
							<li><a href="rpt_op_inv_ingresos.php" target="contenedorPrincipal">Ingresos</a></li>
							<li><a href="rpt_op_inv_salidas.php" target="contenedorPrincipal">Salidas</a></li>
							<li><a href="rpt_op_inv_traspasos.php" target="contenedorPrincipal">Seguimiento Traspasos</a></li>
							<li><a href="rpt_op_inv_ingresossalidas.php" target="contenedorPrincipal">Ingresos Vs. Salidas</a></li>
							<!--li><a href="rptOCPagar.php" target="contenedorPrincipal">OC por Pagar</a></li-->
						</ul>
					</li>	
					<li><span>Costos</span>
						<ul>
							<li><a href="rptOpKardexCostos.php" target="contenedorPrincipal">Kardex de Movimiento Precio Promedio</a></li>
							<!--li><a href="rptOpKardexCostosPEPS.php" target="contenedorPrincipal">Kardex de Movimiento PEPS</a></li>
							<li><a href="rptOpKardexCostosUEPS.php" target="contenedorPrincipal">Kardex de Movimiento UEPS</a></li-->							
							<li><a href="rptOpExistenciasCostos.php" target="contenedorPrincipal">Existencias</a></li>							
						</ul>
					</li-->
					<li><span>Ventas</span>
						<ul>
							<li><a href="rptOpVentasDocumento.php" target="contenedorPrincipal">Ventas x Documento</a></li>
							<li><a href="rptOpVentasDetallado.php" target="contenedorPrincipal">Detallado por Item y Linea</a></li>
							<li><a href="rptOpVentasxItem.php" target="contenedorPrincipal">Ranking de Ventas x Item</a></li>
							<li><a href="rptOpVentasGeneral.php" target="contenedorPrincipal">Ventas x Documento e Item</a></li>
							<li><a href="rptOpVentasxPersonaDetalle.php" target="contenedorPrincipal">Ventas x Vendedor</a></li>
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
					<li><span>Utilidades</span>
						<ul>
							<li><a href="rptOpUtilidadesDocumento.php" target="contenedorPrincipal">Utilidades x Documento</a></li>
							<li><a href="rptOpUtilidadesDocumentoServiteca.php" target="contenedorPrincipal">Utilidades x Documento Serviteca</a></li>
							<li><a href="rptOpUtilidadesxItem.php" target="contenedorPrincipal">Ranking de Utilidades x Linea e Item</a></li>
							<li><a href="rptOpUtilidadesDocItem.php" target="contenedorPrincipal">Utilidades x Documento e Item</a></li>
							<li><a href="rptOpUtilidadesNetas.php" target="contenedorPrincipal">Utilidad Neta x Periodo</a></li>
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
		</div>	
	</nav>
</div>
<script src="dist/mmenu.polyfills.js"></script>
<script src="dist/mmenu.js"></script>
<script src="dist/demo.js"></script>

	</body>
</html>