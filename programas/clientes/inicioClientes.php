<html>
    <head>
        <title>Clientes</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" type="text/css" href="../../lib/css/paneles.css"/>
        <link rel="stylesheet" type="text/css" href="../../stilos.css"/>
        <script type="text/javascript" src="../../lib/externos/jquery/jquery-1.4.4.min.js"></script>
        <script type="text/javascript" src="../../lib/js/xlibPrototipo-v0.1.js"></script>
        <script type='text/javascript' language='javascript'>
/*proceso inicial*/
$(document).ready(function() {
    //
    listadoClientes();
    //
});
/*proceso inicial*/
function listadoClientes() {
    cargarPnl("#pnl00","prgListaClientes.php","");
}
//procesos
function frmAdicionar() {
    cargarPnl("#pnl00","frmClienteAdicionar.php","");
}
function frmModificar() {
    var total=$("#idtotal").val();
    var tag,sel,cod,c=0;
    for(var i=1;i<=total;i++) {
        tag=$("#idchk"+i);
        sel=tag.attr("checked");
        if(sel==true) {
            cod=tag.val(); c++;
        }
    }
    if(c==1) {
        cargarPnl("#pnl00","frmClienteEditar.php","codcli="+cod);
    } else if(c>1) {
        alert("Seleccione solo un elemento para editar.");
    } else {
        alert("Seleccione un elemento para editar.");
    }
}
function frmEliminar() {
    var total=$("#idtotal").val();
    var tag,sel,cods="0",c=0;
    for(var i=1;i<=total;i++) {
        tag=$("#idchk"+i);
        sel=tag.attr("checked");
        if(sel==true) {
            cods=cods+","+tag.val(); c++;
        }
    }
    if(c>0) {
        if(confirm("Esta seguro de eliminar "+c+" elemento(s) ?")) {
            eliminarCliente(cods);
        }
    } else {
        alert("Seleccione para eliminar.");
    }
}
function adicionarCliente() {
    var nomcli = $("#nomcli").val();
    var nit = $("#nit").val();
    var dir = $("#dir").val();
    var tel1 = $("#tel1").val();
    var mail = $("#mail").val();
    var area = $("#area").val();
    var fact = $("#fact").val();
    var apCli = $("#apCli").val();
    var diasCredito = $("#diasCredito").val();
    var tipo_precio = $("#tipo_precio").val();
    var parms="nomcli="+nomcli+"&nit="+nit+"&dir="+dir+"&tel1="+tel1+"&mail="+mail+"&area="+area+"&fact="+fact+"&apCli="+apCli+""+"&diasCredito="+diasCredito+""+"&tipo_precio="+tipo_precio+"";
    cargarPnl("#pnl00","prgClienteAdicionar.php",parms);
}
function modificarCliente() {
    var codcli = $("#codcli").text();
    var nomcli = $("#nomcli").val();
    var nit = $("#nit").val();
    var dir = $("#dir").val();
    var tel1 = $("#tel1").val();
    var mail = $("#mail").val();
    var area = $("#area").val();
    var fact = $("#fact").val();
    var apCli = $("#apCli").val();
    var diasCredito = $("#diasCredito").val();
    var tipo_precio = $("#tipo_precio").val();
    var parms="codcli="+codcli+"&nomcli="+nomcli+"&nit="+nit+"&dir="+dir+"&tel1="+tel1+"&mail="+mail+"&area="+area+"&fact="+fact+"&apCli="+apCli+""+"&diasCredito="+diasCredito+""+"&tipo_precio="+tipo_precio+"";
    cargarPnl("#pnl00","prgClienteModificar.php",parms);
}
function eliminarCliente(cods) {
    var codcli = cods;
    var parms="codcli="+codcli+"";
    cargarPnl("#pnl00","prgClienteEliminar.php",parms);
}

// EDITAR
document.addEventListener('click', function(event) {
    if (event.target.closest('.editarCliente')) {
        let button = event.target.closest('.editarCliente');
        let cod_cliente = button.dataset.cod_cliente;
        cargarPnl("#pnl00", "frmClienteEditar.php", "codcli=" + cod_cliente);
    }
});
// ELIMINAR
document.addEventListener('click', function(event) {
    if (event.target.closest('.eliminarCliente')) {
        let button = event.target.closest('.eliminarCliente');
        let cod_cliente = button.dataset.cod_cliente;
        cargarPnl("#pnl00", "prgClienteEliminar.php", "codcli=" + cod_cliente);
    }
});

function abrirModalFiltro(){
    $('#modalControlVersion').modal('show');
}
/*proceso inicial*/
function filtroCliente() {
    $('#modalControlVersion').one('hidden.bs.modal', function (e) {
        let nombre    = $('#fil_nombre').val();
        let nit       = $('#fil_nit').val();
        let direccion = $('#fil_direccion').val();
        cargarPnl("#pnl00", `prgListaClientes.php?fil_nombre=${nombre}&fil_nit=${nit}&fil_direccion=${direccion}`, "");
    });

    $('#modalControlVersion').modal('hide');
}
        </script>
    </head>
    <body>
        <div id='pnl00'></div>
        <div id='pnldlgfrm'></div>
        <div id='pnldlggeneral'></div>
        <div id='pnldlgenespera'></div>

    </body>
</html>

<?php

?>
