function cambiarDatosProductosTable(valor){
	$("#mensaje_input_codigo_barras").html("");
  var parametros={"codigo":valor};
      $.ajax({
        type: "GET",
        dataType: 'html',
        url: "ajaxSetProductoCodigoBarras.php",
        data: parametros,
        success:  function (respuesta) {
        	var resp=respuesta.split('#####');
        	if(resp[0].trim()=="0"){
               $("#mensaje_input_codigo_barras").html("No se encontró el código de barras: "+ valor);
               $("#input_codigo_barras").val("");
        	}else{
        	  if($("#divItemReporte").length>0){ //para el reporte 
        	  	$('select[name=rpt_grupo]').val(resp[5]);
        	  	//ajaxReporteItems();
                $('select[name=rpt_item]').val(resp[1]);
        	  }else{
                if($("#itemNombreBusqueda").length>0){
                	$("#btnBusqueda").click();
                }else{
        	   //para los formularios de ventas, ingresos o salidas
        		var existeCodigo=0;var filaEncontrado=0;
        		var numRegistro=$('input[name=materialActivo]').val();
	            for (var i = 1; i <= numRegistro; i++) {
	            	if($("#material"+i).length>0){
	            		if($("#material"+i).val()==resp[1]){
                           existeCodigo++; 
                           filaEncontrado=i;
	            		}
	            	}else{
	            	  if($("#materiales"+i).length>0){ //para ventas
	            		if($("#materiales"+i).val()==resp[1]){
                           existeCodigo++; 
                           filaEncontrado=i;
	            		}
	            	 }	
	            	}
	            };
	            if(existeCodigo==0){
	              if($("#ventas_codigo").length>0){
                    soloMasVentas(resp);	//para Ventas
	              }else{
	              	if($("#tipoSalida").length>0){ //para salida
                      soloMasSalida(resp);	//para Ingresos
	              	}else{
	              	  soloMas(resp);	//para Ingresos
	              	}
	                
	              }	
	              $("#mensaje_input_codigo_barras").html("Encontrado "+resp[2]+", código de barras: "+ valor);	 
	            }else{
	            	var cantidadAnterior=parseInt($("#cantidad_unitaria"+filaEncontrado).val());
	            	if($("#cantidad_unitaria"+filaEncontrado).val()==""){
                        cantidadAnterior=1; 
	            	}
	            	$("#cantidad_unitaria"+filaEncontrado).val(cantidadAnterior+1);
	            	if($("#ventas_codigo").length>0){
                      calculaMontoMaterial(filaEncontrado);
	            	}else{
	            		if($("#tipoSalida").length>0){
                          //sin funciones 
	            		}else{
	            	      cambiaCosto(document.getElementsByName('form1'),filaEncontrado);			
	            		}	            	  
	            	}
	            	
	            	$("#mensaje_input_codigo_barras").html(resp[2]+" + 1 :"+ valor);	 
	            }
	           }//fin else para registro kardex
	          }//fin de para salidas ingresos o ventas 
               $("#input_codigo_barras").val("");      
        	}
        }
      });   
 }		

function soloMasVentas(obj){
	if(num>=1000){
		alert("No puede registrar mas de 1000 items en una nota.");
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
			$('input[name=materialActivo]').val(num);
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
			var cod_precio=1;			
			ajax=nuevoAjax();
			ajax.open("GET","ajaxMaterialVentas.php?codigo="+num+"&cod_precio="+cod_precio,true);
			ajax.onreadystatechange=function(){
				if (ajax.readyState==4) {
					div_material.innerHTML=ajax.responseText;
					setMaterialesSoloVentas(obj[1],obj[2]+"(<small>"+obj[6]+" "+obj[8]+" "+obj[7]+"</small>)");
				}
			}		
			ajax.send(null);
		}

	}
}
function soloMas(obj) {
	    	num++;
	    	$('input[name=materialActivo]').val(num);
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
					setMaterialesSolo(obj[1],obj[2],obj[3],obj[4]);
				}
			}		
			ajax.send(null);
		
	}
function soloMasSalida(obj) {
	if(num>=1000){
		alert("No puede registrar mas de 1000 items en una nota.");
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
			$('input[name=materialActivo]').val(num);
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
			ajax.open("GET","ajaxMaterialSalida.php?codigo="+num,true);
			ajax.onreadystatechange=function(){
				if (ajax.readyState==4) {
					div_material.innerHTML=ajax.responseText;
					setMaterialesSoloSalidas(obj[1],obj[2]);
				}
			}		
			ajax.send(null);
		}

	}
	
}		

function setMaterialesSoloSalidas(cod, nombreMat){
	var numRegistro=$('input[name=materialActivo]').val();
	$('#materiales'+numRegistro).val(cod);
	$('#cod_material'+numRegistro).html(nombreMat);
	$("#input_codigo_barras").focus();
	actStock(numRegistro);
    $("#fiel").animate({ scrollTop: $("#fiel")[0].scrollHeight}, 1000);
	$("#fiel").scrollTop( $("#fiel").prop('scrollHeight') );
	
}


function setMaterialesSolo(cod, nombreMat, cantidadPresentacion,costoItem){	
	var numRegistro=$('input[name=materialActivo]').val();
	console.log(numRegistro);
	$('#material'+numRegistro).val(cod);
	$('#cod_material'+numRegistro).html(nombreMat);
	$('#ultimoCosto'+numRegistro).val(costoItem);
	$('#divUltimoCosto'+numRegistro).html("["+costoItem+"]");
	$("#input_codigo_barras").focus();	
    
    $("#fiel").animate({ scrollTop: $("#fiel")[0].scrollHeight}, 1000);
	$("#fiel").scrollTop( $("#fiel").prop('scrollHeight') );

}

function setMaterialesSoloVentas(cod, nombreMat){
	var numRegistro=$('input[name=materialActivo]').val();
	console.log("fila:"+numRegistro);
	$('#materiales'+numRegistro).val(cod);
	$('#cod_material'+numRegistro).html(nombreMat);
	$("#input_codigo_barras").focus();
	actStock(numRegistro);
	$("#fiel").animate({ scrollTop: $("#fiel")[0].scrollHeight}, 1000);
	$("#fiel").scrollTop( $("#fiel").prop('scrollHeight') );
}

$(document).ready(function() {
	if($("#input_codigo_barras").length>0){
	 $("#input_codigo_barras").focus();
     $("#input_codigo_barras").after("<div id='mensaje_input_codigo_barras' class='mensaje-codigo-barras'></div>");
     $("#input_codigo_barras").keypress(function(e) {
	 if(e.which == 13) {  	
       	var valorInput=$(this).val();
        cambiarDatosProductosTable(valorInput); 
        return false;
       }
     });
	}	
});



function mostrarArchivoCambios(filename,idname){
  $("#label_txt_"+idname).html(filename);
  if(filename.length>28){
    $("#label_txt_"+idname).html(filename.substr(0,28)+"...");
  }  
   $("#label_"+idname).attr("title",filename);
   if(filename==""||filename==null){
    $("#label_"+idname).html('<i class="fa fa-upload"></i> SUBIR DATOS EXCEL');
    $("#label_"+idname).removeClass('boton-azul');
    if(!$("#label_"+idname).hasClass("boton-verde")){
      $("#label_"+idname).addClass('boton-verde');//cambiar estilo
    } 
    if($(".confirmar_archivo").length>0){
      if(!$(".confirmar_archivo").hasClass("d-none")){
    	$(".confirmar_archivo").addClass("d-none")
      }	
    } 
   }else{
    $("#label_"+idname).html('<i class="fa fa-check"></i> Cambiar');
    $("#label_"+idname).removeClass('boton-verde');
    if(!($("#label_"+idname).hasClass("boton-azul"))){
      $("#label_"+idname).addClass('boton-azul');//cambiar estilo
    }

    if($(".confirmar_archivo").length>0){
      if($(".confirmar_archivo").hasClass("d-none")){
    	$(".confirmar_archivo").removeClass("d-none")
      }	
    }
   }
}

$(document).ready(function() {
  $(".archivo").change(function(e) {
     var filename = $(this).val().split('\\').pop();
     var idname = $(this).attr('id');
     mostrarArchivoCambios(filename,idname);
   });
  
  /*$("#guarda_ingresomateriales").submit(function( event ) {
  	if($("#tipo_submit").val()==1){
  	  cargarDatosExcelIngresos();	
  	}    
  });*/
});

function cargarSubmitArchivo(valor){
  $("#tipo_submit").val(valor);
}

function cargarDatosExcelIngresos(){
	swal({
        title: '¿Esta Seguro?',
        text: "Se guardarán los datos",
         type: 'warning',
        showCancelButton: true,
        confirmButtonClass: 'boton-azul',
        cancelButtonClass: 'boton-rojo',
        confirmButtonText: 'GUARDAR',
        cancelButtonText: 'CANCELAR',
        buttonsStyling: false
       }).then((result) => {
          if (result.value) {
              cargarDatosExcelIngresosSave();            
            return(true);
          } else if (result.dismiss === 'cancel') {
            return(false);
          }
        });
}

function cargarDatosExcelIngresosSave(){
  $("#tipo_submit").val(1);
}

function cambiarNotaRemision(){
	if($("#boton_nota_remision").length>0){
		var tipo=$("body #tipoDoc").val();


        if (tipo == 2) {
            $("#tipoDoc, #tipoDoc_extra").val(1);
            $("#boton_nota_remision").addClass("boton-plomo-osc").removeClass("boton-plomo");
        } else {
            $("#tipoDoc, #tipoDoc_extra").val(2);
            $("#boton_nota_remision").addClass("boton-plomo").removeClass("boton-plomo-osc");
        }

		$('body #tipoDoc_extra').trigger('change').val(2);

		$("#nitCliente").val(0);
		$("#razonSocial").val(0);
		ajaxNroDoc(form1);
	}
}

function totalesTablaVertical(tabla,columna,fila){
   var main=document.getElementById(tabla);   
   var numFilas=main.rows.length;
   var numCols=main.rows[1].cells.length; 
   for(var j=columna; j<=numCols-1; j++){
    var subtotal=0;
      for(var i=fila; i<=numFilas-2; i++){
            var datoS=main.rows[i].cells[j].innerHTML;
            datoS=datoS.trim();   
            console.log(datoS+" "+typeof(datoS));
            if(datoS=="-"){
              datoS="0";
            }
            datoS=datoS.replace(/,/g,'');
            console.log(datoS);
            var dato=parseFloat(datoS);
            //console.log(dato);
            subtotal=subtotal+dato;
            var subtotalF=number_format(subtotal,2); 
            console.log("subtotal: "+subtotal);
      }
      var html='<th style="text-align:right;">'+subtotalF+'</th>';
      $("tfoot tr").append(html);   
  }
  $("tfoot tr").prepend("<th colspan='"+columna+"'>Totales</th>");   
}