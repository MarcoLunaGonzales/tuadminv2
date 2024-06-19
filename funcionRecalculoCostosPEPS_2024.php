<?php
require('function_formatofecha.php');
require('function_comparafechas.php');

function recalculaCostosPEPS2024($enlaceCon, $codigoItem, $rpt_almacen){
	
    $sqlFechaInicio="SELECT i.fecha from ingreso_almacenes i, ingreso_detalle_almacenes id
        where i.cod_ingreso_almacen=id.cod_ingreso_almacen and i.cod_almacen=1000 
        and i.ingreso_anulado=0 and id.cod_material='1380' and i.fecha<='2024-01-01'
        order by i.cod_ingreso_almacen desc limit 0,1";
    $respFechaInicio=mysqli_query($enlaceCon, $sqlFechaInicio);
    $fecha_iniconsulta="1995-01-01";    
    if($datFechaInicio=mysqli_fetch_array($respFechaInicio)){
        $fecha_iniconsulta=$datFechaInicio[0];
    }
    //$fecha_iniconsulta="1995-01-01";
	$fecha_finconsulta="2030-12-31";

    $banderaPEPS=1;
    $fechaInicioPEPS=$fecha_iniconsulta;


	if($codigoItem!=0 && $codigoItem!=""){
		
		$sqlMateriales="select m.`codigo_material`, m.`descripcion_material` from `material_apoyo` m where m.`codigo_material` in ($codigoItem)";
		//echo $sqlMateriales."<br>";
		$respMateriales=mysqli_query($enlaceCon, $sqlMateriales);
		while($datMateriales=mysqli_fetch_array($respMateriales)){
 
			$codigoItem=$datMateriales[0];
			$nombreItem=$datMateriales[1];
			
            $cantidad_inicial_kardex=0;
            $costoUnitarioAnteriorItem=0;
            $valorAnteriorItem=$costoUnitarioAnteriorItem*$cantidad_inicial_kardex;
            
            $nuevoCostoPromedio=$costoUnitarioAnteriorItem;

			$sql_fechas_ingresos="select distinct(i.fecha) from ingreso_almacenes i, ingreso_detalle_almacenes id
			where i.cod_ingreso_almacen=id.cod_ingreso_almacen and i.cod_almacen='$rpt_almacen' and
			i.ingreso_anulado=0 and id.cod_material='$codigoItem' and i.fecha>='$fecha_iniconsulta' and i.fecha<='$fecha_finconsulta' order by i.fecha";
			$resp_fechas_ingresos=mysqli_query($enlaceCon, $sql_fechas_ingresos);
			$i=1;
			while($dat_fechas_ingresos=mysqli_fetch_array($resp_fechas_ingresos))
			{	$vector_fechas_ingresos[$i]=$dat_fechas_ingresos[0];
				$i++;
			}
			$sql_fechas_salidas="select distinct(s.fecha) from salida_almacenes s, salida_detalle_almacenes sd
			where s.cod_salida_almacenes=sd.cod_salida_almacen and s.cod_almacen='$rpt_almacen' and
			s.salida_anulada=0 and sd.cod_material='$codigoItem' and s.fecha>='$fecha_iniconsulta' and s.fecha<='$fecha_finconsulta' order by s.fecha";
			$resp_fechas_salidas=mysqli_query($enlaceCon, $sql_fechas_salidas);
			$j=1;
			while($dat_fechas_salidas=mysqli_fetch_array($resp_fechas_salidas)){	
                $vector_fechas_salidas[$j]=$dat_fechas_salidas[0];
				$j++;
			}
			$i=$i-1;
			$j=$j-1;
			$ii=1;
			$jj=1;
			$zz=1;
			
			while($ii<=$i and $jj<=$j){	
                $fecha_ingresos=$vector_fechas_ingresos[$ii];
				$fecha_salidas=$vector_fechas_salidas[$jj];
				if(compara_fechas($fecha_ingresos,$fecha_salidas)<0)
				{	$vector_final_fechas[$zz]=$fecha_ingresos;
					$ii++;
				}
				if(compara_fechas($fecha_ingresos,$fecha_salidas)==0)
				{	$vector_final_fechas[$zz]=$fecha_ingresos;
					$ii++;
					$jj++;
				}
				if(compara_fechas($fecha_ingresos,$fecha_salidas)>0)
				{	$vector_final_fechas[$zz]=$fecha_salidas;
					$jj++;
				}

				$zz++;
			}
			if($ii==$i+1){	
                for($kk=$jj;$kk<=$j;$kk++)
				{	$vector_final_fechas[$zz]=$vector_fechas_salidas[$kk];
					$zz++;
				}
			}
			if($jj==$j+1)
			{	for($kk=$ii;$kk<=$i;$kk++)
				{	$vector_final_fechas[$zz]=$vector_fechas_ingresos[$kk];
					$zz++;
				}
			}


            $cantidad_kardex=$cantidad_inicial_kardex;
			$valor_kardex=$valorAnteriorItem;			
			$suma_ingresos=0;
			$suma_salidas=0;

		
			//ACA INICIA EL PROCESO DE COSTEO PROMEDIO POR DEFECTO Y PEPS SI HAY EL CASO			
			for($indice=1;$indice<=$zz;$indice++)
			{	$fecha_consulta=$vector_final_fechas[$indice];
				//hacemos la consulta para ingresos
				$sql_ingresos="select i.nro_correlativo, id.cantidad_unitaria, i.observaciones, ti.nombre_tipoingreso, 
				id.precio_neto, ti.cod_tipoingreso, i.cod_ingreso_almacen
				from ingreso_almacenes i, ingreso_detalle_almacenes id, tipos_ingreso ti
				where i.cod_tipoingreso=ti.cod_tipoingreso and i.cod_ingreso_almacen=id.cod_ingreso_almacen and i.cod_almacen='$rpt_almacen' and
				i.ingreso_anulado=0 and id.cod_material='$codigoItem' and i.fecha='$fecha_consulta'";
				$resp_ingresos=mysqli_query($enlaceCon, $sql_ingresos);
				while($dat_ingresos=mysqli_fetch_array($resp_ingresos))
				{	$nro_ingreso=$dat_ingresos[0];
					$cantidad_ingreso=$dat_ingresos[1];
					$obs_ingreso=$dat_ingresos[2];
					$nombre_ingreso=$dat_ingresos[3];
					$precioNetoIngreso=$dat_ingresos[4];
					$codTipoIngreso=$dat_ingresos[5];
					$codIngresoAlmacen=$dat_ingresos[6];
					
					$suma_ingresos=$suma_ingresos+$cantidad_ingreso;
					$cantidad_kardex=$cantidad_kardex+$cantidad_ingreso;
					
					//ESTA PARTE ES PARA EL PEPS
                    $sqlUpdCosto="update ingreso_detalle_almacenes set costo_promedio=precio_neto, costo_almacen=precio_neto where 
                        cod_ingreso_almacen='$codIngresoAlmacen' and cod_material='$codigoItem'";
                    $respUpdCosto=mysqli_query($enlaceCon, $sqlUpdCosto);	
                    $valorNetoIngreso=$precioNetoIngreso*$cantidad_ingreso;
                        //echo "entro peps<br>";
                    /*REVISAR PARA EL CASO DE LOS AJUSTES*/
					
					$valor_kardex=$valor_kardex+$valorNetoIngreso;
				}
				
				
				//hacemos la consulta para salidas
				$sql_salidas="select s.nro_correlativo, sd.cantidad_unitaria, ts.nombre_tiposalida, s.observaciones, 
					s.territorio_destino, s.cod_salida_almacenes, ts.cod_tiposalida, sd.cod_ingreso_almacen, sd.orden_detalle
				from salida_almacenes s, salida_detalle_almacenes sd, tipos_salida ts
				where s.cod_tiposalida=ts.cod_tiposalida and s.cod_salida_almacenes=sd.cod_salida_almacen and s.cod_almacen='$rpt_almacen' and
				s.salida_anulada=0 and sd.cod_material='$codigoItem' and s.fecha='$fecha_consulta'";
				$resp_salidas=mysqli_query($enlaceCon, $sql_salidas);
				while($dat_salidas=mysqli_fetch_array($resp_salidas))
				{	$nro_salida=$dat_salidas[0];
					$cantidad_salida=$dat_salidas[1];
					$nombre_salida=$dat_salidas[2];
					$obs_salida=$dat_salidas[3];
					$territorio_destino=$dat_salidas[4];
					$cod_salida=$dat_salidas[5];
					$codTipoSalida=$dat_salidas[6];
					$cantidad_kardex=$cantidad_kardex-$cantidad_salida;
					$suma_salidas=$suma_salidas+$cantidad_salida;
					$codIngresoOrigen=$dat_salidas[7];
					
					
					$valor_kardex=$valor_kardex-($cantidad_salida*$nuevoCostoPromedio);
					$costoPeps=0;

                    $sqlOrigen="select i.precio_neto from ingreso_detalle_almacenes i where i.cod_ingreso_almacen='$codIngresoOrigen' and i.cod_material='$codigoItem'";
                    $respOrigen=mysqli_query($enlaceCon, $sqlOrigen);
                    $costoPeps=0;
                    if($datOrigen=mysqli_fetch_array($respOrigen)){
                        $costoPeps=$datOrigen[0];
                    }
                    
                    $sqlUpd="update salida_detalle_almacenes set costo_almacen='$costoPeps' where 
                    cod_salida_almacen='$cod_salida' and cod_material='$codigoItem' and cod_ingreso_almacen='$codIngresoOrigen'"; 
                    $respUpd=mysqli_query($enlaceCon, $sqlUpd);                        
                        
                }
			}
			$suma_saldo_final=$suma_ingresos-$suma_salidas+$cantidad_inicial_kardex;	

		}
	}//FIN CODIGO 0 O VACIO

	return(1);
}

?>