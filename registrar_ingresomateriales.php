<?php
require("conexion.inc");
require("estilos.inc");
?>

<html>
    <head>
        <title>Busqueda</title>
        <script type="text/javascript" src="lib/externos/jquery/jquery-1.4.4.min.js"></script>
        <script type="text/javascript" src="dlcalendar.js"></script>
<script>
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
function ajaxNroSalida(){
	var contenedor;
	var nroSalida = parseInt(document.getElementById('nro_salida').value);
	if(isNaN(nroSalida)){
		nroSalida=0;
	}
	contenedor = document.getElementById('divNroSalida');
	ajax=nuevoAjax();

	ajax.open("GET", "ajaxNroSalida.php?nroSalida="+nroSalida,true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor.innerHTML = ajax.responseText;
		}
	}
	ajax.send(null)
}
function listaMateriales(f){
	var contenedor;
	var codTipo=f.itemTipoMaterial.value;
	var nombreItem=f.itemNombreMaterial.value;
	var codInterno=f.codigoInterno.value;

	contenedor = document.getElementById('divListaMateriales');
	ajax=nuevoAjax();
	ajax.open("GET", "ajaxListaMaterialesIngreso.php?codTipo="+codTipo+"&codInterno="+codInterno+"&nombreItem="+nombreItem,true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor.innerHTML = ajax.responseText;
		}
	}
	ajax.send(null)
}

function buscarMaterial(f, numMaterial){
	f.materialActivo.value=numMaterial;
	document.getElementById('divRecuadroExt').style.visibility='visible';
	document.getElementById('divProfileData').style.visibility='visible';
	document.getElementById('divProfileDetail').style.visibility='visible';
	document.getElementById('divboton').style.visibility='visible';
	document.getElementById('divListaMateriales').innerHTML='';
	document.getElementById('itemNombreMaterial').value='';	
	document.getElementById('itemNombreMaterial').focus();		
}
function Hidden(){
	document.getElementById('divRecuadroExt').style.visibility='hidden';
	document.getElementById('divProfileData').style.visibility='hidden';
	document.getElementById('divProfileDetail').style.visibility='hidden';
	document.getElementById('divboton').style.visibility='hidden';

}
function setMateriales(f, cod, nombreMat, cantidadPresentacion,costoItem){
	var numRegistro=f.materialActivo.value;
	
	document.getElementById('material'+numRegistro).value=cod;
	document.getElementById('cod_material'+numRegistro).innerHTML=nombreMat;
	document.getElementById('ultimoCosto'+numRegistro).value=costoItem;
	document.getElementById('divUltimoCosto'+numRegistro).innerHTML="["+costoItem+"]";
	
	document.getElementById('divRecuadroExt').style.visibility='hidden';
	document.getElementById('divProfileData').style.visibility='hidden';
	document.getElementById('divProfileDetail').style.visibility='hidden';
	document.getElementById('divboton').style.visibility='hidden';
	

	document.getElementById("cantidad_unitaria"+numRegistro).focus();
	
}
		
function cambiaCosto(f, fila){
	var cantidad=document.getElementById('cantidad_unitaria'+fila).value;
	var precioFila=document.getElementById('precio'+fila).value;
	var ultimoCosto=document.getElementById('ultimoCosto'+fila).value;
	console.log(cantidad+" "+ultimoCosto);
	var calculoCosto=parseFloat(cantidad)*parseFloat(ultimoCosto);
	var calculoPrecioTotal=parseFloat(cantidad)*parseFloat(precioFila);	
	if(calculoCosto=="NaN"){
		calculoCosto.value=0;
	}
	document.getElementById('divUltimoCosto'+fila).innerHTML="["+ultimoCosto+"]["+calculoCosto+"]";
	document.getElementById('divPrecioTotal'+fila).innerHTML=calculoPrecioTotal;
	
}
function enviar_form(f)
{   f.submit();
}

	num=0;

	function mas(obj) {

			num++;
			fi = document.getElementById('fiel');
			contenedor = document.createElement('div');
			contenedor.id = 'div'+num;  
			fi.type="style";
			fi.appendChild(contenedor);
			var div_material;
			div_material=document.getElementById("div"+num);			
			ajax=nuevoAjax();
			ajax.open("GET","ajaxMaterial.php?codigo="+num,true);
			ajax.onreadystatechange=function(){
				if (ajax.readyState==4) {
					div_material.innerHTML=ajax.responseText;
					buscarMaterial(form1, num);
				}
			}		
			ajax.send(null);
		
	}	
		
	function menos(numero) {
		if(numero==num){
			num=parseInt(num)-1;
		}
		//num=parseInt(num)-1;
		fi = document.getElementById('fiel');
		fi.removeChild(document.getElementById('div'+numero));		
	}

function pressEnter(e, f){
	tecla = (document.all) ? e.keyCode : e.which;
	if (tecla==13){
		document.getElementById('itemNombreMaterial').focus();
		listaMateriales(f);
		return false;
	}
}
	
function validar(f){   
	f.cantidad_material.value=num;
	var cantidadItems=num;
	
	if(cantidadItems>0){
		var item="";
		var cantidad="";
		var precioBruto="";
		var precioNeto="";
		
		for(var i=1; i<=cantidadItems; i++){
			item=parseFloat(document.getElementById("material"+i).value);			
			if(item==0){
				alert("Debe escoger un item en la fila "+i);
				return(false);
			}
			return(true);
		}
		
	}else{
		alert("El ingreso debe tener al menos 1 item.");
		return(false);
	}
}


	</script>
<?php


if($fecha=="")
{   $fecha=date("d/m/Y");
}

echo "<form action='guarda_ingresomateriales.php' method='post' name='form1'>";
echo "<table border='0' class='textotit' align='center'><tr><th>Registrar Ingreso de Materiales</th></tr></table><br>";
echo "<table border='0' class='texto' cellspacing='0' align='center' width='90%' style='border:#ccc 1px solid;'>";
echo "<tr><th>Numero de Ingreso</th><th>Fecha</th><th>Tipo de Ingreso</th><th>Factura</th></tr>";
echo "<tr>";
$sql="select nro_correlativo from ingreso_almacenes where cod_almacen='$global_almacen' order by cod_ingreso_almacen desc";
$resp=mysqli_query($enlaceCon,$sql);
$dat=mysqli_fetch_array($resp);
$num_filas=mysqli_num_rows($resp);
if($num_filas==0)
{   $nro_correlativo=1;
}
else
{   $nro_correlativo=$dat[0];
    $nro_correlativo++;
}
echo "<td align='center'>$nro_correlativo</td>";
echo "<td align='center'>";

echo "<input type='text' disabled='true' class='texto' value='$fecha' id='fecha' size='10' name='fecha'>";
echo "<img id='imagenFecha' src='imagenes/fecha.bmp'>";
echo "</td>";

$sql1="select cod_tipoingreso, nombre_tipoingreso from tipos_ingreso order by nombre_tipoingreso";
$resp1=mysqli_query($enlaceCon,$sql1);
echo "<td align='center'><select name='tipo_ingreso' id='tipo_ingreso' class='texto'>";
while($dat1=mysqli_fetch_array($resp1))
{   $cod_tipoingreso=$dat1[0];
    $nombre_tipoingreso=$dat1[1];
    echo "<option value='$cod_tipoingreso'>$nombre_tipoingreso</option>";
}
echo "</select></td>";
echo "<td align='center'><input type='number' class='texto' name='nro_factura' value='' id='nro_factura' required></td></tr>";

echo "<tr><th>Proveedor</th>";
echo "<th colspan='3'>Observaciones</th></tr>";
$sql1="select cod_proveedor, nombre_proveedor from proveedores order by 2";
$resp1=mysqli_query($enlaceCon,$sql1);
echo "<tr><td align='center'><select name='proveedor' id='proveedor' class='texto'>";
while($dat1=mysqli_fetch_array($resp1))
{   $codigo=$dat1[0];
    $nombre=$dat1[1];
    echo "<option value='$codigo'>$nombre</option>";
}
echo "</select></td>";
echo "<td colspan='4' align='center'><input type='text' class='texto' name='observaciones' value='$observaciones' size='100'></td></tr>";
echo "</table><br>";
?>
		<fieldset id="fiel" style="width:98%;border:0;" >
			<table align="center"class="text" cellSpacing="1" cellPadding="2" width="100%" border="0" id="data0" style="border:#ccc 1px solid;">
				<tr>
					<td align="center" colspan="6">
						<input class="boton" type="button" value="Nuevo Item (+)" onclick="mas(this)" accesskey="A"/>
					</td>
				</tr>
				<tr>
					<td align="center" colspan="6">
					<div style="width:100%;" align="center"><b>DETALLE</b></div>
					</td>				
				</tr>				
				<tr class="titulo_tabla" align="center">
					<td width="5%" align="center">&nbsp;</td>
					<td width="35%" align="center">Producto</td>
					<td width="10%" align="center">Cantidad</td>
					<td width="10%" align="center">Lote</td>
					<td width="10%" align="center">Precio[u]</td>
					<td width="10%" align="center">PrecioTotal</td>
					<td width="10%" align="center">&nbsp;</td>
				</tr>
			</table>
		</fieldset>


<?php


echo "<div class='divBotones'>
<input type='submit' class='boton' value='Guardar' onClick='return validar(this.form);'></center>
<input type='button' class='boton2' value='Cancelar' onClick='location.href=\"navegador_ingresomateriales.php\"'></center>
</div>";

echo "</div>";
echo "<script type='text/javascript' language='javascript'  src='dlcalendar.js'></script>";

?>



<div id="divRecuadroExt" style="background-color:#666; position:absolute; width:1100px; height: 500px; top:30px; left:150px; visibility: hidden; opacity: .70; -moz-opacity: .70; filter:alpha(opacity=70); -webkit-border-radius: 20px; -moz-border-radius: 20px; z-index:2;">
</div>

<div id="divboton" style="position: absolute; top:20px; left:1220px;visibility:hidden; text-align:center; z-index:3">
	<a href="javascript:Hidden();"><img src="imagenes/cerrar4.png" height="45px" width="45px"></a>
</div>

<div id="divProfileData" style="background-color:#FFF; width:1050px; height:450px; position:absolute; top:50px; left:170px; -webkit-border-radius: 20px; 	-moz-border-radius: 20px; visibility: hidden; z-index:2;">
  	<div id="divProfileDetail" style="visibility:hidden; text-align:center; height:445px; overflow-y: scroll;">
		<table align='center' class="texto">
			<tr><th>Grupo</th><th>CodInterno</th><th>Material</th><th>&nbsp;</th></tr>
			<tr>
			<td><select name='itemTipoMaterial' id="itemTipoMaterial" class="textogranderojo" style="width:300px">
			<?php
			$sqlTipo="select g.cod_grupo, g.nombre_grupo from grupos g
			where g.estado=1 order by 2;";
			$respTipo=mysqli_query($enlaceCon,$sqlTipo);
			echo "<option value='0'>--</option>";
			while($datTipo=mysqli_fetch_array($respTipo)){
				$codTipoMat=$datTipo[0];
				$nombreTipoMat=$datTipo[1];
				echo "<option value=$codTipoMat>$nombreTipoMat</option>";
			}
			?>
			</select>
			</td>
			<td>
				<input type='text' name='codigoInterno' id='codigoInterno' class="textomedianorojo" onkeypress="return pressEnter(event, this.form);">
			</td>
			<td>
				<input type='text' name='itemNombreMaterial' id="itemNombreMaterial" class="textogranderojo"  onkeypress="return pressEnter(event, this.form);">
			</td>
			<td>
				<input type='button' class='boton' value='Buscar' onClick="listaMateriales(this.form)">
			</td>
			</tr>
			
		</table>
		<div id="divListaMateriales">
		</div>
	
	</div>
</div>
<input type='hidden' name='materialActivo' value="0">
<input type='hidden' name='cantidad_material' value="0">

</form>
</body>