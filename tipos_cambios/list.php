<?php
    require("../conexionmysqli.php");
    require("../funciones.php");
	require("../estilos.inc");

$fechaActual=date("Y-m-d");
setlocale(LC_TIME, "Spanish");
?>

<div class="content">
	<div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
              <div class="card">
                <div class="card-header card-header-primary card-header-icon">
                  <div class="card-icon">
                    <i class="material-icons">assignment</i>
                  </div>
                  <h4 class="card-title">Tipo de Cambio</h4>
                </div>
                <div class="card-body">
                  <center><h4 class="text-warning font-weight-bold"><?=strftime('%d de %B del %Y',strtotime($fechaActual))?></h4></center>
                  <div class="table-responsive" id="data_comprobantes">
                    <table id="tablePaginator" class="table table-condensed">
                      <thead>
                        <tr style="background:#732590;color:white;">
                          <th class="text-center">#</th>
                          <th class="text-left">Nombre</th>
                          <th>Abrev</th>
                          <th>Tipo de Cambio (Hoy)</th>
                          <th>Estado</th>
                          <th class="text-right">Actions</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                            $index=1;
                            $cont=0;
                            $sql  = "SELECT codigo,nombre,abreviatura,estado from monedas";
                            $resp = mysqli_query($enlaceCon, $sql);
                            
                            while ($data = mysqli_fetch_array($resp)) {
                                // Asignación de valores a variables
                                $codigo      = $data['codigo'];
                                $nombre      = $data['nombre'];
                                $abreviatura = $data['abreviatura'];
                                $estado      = $data['estado'];
                                if($codigo!=0){
                                    $valorTipo=obtenerValorTipoCambio($codigo,$fechaActual);
                                    if($valorTipo==0){
                                        $valor="Sin registro actual"; 
                                        $estiloTipo="text-danger";
                                        $cont++;
                                        $html='<input type="hidden" id="codigo'.$cont.'" value="'.$codigo.'"><input type="number" id="valor'.$cont.'" name="valor'.$cont.'" step="0.0001" placeholder="Ingrese el valor de '.$abreviatura.' en Bs." class="form-control">';
                                    }else{
                                        $valor=$valorTipo;
                                        $estiloTipo="text-success";
                                        $html='';
                                    }
                        ?>
                            <tr>
                                <td align="center"><?=$index;?></td>
                                <td class="text-left"><?=$nombre;?></td>
                                <td class="font-weight-bold"><?=$abreviatura;?></td>
                                <td class="<?=$estiloTipo?>"><b><?=$valor;?></b><br><?=$html?></td>
                                <td>
                                    <?=$estado== 1 ? 'Activo' : ' Inactivo'?>                 
                                </td>
                                <td class="td-actions text-right">
                                    <a href='historial.php?codigo=<?=$codigo;?>' title="historial" class="button">
                                    <i class="material-icons">history</i>
                                    </a>
                                    <button type="button" class="btn btn-danger" onclick="alerts.showSwal('warning-message-and-confirmation','deleteTipoCambioHoy.php?codigo=<?=$codigo;?>')" title="borrar">
                                        <i class="material-icons" style="margin:1px;">delete</i>
                                    </button>

                                </td>
                            </tr>
                            <?php
                                        $index++;
                                    }
                                }
                            ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
              <?php 
              if($cont>0){
      				?><div class="card-footer fixed-bottom">
                <input type="hidden" value="<?=$cont?>" id="numeroMoneda">
                <a href="#" onclick="guardarValoresMoneda();" class="btn btn-info">Guardar Valores</a>
              </div><?php		  
              }
              ?>
            </div>
          </div>  
        </div>
    </div>
