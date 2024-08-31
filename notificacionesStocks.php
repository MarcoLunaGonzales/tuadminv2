<?php

require_once 'conexionmysqli.inc';
require("estilos.inc");
?>
<div class="card">
    <div class="card-header card-header-icon text-center">
        <h4 class="card-title"><b>Notificación de Inventario</b></h4>
    </div>
    <div class="card-body text-center">
        <p class="mb-0"><strong>Atención:</strong> Algunos productos están por debajo del nivel mínimo de stock. Por favor, revisa y actualiza el inventario para evitar posibles desabastecimientos.</p>
    </div>
    <div class="text-center mb-2">
        <a href="/ruta-al-reporte" class="btn btn-warning btn-sm">Ver Reporte</a>
    </div>
</div>
