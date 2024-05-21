<!DOCTYPE html>
<?php
echo "</head><body onLoad='funcionInicio();'>";
require("conexion.inc");
require("estilos.inc");
require("funciones.php");
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

// Configuración | Tipo de Moneda => 1:Bs 2:$us
$tipoMonedaConfig 	= obtenerValorConfiguracion(10);
$verificaConversion = obtieneValorConversionActual($tipoMonedaConfig);
// Configuración | Valida Pago de Credido Pendiente |(1) Contado y Credito | (2) Credito
$validacionCreditoConfig = obtenerValorConfiguracion(11);
?>
<html>
    <head>
        <title>Registrar Venta</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <script type="text/javascript" src="lib/js/xlibPrototipoSimple-v0.1.js"></script>
		<script type="text/javascript" src="functionsGeneral.js"></script>
<script type='text/javascript' language='javascript'>

var validacionCreditoConfig = <?=empty($validacionCreditoConfig) ? 1 : $validacionCreditoConfig ?>;

function funcionInicio(){
	//document.getElementById('nitCliente').focus();
}


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

let cod_sucursal_actual = <?=$_COOKIE['global_almacen']?>;
function listaMateriales(f){
	var contenedor;
	var codMarca=f.itemMarca.value;
	var nombreItem=f.itemNombreMaterial.value;
	var codInterno=f.codigoInterno.value;
	contenedor = document.getElementById('divListaMateriales');
	let stock = $('#solo_stock').is(':checked') ? 1 : 0;

	var arrayItemsUtilizados=new Array();	
	var i=0;
	for(var j=1; j<=num; j++){
		if(document.getElementById('materiales'+j)!=null){
			console.log("codmaterial: "+document.getElementById('materiales'+j).value);
			if(document.getElementById('cod_sucursales'+j).value == cod_sucursal_actual){
				arrayItemsUtilizados[i]=document.getElementById('materiales'+j).value;
				i++;
			}
		}
	}
	console.log("arrayItemsUtilizados"+arrayItemsUtilizados);
	
	ajax=nuevoAjax();
	ajax.open("GET", "ajaxListaMateriales.php?codMarca="+codMarca+"&nombreItem="+nombreItem+"&stock="+stock+"&codInterno="+codInterno+"&arrayItemsUtilizados="+arrayItemsUtilizados,true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.send(null)
}

function ajaxTipoDoc(f){
	var contenedor;
	contenedor=document.getElementById("divTipoDoc");
	ajax=nuevoAjax();
	var codTipoSalida=(f.tipoSalida.value);
	ajax.open("GET", "ajaxTipoDoc.php?codTipoSalida="+codTipoSalida,true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.send(null);
}


function ajaxNroDoc(f){
	var contenedor;
	contenedor=document.getElementById("divNroDoc");
	ajax=nuevoAjax();
	var codTipoDoc=(f.tipoDoc.value);
	ajax.open("GET", "ajaxNroDoc.php?codTipoDoc="+codTipoDoc,true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.send(null);
}

function actStock(indice){
	var contenedor;
	contenedor=document.getElementById("idstock"+indice);
	var codmat=document.getElementById("materiales"+indice).value;
    var codalm=document.getElementById("global_almacen").value;
	ajax=nuevoAjax();
	ajax.open("GET", "ajaxStockSalidaMateriales.php?codmat="+codmat+"&codalm="+codalm+"&indice="+indice,true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor.innerHTML = ajax.responseText;
			ajaxPrecioItem(indice);
		}
	}
	totales();
	ajax.send(null);
}

function ajaxPrecioItem(indice){
	var contenedor;
	contenedor=document.getElementById("idprecio"+indice);
	var codmat=document.getElementById("materiales"+indice).value;
	// var tipoPrecio=document.getElementById("tipoPrecio"+indice).value;
	var tipoPrecio=1;
	var cantidadUnitaria=document.getElementById("cantidad_unitaria"+indice).value;
	ajax=nuevoAjax();
	ajax.open("GET", "ajaxPrecioItem.php?codmat="+codmat+"&indice="+indice+"&tipoPrecio="+tipoPrecio,true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			var respuesta=ajax.responseText.split("#####");
			contenedor.innerHTML = respuesta[0];
            document.getElementById("descuentoProducto"+indice).value=(respuesta[1]*parseFloat(cantidadUnitaria)); 
			calculaMontoMaterial(indice);
		}
	}
	ajax.send(null);
}

function ajaxRazonSocial(f){
	var contenedor;
	contenedor=document.getElementById("divRazonSocial");
	var nitCliente=document.getElementById("nitCliente").value;
	ajax=nuevoAjax();
	ajax.open("GET", "ajaxRazonSocial.php?nitCliente="+nitCliente,true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			console.log(ajax.responseText)
			contenedor.innerHTML = ajax.responseText;
			document.getElementById('razonSocial').focus();
			ajaxClienteBuscar();
		}
	}
	ajax.send(null);
}

/**
 * Buscar Cliente con Change
 */
 function ajaxClienteBuscar(f){
	var contenedor;
	contenedor=document.getElementById("divCliente");
	var nitCliente=document.getElementById("nitCliente").value;
	ajax=nuevoAjax();
	ajax.open("GET", "ajaxClienteLista.php?nitCliente="+nitCliente,true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			var datos_resp=ajax.responseText.split("####");
			//alert(datos_resp[1])
			//$("#cliente").val(datos_resp[1]);			
			$("#cliente").html(datos_resp[1]);				
			ajaxRazonSocialCliente(document.getElementById('form1'));
			$("#cliente").selectpicker('refresh');

			// Pago de Credito Pendiente, valida el tipo de venta de: | (1) Contado y Credito | (2) Credito
			if(validacionCreditoConfig == 1){
				verificarDeudaCliente($('#cliente').val());
			}
			
			// Verificación de Deudas del Cliente
			if($('#tipoVenta').val() == 4){
				verificarDeudaCliente($('#cliente').val());
			}
		}
	}
	ajax.send(null);
}

function calculaMontoMaterial(indice){

	var cantidadUnitaria=document.getElementById("cantidad_unitaria"+indice).value;
	var precioUnitario=document.getElementById("precio_unitario"+indice).value;
	var descuentoUnitario=document.getElementById("descuentoProducto"+indice).value;
	
	var montoUnitario=(parseFloat(cantidadUnitaria)*parseFloat(precioUnitario)) - (parseFloat(descuentoUnitario));
	montoUnitario=Math.round(montoUnitario*100)/100
		
	document.getElementById("montoMaterial"+indice).value=montoUnitario;
	
	totales();
}

function totales(){
	var subtotal=0;
    for(var ii=1;ii<=num;ii++){
	 	if(document.getElementById('materiales'+ii)!=null){
			var monto=document.getElementById("montoMaterial"+ii).value;
			subtotal=subtotal+parseFloat(monto);
		}
    }
    var subtotalPrecio=0;
    for(var ii=1;ii<=num;ii++){
	 	if(document.getElementById('materiales'+ii)!=null){
			var precio=document.getElementById("precio_unitario"+ii).value;
			var cantidad=document.getElementById("cantidad_unitaria"+ii).value;
			subtotalPrecio=subtotalPrecio+parseFloat(precio*cantidad);
		}
    }
    //document.getElementById("total_precio_sin_descuento").innerHTML=subtotalPrecio;

    subtotalPrecio=Math.round(subtotalPrecio*100)/100;

	subtotal=Math.round(subtotal*100)/100;
	
	var tipo_cambio=$("#tipo_cambio_dolar").val();

    document.getElementById("totalVenta").value=subtotal;
	document.getElementById("totalFinal").value=subtotal;

	document.getElementById("totalVentaUSD").value=Math.round((subtotal/tipo_cambio)*100)/100;
	document.getElementById("totalFinalUSD").value=Math.round((subtotal/tipo_cambio)*100)/100;

    //setear descuento o aplicar la suma total final con el descuento
	document.getElementById("descuentoVenta").value=0;
	document.getElementById("descuentoVentaUSD").value=0;
	aplicarCambioEfectivo();
	minimoEfectivo();
}

function aplicarDescuento(f){
	var tipo_cambio=$("#tipo_cambio_dolar").val();
	var total=document.getElementById("totalVenta").value;
	var descuento=document.getElementById("descuentoVenta").value;
	
	descuento=Math.round(descuento*100)/100;
	
	document.getElementById("totalFinal").value=Math.round((parseFloat(total)-parseFloat(descuento))*100)/100;
	var descuentoUSD=(parseFloat(total)-parseFloat(descuento))/tipo_cambio;
	document.getElementById("descuentoVentaUSD").value=Math.round((descuento/tipo_cambio)*100)/100;
	document.getElementById("totalFinalUSD").value=Math.round((descuentoUSD)*100)/100;

	document.getElementById("descuentoVentaPorcentaje").value=Math.round((parseFloat(descuento)*100)/(parseFloat(total)));
	document.getElementById("descuentoVentaUSDPorcentaje").value=Math.round((parseFloat(descuento)*100)/(parseFloat(total)));
	aplicarCambioEfectivo();
	minimoEfectivo();
	//totales();
	
}
function aplicarDescuentoUSD(f){
	var tipo_cambio=$("#tipo_cambio_dolar").val();
	var total=document.getElementById("totalVentaUSD").value;
	var descuento=document.getElementById("descuentoVentaUSD").value;
	
	descuento=Math.round(descuento*100)/100;
	
	document.getElementById("totalFinalUSD").value=Math.round((parseFloat(total)-parseFloat(descuento))*100)/100;
	var descuentoBOB=(parseFloat(total)-parseFloat(descuento))*tipo_cambio;
	document.getElementById("descuentoVenta").value=Math.round((descuento*tipo_cambio)*100)/100;
	document.getElementById("totalFinal").value=Math.round((descuentoBOB)*100)/100;
	document.getElementById("descuentoVentaPorcentaje").value=Math.round((parseFloat(descuento)*100)/(parseFloat(total)));
	document.getElementById("descuentoVentaUSDPorcentaje").value=Math.round((parseFloat(descuento)*100)/(parseFloat(total)));
	aplicarCambioEfectivoUSD();
	minimoEfectivo();
	//totales();
}

function aplicarDescuentoPorcentaje(f){
	var tipo_cambio=$("#tipo_cambio_dolar").val();
	var total=document.getElementById("totalVenta").value;
    
    var descuentoPorcentaje=document.getElementById("descuentoVentaPorcentaje").value;
    document.getElementById("descuentoVentaUSDPorcentaje").value=descuentoPorcentaje;

	var descuento=document.getElementById("descuentoVenta").value;
	
	descuento=Math.round(parseFloat(descuentoPorcentaje)*parseFloat(total)/100);
	
	document.getElementById("totalFinal").value=Math.round((parseFloat(total)-parseFloat(descuento))*100)/100;
	var descuentoUSD=(parseFloat(total)-parseFloat(descuento))/tipo_cambio;
	document.getElementById("descuentoVenta").value=Math.round((descuento)*100)/100;
	document.getElementById("descuentoVentaUSD").value=Math.round((descuento/tipo_cambio)*100)/100;
	document.getElementById("totalFinalUSD").value=Math.round((descuentoUSD)*100)/100;
	
	aplicarCambioEfectivo();
	minimoEfectivo();
	//totales();
}
function aplicarDescuentoUSDPorcentaje(f){
	var tipo_cambio=$("#tipo_cambio_dolar").val();
	var total=document.getElementById("totalVenta").value;
    
    var descuentoPorcentaje=document.getElementById("descuentoVentaUSDPorcentaje").value;
    document.getElementById("descuentoVentaPorcentaje").value=descuentoPorcentaje;

	var descuento=document.getElementById("descuentoVenta").value;
	
	descuento=Math.round(parseFloat(descuentoPorcentaje)*parseFloat(total))/100;
	
	document.getElementById("totalFinal").value=Math.round((parseFloat(total)-parseFloat(descuento))*100)/100;
	var descuentoUSD=(parseFloat(total)-parseFloat(descuento))/tipo_cambio;
	document.getElementById("descuentoVenta").value=Math.round((descuento)*100)/100;
	document.getElementById("descuentoVentaUSD").value=Math.round((descuento/tipo_cambio)*100)/100;
	document.getElementById("totalFinalUSD").value=Math.round((descuentoUSD)*100)/100;
	
	aplicarCambioEfectivo();
	minimoEfectivo();
	//totales();
}
function minimoEfectivo(){
  //obtener el minimo a pagar
	var minimoEfectivo=$("#totalFinal").val();
	var minimoEfectivoUSD=$("#totalFinalUSD").val();
	//asignar el minimo al atributo min
	//$("#efectivoRecibidoUnido").attr("min",minimoEfectivo);
	//$("#efectivoRecibidoUnidoUSD").attr("min",minimoEfectivoUSD);		
}
function aplicarCambioEfectivo(f){
	var tipo_cambio=$("#tipo_cambio_dolar").val();
	var recibido=document.getElementById("efectivoRecibido").value;
	var total=document.getElementById("totalFinal").value;

	var cambio=Math.round((parseFloat(recibido)-parseFloat(total))*100)/100;
	document.getElementById("cambioEfectivo").value=parseFloat(cambio);
	document.getElementById("efectivoRecibidoUSD").value=Math.round((recibido/tipo_cambio)*100)/100;
	document.getElementById("cambioEfectivoUSD").value=Math.round((cambio/tipo_cambio)*100)/100;	
	minimoEfectivo();
}
function aplicarCambioEfectivoUSD(f){
	var tipo_cambio=$("#tipo_cambio_dolar").val();
	var recibido=document.getElementById("efectivoRecibidoUSD").value;
	var total=document.getElementById("totalFinalUSD").value;

	var cambio=Math.round((parseFloat(recibido)-parseFloat(total))*100)/100;
	document.getElementById("cambioEfectivoUSD").value=parseFloat(cambio);
	document.getElementById("efectivoRecibido").value=Math.round((recibido*tipo_cambio)*100)/100;
	document.getElementById("cambioEfectivo").value=Math.round((cambio*tipo_cambio)*100)/100;	
	minimoEfectivo();
}
function aplicarMontoCombinadoEfectivo(f){
   var efectivo=$("#efectivoRecibidoUnido").val();	
   var efectivoUSD=$("#efectivoRecibidoUnidoUSD").val();	
  if(efectivo==""){
   efectivo=0;
  }
  if(efectivoUSD==""){
   efectivoUSD=0;
  }	

  var tipo_cambio=$("#tipo_cambio_dolar").val();
  var monto_dolares_bolivianos=parseFloat(efectivoUSD)*parseFloat(tipo_cambio);
  var monto_total_bolivianos=monto_dolares_bolivianos+parseFloat(efectivo);
  document.getElementById("efectivoRecibido").value=Math.round((monto_total_bolivianos)*100)/100;
  document.getElementById("efectivoRecibidoUSD").value=Math.round((monto_total_bolivianos/tipo_cambio)*100)/100;
  aplicarCambioEfectivo(f);
}
function buscarMaterial(f, numMaterial){
	f.materialActivo.value=numMaterial;
	document.getElementById('divRecuadroExt').style.visibility='visible';
	document.getElementById('divProfileData').style.visibility='visible';
	document.getElementById('divProfileDetail').style.visibility='visible';
	document.getElementById('divboton').style.visibility='visible';
	
	document.getElementById('divListaMateriales').innerHTML='';
	document.getElementById('itemNombreMaterial').value='';	
	document.getElementById('codigoInterno').value='';	
	document.getElementById('codigoInterno').focus();	
	
}
function Hidden(){
	document.getElementById('divRecuadroExt').style.visibility='hidden';
	document.getElementById('divProfileData').style.visibility='hidden';
	document.getElementById('divProfileDetail').style.visibility='hidden';
	document.getElementById('divboton').style.visibility='hidden';

}
function setMateriales(f, cod, nombreMat, precioVentaArray, stockProducto, precioProducto){
	var numRegistro=f.materialActivo.value;
	
	document.getElementById('materiales'+numRegistro).value=cod;
	document.getElementById('cod_material'+numRegistro).innerHTML=nombreMat;
	
	document.getElementById('divRecuadroExt').style.visibility='hidden';
	document.getElementById('divProfileData').style.visibility='hidden';
	document.getElementById('divProfileDetail').style.visibility='hidden';
	document.getElementById('divboton').style.visibility='hidden';
	
	document.getElementById("cantidad_unitaria"+numRegistro).focus();

	document.getElementById('precioVentaArray'+numRegistro).value=precioVentaArray;
	// Obtiene el precio de acuerdo a la cantidad del producto
	obtienePrecioProducto(numRegistro);

	document.getElementById('stock'+numRegistro).value=stockProducto;
	// actStock(numRegistro);
	
	calculaMontoMaterial(numRegistro);
}

/**
 * Obtiene precio de acuerdo a la cantidad
 */
function obtienePrecioProducto(index){
    let precioArrayString = document.getElementById("precioVentaArray" + index).value;
	// Remueve las comillas simples alrededor de los números
	precioArrayString = precioArrayString.replace(/'/g, '"');
	// Parsea la cadena a un array
	var precios = JSON.parse(precioArrayString);
	
	// Tipo Venta
	let tipo_venta = document.getElementById("tipo").value;
	// Cantidad Unitaria
	let cantidad_unitaria = document.getElementById("cantidad_unitaria" + index).value;
	// Precio de Producto Final
	let precioProducto = 0;
	// console.log(precios)
	let moneda_abreviatura = '';
	let cambio_valor = 0;
	console.log(precios)
    for (let i = 0; i < precios.length; i++) {
        let codTipoVenta   = parseFloat(precios[i][0]);
        let cantidadInicio = parseFloat(precios[i][1]);
        let cantidadFinal  = parseFloat(precios[i][2]);
        let precio  	   = parseFloat(precios[i][3]);
		// Tipo Moneda Abreviatura
        moneda_abreviatura = precios[i][4];
		// valor de Conversión
        cambio_valor = precios[i][5] ? parseFloat(precios[i][5]) : 1;
		
		// console.log("Ctrl codTipoVenta"+codTipoVenta);
		// console.log("cantidadInicio: "+cantidadInicio+" | cantidadFinal:"+cantidadFinal+" | codTipoVenta:"+tipo_venta);

        // Si no se encontró una coincidencia por tipo de venta, verifica solo la cantidad
        if (cantidad_unitaria >= cantidadInicio && cantidad_unitaria <= cantidadFinal) {
			// console.log('Entroooooo')
            precioProducto = precio;
        }
		// Verifica si la cantidad está en el rango y coincide con el tipo de venta
		if (cantidad_unitaria >= cantidadInicio && cantidad_unitaria <= cantidadFinal && codTipoVenta == tipo_venta) {
            precioProducto = precio;
            break; // Sal del bucle si encuentras una coincidencia
        }
    }
	// CONVERSIÓN
	let precio_final = precioProducto * cambio_valor;
	console.log(precioProducto+" "+cambio_valor)
	document.getElementById("precio_unitario" + index).value = precio_final;
	document.getElementById("precio_of" + index).innerHTML 	 = precioProducto + ' ' + moneda_abreviatura;
}

		
function precioNeto(fila){

	var precioCompra=document.getElementById('precio'+fila).value;
		
	var importeNeto=parseFloat(precioCompra)- (parseFloat(precioCompra)*0.13);

	if(importeNeto=="NaN"){
		importeNeto.value=0;
	}
	document.getElementById('neto'+fila).value=importeNeto;
}
function fun13(cadIdOrg,cadIdDes)
{   var num=document.getElementById(cadIdOrg).value;
    num=(100-13)*num/100;
    document.getElementById(cadIdDes).value=num;
}

num=0;
cantidad_items=0;

function mas(obj) {
	if(num>=1000){
		alert("No puede registrar mas de 15 items en una nota.");
	}else{
		//aca validamos que el item este seleccionado antes de adicionar nueva fila de datos
		var banderaItems0=0;
		for(var j=1; j<=num; j++){
			if(document.getElementById('materiales'+j)!=null){
				if(document.getElementById('materiales'+j).value==0){
					banderaItems0=1;
				}
			}
		}
		//fin validacion
		console.log("bandera: "+banderaItems0);

		if(banderaItems0==0){
			num++;
			cantidad_items++;
			console.log("num: "+num);
			console.log("cantidadItems: "+cantidad_items);
			fi = document.getElementById('fiel');
			contenedor = document.createElement('div');
			contenedor.id = 'div'+num;  
			fi.type="style";
			fi.appendChild(contenedor);
			var div_material;
			div_material=document.getElementById("div"+num);			
			ajax=nuevoAjax();
			var cod_precio=1;			
			ajax.open("GET","ajaxMaterialVentas.php?codigo="+num+"&cod_precio="+cod_precio,true);
			ajax.onreadystatechange=function(){
				if (ajax.readyState==4) {
					div_material.innerHTML=ajax.responseText;
					buscarMaterial(form1, num);
				}
			}		
			ajax.send(null);
		}

	}
	
}
		
function menos(numero) {
	cantidad_items--;
	console.log("TOTAL ITEMS: "+num);
	console.log("NUMERO A DISMINUIR: "+numero);
	if(numero==num){
		num=parseInt(num)-1;
 	}
	fi = document.getElementById('fiel');
	fi.removeChild(document.getElementById('div'+numero));
	totales();
}

function pressEnter(e, f){
	tecla = (document.all) ? e.keyCode : e.which;
	if (tecla==13){
		document.getElementById('itemNombreMaterial').focus();
		listaMateriales(f);
		return false;
	}
}

// Función para verificar deuda de cliente
function verificarDeudaCliente(cod_cliente) {
    $.ajax({
		type: "POST",
		url: "ajaxVerificarDeudaCliente.php",
		data: {
			cod_cliente: cod_cliente
		},
		dataType: "json",
		success: function (response) {
			// Manejar la respuesta del backend
			if (response.status) { // Si tiene Deuda
				alert(response.message);
				$('#tipoVenta').trigger('change').val(1);
				$('#tipo').trigger('change').val(1);
				
				// Pago de Credito Pendiente, valida el tipo de venta de: | (1) Contado y Credito | (2) Credito
				if(validacionCreditoConfig == 1){
					$('#nitCliente').val('').trigger('change');
				}
			}
		},
		error: function (xhr, status, error) {
			// Manejar errores de la solicitud AJAX
			alert("Error en la solicitud AJAX: " + error);
		}
	});
}

function validar(f){

	document.getElementById('cantidad_material').value=num;
	// Si el tipo pago es CREDITO y no se seleccionó clietne no se termina el proceso
	let tipo_pago = $('#tipoVenta').val();
	if(tipo_pago == 4 && ($('#cliente').val() == 146 || $('#cliente').val() == '')){
		Swal.fire({
			text: "Ups! Debe seleccionar un cliente",
			type: "error",
			position: "top",
			toast:true,
			showConfirmButton: false,
			timer: 3000
		});
		return(false);
	} 

	/******** Validacion productos vacios ********/
	var banderaValidacionDetalle=0;
	var inputs = $('form input[name^="materiales"]');
	inputs.each(function() {
  	var value = $(this).val();
  	if(value==0 || value==""){
			banderaValidacionDetalle=1;
  	}else{
  	}
	});
	if(banderaValidacionDetalle==1){
		Swal.fire("Productos!", "Debe seleccionar un producto para cada fila.", "info");
		return(false);
	}
	/******** Fin validacion productos vacios ********/
	
	/******** Validacion Cantidades ********/
	var banderaValidacionDetalle=0;
	var inputs = $('form input[name^="cantidad_unitaria"]');
	inputs.each(function() {
  	var value = $(this).val();
  	if(value<=0 || value==""){
			banderaValidacionDetalle=1;
  	}else{
  	}
	});
	if(banderaValidacionDetalle==1){
		Swal.fire("Cantidades!", "Hay algún campo con la cantidad vacia o en cero.", "info");
		return(false);
	}
	/******** Fin validacion Cantidades ********/

	/******** Validacion Precios ********/
	var banderaValidacionDetalle=0;
	var inputs = $('form input[name^="precio_unitario"]');
	inputs.each(function() {
  	var value = $(this).val();
  	if(value<=0 || value==""){
			banderaValidacionDetalle=1;
  	}else{
  	}
	});
	if(banderaValidacionDetalle==1){
		Swal.fire("Precios!", "No pueden existir precios menores o iguales a 0.", "info");
		return(false);
	}
	/******** Fin Precios ********/

	/******** Validacion Montos Productos ********/
	var banderaValidacionDetalle=0;
	var inputs = $('form input[name^="montoMaterial"]');
	inputs.each(function() {
  	var value = $(this).val();
  	if(value==0 || value==""){
			banderaValidacionDetalle=1;
  	}else{
  	}
	});
	if(banderaValidacionDetalle==1){
		Swal.fire("Montos por Producto!", "No puede existir la venta de un producto en 0.", "info");
		return(false);
	}
	/******** Fin Montos Productos ********/


	/**************************************************/
	/************ Validacion de Stocks ****************/
	/**************************************************/
	banderaValidacionDetalle=0;
	var inputs_stocks = $('form input[name^="stock"]');
	var inputs_cantidades = $('form input[name^="cantidad_unitaria"]');
	for (var i = 0; i < inputs_stocks.length; i++) {
		var cantidadFila = parseFloat(inputs_cantidades[i].value);
		var stockFila = parseFloat(inputs_stocks[i].value);
		if(cantidadFila>stockFila){
			banderaValidacionDetalle=1;
		}
		console.log("cantidadStock: "+stockFila);
		console.log( "cantidadFila: "+cantidadFila);
	}
	if(banderaValidacionDetalle==1){
		Swal.fire("Stocks!", "NO puede sacar cantidades mayores al stock.", "error");
		return(false);
	}
	/**************************************************/
	/************ Fin Validacion de Stocks ************/
	/**************************************************/

	/**************** Validar Efectivo y Total Final ****************/
	var efectivoRecibido = parseFloat($("#efectivoRecibido").val());
	var totalFinalVenta = parseFloat($("#totalFinal").val());
	if (isNaN(efectivoRecibido)) { efectivoRecibido = 0; }
	if (isNaN(totalFinalVenta)) { totalFinalVenta = 0; }
	console.log("efectivo: "+efectivoRecibido);
	if(efectivoRecibido < totalFinalVenta){
		Swal.fire("Error en Monto Recibido en Efectivo!", "<b>El monto en efectivo NO puede ser menor al monto total.</b>", "error");
		//document.getElementById('efectivoRecibidoUnido').focus();	
			return (false);
	}  	
	if(totalFinalVenta<=0){
		Swal.fire("Monto Final!", "El Monto Final del documento no puede ser 0", "info");
		return(false);
	}
	/**************** Fin Validar Efectivo y Total Final ****************/

	Swal.fire({
		title: '¿Esta seguro de Realizar la Venta?',
		text: "",
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		confirmButtonText: 'Si, Vender/Facturar!'
		}).then((result) => {
				console.log(result);
				console.log("resultado: "+result.value);
			if (result.value) {
				console.log("Enviando");
				document.getElementById("btsubmit").value = "Enviando...";
					document.getElementById("btsubmit").disabled = true;   
					document.forms[0].submit();
			}if(result.dismiss){
						console.log("Cancelando....");
						return false;
			}
	});		
	
  	return false;
}

function validarCotizacion(f){

	document.getElementById('cantidad_material').value=num;
	// Si el tipo pago es CREDITO y no se seleccionó clietne no se termina el proceso
	let tipo_pago = $('#tipoVenta').val();
	if(tipo_pago == 4 && ($('#cliente').val() == 146 || $('#cliente').val() == '')){
		Swal.fire({
			text: "Ups! Debe seleccionar un cliente",
			type: "error",
			position: "top",
			toast:true,
			showConfirmButton: false,
			timer: 3000
		});
		return(false);
	} 

	/******** Validacion productos vacios ********/
	var banderaValidacionDetalle=0;
	var inputs = $('form input[name^="materiales"]');
	inputs.each(function() {
		var value = $(this).val();
		if(value==0 || value==""){
				banderaValidacionDetalle=1;
		}
	});
	if(banderaValidacionDetalle==1){
		Swal.fire("Productos!", "Debe seleccionar un producto para cada fila.", "info");
		return(false);
	}
	/******** Fin validacion productos vacios ********/

	/******** Validacion Cantidades ********/
	var banderaValidacionDetalle=0;
	var inputs = $('form input[name^="cantidad_unitaria"]');
	inputs.each(function() {
	var value = $(this).val();
	if(value<=0 || value==""){
			banderaValidacionDetalle=1;
	}else{
	}
	});
	if(banderaValidacionDetalle==1){
		Swal.fire("Cantidades!", "Hay algún campo con la cantidad vacia o en cero.", "info");
		return(false);
	}
	/******** Fin validacion Cantidades ********/

	/******** Validacion Precios ********/
	var banderaValidacionDetalle=0;
	var inputs = $('form input[name^="precio_unitario"]');
	inputs.each(function() {
	var value = $(this).val();
	if(value<=0 || value==""){
			banderaValidacionDetalle=1;
	}else{
	}
	});
	if(banderaValidacionDetalle==1){
		Swal.fire("Precios!", "No pueden existir precios menores o iguales a 0.", "info");
		return(false);
	}
	/******** Fin Precios ********/

	/******** Validacion Montos Productos ********/
	var banderaValidacionDetalle=0;
	var inputs = $('form input[name^="montoMaterial"]');
	inputs.each(function() {
	var value = $(this).val();
	if(value==0 || value==""){
			banderaValidacionDetalle=1;
	}else{
	}
	});
	if(banderaValidacionDetalle==1){
		Swal.fire("Montos por Producto!", "No puede existir la venta de un producto en 0.", "info");
		return(false);
	}
	/******** Fin Montos Productos ********/


	// /**************************************************/
	// /************ Validacion de Stocks ****************/
	// /**************************************************/
	// banderaValidacionDetalle=0;
	// var inputs_stocks = $('form input[name^="stock"]');
	// var inputs_cantidades = $('form input[name^="cantidad_unitaria"]');
	// for (var i = 0; i < inputs_stocks.length; i++) {
	// 	var cantidadFila = parseFloat(inputs_cantidades[i].value);
	// 	var stockFila = parseFloat(inputs_stocks[i].value);
	// 	if(cantidadFila>stockFila){
	// 		banderaValidacionDetalle=1;
	// 	}
	// 	console.log("cantidadStock: "+stockFila);
	// 	console.log( "cantidadFila: "+cantidadFila);
	// }
	// if(banderaValidacionDetalle==1){
	// 	Swal.fire("Stocks!", "NO puede sacar cantidades mayores al stock.", "error");
	// 	return(false);
	// }
	// /**************************************************/
	// /************ Fin Validacion de Stocks ************/
	// /**************************************************/

	// /**************** Validar Efectivo y Total Final ****************/
	// var efectivoRecibido = parseFloat($("#efectivoRecibido").val());
	// var totalFinalVenta = parseFloat($("#totalFinal").val());
	// if (isNaN(efectivoRecibido)) { efectivoRecibido = 0; }
	// if (isNaN(totalFinalVenta)) { totalFinalVenta = 0; }
	// console.log("efectivo: "+efectivoRecibido);
	// if(efectivoRecibido < totalFinalVenta){
	// 	Swal.fire("Error en Monto Recibido en Efectivo!", "<b>El monto en efectivo NO puede ser menor al monto total.</b>", "error");
	// 	//document.getElementById('efectivoRecibidoUnido').focus();	
	// 		return (false);
	// }  	
	// if(totalFinalVenta<=0){
	// 	Swal.fire("Monto Final!", "El Monto Final del documento no puede ser 0", "info");
	// 	return(false);
	// }
	// /**************** Fin Validar Efectivo y Total Final ****************/
		
	f.action="guardarCotizacion.php";
	f.submit();
}
// function checkSubmit() {
//     document.getElementById("btsubmit").value = "Enviando...";
//     document.getElementById("btsubmit").disabled = true;
//     return true;
// }	

// $(document).ready(function() {
//   $("#guardarSalidaVenta").submit(function(e) {
//       var mensaje="";
//       if(parseFloat($("#efectivoRecibido").val())<parseFloat($("#totalFinal").val())){
//         mensaje+="<p></p>";
//         alert("El monto en efectivo NO debe ser menor al monto total");
//         return false;
//       }else{
//       	document.getElementById("btsubmit").value = "Enviando...";
//         document.getElementById("btsubmit").disabled = true;
//       }     
//     });
// });

	/**
	 * Tipo de Documento identidad NIT, CI, PAS, CEX
	 */
	function mostrarComplemento(){
		var tipo=$("#tipo_documento").val();
		if(tipo==1){
			$("#complemento").attr("type","text");
			$("#nitCliente").attr("placeholder","INGRESE EL CARNET");
		}else{
			$("#complemento").attr("type","hidden");
			if(tipo==5){
				$("#nitCliente").attr("placeholder","INGRESE EL NIT");	
			}else{
				$("#nitCliente").attr("placeholder","INGRESE EL DOCUMENTO");
			}
			
		}
	}
	/**
	 * Registro de Cliente
	 * Formulario
	 */
	
	function registrarNuevoCliente(){
		$("#nomcli").val("");
		$("#apcli").val("");
		$("#ci").val("");
		$("#nit").val("");
		$("#dir").val("");
		$("#tel1").val("");
		$("#mail").val("");
		$("#fact").val("");
		if($("#nitCliente").val()!=""){
			$("#nit").val($("#nitCliente").val());
			//$("#nomcli").val($("#razonSocial").val());
			$("#fact").val($("#razonSocial").val());		
			$("#boton_guardado_cliente").attr("onclick","adicionarCliente()");		
			$("#titulo_cliente").html("NUEVO CLIENTE");
			$("#modalNuevoCliente").modal("show");
		}else{
			alert("Ingrese el NIT para registrar el cliente!");
		}	
	}
	/**
	 * Guardar Cliente
	 */
	function adicionarCliente() {	
		var nomcli = $("#nomcli").val();
		var apcli = $("#apcli").val();
		var ci = $("#ci").val();
		var nit = $("#nit").val();
		var dir = $("#dir").val();
		var tel1 = $("#tel1").val();
		var mail = $("#mail").val();
		var area = $("#area").val();
		var fact = $("#fact").val();
		var edad = '';
		var genero = '';
		var tipoPrecio = '';	

		if (nomcli == "" || nit == "" || (mail == "" && tel1 == "")) {
			Swal.fire("Informativo!", "Debe llenar los campos obligatorios", "warning");
		} else {
			if (validarCorreoUnicoCliente(0, nit, mail) == 0) {
				Swal.fire("Error!", "El cliente con correo: " + mail + ", ya se encuentra registrado!", "error");
			} else {
				var parametros = {
					"nomcli": nomcli,
					"nit": nit,
					"ci": ci,
					"dir": dir,
					"tel1": tel1,
					"mail": mail,
					"area": area,
					"fact": fact,
					"edad": edad,
					"apcli": apcli,
					"tipoPrecio": tipoPrecio,
					"genero": genero,
					"dv": 1
				};
				$.ajax({
					type: "GET",
					dataType: 'html',
					url: "programas/clientes/prgClienteAdicionar.php",
					data: parametros,
					success: function (resp) {      						
						var r = resp.split("#####");

						console.log("response:" + r);
						console.log("response[]:" + r[1]);

						if (parseInt(r[1]) > 0) {           	
							refrescarComboCliente(r[1]);

							$("#nomcli").val("");
							$("#apcli").val("");
							$("#ci").val("");
							$("#nit").val("");
							$("#dir").val("");
							$("#tel1").val("");
							$("#mail").val("");
							$("#area").val("");
							$("#fact").val("");
							// $("#edad").val(0);
							// $("#genero").val(0);
						} else {		           	
							$("#modalNuevoCliente").modal("hide"); 
							Swal.fire("Error!", "Error al crear cliente", "error");
						}            
					}
				});	
			}
		}
	}
	/**
	 * Validación de Correo Electrónico UNICO
	 */
	function validarCorreoUnicoCliente(cliente,nit,correo){
		var dato=0;
		var parametros={"cliente":cliente,"nit":nit,"correo":correo};
		$.ajax({
			type: "GET",
			dataType: 'html',
			url: "validarCorreoUnicoCliente.php",
			data: parametros,
			async:false,
			success:  function (resp) {
			dato=resp;     
			}
		});
		return dato;
	}
	/**
	 * Refresca combo de cliente Actual
	 */
	function refrescarComboCliente(cliente){
		var parametros={"cliente":cliente,"nit":$("#nitCliente").val()};
		$.ajax({
			type: "GET",
			dataType: 'html',
			url: "listaClientesActual.php",
			data: parametros,
			success:  function (resp) {
				console.log(resp)
				Swal.fire("Correcto!", "Se guardó el cliente con éxito", "success");   
				$("#cliente").html(resp); 		   
				ajaxRazonSocialCliente(document.getElementById('form1'));
				$("#cliente").selectpicker("refresh");       
				$("#modalNuevoCliente").modal("hide");                  	   
			}
		});	
	}
	/**
	 * Obtiene razon social
	 */
	function ajaxRazonSocialCliente(f){
		var contenedor;
		contenedor=document.getElementById("divRazonSocial");
		var cliente=document.getElementById("cliente").value;
		ajax=nuevoAjax();
		ajax.open("GET", "ajaxRazonSocialCliente.php?cliente="+cliente,true);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4) {
				if(cliente!=146){
					contenedor.innerHTML = ajax.responseText;
				}			
				document.getElementById('razonSocial').focus();
				if($("#cliente").val()==146){
					$("#razonSocial").attr("readonly",false);								
				}else{
					$("#razonSocial").attr("readonly",true);	
				}
				
			}
		}
		ajax.send(null);
	}
	/**
	 * Verificación de Evento
	 */
	
	function check(e) {
		tecla = (document.all) ? e.keyCode : e.which;
		//Tecla de retroceso para borrar, siempre la permite
		if (tecla == 8||tecla==13) {
			return true;
		}
		// Patron de entrada, en este caso solo acepta numeros y letras
		if($("#tipo_documento").val()!=1){
			patron = /[A-Za-z0-9-]/;
		}else{
			patron = /[0-9]/;
		}
		tecla_final = String.fromCharCode(tecla);
		return patron.test(tecla_final);
	}
	/**
	 * Tipo de Documento identidad NIT, CI, PAS, CEX
	 */
	function mostrarComplemento(){
		var tipo=$("#tipo_documento").val();
		if(tipo==1){
			$("#complemento").attr("type","text");
			$("#nitCliente").attr("placeholder","INGRESE EL CARNET");
		}else{
			$("#complemento").attr("type","hidden");
			if(tipo==5){
				$("#nitCliente").attr("placeholder","INGRESE EL NIT");	
			}else{
				$("#nitCliente").attr("placeholder","INGRESE EL DOCUMENTO");
			}
			
		}
	}
	/**
	 * VERIFICACIÓN DE CUFD - MINKA_SIAT
	 */
	$(document).ready(function () {
		// Verificación - NOTA DE EMISIÓN
		let tipoDoc = $('#tipoDoc').val();
		if(tipoDoc == 1){
			$.ajax({
				url: "service_minka_siat/servicio_cufd.php",
				type: "POST",
				dataType: "json",
				contentType: "application/json",
				success: function (data) {
					if (data.status == true) {
						$('.divBotones2').show();
						Swal.fire({
							text: "¡Exito! " + data.message,
							type: "success",
							position: "top",
							toast:true,
							showConfirmButton: false,
							timer: 3000
						});
					} else {
						/**
						 * Generación de NUEVO CUFD
						 */
						Swal.fire({
							title: '¿Generar un nuevo CUFD?',
							text: 'Esta acción generará un nuevo CUFD. ¿Estás seguro?',
							type: 'warning',
							showCancelButton: true,
							confirmButtonText: 'Sí',
							cancelButtonText: 'No'
						}).then((result) => {
							if (result.value) {
								$.ajax({
									url: "service_minka_siat/servicio_cufd_generar.php",
									type: 'POST',
									dataType: "json",
									contentType: "application/json",
									success: function(data) {
										if (data.status == true) {
											$('.divBotones2').show();
											Swal.fire({
												text: 'Éxito ' + data.message,
												type: 'success',
												position: "top",
												toast:true,
												showConfirmButton: false,
												timer: 3500
											});
										} else {
											$('.divBotones2').hide();
											Swal.fire({
												text: "Error! " + data.message,
												type: "error",
												position: "top",
												toast:true,
												showConfirmButton: false,
												timer: 4500
											});
										}
									},
									error: function() {
										Swal.fire({
											text: "Error! Hubo un error al generar el nuevo CUFD.",
											type: "error",
											position: "top",
											toast:true,
											showConfirmButton: false,
											timer: 4000
										});
									}
								});
							}
						});
					}
				},
				error: function (xhr, status, error) {
					$('.divBotones2').hide();
					Swal.fire('Error', 'Ocurrió un error en la llamada al servicio web.', 'error');
				}
			});
		}else if(tipoDoc == 2){
			$('.divBotones2').show();
		}
	});
	/**
	 * Selección de tipo de Pago
	 * tipo_pago = tipoVenta
	 */
	$('body').on('change', '#tipoVenta', function(){
		// Tipo de pago: TARJETA
		if($(this).val() == 2){
			$('#nroTarjeta_form').val('').prop("required", true).show();
		}else{
			$('#nroTarjeta_form').val('').prop("required", false).hide();
		}

		// Cuando el tipo de venta es CREDITO se verifica Deuda
		if($(this).val() == 4){
			// Verificación de Deudas del Cliente
			verificarDeudaCliente($('#cliente').val());
		}
	});
	/******************
	 * NRO DE TARJETA *
	 ******************/
	// Función para enmascarar el número de tarjeta
	function maskCardNumber(input) {
		let cardNumber = input.val().replace(/\D/g, ''); // Eliminar caracteres no numéricos
		const maxLength = 8; // Longitud máxima permitida
		// Limitar a 16 caracteres (8 asteriscos + 8 dígitos)
		if (cardNumber.length > maxLength) {
			cardNumber = cardNumber.slice(0, maxLength);
		}
		// Agregar 8 asteriscos después de los primeros 4 dígitos
		const maskedCardNumber = cardNumber.replace(/^(\d{4})(.*)$/, (_, first4, rest) => first4 + '********' + rest);
		// Actualizar el valor del input
		input.val(maskedCardNumber);
	}

	// Evento para controlar los cambios en el input
	$('body').on('input', '#nroTarjeta_form', function() {
		maskCardNumber($(this));
	});

	// Evento para borrar los asteriscos si se borra el dígito 13
	$('body').on('keydown', '#nroTarjeta_form', function(event) {
		const key = event.key;
		const cardNumber = $(this).val().replace(/\D/g, ''); // Eliminar caracteres no numéricos

		// Permitir borrar caracteres, incluyendo los asteriscos
		if (key === 'Backspace' || key === 'Delete') {
			if (cardNumber.length >= 13) {
				$(this).val(cardNumber.slice(0, 12)); // Borrar el dígito 13 y los asteriscos
			} else {
				$(this).val(cardNumber); // Permitir borrar solo los dígitos
			}
		} else {
			maskCardNumber($(this));
		}
	});
	/*******************/

	/**
	 * Se obtien el precio de acuerdo a la cantidad por producto
	 */
	$('body').on('keyup', '.cantidad_upd', function() {
		let index        = $(this).data('index');
		let cantidad     = $(this).val();
		obtienePrecioProducto(index);
		// let cod_material = $('#materiales' + index).val();
		// let tipo_venta   = $('#tipoVenta').val();
		
		// Realizar la solicitud AJAX después del retraso TIME
		// $.ajax({
		// 	type: "POST",
		// 	url: "ajax_producto_precio_rango.php",
		// 	data: {
		// 		tipo_venta: tipo_venta,
		// 		cod_material: cod_material,
		// 		cantidad: cantidad
		// 	},
		// 	dataType: "json",
		// 	success: function(response) {
		// 		if (response && response.precio !== undefined) {
		// 			$('#precio_unitario' + index).val(response.precio);
		// 		} else {
		// 			$('#precio_unitario' + index).val(0);
		// 			// Manejar un caso de respuesta no válida
		// 			console.error("Respuesta no válida del servidor");
		// 		}
		// 		total();
		// 	},
		// 	error: function(xhr, status, error) {
		// 		// Manejar errores de la solicitud AJAX
		// 		alert("Error en la solicitud AJAX: " + error);
		// 	}
		// });
	});

	// De acuerdo al TIPO se verifica la Deuda de Credito
	$('body').on('change', '#tipo', function(){
		$('.formIndex').each(function(index, element) {
			var index = $(element).val();
			obtienePrecioProducto(index);
		});
		totales();
		// TipoVenta
		if($(this).val() == 1){ // CONTADO
			$('#tipoVenta').trigger('change').val(1);
		}else if($(this).val() == 2){ // CREDITO
			console.log('credito')
			$('#tipoVenta').trigger('change').val(4);
			verificarDeudaCliente($('#cliente').val());
		}
		$("#tipoVenta").selectpicker('refresh');
	});


	var tablaBuscadorSucursales=null;
	function encontrarMaterial(numMaterial){
		fila_seleccionada = numMaterial;

		var cod_material  = $("#materiales"+numMaterial).val();
		var parametros={"cod_material":cod_material};
		$.ajax({
			type: "GET",
			dataType: 'html',
			url: "ajax_encontrar_productos.php",
			data: parametros,
			success:  function (resp) { 
			// alert(resp);           
				$("#modalProductosCercanos").modal("show");
				$("#tabla_datosE").html(resp);   
			}
		});	
	}
</script>


<?php
 
	$fecha=date("Y-m-d");

	$sqlCambioUsd="select valor from cotizaciondolar order by 1 desc limit 1";
	$respUsd=mysqli_query($enlaceCon,$sqlCambioUsd);
	$tipoCambio=1;
	while($filaUSD=mysqli_fetch_array($respUsd)){
		$tipoCambio=$filaUSD[0];	
	}
?><input type="hidden" id="tipo_cambio_dolar" value="<?=$tipoCambio?>"><?php
$usuarioVentas=$_COOKIE['global_usuario'];
$globalAgencia=$_COOKIE['global_agencia'];
$globalAlmacen=$_COOKIE['global_almacen'];

//SACAMOS LA CONFIGURACION PARA EL DOCUMENTO POR DEFECTO
$sqlConf="select valor_configuracion from configuraciones where id_configuracion=1";
$respConf=mysqli_query($enlaceCon,$sqlConf);
$tipoDocDefault=mysqli_result($respConf,0,0);

//SACAMOS LA CONFIGURACION PARA EL CLIENTE POR DEFECTO
$sqlConf="select valor_configuracion from configuraciones where id_configuracion=2";
$respConf=mysqli_query($enlaceCon,$sqlConf);
$clienteDefault=mysqli_result($respConf,0,0);

//SACAMOS LA CONFIGURACION PARA CONOCER SI LA FACTURACION ESTA ACTIVADA
$sqlConf="select valor_configuracion from configuraciones where id_configuracion=3";
$respConf=mysqli_query($enlaceCon,$sqlConf);
$facturacionActivada=mysqli_result($respConf,0,0);

//SACAMOS LA CONFIGURACION PARA CONOCER SI PERMITIMOS VENDER POR DEBAJO DEL COSTO
$sqlConf="select valor_configuracion from configuraciones where id_configuracion=5";
$respConf=mysqli_query($enlaceCon,$sqlConf);
$ventaDebajoCosto=mysqli_result($respConf,0,0);

?>

<!-- Estilo de Input Complemento -->
<style>
	.elegant-input {
		border: 2px solid #aaa; /* Borde del input */
		border-radius: 5px; 	/* Bordes redondeados */
		padding: 3px; 			/* Espaciado interno */
		font-size: 11px; 		/* Tamaño de la fuente */
		text-transform: uppercase; 		/* Convertir el texto a mayúsculas */
		background: #D2FFE8; 			/* Color de fondo */
		outline: none; 					/* Eliminar el resaltado del borde al enfocar */
		transition: border-color 0.3s; 	/* Transición del color del borde */
	}
	.custom-input {
		border: 2px solid #aaa; /* Borde del input */
		border-radius: 5px; 	/* Bordes redondeados */
		padding: 3px; 			/* Espaciado interno */
		font-size: 11px; 		/* Tamaño de la fuente */
		text-transform: uppercase; 		/* Convertir el texto a mayúsculas */
		outline: none; 					/* Eliminar el resaltado del borde al enfocar */
		transition: border-color 0.3s; 	/* Transición del color del borde */
	}
	.text-description {
		color: gray;
	}
</style>

<form action='guardarSalidaMaterial.php' method='POST' name='form1' id="guardarSalidaVenta" onsubmit="return validar(this)"><!--onsubmit='return checkSubmit();'-->
<div class="row justify-content-center">
    <div class="col-md-4">
		<?php
			if($verificaConversion[0] > 0){
		?>
			<div class="text-center p-1" role="alert" style="background-color: #d4edda; border-color: #c3e6cb; color: #155724;">
				<b>
				<small>Los precios oficiales están en dólares ($). Se realizará la conversión.</small>
				</b>
			</div>
		<?php
			}else{
		?>
			<div class="text-center p-1" role="alert" style="background-color: #f8d7da; border-color: #f5c6cb; color: #721c24;">
				<b>
					¡Alerta! Debe ingresar el tipo de cambio para la fecha actual.
				</b>
			</div>
		<?php
			}
		?>
	</div>
</div>

<!--h1>Registrar Venta</h1-->
<?php
	// CODIGO DE COTIZACIÓN
	$cod_cotizacion = empty($_GET['cod_cotizacion']) ? '' : $_GET['cod_cotizacion'];
	$sqlCotizacion = "SELECT c.razon_social, 
							c.nit, 
							c.observaciones, 
							c.descuento, 
							c.cod_cliente, 
							c.cod_tipoventa,
							c.cod_tipopago,
							c.cod_tipo_doc,
							c.cod_chofer as cod_vendedor
						FROM cotizaciones c
						WHERE c.codigo = '$cod_cotizacion'";
	// echo $sqlCotizacion;
	$respUsd=mysqli_query($enlaceCon,$sqlCotizacion);
	$cab_razon_social = '';	
	$cab_nit 		  = '';	
	$cab_observacion  = '';	
	$cab_descuento 	  = 0;	
	$cab_cod_cliente  = 0;	
	$cab_cod_tipoventa = '';
	$cab_cod_tipopago  = '';
	$cab_cod_tipo_doc  = '';
	$cab_cod_vendedor  = '';
	while($rowCot=mysqli_fetch_array($respUsd)){
		$cab_razon_social = $rowCot['razon_social'];
		$cab_nit 		  = $rowCot['nit'];	
		$cab_observacion  = $rowCot['observaciones'];	
		$cab_descuento    = $rowCot['descuento'];
		$cab_cod_cliente  = $rowCot['cod_cliente'];	
		$cab_cod_tipoventa = $rowCot['cod_tipoventa'];
		$cab_cod_tipopago  = $rowCot['cod_tipopago'];
		$cab_cod_tipo_doc  = $rowCot['cod_tipo_doc'];
		$cab_cod_vendedor  = $rowCot['cod_vendedor'];
	}
?>
<table class='texto' align='center' width='100%'>
<tr>
<th align='center' width="10%">
	<input type="hidden" value="<?=$tipoDocDefault;?>" id="tipoDoc" name="tipoDoc">
	<?php

		if($facturacionActivada==1){
			$sql="select codigo, nombre, abreviatura from tipos_docs where codigo in (1,2) order by 2 desc";
		}else{
			$sql="select codigo, nombre, abreviatura from tipos_docs where codigo in (2) order by 2 desc";
		}
		$resp=mysqli_query($enlaceCon,$sql);

		echo "<select name='tipoDoc_extra' id='tipoDoc_extra' disabled class='selectpicker form-control' data-style='btn btn-primary'>";
		echo "<option value=''>-</option>";
		while($dat=mysqli_fetch_array($resp)){
			$codigo=$dat[0];
			$nombre=$dat[2];
			if($codigo==$tipoDocDefault){
				echo "<option value='$codigo' selected>$nombre</option>";
			}else{
				echo "<option value='$codigo'>$nombre</option>";
			}
		}
		echo "</select>";
		?>
</th>
<input type="hidden" name="tipoSalida" id="tipoSalida" value="1001">
<th width="20%">
	<div class="dropdown bootstrap-select form-control show">
		<select name="tipo_documento" class="selectpicker form-control" data-live-search="true" id="tipo_documento" required="" data-style="btn btn-success" onChange='mostrarComplemento(form1);'>
		<!-- Tipo de Documento por Defecto => NIT -->
		<?php
			$sql2="SELECT codigoClasificador,descripcion FROM siat_sincronizarparametricatipodocumentoidentidad;";
			$resp2=mysqli_query($enlaceCon,$sql2);

			while($dat2=mysqli_fetch_array($resp2)){
				$cod_tipodocumento = $dat2[0];
				$nombreCliente	   = $dat2[1]." ".$dat2[2];
				$select = ($cab_cod_tipo_doc == $cod_tipodocumento) ? 'selected' : '';
		?>
			<option value='<?php echo $cod_tipodocumento?>' <?= $select ?>><?php echo $nombreCliente?></option>
		<?php
			}
		?>
		</select>
	</div>
</th>
<th width="20%">
	<div id='divNIT'>
		<input type='text' value='<?php echo empty($cod_cotizacion) ? $nitDefault : $cab_nit; ?>' name='nitCliente' id='nitCliente' onchange="ajaxRazonSocial(this.form);" onkeypress="return check(event)" placeholder="INGRESE EL CARNET o NIT" required class="custom-input" style="width: 100%;">
	</div>
	<input type="hidden" name="complemento" id="complemento" class="elegant-input" placeholder="COMPLEMENTO" onkeyup="javascript:this.value=this.value.toUpperCase();" style="width: 100%;">
</th>

<th width="20%">
	<div id='divRazonSocial'>
		<input type='text' name='razonSocial' id='razonSocial' value='<?php echo empty($cab_razon_social) ? $razonSocialDefault : $cab_razon_social;?>'style="width: 100%;" onKeyUp='javascript:this.value=this.value.toUpperCase();' placeholder="NOMBRE / RAZON SOCIAL" required class="custom-input">
	</div>
</th>

<th align='center' id='divCliente' width="25%">		
	<select name='cliente' class='selectpicker form-control' data-live-search="true" id='cliente' onChange='ajaxRazonSocialCliente(this.form);' required data-style="btn btn-secondary">
		<option value='146'>NO REGISTRADO</option>
		<?php
			$sql = "SELECT c.cod_cliente, c.nombre_cliente, c.nit_cliente
					FROM clientes c
					WHERE c.cod_cliente != 146";
			$resp=mysqli_query($enlaceCon,$sql);
			while($rowCot=mysqli_fetch_array($resp)){
				$selected = ($cab_cod_cliente = $rowCot['cod_cliente']) ? 'selected' : '';
		?>
		<option value='<?=$rowCot['cod_cliente']?>' <?=$selected?>><?=$rowCot['nombre_cliente']?></option>
		<?php
			}
		?>
	</select>
</th>
<th width="5%">
	<a href="#" title="Registrar Nuevo Cliente" data-toggle='tooltip' onclick="registrarNuevoCliente(); return false;" class="btn btn-success btn-round btn-sm text-white circle" id="button_nuevo_cliente">+</a>
	<input type="hidden" name="nroCorrelativo" id="nroCorrelativo" value="0">
</th>

</tr>
<?php
if($tipoDocDefault==2){
	$razonSocialDefault="-";
	$nitDefault="0";
}else{
	$razonSocialDefault="";
	$nitDefault="";
}
?>
<tr>
	<th>
		<input type="text" class="custom-input" value="<?php echo $fecha?>" id="fecha" name="fecha" size="10" readonly><img id="imagenFecha" src="imagenes/fecha.bmp"> 
	</th>
	<th>
		Tipo Venta
		<div id='divTipo'>
			<?php
				$sql1="SELECT cod_tipoventa, nombre_tipoventa 
						FROM tipos_venta tv  
						WHERE tv.estado = 1";
				$resp1=mysqli_query($enlaceCon,$sql1);
				echo "<select class='selectpicker form-control' name='tipo' data-style='btn btn-success' data-live-search='true' id='tipo'>";
				while($dat=mysqli_fetch_array($resp1)){
					$codigo=$dat[0];
					$nombre=$dat[1];
					$selected = $cab_cod_tipoventa == $codigo ? 'selected' : '';
					echo "<option value='$codigo' $selected>$nombre</option>";
				}
				echo "</select>";
				?>

		</div>
	</th>
	<th>
		Tipo Pago
		<div id='divTipoVenta'>
			<?php
				$sql1="select cod_tipopago, nombre_tipopago from tipos_pago order by 1";
				$resp1=mysqli_query($enlaceCon,$sql1);
				echo "<select class='selectpicker form-control' name='tipoVenta' data-style='btn btn-success' data-live-search='true' id='tipoVenta'>";
				while($dat=mysqli_fetch_array($resp1)){
					$codigo=$dat[0];
					$nombre=$dat[1];
					$selected = $cab_cod_tipopago == $codigo ? 'selected' : '';
					echo "<option value='$codigo' $selected>$nombre</option>";
				}
				echo "</select>";
				?>

		</div><br>
		<!-- Numero de Tarjeta -->
		<input type='text' style='display:none;' name='nroTarjeta_form' id='nroTarjeta_form' class="custom-input" placeholder="Ingresar nro. tarjeta" style="width: 100%;">
	</th>
	<th>
		Vendedor
		<select class='selectpicker form-control' data-style='btn btn-rose' data-live-search='true' name='cod_vendedor' id='cod_vendedor' required>
			<option value=''>----</option>
			<?php
			$sql2="select f.`codigo_funcionario`,
				concat(f.`paterno`,' ', f.`nombres`) as nombre from `funcionarios` f, funcionarios_agencias fa 
				where fa.codigo_funcionario=f.codigo_funcionario and fa.`cod_ciudad`='$globalAgencia' and estado=1 order by 2";
			$resp2=mysqli_query($enlaceCon,$sql2);

			while($dat2=mysqli_fetch_array($resp2)){
				$codVendedor=$dat2[0];
				$nombreVendedor=$dat2[1];
				$selected = $cab_cod_vendedor == $codVendedor ? 'selected' : '';
			?>		
			<option value='<?php echo $codVendedor?>' <?=empty($cod_cotizacion) ? (($codVendedor==$global_usuario)?"selected":"") : $selected;?> ><?php echo $nombreVendedor?></option>
			<?php    
			}
			?>
		</select>
	</th>
	<th colspan="2">Observaciones:<input type='text' class='custom-input' name='observaciones' size='50' rows="3" value="<?=$cab_observacion?>">
	</th>

</tr>
</table>
<input type="hidden" id="ventas_codigo"><!--para validar la funcion mas desde ventas-->

<?php
	if($verificaConversion[0] > 0){
?>
<div class="codigo-barras div-center">
		<input class="btn btn-blue" type="button" value="Nuevo Producto(+)" onclick="mas(this);" accesskey="a"/>
        <input type="text" class="form-codigo-barras" id="input_codigo_barras" placeholder="Ingrese el código de barras." autofocus autocomplete="off">

</div>
<?php
	}
?>


<fieldset id="fiel" style="width:100%;border:0;">
	<table align="center" class="texto" width="100%" id="data0">
	<!--tr>
		<td align="center" colspan="9">
			<b>Detalle de la Venta    </b>
		</td>
	</tr-->

	<tr align="center">
		<td width="5%">&nbsp;</td>
		<td width="30%">Material</td>
		<td width="10%">Stock</td>
		<td width="10%">Cantidad</td>
		<td width="10%">Precio (Bs.)</td>
		<td width="15%">Desc.</td>
		<td width="10%">Monto</td>
		<td width="10%">&nbsp;</td>
	</tr>
	




	<!-- ITEMS DE COTIZACIÓN -->
	<?php
	
	$globalAlmacen 	 		= $_COOKIE['global_almacen'];
	$globalAlmacenNombre 	= $_COOKIE['global_almacen_nombre'];
	$nro_materialActivo 	= 0;
	$cotizacion_total_final = 0;
	if(!empty($cod_cotizacion)){
		// Obtenemos control de fecha

		$sql2="SELECT
					m.codigo_material as cod_material,
					cd.orden_detalle,
					m.descripcion_material,
					cd.precio_unitario,
					cd.cantidad_unitaria,
					cd.descuento_unitario,
					cd.monto_unitario 
				FROM cotizaciones_detalle cd
				LEFT JOIN material_apoyo m ON m.codigo_material = cd.cod_material
				WHERE m.codigo_material = cd.cod_material 
				AND cd.cod_cotizacion = '$cod_cotizacion'
				ORDER BY cd.orden_detalle DESC";
		// echo $sql2;
		$resp2=mysqli_query($enlaceCon,$sql2);
		while($rawCotizacion = mysqli_fetch_array($resp2)){
			$nro_materialActivo++;
			$num = $nro_materialActivo;
			$cod_material 		  = $rawCotizacion['cod_material'];
			$descripcion_material = $rawCotizacion['descripcion_material'];

			// Obtiene precios de la tabla PRECIOS
			$arrayPrecios = [];
			$sqlPrecio = "SELECT p.cod_tipoventa, 
								p.cantidad_inicio, 
								p.cantidad_final, 
								p.precio, 
								m.abreviatura as moneda_abreviatura, 
								tm.valor as cambio_valor
							FROM precios p
							LEFT JOIN monedas m ON m.codigo = p.cod_moneda
							LEFT JOIN tipo_cambiomonedas tm ON tm.cod_moneda = p.cod_moneda
							WHERE p.codigo_material = '$cod_material'";
			// echo $sqlPrecio;
			$respPrecios = mysqli_query($enlaceCon,$sqlPrecio);
			while($dataPrecio=mysqli_fetch_array($respPrecios)){
				$arrayPrecios[] = [
					$dataPrecio['cod_tipoventa'], 
					$dataPrecio['cantidad_inicio'], 
					$dataPrecio['cantidad_final'], 
					$dataPrecio['precio'], 
					$dataPrecio['moneda_abreviatura'], 
					$dataPrecio['cambio_valor']];
			}
			$arrayPrecios = json_encode($arrayPrecios);
			// Reemplazar comillas dobles por comillas simples
			$jsonPrecios = str_replace('"', "'", $arrayPrecios);
			$htmlPrecios = htmlspecialchars($jsonPrecios, ENT_QUOTES, 'UTF-8');

			$stockProducto 		  = stockProducto($globalAlmacen, $cod_material);
			
			$cotizacion_cantidad  = $rawCotizacion['cantidad_unitaria'];
			$cotizacion_total  	  = $rawCotizacion['monto_unitario'];
			$cotizacion_precio_unitario    = $rawCotizacion['precio_unitario'];
			$cotizacion_descuento_unitario = $rawCotizacion['descuento_unitario'];

			// Calcular monto final
			$cotizacion_total_item 		 = round(($cotizacion_total - $cotizacion_descuento_unitario), 2);
			// Calcular el porcentaje de descuento
			$cotizacion_descuento_porcentaje = round((($cotizacion_descuento_unitario / $cotizacion_total) * 100), 2);

			// Suma total FINAL
			$cotizacion_total_final += $cotizacion_total_item;
						

			/* Fin diferencia de fecha */
	?>
<div id="div<?=$nro_materialActivo?>">
	<link href="stilos.css" rel="stylesheet" type="text/css">
	<table border="0" align="center" width="100%"  class="texto" id="data<?php echo $num?>" >
		<tbody>
			<tr bgcolor="#FFFFFF">

			<td width="5%" align="center">
				<a href="javascript:buscarMaterial(form1, <?php echo $num;?>)"><img src='imagenes/buscar2.png' title="Buscar Producto" width="30"></a>
				<a href="javascript:encontrarMaterial(<?php echo $num;?>)" class="btn btn-primary btn-sm btn-fab"><i class='material-icons float-left' title="Ver en otras Sucursales">place</i></a>
			</td>

			<td width="30%" align="center">
				<input type="hidden" class="formIndex" value="<?php echo $num;?>">
				<input type="hidden" name="precioVentaArray<?php echo $num;?>" id="precioVentaArray<?php echo $num;?>" value="<?=$htmlPrecios?>">
				<!-- Codigo de Material -->
				<input type="hidden" name="materiales<?php echo $num;?>" id="materiales<?php echo $num;?>" value="<?=$cod_material?>">
				<!-- Codigo de Sucursal -->
				<input type="hidden" name="cod_sucursales<?php echo $num;?>" id="cod_sucursales<?php echo $num;?>" value="<?=$globalAlmacen?>">

				<strong id="nombreSucursal<?php echo $num;?>"><?=$globalAlmacenNombre?></strong>
				<div id="cod_material<?php echo $num;?>" class='textomedianonegro'><?=$descripcion_material?></div>
			</td>

			<td width="10%" align="center">
				<div id='idstock<?php echo $num;?>'>
					<!-- <input type='hidden' id='stock<?php echo $num;?>' name='stock<?php echo $num;?>' value=''> -->
					<input type='number' id='stock<?php echo $num;?>' name='stock<?php echo $num;?>' style="height:20px;font-size:19px;width:80px;color:red;" readonly value="<?=$stockProducto?>">
				</div>
			</td>

			<td align="center" width="10%">
				<!-- <input class="inputnumber cantidad_upd" data-index="<?php echo $num;?>" type="number" min="1" id="cantidad_unitaria<?php echo $num;?>" onKeyUp='calculaMontoMaterial(<?php echo $num;?>);' name="cantidad_unitaria<?php echo $num;?>" onChange='calculaMontoMaterial(<?php echo $num;?>);' step="1" value="1" style="height:20px;font-size:19px;width:100px;color:black;" required>  -->
				<input class="inputnumber cantidad_upd" 
						data-index="<?php echo $num;?>" 
						type="number" 
						min="1" 
						id="cantidad_unitaria<?php echo $num;?>"  
						name="cantidad_unitaria<?php echo $num;?>" 
						oninput='calculaMontoMaterial(<?php echo $num;?>);' 
						onchange='calculaMontoMaterial(<?php echo $num;?>);' 
						step="1" 
						value="<?=$cotizacion_cantidad?>" 
						style="height:20px;font-size:19px;width:100px;color:black;" 
						required> 
			</td>


			<td align="center" width="15%">
				<div id='idprecio<?php echo $num;?>'>
					<input class="inputnumber" type="number" min="1" id="precio_unitario<?php echo $num;?>" name="precio_unitario<?php echo $num;?>" onKeyUp='calculaMontoMaterial(<?php echo $num;?>);' onChange='calculaMontoMaterial(<?php echo $num;?>);' step="0.01" style="height:20px;font-size:19px;width:100px;color:blue;" required value="<?=$cotizacion_precio_unitario?>"><br>
					<b id="precio_of<?php echo $num;?>" style="font-size:15px;color:red;">Of.</b>
				</div>
			</td>

			<td align="center" width="15%">
				<input class="inputnumber" type="number" value="<?=$cotizacion_descuento_unitario?>" id="descuentoProducto<?php echo $num;?>" name="descuentoProducto<?php echo $num;?>" onKeyUp='calculaMontoMaterial(<?php echo $num;?>);' onChange='calculaMontoMaterial(<?php echo $num;?>);'  value="0" step="0.01" readonly>
			</td>

			<td align="center" width="10%">
				<input class="inputnumber" type="number" value="<?=$cotizacion_total_item?>" id="montoMaterial<?php echo $num;?>" name="montoMaterial<?php echo $num;?>" value="0"  step="0.01" style="height:20px;font-size:20px;width:120px;color:red;" required readonly> 
			</td>

			<td align="center"  width="10%" ><input class="boton2peque" type="button" value="-" onclick="menos(<?php echo $num;?>)" /></td>

			</tr>
		</tbody>
	</table>
</div>
<script>
	obtienePrecioProducto(<?= $nro_materialActivo;?>);
	calculaMontoMaterial(<?= $nro_materialActivo;?>);
</script>

<?php
	}
}
?>







	</table>
</fieldset>


<div id="divRecuadroExt" style="background-color:#666; position:absolute; width:1000px; height: 400px; top:30px; left:150px; visibility: hidden; opacity: .70; -moz-opacity: .70; filter:alpha(opacity=70); -webkit-border-radius: 20px; -moz-border-radius: 20px; z-index:2; overflow: auto;">
</div>

<div id="divboton" style="position: absolute; top:20px; left:1120px;visibility:hidden; text-align:center; z-index:3">
	<a href="javascript:Hidden();"><img src="imagenes/cerrar4.png" height="45px" width="45px"></a>
</div>

<div id="divProfileData" style="background-color:#FFF; width:950px; height:350px; position:absolute; top:50px; left:170px; -webkit-border-radius: 20px; 	-moz-border-radius: 20px; visibility: hidden; z-index:2; overflow: auto;">
  	<div id="divProfileDetail" style="visibility:hidden; text-align:center">
		<table align='center'>
			<tr>
				<th>
				</th>
				<th>Marca</th><th>CodInterno</th><th>Material</th><th>&nbsp;</th></tr>
			<tr>
			<td>
				<div class="custom-control" style="padding-left: 0px;">
					<input type="checkbox" class="" id="solo_stock" value="1">
					<label class="text-dark font-weight-bold" for="solo_stock">Solo Productos con Stock</label>
				</div>
			</td>
			<td><select class="textomedianorojo" name='itemMarca' style="width:200px">
			<?php
			$sqlTipo="SELECT pl.cod_linea_proveedor, CONCAT(p.nombre_proveedor,' - ',pl.nombre_linea_proveedor) 
						FROM proveedores p, proveedores_lineas pl 
						WHERE p.cod_proveedor=pl.cod_proveedor 
						AND pl.estado=1 
						ORDER BY 2;";
			$respTipo=mysqli_query($enlaceCon,$sqlTipo);
			echo "<option value='0'>--</option>";
			while($datTipo=mysqli_fetch_array($respTipo)){
				$codTipoMat		= $datTipo[0];
				$nombreTipoMat	= $datTipo[1];
				echo "<option value=$codTipoMat>$nombreTipoMat</option>";
			}
			?>
			</select>
			</td>
			<td>
				<input type='text' name='codigoInterno' id='codigoInterno' class="textomedianorojo" onkeypress="return pressEnter(event, this.form);">
			</td>
			<td>
				<input type='text' name='itemNombreMaterial' id='itemNombreMaterial' class="textomedianorojo" onkeypress="return pressEnter(event, this.form);">
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
<div style="height:200px;"></div>

<div class="pie-div">
	<div class='float-right' style="padding-right:15px;"><a href='#' class='boton-plomo' style="width:10px !important;height:10px !important;font-size:10px !important;" id="boton_nota_remision" onclick="cambiarNotaRemision()">F</a></div>
	<table class="pie-montos">
      <tr>
        <td>
	<table id='' width='100%' border="0">
		<tr>
			<td align='right' width='90%' style="color:#777B77;font-size:12px;"></td><td align='center' colspan="2"><b style="font-size:20px;color:#0691CD;">Bs.</b></td>
		</tr>

		<tr>
			<td align='right' width='90%' style="color:#777B77;font-size:12px;">Monto Nota</td><td><input type='number' name='totalVenta' id='totalVenta' readonly style="background:#B0B4B3;width:120px;"></td>
			<td align='center' width='90%' style="color:#777B77;font-size:12px;"><b style="font-size:12px;color:#0691CD;">Efectivo Recibido</b></td>
		</tr>
		<tr>
			<td align='right' width='90%' style="font-weight:bold;color:red;font-size:12px;">Descuento</td><td><input type='number' name='descuentoVenta' id='descuentoVenta' onChange='aplicarDescuento(form1);' style="height:20px;font-size:19px;width:120px;color:red;" onkeyup='aplicarDescuento(form1);' onkeydown='aplicarDescuento(form1);' value="0" step='0.01' required></td>
			<td><input type='number' style="background:#B0B4B3; width:120px;" name='efectivoRecibido' id='efectivoRecibido' readonly step="any" onChange='aplicarCambioEfectivo(form1);' onkeyup='aplicarCambioEfectivo(form1);' onkeydown='aplicarCambioEfectivo(form1);'></td>		
		</tr>
		<tr>
			<td align='right' width='90%' style="font-weight:bold;color:red;font-size:12px;">Descuento %</td><td><input type='number' name='descuentoVentaPorcentaje' id='descuentoVentaPorcentaje' style="height:20px;font-size:19px;width:120px;color:red;" onChange='aplicarDescuentoPorcentaje(form1);' onkeyup='aplicarDescuentoPorcentaje(form1);' onkeydown='aplicarDescuentoPorcentaje(form1);' value="0" step='0.01'></td>
			<td align='center' width='90%' style="color:#777B77;font-size:12px;"><b style="font-size:12px;color:#0691CD;">Cambio</b></td>
		</tr>
		<tr>
			<td align='right' width='90%' style="font-weight:bold;font-size:12px;color:red;">Monto Final</td><td><input type='number' name='totalFinal' id='totalFinal' readonly style="background:#0691CD;height:20px;font-size:19px;width:120px;;color:#fff;"></td>
			<td><input type='number' name='cambioEfectivo' id='cambioEfectivo' readonly style="background:#7BCDF0;height:20px;font-size:18px;width:120px;"></td>
		</tr>
	</table>
      
        </td>
        <td>
	<table id='' width='100%' border="0">
		<tr>
			<td align='right' width='90%' style="color:#777B77;font-size:12px;"></td><td align='center' colspan="2"><b style="font-size:20px;color:#189B22;">$ USD</b></td>
		</tr>
		<tr>
			<td align='right' width='90%' style="color:#777B77;font-size:12px;">Monto Nota</td>
			<td><input type='number' name='totalVentaUSD' id='totalVentaUSD' readonly style="background:#B0B4B3; width:120px;"></td>
			<td align='right' width='90%' style="color:#777B77;font-size:12px;"><b style="font-size:12px;color:#189B22;">Efectivo Recibido</b></td>
		</tr>
		<tr>
			<td align='right' width='90%' style="font-weight:bold;color:red;font-size:12px;">Descuento</td>
			<td><input type='number' name='descuentoVentaUSD' id='descuentoVentaUSD' style="height:20px;font-size:19px;width:120px;color:red;" onChange='aplicarDescuentoUSD(form1);' onkeyup='aplicarDescuentoUSD(form1);' onkeydown='aplicarDescuentoUSD(form1);' value="0" step='0.01' required></td>
			<td><input type='number' name='efectivoRecibidoUSD' id='efectivoRecibidoUSD' style="background:#B0B4B3; width:120px;" step="any" readonly onChange='aplicarCambioEfectivoUSD(form1);' onkeyup='aplicarCambioEfectivoUSD(form1);' onkeydown='aplicarCambioEfectivoUSD(form1);'></td>
		</tr>
		<tr>
			<td align='right' width='90%' style="font-weight:bold;color:red;font-size:12px;">Descuento %</td>
			<td><input type='number' name='descuentoVentaUSDPorcentaje' id='descuentoVentaUSDPorcentaje' style="height:20px;font-size:19px;width:120px;color:red;" onChange='aplicarDescuentoUSDPorcentaje(form1);' onkeyup='aplicarDescuentoUSDPorcentaje(form1);' onkeydown='aplicarDescuentoUSDPorcentaje(form1);' value="0" step='0.01'></td>
			<td align='right' width='90%' style="color:#777B77;font-size:12px;"><b style="font-size:12px;color:#189B22;">Cambio</b></td>
		</tr>
		<tr>
			<td align='right' width='90%' style="font-weight:bold;color:red;font-size:12px;">Monto Final</td>
			<td><input type='number' name='totalFinalUSD' id='totalFinalUSD' readonly style="background:#189B22;height:20px;font-size:19px;width:120px;color:#fff;"> </td>
			<td><input type='number' name='cambioEfectivoUSD' id='cambioEfectivoUSD' readonly style="background:#4EC156;height:20px;font-size:19px;width:120px;"></td>
		</tr>
	</table>
        </td>
      </tr>
	</table>


<?php
$banderaErrorFacturacion = 0;
if($banderaErrorFacturacion==0){
	echo "<div class='divBotones2' style='display:none;'>
	        <input type='submit' class='boton-verde' value='Guardar Venta' id='btsubmit' name='btsubmit' style='z-index:0;'>
			
		
            <table style='width:330px;padding:0 !important;margin:1 !important;bottom:25px;position:fixed;left:100px;'>
            <tr>
				<td style='font-size:12px;color:#0691CD; font-weight:bold;'>EFECTIVO Bs.</td>
				<td style='font-size:12px;color:#189B22; font-weight:bold;'>EFECTIVO $ USD</td>
				<td></td>
			</tr>
			<tr>
               	<td><input type='number' name='efectivoRecibidoUnido' onChange='aplicarMontoCombinadoEfectivo(form1);' onkeyup='aplicarMontoCombinadoEfectivo(form1);' onkeydown='aplicarMontoCombinadoEfectivo(form1);' id='efectivoRecibidoUnido' style='height:25px;font-size:18px;width:120px;' step='any'></td>
				<td><input type='number' name='efectivoRecibidoUnidoUSD' onChange='aplicarMontoCombinadoEfectivo(form1);' onkeyup='aplicarMontoCombinadoEfectivo(form1);' onkeydown='aplicarMontoCombinadoEfectivo(form1);' id='efectivoRecibidoUnidoUSD' style='height:25px;font-size:18px;width:120px;' step='any'></td>
				<td>
				<a href='#' class='btn btn-default btn-sm btn-fab' style='background:#FF0000' onclick='validarCotizacion(form1); return false;' id='boton_cotizacion' data-toggle='tooltip' data-original-title='Guardar como Cotización'><i class='material-icons'>file_download</i></a>
				</td>
             </tr>
            </table>

			";
	echo "</div>";	
}else{
	echo "";
}


?>

</div>

<input type='hidden' name='materialActivo' id='materialActivo' value="<?=$nro_materialActivo?>">
<input type='hidden' name='cantidad_material' id='cantidad_material' value="<?=$nro_materialActivo?>">
<script> 
	num = <?=$nro_materialActivo?>;
	cantidad_items = <?=$nro_materialActivo?>;
	totales();
</script>

</form>
<div class="modal fade modal-primary" id="modalNuevoCliente" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content card" style="background:#1F2E84 !important;color:#fff;">
            <div class="card-header card-header-warning card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">add</i>
                </div>
                <h4 class="card-title text-white font-weight-bold" id="titulo_cliente">Nuevo Cliente</h4>
                <button type="button" class="btn btn-danger btn-sm btn-fab float-right" data-dismiss="modal" aria-hidden="true" style="position:absolute;top:0px;right:0;">
                    <i class="material-icons">close</i>
                </button>
            </div>
            <div class="card-body">
                <div class="row">
                    <label class="col-sm-2 col-form-label text-white">Nombre (*)</label>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <input class="form-control" style="color:black;background: #fff;text-transform:uppercase;" type="text" id="nomcli" required value="<?php echo "$nomCliente"; ?>" placeholder="Nombre del Cliente" onkeyup="javascript:this.value=this.value.toUpperCase();"/>
                        </div>
                    </div>
                    <label class="col-sm-1 col-form-label text-white">Apellidos</label>
                    <div class="col-sm-5">
                        <div class="form-group">
                            <input class="form-control" style="color:black;background: #fff;text-transform:uppercase;" type="text" id="apcli" value="<?php echo "$apCliente"; ?>" required placeholder="Apellido(s) del Cliente" onkeyup="javascript:this.value=this.value.toUpperCase();"/>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 col-form-label text-white">Teléfono (*)</label>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <input class="form-control" style="color:black;background: #fff;" type="text" id="tel1" value="<?php echo "$telefono1"; ?>" required placeholder="Telefono/Celular"/>
                        </div>
                    </div>
                    <label class="col-sm-1 col-form-label text-white">Email (*)</label>
                    <div class="col-sm-5">
                        <div class="form-group">
                            <input class="form-control" style="color:black;background: #fff;" type="email" id="mail" value="<?php echo "$email"; ?>" required placeholder="cliente@correo.com"/>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 col-form-label text-white">CI</label>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <input class="form-control" style="color:black;background: #fff;" type="text" id="ci" value="<?php echo "$ciCliente"; ?>" required/>
                        </div>
                    </div>
                    <label class="col-sm-1 col-form-label text-white">NIT(*)</label>
                    <div class="col-sm-5">
                        <div class="form-group">
                            <input class="form-control" style="color:black;background: #fff;" type="text" id="nit" value="<?php echo "$nitCliente"; ?>" readonly/>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 col-form-label text-white">Razon Social ó Nombre Factura</label>
                    <div class="col-sm-10">
                        <div class="form-group">
                            <input class="form-control" style="color:black;background: #fff;" type="text" id="fact" value="<?php echo "$nomFactura"; ?>" required/>
                        </div>
                    </div>
                </div>
                <hr style="background: #FFD116;color:#FFD116;">
                <div class="row">
                    <label class="col-sm-2 col-form-label text-white">Dirección</label>
                    <div class="col-sm-10">
                        <div class="form-group">
                            <input class="form-control" style="color:black;background: #fff;" type="text" id="dir" value="<?php echo "$dirCliente"; ?>" required placeholder="Zona / Avenida-Calle / Puerta"/>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="area" id="area" value="1">
            </div>
            <div class="card-footer">
                <div class="">
                    <input class="btn btn-warning" id="boton_guardado_cliente" type="button" value="Guardar" onclick="javascript:adicionarCliente();" />
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Ver Productos de otras Sucursales -->
<div class="modal fade modal-primary" id="modalProductosCercanos" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content card">
                <div class="card-header card-header-primary card-header-icon">
                  <div class="card-icon">
                    <i class="material-icons">place</i>
                  </div>
                  <h4 class="card-title text-primary font-weight-bold">Stock de Productos en Sucursales</h4>
                  <button type="button" class="btn btn-danger btn-sm btn-fab float-right" data-dismiss="modal" aria-hidden="true" style="position:absolute;top:0px;right:0;">
                    <i class="material-icons">close</i>
                  </button>
                </div>
                <div class="card-body">
                	<div class="form-group">
												 <input type="text" class="form-control pull-right" style="width:20%" id="busqueda_sucursal" placeholder="Buscar Sucursal">
									</div>
									<br>
                  <table class="table table-sm table-bordered" id='tabla_sucursal'>
                    <thead>
                      <tr style='background: #ADADAD;color:#000;'>
                      	<th width='10%'>-</th>
                    	</tr>
                    </thead>
                    <tbody id="tabla_datosE">
                    </tbody>
                  </table>
                  <br><br>
                </div>
      </div>  
    </div>
  </div>
<script>
	$('body #tipoDoc_extra').trigger('change').val(<?=$tipoDocDefault?>);

	// Modificación de Sucursal en lista de ITEMS
	let fila_seleccionada = 0;
	$('body').on('click', '.nueva_sucursal', function(){
		let cod_sucursal = $(this).data('cod_sucursal');
		let nombre 		 = $(this).data('nombre');
		let stock 		 = $(this).data('stock');
		
		$('#cod_sucursales'+fila_seleccionada).val(cod_sucursal);
		$('#nombreSucursal'+fila_seleccionada).html(nombre);
		$('#stock'+fila_seleccionada).val(stock);
		$('#modalProductosCercanos').modal('toggle');
	});
</script>
</body>
</html>