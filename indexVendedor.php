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
		<p style="font-size: 25px;"><?=$_COOKIE["global_empresa_nombre"];?></p>
		<div style="position:absolute; width:95%; height:50px; text-align:right; top:0px; font-size: 9px; font-weight: bold; color: #fff;">
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
			<li><a href="navegadorCotizaciones.php" target='_blank'>Cotizaciones</a></li>
			
			<li><a href="listadoProductosStock.php" target='_blank'>Stock Actual **</a></li>

			<li><span>Cobranzas</span>
				<ul>
					<li><a href="cobranzas/navegadorCobranzas.php" target="contenedorPrincipal">Listado de Cobranzas</a></li>
				</ul>	
			</li>

			<li><a href="navegador_gastos.php" target="contenedorPrincipal">Gastos</a></li>			


		</div>	
	</nav>
</div>
<script src="dist/mmenu.polyfills.js"></script>
<script src="dist/mmenu.js"></script>
<script src="dist/demo.js"></script>

	</body>
</html>