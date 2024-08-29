<html>
    <head>
        <title>Vehiculos</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <script type="text/javascript" src="lib/js/xlibPrototipoSimple-v0.1.js"></script>
<?php

require("../conexion.inc");
require("../estilos_almacenes.inc");

$cod_transportadora = $_GET['cod_transportadora'] ?? '';

echo "<body>";
?>
<div>

	<div class="container mt-4">
        <!-- Botón de Agregar -->
        <div class="d-flex justify-content-between mb-2">
            <h4>Lista de Vehiculos</h4>
            <div class="text-end">
                <button class="btn btn-danger" type="button" onclick="window.location.href='../transportadoras/generalLista.php'">
                    <i class="material-icons" style="font-size: 16px">arrow_back</i> Volver
                </button>
                <button class="btn btn-primary" type="button" id="agregarRegistro">
                    <i class="material-icons" style="font-size: 16px">add</i> Agregar
                </button>
            </div>
        </div>
        
        <input type="hidden" id="cod_transportadora" value="<?=$cod_transportadora?>">
        <!-- Tabla -->
        <table class="table table-striped">
            <thead class="table-dark">
                <tr>
                    <th width="10%">#</th>
                    <th>Nombre</th>
                    <th>Placa</th>
                    <th width="10%">Estado</th>
                    <th width="10%">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $sql = "SELECT v.codigo, v.placa, v.nombre, v.estado
                            FROM vehiculos v
                            WHERE v.cod_transportadora = '$cod_transportadora'
                            ORDER BY v.codigo DESC";
                    $resp = mysqli_query($enlaceCon, $sql);
                    
                    while ($row = mysqli_fetch_assoc($resp)) {
                ?>
                <tr>
                    <td><?= $row['codigo'] ?></td>
                    <td><?= $row['nombre'] ?></td>
                    <td><?= $row['placa'] ?></td>
                    <td>
                        <?php
                            if($row['estado'] == 1){
                        ?>
                        <span class="badge bg-success">Activo</span>
                        <?php
                            }else{
                        ?>
                        <span class="badge bg-danger">Inactivo</span>
                        <?php
                            }
                        ?>
                    </td>
                    <td>
                        <!-- Editar -->
                        <button class="btn btn-sm btn-info pl-2 pt-3 pr-2 pb-1 editVehiculo" 
                                data-codigo="<?=$row['codigo']?>"
                                data-nombre="<?=$row['nombre']?>"
                                data-placa="<?=$row['placa']?>">
                            <i class="material-icons" style="font-size: 16px">edit</i>
                        </button>
                        <?php
                            if($row['estado'] == 1){
                        ?>
                        <button class="btn btn-sm btn-danger pl-2 pt-3 pr-2 pb-1 modificarEstado" 
                                data-codigo="<?=$row['codigo']?>"
                                title="Modificar Estado">
                            <i class="material-icons" style="font-size: 16px">close</i>
                        </button>
                        <?php
                            }else{
                        ?>
                        <button class="btn btn-sm btn-success pl-2 pt-3 pr-2 pb-1 modificarEstado" 
                                data-codigo="<?=$row['codigo']?>"
                                title="Modificar Estado">
                            <i class="material-icons" style="font-size: 16px">check</i>
                        </button>
                        <?php
                            }
                        ?>
                    </td>
                </tr>
                <?php
                    }
                ?>
            </tbody>
        </table>
        
		<!-- MODAL TRANSPORTADORA -->
		<div class="modal fade" id="transportadoraModal" tabindex="-1" role="dialog" aria-labelledby="modalVehiculo" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="modalLabel"><b>Registro de Vehiculo</b></h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<form id="formularioRegistro" method="GET">
                            <input type="hidden" id="codigo">
							<div class="row pb-2">
								<label class="col-sm-3 elegant-label"><span class="text-danger">*</span> Nombre:</label>
								<div class="col-sm-9">
									<input class="elegant-input" type="text" id="tr_nombre" v-model="tr_nombre" placeholder="Ingresar nombre de transportadora"/>
								</div>
							</div>
							<div class="row pb-2">
								<label class="col-sm-3 elegant-label"><span class="text-danger">*</span> Placa:</label>
								<div class="col-sm-9">
									<input class="elegant-input" type="text" id="tr_placa" v-model="tr_placa" placeholder="Ingresar Nro.. de Placa"/>
								</div>
							</div>
							<div class="modal-footer p-0 pt-2">
								<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
								<button type="button" class="btn btn-primary" id="guardarVehiculo">Guardar</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>


	</div>
</div>

    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script>
    $(document).ready(function() {

        // Abrir el modal para agregar una nueva transportadora
        $('#agregarRegistro').on('click', function() {
            $('#codigo').val(0);
            $('#tr_nombre').val('');
            $('#tr_placa').val('');
            $('#modalLabel').text('Agregar Vehiculo');
            $('#transportadoraModal').modal('show');
        });

        /**********************************************************
         * Enviar el formulario para agregar/editar transportadora
         **********************************************************/
        $('body').on('click', '#guardarVehiculo', function(e) {
            let codigo = $('#codigo').val();
            let nombre = $('#tr_nombre').val();
            let placa  = $('#tr_placa').val();
            let cod_transportadora = $('#cod_transportadora').val();

            if(codigo > 0) {
                // Editar transportadora
                const formData = new FormData();
                formData.append('codigo', codigo);
                formData.append('nombre', nombre);
                formData.append('placa', placa);
                axios.post('actualizar.php', formData, {
                    headers: { 'Content-Type': 'multipart/form-data' }
                })
                .then(response => {
                    if (response.data.success) {
                        Swal.fire({
                            type: 'success',
                            title: 'Éxito',
                            text: response.data.message,
                        }).then((result) => {
                            location.reload();
                        });
                        $('#transportadoraModal').modal('hide');
                    } else {
                        Swal.fire({
                            type: 'warning',
                            title: 'Ops!',
                            text: response.data.message
                        });
                    }
                })
                .catch(error => {
                    console.error('Error al guardar el transportadora:', error);
                });
            } else {
                // Agregar nueva transportadora
                const formData = new FormData();
                formData.append('cod_transportadora', cod_transportadora);
                formData.append('nombre', nombre);
                formData.append('placa', placa);
                axios.post('registrar.php', formData, {
                    headers: { 'Content-Type': 'multipart/form-data' }
                })
                .then(response => {
                    if (response.data.success) {
                        Swal.fire({
                            type: 'success',
                            title: 'Éxito',
                            text: response.data.message,
                        }).then((result) => {
                            location.reload();
                        });
                        $('#transportadoraModal').modal('hide');
                    } else {
                        Swal.fire({
                            type: 'warning',
                            title: 'Ops!',
                            text: response.data.message
                        });
                    }
                })
                .catch(error => {
                    console.error('Error al guardar el transportadora:', error);
                });
            }
        });

        /**
         * Modal para Editar
         **/
        $(document).on('click', '.editVehiculo', function() {
            let codigo  = $(this).data('codigo');
            let nombre  = $(this).data('nombre');
            let placa   = $(this).data('placa');
            $('#codigo').val(codigo);
            $('#tr_nombre').val(nombre);
            $('#tr_placa').val(placa);
            $('#modalLabel').text('Editar Vehiculo');
            $('#transportadoraModal').modal('show');
        });

        // Eliminar transportadora
        $(document).on('click', '.modificarEstado', function() {
            let codigo = $(this).data('codigo');

            Swal.fire({
                title: '¿Estás seguro?',
                text: "Se modificará el estado del registro seleccionado",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí',
                cancelButtonText: 'No'
            }).then((result) => {
                if (result.value) {
                    const formData = new FormData();
                    formData.append('codigo', codigo);
                    axios.post('actualizarEstado.php', formData, {
                        headers: { 'Content-Type': 'multipart/form-data' }
                    })
                    .then(response => {
                        if (response.data.success) {
                            Swal.fire({
                                type: 'success',
                                title: 'Éxito',
                                text: response.data.message,
                            }).then((result) => {
                                location.reload();
                            });
                            $('#transportadoraModal').modal('hide');
                        } else {
                            Swal.fire({
                                type: 'warning',
                                title: 'Ops!',
                                text: response.data.message
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error al guardar el transportadora:', error);
                    });
                }
            })
        });

        // Función para cargar la lista de transportadoras
        function loadVehiculos() {
            $.get('listar_transportadoras.php', function(data) {
                $('#transportadoraList').html(data);
            });
        }
    });
    </script>
	<style>
		/**
		 * ESTILO DE FORMULARIO
		 **/
		.elegant-label {
            font-family: 'Poppins', sans-serif;
			font-weight: bold;
			color: #6c757d;
			display: flex;
			align-items: center;
			margin-bottom: 0;
		}

		.elegant-label span.text-danger {
			margin-right: 5px;
		}
		.elegant-input {
			width: 100%;
			border: 2px solid #ced4da;
			border-radius: 5px;
			padding: 5px 5px;
			transition: all 0.3s ease;
		}

		.elegant-input:focus {
			border-color: #80bdff;
			box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
		}

		.elegant-input::placeholder {
			color: #999;
			opacity: 1;
		}
	</style>

</body>