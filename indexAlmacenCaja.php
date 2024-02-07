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
			<button onclick="location.href='salir.php'" style="position:relative;z-index:99999;right:0px;" class="boton-rojo" title="Salir">
				<i class="material-icons" style="font-size: 16px">logout</i>
			</button>	
		<div>
		<div style="position:absolute; width:95%; height:50px; text-align:left; top:0px; font-size: 12px; font-weight: bold; color: #ffff00;">
			[<?=$nombreUsuarioSesion; ?>]&nbsp;&nbsp;&nbsp;[<?=$nombreAlmacenSesion;?>]
			<!--button onclick="window.contenedorPrincipal.location.href='cambiarSucursalSesion.php'" style="position:relative;z-index:99999;right:0px;" class="boton-rojo" title="Cambiar Sucursal" formtarget="contenedorPrincipal">
				<i class="material-icons" style="font-size: 16px">swap_horiz</i>
			</button-->
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
			<li><span>Ingresos</span>
				<ul>
					<li><a href="navegador_ingresomateriales.php" target="contenedorPrincipal">Ingreso de Materiales</a></li>
					<li><a href="navegador_ingresotransito.php" target="contenedorPrincipal">Ingreso de Materiales en Transito</a></li>
				</ul>	
			</li>
			<li><span>Salidas</span>
				<ul>
					<li><a href="navegador_salidamateriales.php" target="contenedorPrincipal">Listado de Traspasos</a></li>
					<li><a href="navegadorVentas.php" target="contenedorPrincipal">Listado de Ventas</a></li>
				</ul>	
			</li>

			<!-- Versión Nueva -->
			<li><a href="registrar_salidaventas.php" target='_blank'>Vender / Facturar</a></li>
			<li><a href="rptOpArqueoDiario.php?variableAdmin=1" target="contenedorPrincipal" >Arqueo de Caja</a></li>
										
			<li><span>Gastos</span>
				<ul>
					<li><a href="navegador_gastos.php" target="contenedorPrincipal">Gastos</a></li>			
				</ul>	
			</li>

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
					<li><span>Ventas</span>
						<ul>
							<li><a href="rptOpVentasDocumento.php" target="contenedorPrincipal">Ventas x Documento</a></li>
							<li><a href="rptOpVentasDetallado.php" target="contenedorPrincipal">Detallado por Item y Linea</a></li>
						</ul>	
					</li>
				</ul>
			</li>		
			<li><span>Utilitarios</span>
				<ul>
					<li><a href="configuraciones_sistema.php" target="contenedorPrincipal">Configuraciones</a></li>
					<li><a href="reprocesarcostos.php" target="_blank">Reprocesar Costos</a></li>
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