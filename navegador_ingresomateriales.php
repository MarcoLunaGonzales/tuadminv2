<?php
require("conexionmysqli.inc");
require('function_formatofecha.php');
require("estilos_almacenes.inc");
?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link href="lib/externos/jquery/jquery-ui/completo/jquery-ui-1.8.9.custom.css" rel="stylesheet" type="text/css"/>
        <link href="lib/css/paneles.css" rel="stylesheet" type="text/css"/>
        <script type="text/javascript" src="lib/externos/jquery/jquery-1.4.4.min.js"></script>
        <script type="text/javascript" src="lib/externos/jquery/jquery-ui/minimo/jquery.ui.core.min.js"></script>
        <script type="text/javascript" src="lib/externos/jquery/jquery-ui/minimo/jquery.ui.widget.min.js"></script>
        <script type="text/javascript" src="lib/externos/jquery/jquery-ui/minimo/jquery.ui.button.min.js"></script>
        <script type="text/javascript" src="lib/externos/jquery/jquery-ui/minimo/jquery.ui.mouse.min.js"></script>
        <script type="text/javascript" src="lib/externos/jquery/jquery-ui/minimo/jquery.ui.draggable.min.js"></script>
        <script type="text/javascript" src="lib/externos/jquery/jquery-ui/minimo/jquery.ui.position.min.js"></script>
        <script type="text/javascript" src="lib/externos/jquery/jquery-ui/minimo/jquery.ui.resizable.min.js"></script>
        <script type="text/javascript" src="lib/externos/jquery/jquery-ui/minimo/jquery.ui.dialog.min.js"></script>
        <script type="text/javascript" src="lib/externos/jquery/jquery-ui/minimo/jquery.ui.datepicker.min.js"></script>
        <script type="text/javascript" src="lib/js/xlibPrototipo-v0.1.js"></script>
        <script type='text/javascript' language='javascript'>
function nuevoAjax()
{	var xmlhttp=false;
	try {
			xmlhttp = new ActiveXObject('Msxml2.XMLHTTP');
	} catch (e) {
	try {
		xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
	} catch (E) {
		xmlhttp = false;
	}
	}
	if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
 	xmlhttp = new XMLHttpRequest();
	}
	return xmlhttp;
}
		
function ShowBuscar(){
	document.getElementById('divRecuadroExt').style.visibility='visible';
	document.getElementById('divProfileData').style.visibility='visible';
	document.getElementById('divProfileDetail').style.visibility='visible';
}

function HiddenBuscar(){
	document.getElementById('divRecuadroExt').style.visibility='hidden';
	document.getElementById('divProfileData').style.visibility='hidden';
	document.getElementById('divProfileDetail').style.visibility='hidden';
}
		
function funOk(codReg,funOkConfirm)
{   $.get("programas/ingresos/frmConfirmarCodigoIngreso.php","codigo="+codReg, function(inf1) {
        dlgAC("#pnldlgAC","Codigo de confirmacion",inf1,function(){
            var cad1=$("input#idtxtcodigo").val();
            var cad2=$("input#idtxtclave").val();
            if(cad1!="" && cad2!="") {
                dlgEsp.setVisible(true);
                $.get("programas/ingresos/validacionCodigoConfirmar.php","codigo="+cad1+"&clave="+cad2, function(inf2) {
                    inf2=xtrim(inf2);
                    dlgEsp.setVisible(false);
                    if(inf2=="" || inf2=="OK") {
                        /**/funOkConfirm();/**/
                    } else {
                        dlgA("#pnldlgA2","Informe","<div class='pnlalertar'>El codigo ingresado es incorrecto.</div>",function(){},function(){});
                    }
                });
            } else {
                dlgA("#pnldlgA3","Informe","<div class='pnlalertar'>Introducir el codigo de confirmacion.</div>",function(){},function(){});
            }
        },function(){});
    });
}

function funModif(codReg,funOkConfirm)
{   $.get("programas/ingresos/frmModificarIngreso.php","codigo="+codReg, function(inf1) {
        dlgAC("#pnldlgAC","Modificar Tipo de Ingreso y Proveedor",inf1,function(){
            var cad1=$("select[id=combotipoingreso]").val();
            var cad2=$("select[id=comboproveedor]").val();
            if(cad1!="" && cad2!="") {
                dlgEsp.setVisible(true);
                $.get("programas/ingresos/guardarModifTipoProveIngreso.php","combotipoingreso="+cad1+"&comboproveedor="+cad2+"&codigo="+codReg, function(inf2) {
                    dlgEsp.setVisible(false);
                    funOkConfirm();
                });
            } else {
                dlgA("#pnldlgA3","Informe","<div class='pnlalertar'>Debe seleccionar datos.</div>",function(){},function(){});
            }
        },function(){});
    });
}


function ajaxBuscarIngresos(f){
	var fechaIniBusqueda, fechaFinBusqueda, notaIngreso, verBusqueda, global_almacen, provBusqueda;
	fechaIniBusqueda=document.getElementById("fechaIniBusqueda").value;
	fechaFinBusqueda=document.getElementById("fechaFinBusqueda").value;
	notaIngreso=document.getElementById("notaIngreso").value;
	global_almacen=document.getElementById("global_almacen").value;
	provBusqueda=document.getElementById("provBusqueda").value;
	var contenedor;
	contenedor = document.getElementById('divCuerpo');
	ajax=nuevoAjax();

	ajax.open("GET", "ajaxNavIngresos.php?fechaIniBusqueda="+fechaIniBusqueda+"&fechaFinBusqueda="+fechaFinBusqueda+"&notaIngreso="+notaIngreso+"&global_almacen="+global_almacen+"&provBusqueda="+provBusqueda,true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor.innerHTML = ajax.responseText;
			HiddenBuscar();
		}
	}
	ajax.send(null)
}



function enviar_nav()
{   location.href='registrar_ingresomateriales.php';
}

function editarIngresoTipoProv(codigoIngreso)
{   funModif(codigoIngreso,function(){
		alert("Se modicaron los Datos!");
		location.href='navegador_ingresomateriales.php';
	});
}

function editar_ingreso(f)
{   var i;
    var j=0;
    var j_cod_registro;
    var fecha_registro;
    for(i=0;i<=f.length-1;i++)
    {   if(f.elements[i].type=='checkbox')
        {   if(f.elements[i].checked==true)
            {   j_cod_registro=f.elements[i].value;
                fecha_registro=f.elements[i-1].value;
                j=j+1;
            }
        }
    }
    if(j>1)
    {   alert('Debe seleccionar solamente un registro para anularlo.');
    }
    else
    {   if(j==0)
        {   alert('Debe seleccionar un registro para anularlo.');
        }
        else
        {      //location.href='editar_ingresomateriales.php?codigo_registro='+j_cod_registro+'&grupo_ingreso=1&valor_inicial=1';
                funOk(j_cod_registro,function(){
                    location.href='editar_ingreso.php?codIngreso='+j_cod_registro+'';
                });
        }
    }
}
function anular_ingreso(f)
{   var i;
    var j=0;
    var j_cod_registro;
    var fecha_registro;
    for(i=0;i<=f.length-1;i++)
    {   if(f.elements[i].type=='checkbox')
        {   if(f.elements[i].checked==true)
            {   j_cod_registro=f.elements[i].value;
                fecha_registro=f.elements[i-1].value;
                j=j+1;
            }
        }
    }
    if(j>1)
    {   alert('Debe seleccionar solamente un registro para anularlo.');
    }
    else
    {   if(j==0)
        {   alert('Debe seleccionar un registro para anularlo.');
        }
        else
        {   //window.open('anular_ingreso.php?codigo_registro='+j_cod_registro+'&grupo_ingreso=2','','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=280,height=150');
                funOk(j_cod_registro,function(){
                    location.href='anular_ingreso.php?codigo_registro='+j_cod_registro+'';
                });
        }
    }
}
        </script>
    </head>
    <body>

<?php
 
echo "<form method='post' action='navegador_ingresomateriales.php'>";
echo "<input type='hidden' name='fecha_sistema' value='$fecha_sistema'>";

$consulta = "
    SELECT i.cod_ingreso_almacen, i.fecha, i.hora_ingreso, ti.nombre_tipoingreso, i.observaciones, i.nota_entrega, i.nro_correlativo, i.ingreso_anulado,
	(select p.nombre_proveedor from proveedores p where p.cod_proveedor=i.cod_proveedor) as proveedor, i.nro_factura_proveedor, 
	(select s.nro_correlativo from salida_almacenes s where s.cod_salida_almacenes=i.cod_salida_almacen), 
	(select a.nombre_almacen from salida_almacenes s, almacenes a where a.cod_almacen=s.cod_almacen and s.cod_salida_almacenes=i.cod_salida_almacen), i.cod_salida_almacen
    FROM ingreso_almacenes i, tipos_ingreso ti
    WHERE i.cod_tipoingreso=ti.cod_tipoingreso
    AND i.cod_almacen='$global_almacen'";
   $consulta = $consulta."ORDER BY i.nro_correlativo DESC limit 0, 50 ";
//echo "MAT:$sql";
$resp = mysqli_query($enlaceCon,$consulta);
echo "<h1>Ingreso de Materiales</h1>";

echo "<table border='1' cellspacing='0' class='textomini'><tr><th>Leyenda:</th><th>Ingresos Anulados</th><td bgcolor='#ff8080' width='10%'></td><th>Ingresos con movimiento</th><td bgcolor='#ffff99' width='10%'></td><th>Ingresos sin movimiento</th><td bgcolor='' width='10%'>&nbsp;</td></tr></table><br>";

echo "<div class='divBotones'><input type='button' value='Registrar Ingreso' name='adicionar' class='boton' onclick='enviar_nav()'>
<input type='button' value='Editar Ingreso' class='boton' onclick='editar_ingreso(this.form)'>
<input type='button' value='Anular Ingreso' name='adicionar' class='boton2' onclick='anular_ingreso(this.form)'>
<td><input type='button' value='Buscar' class='boton' onclick='ShowBuscar()'></div>";

echo "<div id='divCuerpo'>";
echo "<br><center><table class='texto'>";
echo "<tr><th>&nbsp;</th><th>Nro. Ingreso</th><th>Nro.Factura</th><th>Fecha</th><th>Tipo de Ingreso</th>
<th>Proveedor</th>
<th>Observaciones</th><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th></tr>";
while ($dat = mysqli_fetch_array($resp)) {
    $codigo = $dat[0];
    $fecha_ingreso = $dat[1];
    $fecha_ingreso_mostrar = "$fecha_ingreso[8]$fecha_ingreso[9]-$fecha_ingreso[5]$fecha_ingreso[6]-$fecha_ingreso[0]$fecha_ingreso[1]$fecha_ingreso[2]$fecha_ingreso[3]";
    $hora_ingreso = $dat[2];
    $nombre_tipoingreso = $dat[3];
    $obs_ingreso = $dat[4];
    $nota_entrega = $dat[5];
    $nro_correlativo = $dat[6];
    $anulado = $dat[7];
	$proveedor=$dat[8];
	$nroFacturaProveedor=$dat[9];

	$nroSalidaOrigen=$dat[10];
	$almacenOrigen=$dat[11];

	$codSalidaOrigen=$dat[12];

	$txtTraspasoOrigen="";
	if($nroSalidaOrigen!="" || $nroSalidaOrigen!=0){
		$txtTraspasoOrigen="<span class='textomedianoazul'>
		<a href='navegador_detallesalidamateriales.php?codigo_salida=$codSalidaOrigen'  target='_blank'>A. Origen: $almacenOrigen Nro. Doc: $nroSalidaOrigen</a></span>";
	}

	if($nroFacturaProveedor==0){
		$nroFacturaProveedor="-";
	}

    echo "<input type='hidden' name='fecha_ingreso$nro_correlativo' value='$fecha_ingreso_mostrar'>";
    $sql_verifica_movimiento = "select * from salida_almacenes s, salida_detalle_almacenes sd, ingreso_almacenes i
		where s.cod_salida_almacenes=sd.cod_salida_almacen  and sd.cod_ingreso_almacen=i.cod_ingreso_almacen and s.salida_anulada=0 and i.cod_ingreso_almacen='$codigo'";
	//echo $sql_verifica_movimiento;
    $resp_verifica_movimiento = mysqli_query($enlaceCon,$sql_verifica_movimiento);
    $num_filas_movimiento = mysqli_num_rows($resp_verifica_movimiento);
    if ($num_filas_movimiento > 0) {
        $color_fondo = "#ffff99";
        $chkbox = "";
    }
    if ($anulado == 1) {
        $color_fondo = "#ff8080";
        $chkbox = "";
    }
    if ($num_filas_movimiento == 0 and $anulado == 0) {
        $color_fondo = "";
        $chkbox = "<input type='checkbox' name='codigo' value='$codigo'>";
    }
    echo "<tr bgcolor='$color_fondo'><td align='center'>$chkbox</td><td align='center'>$nro_correlativo</td><td align='center'>$nroFacturaProveedor</td>
	<td align='center'>$fecha_ingreso_mostrar $hora_ingreso</td><td>$nombre_tipoingreso</td>
	<td>&nbsp;$proveedor</td>
	<td align='left'>&nbsp;$txtTraspasoOrigen $obs_ingreso</td>
	<td align='center'>
		<a target='_BLANK' href='formatoNotaIngreso.php?codigo_ingreso=$codigo'><img src='imagenes/factura1.jpg' border='0' width='30' heigth='30' title='Imprimir'></a>
	</td>	<td align='center'>
		<a target='_BLANK' href='navegador_detalleingresomateriales.php?codigo_ingreso=$codigo'><img src='imagenes/icon_detail.png' border='0' width='30' heigth='30' title='Ver Detalles del Ingreso'></a>
	</td>
	<td align='center'>
		<a href='#' onclick='javascript:editarIngresoTipoProv($codigo)' > 
			<img src='imagenes/edit.png' border='0' width='30' heigth='30' title='Editar Tipo & Proveedor'>
		</a>
	</td>
		<td align='center'>
		<a  href='costosImportacionIngreso.php?codigo_ingreso=$codigo'>
			<img src='imagenes/imp9.jpg' border='0' width='50' heigth='50' title='Asociar Costos de Importacion'>
		</a>
	</td>
	<td align='center'>
		<a target='_BLANK' href='navegador_detalleingresomateriales2.php?codigo_ingreso=$codigo'>
		<img src='imagenes/documento.png' border='0' width='30' heigth='30' title='Ingreso y Costos de Importacion'></a>
	</td>
	<td align='center'>
		<a target='_BLANK' href='navegadorDetalleIngresoImpUtilidad.php?codigo_ingreso=$codigo'>
		<img src='imagenes/procesar.png' border='0' width='30' heigth='30' title='Configurar Utilidades'></a>
	</td>
	</tr>";
}
echo "</table></center><br>";
echo "</div>";

echo "<div class='divBotones'><input type='button' value='Registrar Ingreso' name='adicionar' class='boton' onclick='enviar_nav()'>
<input type='button' value='Editar Ingreso' class='boton' onclick='editar_ingreso(this.form)'>
<input type='button' value='Anular Ingreso' name='adicionar' class='boton2' onclick='anular_ingreso(this.form)'>
<td><input type='button' value='Buscar' class='boton' onclick='ShowBuscar()'></div>";
echo "</form>";
?>

<div id="divRecuadroExt" style="background-color:#666; position:absolute; width:800px; height: 400px; top:30px; left:150px; visibility: hidden; opacity: .70; -moz-opacity: .70; filter:alpha(opacity=70); -webkit-border-radius: 20px; -moz-border-radius: 20px; z-index:2;">
</div>

<div id="divProfileData" style="background-color:#FFF; width:750px; height:350px; position:absolute; top:50px; left:170px; -webkit-border-radius: 20px; 	-moz-border-radius: 20px; visibility: hidden; z-index:2;">
  	<div id="divProfileDetail" style="visibility:hidden; text-align:center">
		<h2 align='center' class='texto'>Buscar Ingresos</h2>
		<table align='center' class='texto'>
			<tr>
				<td>Fecha Ini</td>
				<td>
				<input type='date' name='fechaIniBusqueda' id="fechaIniBusqueda" class='texto'>
				</td>
			</tr>
			<tr>
				<td>Fecha Fin</td>
				<td>
				<input type='date' name='fechaFinBusqueda' id="fechaFinBusqueda" class='texto'>
				</td>
			</tr>
			<tr>
				<td>Nota de Ingreso</td>
				<td>
				<input type='text' name='notaIngreso' id="notaIngreso" class='texto'>
				</td>
			</tr>			
			<tr>
				<td>Proveedor:</td>
				<td>
					<select name="ProvBusqueda" class="texto" id="provBusqueda">
						<option value="0">Todos</option>
					<?php
						$sqlProv="select cod_proveedor, nombre_proveedor from proveedores order by 2";
						$respProv=mysqli_query($enlaceCon,$sqlProv);
						while($datProv=mysqli_fetch_array($respProv)){
							$codProvBus=$datProv[0];
							$nombreProvBus=$datProv[1];
					?>
							<option value="<?php echo $codProvBus;?>"><?php echo $nombreProvBus;?></option>
					<?php
						}
					?>
					</select>
				
				</td>
			</tr>			
		</table>	
		<center><br>
			<input type='button' value='Buscar' class='boton' onClick="ajaxBuscarIngresos(this.form)">
			<input type='button' value='Cancelar' class='boton2' onClick="HiddenBuscar();">
			
		</center>
	</div>
</div>

        <script type='text/javascript' language='javascript'>
        </script>
        <div id="pnldlgfrm"></div>
        <div id="pnldlgSN"></div>
        <div id="pnldlgAC"></div>
        <div id="pnldlgA1"></div>
        <div id="pnldlgA2"></div>
        <div id="pnldlgA3"></div>
        <div id="pnldlgArespSvr"></div>
        <div id="pnldlggeneral"></div>
        <div id="pnldlgenespera"></div>
    </body>
</html>
