<html>
    <head>
        <title>Busqueda</title>
        <script type="text/javascript" src="lib/externos/jquery/jquery-1.4.4.min.js"></script>
        <script type="text/javascript" src="dlcalendar.js"></script>
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
	contenedor = document.getElementById('divListaMateriales');
	ajax=nuevoAjax();
	ajax.open("GET", "ajaxListaMateriales.php?codTipo="+codTipo+"&nombreItem="+nombreItem,true);
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
	document.getElementById('itemNombreMaterial').focus();
}
function setMateriales(f, cod, nombreMat){
	var numRegistro=f.materialActivo.value;
	
	document.getElementById('material'+numRegistro).value=cod;
	document.getElementById('cod_material'+numRegistro).value=nombreMat;
	
	document.getElementById('divRecuadroExt').style.visibility='hidden';
	document.getElementById('divProfileData').style.visibility='hidden';
	document.getElementById('divProfileDetail').style.visibility='hidden';
	
	document.getElementById("cantidad_unitaria"+numRegistro).focus();
	
}
		
function precioNeto(fila){

	var precioCompra=document.getElementById('precio'+fila).value;
	var cantidadUnit=document.getElementById('cantidad_unitaria'+fila).value;
		
	var precioTotal=parseFloat(precioCompra)*parseFloat(cantidadUnit);

	if(precioTotal=="NaN"){
		precioTotal.value=0;
	}
	document.getElementById('neto'+fila).value=precioTotal;
	
	totalesMonto();
}

function totalesMonto(){
	var cantidadTotal=0;
	var precioTotal=0;
	var montoTotal=0;
    for(var ii=1;ii<=num;ii++){
	 	var cant=document.getElementById("cantidad_unitaria"+ii).value;
		var precio=document.getElementById("precio"+ii).value;
		montoTotal=montoTotal+(cant*precio);
	}
	montoTotal=Math.round(montoTotal*100)/100;
	
    document.getElementById("totalCompra").value=montoTotal;
	var descuentoTotal=document.getElementById("descuentoTotal").value;
	var totalSD=montoTotal-(montoTotal*(descuentoTotal/100));
	document.getElementById("totalCompraSD").value=totalSD;
	
}
function enviar_form(f)
{   f.submit();
}
function fun13(cadIdOrg,cadIdDes)
{   var num=document.getElementById(cadIdOrg).value;
    num=(100-13)*num/100;
    document.getElementById(cadIdDes).value=num;
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
		    }
	    }		
		ajax.send(null);
	}	
		
	function menos(numero) {
		 num=parseInt(num)-1;
		 fi = document.getElementById('fiel');
  		 fi.removeChild(document.getElementById('div'+numero));
			
 		 calcularTotal();
		 
	}

function validar(f)
{   var cantidadItems=num;
	f.cantidad_material.value=num;
	//alert(num);
	if(cantidadItems>0){
		var nroFactura=document.getElementById("nro_factura").value;
		var item="";
		var cantidad="";
		var precioBruto="";
		var precioNeto="";

		if(nroFactura==""){
			alert("La Factura no puede ir vacia."); return(false);
		}
		
		for(var i=1; i<=cantidadItems; i++){
			item=parseFloat(document.getElementById("material"+i).value);
			cantidad=parseFloat(document.getElementById("cantidad_unitaria"+i).value);
			precioBruto=parseFloat(document.getElementById("precio"+i).value);
			precioNeto=parseFloat(document.getElementById("neto"+i).value);
			
			if(item==0){
				alert("Debe escoger un item en la fila "+i);
				return(false);
			}
			if(cantidad==0){
				alert("La cantidad no puede ser 0 ni vacia. Fila "+i);
				return(false);
			}

			totales();
			return(true);
		}		
	}else{
		alert("La OC debe tener al menos 1 item.");
		return(false);
	}
	
}
function totales(){
	var subtotal=0;
    for(var ii=1;ii<=num;ii++){
	 	var monto=document.getElementById("neto"+ii).value;
		subtotal=subtotal+parseFloat(monto);
    }
	subtotal=Math.round(subtotal*100)/100;
	
    document.getElementById("totalOC").value=subtotal;
	//alert(subtotal);
}

	</script>
<?php

require("conexion.inc");

require("estilos_almacenes.inc");

if($fecha=="")
{   $fecha=date("d/m/Y");
}

echo "<form action='guardaOC.php' method='post' name='form1'>";
echo "<table border='0' class='textotit' align='center'><tr><th>Registrar Orden de Compra</th></tr></table><br>";
echo "<table border='0' class='texto' cellspacing='0' align='center' width='90%' style='border:#ccc 1px solid;'>";
echo "<tr><th>Numero de O.C.</th><th>Fecha O.C.</th><th>Proveedor</th><th>Nro. Factura</th><th>Tipo de Pago</th></tr>";
echo "<tr>";
$sql="select nro_orden from orden_compra order by cod_orden desc";
$resp=mysql_query($sql);
$dat=mysql_fetch_array($resp);
$num_filas=mysql_num_rows($resp);
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

$sql1="select cod_proveedor, nombre_proveedor from proveedores order by 2";
$resp1=mysql_query($sql1);
echo "<td align='center'><select name='proveedor' id='proveedor' class='texto'>";
while($dat1=mysql_fetch_array($resp1))
{   $codigo=$dat1[0];
    $nombre=$dat1[1];
    echo "<option value='$codigo'>$nombre</option>";
}
echo "</select></td>";

echo "<td align='center'><input type='text' class='texto' name='nro_factura' id='nro_factura' size='10' value='0'></td>";

$sql1="select cod_tipopago, nombre_tipopago from tipos_pago order by cod_tipopago";
$resp1=mysql_query($sql1);
echo "<td align='center'><select name='tipo_pago' id='tipo_pago' class='texto'>";
while($dat1=mysql_fetch_array($resp1))
{   $cod_tipopago=$dat1[0];
    $nombre_tipopago=$dat1[1];
    echo "<option value='$cod_tipopago'>$nombre_tipopago</option>";
}
echo "</select></td>";

echo "<tr><th colspan='4'>Observaciones</th><th>Tipo Factura</th></tr>";
echo "<tr><td colspan='4' align='center'><input type='text' class='texto' name='observaciones' value='$observaciones' size='100'></td>
	<td><select name='tipoFactura'>
		<option value='0'>Factura Propia</option>
		<option value='1'>Terceros</option>
	</select></td>
</tr>";
echo "</table><br>";


?>
		<fieldset id="fiel" style="width:98%;border:0;" >
			<table align="center"class="text" cellSpacing="1" cellPadding="2" width="100%" border="0" id="data0" style="border:#ccc 1px solid;">
				<tr>
					<td align="center" colspan="6">
						<input class="boton" type="button" value="Nuevo Item (+)" onclick="mas(this)" accesskey="N"/>
					</td>
				</tr>
				<tr>
					<td align="center" colspan="6">
					<div style="width:100%;" align="center"><b>DETALLE</b></div>
					</td>				
				</tr>				
				<tr class="titulo_tabla" align="center">
					<td width="40%" align="center">Producto</td>
					<td width="10%" align="center">Cantidad</td>
					<td width="10%" align="center">Lote</td>
					<td width="10%" align="center">Vencimiento</td>
					<td width="10%" align="center">Precio </td>
					<td width="10%" align="center">Precio Neto</td>
					<td width="10%" align="center">&nbsp;</td>
				</tr>
				

			</table>
		</fieldset>
		
		<table align="center"class="text" cellSpacing="1" cellPadding="2" width="100%" border="0" id="data0" style="border:#ccc 1px solid;">
			<tr>
				<td align='right'>Total Compra</td><td align='right'><input type='text' name='totalCompra' id='totalCompra' value='0' size='3'></td>
			</tr>
			<tr>
				<td align='right'>Descuento</td><td align='right'><input type='text' name='descuentoTotal' id='descuentoTotal' value='0' size='3' onKeyUp='totalesMonto();' ></td>
			</tr>
			<tr>
				<td align='right'>Total</td><td align='right'><input type='text' name='totalCompraSD' id='totalCompraSD' value='0' size='3'></td>
			</tr>
		</table>


<?php

echo "<div class='divBotones'>
<input type='submit' class='boton' value='Guardar' onClick='return validar(this.form);'>
<input type='button' class='boton2' value='Cancelar' onClick='location.href=\"navegador_ordenCompra.php\"'>
</div>";
echo "</div>";

?>



<div id="divRecuadroExt" style="background-color:#666; position:absolute; width:800px; height: 400px; top:30px; left:150px; visibility: hidden; opacity: .70; -moz-opacity: .70; filter:alpha(opacity=70); -webkit-border-radius: 20px; -moz-border-radius: 20px; z-index:2;">
</div>

<div id="divProfileData" style="background-color:#FFF; width:750px; height:350px; position:absolute; top:50px; left:170px; -webkit-border-radius: 20px; 	-moz-border-radius: 20px; visibility: hidden; z-index:2;">
  	<div id="divProfileDetail" style="visibility:hidden; text-align:center">
		<table align='center' class="texto">
			<tr><th>Linea</th><th>Material</th><th>&nbsp;</th></tr>
			<tr>
			<td><select name='itemTipoMaterial' id="itemTipoMaterial">
			<?php
			$sqlTipo="select pl.cod_linea_proveedor, CONCAT(p.nombre_proveedor,' - ',pl.nombre_linea_proveedor) from proveedores p, proveedores_lineas pl 
			where p.cod_proveedor=pl.cod_proveedor and pl.estado=1 order by 2;";
			$respTipo=mysql_query($sqlTipo);
			echo "<option value='0'>--</option>";
			while($datTipo=mysql_fetch_array($respTipo)){
				$codTipoMat=$datTipo[0];
				$nombreTipoMat=$datTipo[1];
				echo "<option value=$codTipoMat>$nombreTipoMat</option>";
			}
			?>
			</select>
			</td>
			<td>
				<input type='text' name='itemNombreMaterial' id="itemNombreMaterial">
			</td>
			<td>
				<input type='button' value='Buscar' onClick="listaMateriales(this.form)">
			</td>
			</tr>
			
		</table>
		<div id="divListaMateriales">
		</div>
	
	</div>
</div>
<input type='hidden' name='materialActivo' value="0">
<input type='hidden' name='cantidad_material' value="0">
<input type='hidden' name='totalOC' id="totalOC" value="0">



</form>
</body>