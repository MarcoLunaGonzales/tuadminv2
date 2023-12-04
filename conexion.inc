<?php

require_once 'config.php';

if(!isset($_COOKIE["global_usuario"])){
?>

	<script type="text/javascript">
    	alert("Debe iniciar Sesion!!!")
      		location.href="index.html";
    </script>
<?php

}

if(!function_exists('register_globals')){
	include('register_globals.php');
	register_globals();
}else{
}

//$conexion=mysql_connect("www.minkasoftware.com","minkauserbd","4868422Marco$");
//$bd=mysql_select_db("tuadmintecniplast");
$conexion=mysql_connect(DATABASE_HOST,DATABASE_USER,DATABASE_PASSWORD);
$bd=mysql_select_db(DATABASE_NAME);

?>
