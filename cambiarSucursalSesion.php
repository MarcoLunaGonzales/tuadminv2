
<?php
require_once 'conexionmysqli.inc';
require("estilos_almacenes.inc");


echo "<script language='JavaScript'>
function nuevoAjax()
{ var xmlhttp=false;
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
function ajaxAlmacen(){
  var contenedor;
  contenedor = document.getElementById('divAlmacen');
  var codTerritorio = document.getElementById('cod_ciudad').value;
  ajax=nuevoAjax();
  ajax.open('GET', 'ajaxAlmacenes.php?codTerritorio='+codTerritorio,true);
  ajax.onreadystatechange=function() {
    if (ajax.readyState==4) {
      contenedor.innerHTML = ajax.responseText;
    }else{
      contenedor.innerHTML = 'Cargando...';
    }
  }
  ajax.send(null)
}
    </script>";
/* error_reporting(E_ALL);
 ini_set('display_errors', '1');
*/

$globalTipo=$_COOKIE['global_tipo_almacen'];
$global_agencia=$_COOKIE['global_agencia'];
?>

              <form id="form1" class="form-horizontal" action="saveSucursalSesion.php" method="post">
              <div class="card">
                <div class="card-header card-header-icon">
                  <CENTER><h4 class="card-title"><b>Cambiar Sucursal y Almacen</b></h4></CENTER>
                </div>
                
                <div class="card-body">
                  <div class="">
                    <table class="texto table-striped" width="30%" align="center">
                      <thead>
                        <tr>
                          <th>Sucursal</th>
                          <th>Almacen</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td align="center">                            
                               <select name="cod_ciudad" id="cod_ciudad" class="selectpicker" data-style="btn btn-info" data-show-subtext="true" data-live-search="true"  onChange="ajaxAlmacen(this);" required>

                              <option value="">--SELECCIONE UNA SUCURSAL--</option>
                              <?php
                               $sql="select cod_ciudad,descripcion from ciudades order by 1";
                               $resp=mysqli_query($enlaceCon,$sql);
                               while($dat=mysqli_fetch_array($resp)){
                                 $codigo=$dat[0];
                                 $nombre=$dat[1];
                                 echo "<option value='$codigo'>$nombre</option>";
                               }

                              
                                ?>
                            </select>
                          </td>
                          <td>
                            <div id='divAlmacen'>
                              <select name='rpt_almacen' class='texto' id='rpt_almacen' required>
                                <option value=''>Seleccionar Almacen</option>
                              </select>
                            </div>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
			  <br>
              <div class="divBotones">
                    <button type="submit" class="boton">Guardar</button>
              </div>
               </form>
<?php

?>

