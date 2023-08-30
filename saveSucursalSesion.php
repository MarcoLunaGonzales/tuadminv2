<?php
$estilosVenta=1; //para no ejecutar las librerias js css
require("funciones.php");
require("conexionmysqli.inc");

$cod_ciudad=$_POST['cod_ciudad'];
$codAlmacen=$_POST['rpt_almacen'];

   setcookie("global_agencia",$cod_ciudad);
   setcookie("global_almacen",$codAlmacen);
   
   if(isset($_POST["url"])){
   	$url=$_POST["url"];
    ?>
   <script type="text/javascript">window.location.href='<?=$url?>';</script>
   <?php
   }else{
   ?>
   <script type="text/javascript">parent.window.location.href=window.parent.location;</script>
   <?php	
   }


