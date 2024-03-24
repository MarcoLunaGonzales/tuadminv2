<?php 
session_start();
require("../conexionmysqli.php");
require("../funciones.php");
require("../estilos.inc");

setlocale(LC_TIME, "Spanish");
$anio=date("Y");
$ms=date("m");
$dia=date("d");
$porciones = explode("-", $_GET['fecha']);
$df=date("d",(mktime(0,0,0,$porciones[1]+1,1,$porciones[0])-1));
$fechaActual=$_GET['fecha']."-".$df;
$mes=strToUpper(strftime('%B',strtotime($fechaActual)));
$d=strftime('%d',strtotime($fechaActual));
$m=strftime('%m',strtotime($fechaActual));
$y=strftime('%Y',strtotime($fechaActual));

$fechaInicio=$y."-".$m."-01";
$du=date("d",(mktime(0,0,0,$m+1,1,$y)-1));

if(isset($_GET['codigo'])){
	$globalCode=$_GET['codigo'];
}else{
	$globalCode=0;
}


$resp = obtenerMoneda($_GET['codigo']);
$codigo 	 = '';
$abreviatura = '';
$nombre 	 = '';
$estado 	 = '';
while ($row = mysqli_fetch_array($resp)) {
	$codigo 	 = $row['codigo'];
	$abreviatura = $row['abreviatura'];
	$nombre 	 = $row['nombre'];
	$estado 	 = $row['estado'];
}

?>                   
		                    <div class="row">
		                    	<div class="col-sm-6">
		                    		<label class="text-muted"><small>(Tabla de <b>VALORES REGISTRADOS</b> mes de <?=$mes?> - gestion <?=$y?>)</small></label>
		                    		<table class="table table-striped table-success">
		                    	 <?php
						$tipoCambio=obtenerTipoCambio($globalCode,$fechaInicio,$fechaActual);
						$idFila=1;$index=0;$dias=[];
						           ?>
                                    <thead>
                                    	<tr class="bg-dark text-white">
                                    	   <th>#</th>
                                           <th>Fecha</th>
                                           <th>Valor Bs.</th>
                                           <th>Moneda</th>
                                    	</tr>
                                    </thead>
                                    <tbody>
						           <?php
						while ($row = mysqli_fetch_array($tipoCambio)) {
							$dias[$index]=strftime('%d',strtotime($row['fecha']));
							?>
							<?php
							$codigo=$row['id'];
							$codMoneda=$row['cod_moneda'];
							$fecha=$row['fecha'];
							$valor=$row['valor'];
							?>
                             <tr><td><?=$idFila?></td><td><?=$fecha?></td><td><?=$valor?></td><td><?=$abreviatura?></td></tr>
							<?php
                           $idFila++;
                           $index++;
                         }
						 ?>	      </tbody>
		                    	</table>
		                    	</div>
		                    	<div class="col-sm-6">
		                    		<label class="text-muted"><small>(Tabla de <b>NUEVOS REGISTROS</b> mes de <?=$mes?> - gestion <?=$y?>)</small></label>
		                    		<table class="table table-sm">
                                    <thead>
                                    	<tr class="bg-dark text-white">
                                    	   <th>#</th>
                                           <th>Fecha</th>
                                           <th>Valor Bs.</th>
                                           <th>Moneda</th>
                                    	</tr>
                                    </thead>
                                    <tbody>
						           <?php
						           $idFila=1;
                                    //$dias=(int)$du-$idFila;
                                    $dd=(int)$idFila;$count=1;
                                    for ($i=0; $i < (int)$du; $i++) {
                                    	$existe=0;
                                    	 for ($j=0; $j < $index; $j++) { 
                                    	 	if(($i+1)==(int)$dias[$j]){
                                    	 		$existe=1;
                         	                 }
                                    	 }
                                    	 if($existe==0){
                                    	 	if($idFila<10){$ddi="0".$idFila;}else{$ddi=$idFila;}
                                    	 	if((int)$ddi==(int)$dia&&(int)$y==(int)$anio&&(int)$m==(int)$ms){$estilo="bg-warning";}else{$estilo="";}
                                    	 	?>
                                        <tr class="<?=$estilo?>"><td><?=$count?></td><td><input type="text" readonly value="<?=$y?>-<?=$m?>-<?=$ddi?>" class="form-control" id="fecha<?=$count?>" name="fecha<?=$count?>"></td><td><input type="number" class="form-control" id="valor<?=$count?>" placeholder="ingrese un valor" name="valor<?=$count?>" step="0.0000001"></td><td><?=$abreviatura?></td></tr>
							            <?php
                                         $count++;
							             }
							            $idFila++;
                                      }
						             ?>				    
						           </tbody>
		                    	</table>
		                    	</div>
		                    </div>
		                   <input type="text" name="cantidad_filas" id="cantidad_filas" value="<?=$count?>">