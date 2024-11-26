<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Reservas</title>
    <!-- CSS de DataTables y jQuery -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/public/css/admin/estilos.css">
    <!-- Agregar Select2 CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <!-- Agregar Select2 JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
</head>

<body>
    <!-- Tabla en html -->
    <div class="container gestion-container">
        <h2 class="gestion-titulo">Gestión de Reservas</h2>
        <button type="button" id="btnAgregarReserva" class="gestion-boton-agregar">
            Agregar <i class="bi bi-box-arrow-in-up-right"></i>
        </button>
        <br>
        <br>
        <div class="table-responsive">
            <table id="reservasTable" class="gestion-tabla">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre de Cliente</th>
                        <th>Comprobante</th>
                        <th>Estado Pago</th>
                        <th>Direccion-Reserva</th>
                        <th>Fecha-Reserva</th>
                        <th>Hora</th>
                        <th>Estado Reserva</th>
                        <th>Detalle</th>
                        <th>Imprimir</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Aquí se insertarán las filas de datos dinámicamente -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal para AGREGAR Reservas -->
    <div class="modal fade" id="modalFormulario" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content custom-modal-style">
                <div class="modal-header">
                    <h5 class="modal-title gestion-modal-titulo">Agregar una Reserva</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formReservas" enctype="multipart/form-data">
                        <div class="row">
                            <!-- Columna 1: Usuarios -->
                            <div class="col-md-4">
                                <h6 class="text-center">Usuarios</h6>
                                <div id="usuariosInputs">
                                    <!-- Nombre de Usuario (Select) -->
                                    <div class="mb-3">
                                        <label for="nombreUsuario" class="form-label gestion-form-label">Nombre de Usuario</label>
                                        <select id="nombreUsuario" class="form-select gestion-form-input" style="width: 50%;" required>
                                            <option value="" disabled selected>Seleccione un usuario</option>
                                        </select>
                                    </div>
                                    <input type="hidden" id="idCliente">

                                    <!-- Servicios -->
                                    <div class="mb-3">
                                        <label class="form-label gestion-form-label">Servicios</label>
                                        <div class="table-responsive">
                                            <table id="serviciosId" class="gestion-tabla">
                                                <thead>
                                                    <tr>
                                                        <th>Id</th>
                                                        <th>Servicio</th>
                                                        <th>Categoria</th>
                                                        <th>Costo/hr</th>
                                                        <th>Accion</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <!-- Aquí se insertarán las filas de datos dinámicamente -->
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Columna 2: Resumen -->
                            <div class="col-md-4">
                                <h6 class="text-center">Resumen</h6>
                                <div id="resumenInputs">
                                    <!-- Resumen de los productos seleccionados -->
                                    <div class="mb-3">
                                        <div class="table-responsive contenedorTablaResumen">
                                            <table class="gestion-tabla">
                                                <thead>
                                                    <tr>
                                                        <th>Producto</th>
                                                        <th>Cantidad</th>
                                                        <th>Costo</th>
                                                        <th>Subtotal</th>
                                                        <th>Accion</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="resumenProductos">
                                                    <!-- Aquí se generarán los productos seleccionados por el usuario -->
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <!-- Total por hora -->
                                    <div class="mb-3">
                                        <label class="form-label gestion-form-label">Total * Hora</label>
                                        <input type="text" id="totalHora" class="form-control gestion-form-input" disabled>
                                    </div>
                                </div>
                            </div>

                            <!-- Columna 3: Reserva de Título -->
                            <div class="col-md-4">
                                <h6 class="text-center">Reserva de Título</h6>
                                <div id="reservaInputs">
                                    <!-- Duración y Total Final Hora en la misma fila -->
                                    <div class="mb-3 row">
                                        <!-- Duración -->
                                        <div class="col-md-6">
                                            <label for="duracion" class="form-label gestion-form-label">Duración</label>
                                            <input type="number" id="duracion" min="1" max="12" value="" class="form-control gestion-form-input" required disabled placeholder="Duración del Evento">
                                        </div>

                                        <!-- Total Final Hora -->
                                        <div class="col-md-6">
                                            <label for="totalFinalHora" class="form-label gestion-form-label">Total Final Hora</label>
                                            <input type="text" id="totalFinalHora" class="form-control gestion-form-input" disabled value="$0.00">
                                        </div>
                                    </div>

                                    <!-- Fecha y Hora en la misma fila -->
                                    <div class="mb-3 row">
                                        <!-- Fecha -->
                                        <div class="col-md-6">
                                            <label for="fecha" class="form-label gestion-form-label">Fecha</label>
                                            <input type="date" id="fecha" class="form-control gestion-form-input" required min="">
                                        </div>

                                        <!-- Hora -->
                                        <div class="col-md-6">
                                            <label for="hora" class="form-label gestion-form-label">Hora</label>
                                            <input type="number" id="hora" class="form-control gestion-form-input" required min="1" max="12" value="" placeholder="Hora de Evento">
                                            <div>
                                                <input type="radio" id="am" name="hora" value="AM" required>
                                                <label for="am">AM</label>
                                                <input type="radio" id="pm" name="hora" value="PM" required>
                                                <label for="pm">PM</label>
                                            </div>
                                            <input type="hidden" id="horaElejida" value="">
                                        </div>
                                    </div>

                                    <!-- Distrito -->
                                    <div class="mb-3">
                                        <label for="distrito" class="form-label gestion-form-label">Distrito</label>
                                        <select id="distrito" class="form-select gestion-form-input" required>
                                            <option value="" disabled selected>Seleccione un distrito</option>
                                        </select>
                                    </div>
                                    <input type="hidden" id="idDistrito">

                                    <!-- Dirección -->
                                    <div class="mb-3">
                                        <label for="direccion" class="form-label gestion-form-label">Dirección</label>
                                        <input type="text" id="direccion" class="form-control gestion-form-input" required placeholder="Escribe tu dirección (Ej: Av. Siempre Viva 742, Springfield)">
                                    </div>

                                    <!-- Teléfono -->
                                    <div class="mb-3">
                                        <label for="telefono" class="form-label gestion-form-label">Teléfono</label>
                                        <input type="text" id="telefono" class="form-control gestion-form-input"
                                            required pattern="9[0-9]{8}" title="Debe contener 9 dígitos y comenzar con 9."
                                            placeholder="Escribe tu número de celular (Ej: 912345678)">
                                    </div>

                                    <!-- Método de pago -->
                                    <div class="mb-3">
                                        <label for="metodoPago" class="form-label gestion-form-label">Método de Pago</label>
                                        <select id="metodoPago" class="form-select gestion-form-input" required>
                                            <option value="" disabled selected>Seleccione un método de pago</option>
                                        </select>
                                    </div>
                                    <input type="hidden" id="idMetodoPago"> <!-- Input oculto para guardar el ID seleccionado -->

                                    <!-- Subir imagen -->
                                    <div class="mb-3">
                                        <label for="imagenPago" class="form-label gestion-form-label">Subir comprobante de pago</label>
                                        <input type="file" id="imagenPago" class="form-control gestion-form-input"
                                            accept="image/*"
                                            title="Seleccione una imagen" required>
                                        <small class="form-text text-muted">Formatos permitidos: JPG, PNG, GIF.</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 text-end">
                            <button type="submit" class="gestion-boton-modal">
                                Agregar Reserva
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Editar RESERVAS -->
    <div class="modal fade" id="modalEditar" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header gestion-modal-header">
                    <h5 class="modal-title gestion-modal-titulo">Editar Reserva</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formEditarReserva" enctype="multipart/form-data">
                        <div class="row">
                            <!-- Primera columna -->
                            <!-- id reserva -->
                            <input type="" id="id_Reserva" value="0">
                            <div class="col-md-6">
                                <!-- Cliente -->
                                <div class="mb-3">
                                    <label for="id_editar_cliente" class="form-label gestion-form-label">Nombre del cliente</label>
                                    <input type="text" id="id_editar_cliente" class="form-control gestion-form-input" disabled>
                                </div>

                                <!-- Distrito -->
                                <div class="mb-3">
                                    <label for="id_editar_distrito" class="form-label gestion-form-label">Distrito</label>
                                    <select id="id_editar_distrito" class="form-select gestion-form-input" required style="width: 100%;">
                                        <option value="" disabled selected>Seleccione un distrito</option>
                                    </select>
                                </div>
                                <!-- id del distriuto -->
                                <input type="" id="idDistrito_editar" value="">

                                <!-- Foto -->
                                <div class="mb-3 d-flex flex-column align-items-center">
                                    <label for="id_editar_foto" class="form-label gestion-form-label">Comprobante de Pago</label>
                                    <img id="id_editar_foto" src="ruta/a/imagen.jpg" alt="Foto de la reserva" class="img-fluid border"
                                        style="height: 100%; max-height: 250px;">
                                </div>
                            </div>

                            <!-- Segunda columna -->
                            <div class="col-md-6">
                                <!-- Dirección -->
                                <div class="mb-3">
                                    <label for="id_editar_direccion" class="form-label gestion-form-label">Dirección</label>
                                    <input type="text" id="id_editar_direccion" class="form-control gestion-form-input"
                                        required
                                        maxlength="255"
                                        title="Ingrese la dirección del cliente"
                                        placeholder="Ingrese la direccion del evento.">
                                </div>

                                <!-- Fecha Reserva -->
                                <div class="mb-3">
                                    <label for="id_editar_fecha_reserva" class="form-label gestion-form-label">Fecha Reserva</label>
                                    <input type="date" id="id_editar_fecha_reserva" class="form-control gestion-form-input" required>
                                </div>

                                <!-- Hora y AM/PM -->
                                <div class="mb-3 row">
                                    <div class="col-md-6">
                                        <label for="id_editar_hora_reserva" class="form-label gestion-form-label">Hora Reserva</label>
                                        <input type="number" id="id_editar_hora_reserva" class="form-control gestion-form-input" required
                                            min="0" max="23"
                                            title="Ingrese la hora en formato 24 horas" placeholder="Duración del Evento">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="id_editar_ampm" class="form-label gestion-form-label">AM/PM</label>
                                        <select id="id_editar_ampm" class="form-select gestion-form-input" required>
                                            <option value="AM">AM</option>
                                            <option value="PM">PM</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Teléfono de Contacto -->
                                <div class="mb-3">
                                    <label for="id_editar_telefono_contacto" class="form-label gestion-form-label">Teléfono de Contacto</label>
                                    <input type="tel" id="id_editar_telefono_contacto" class="form-control gestion-form-input" required
                                        required pattern="9[0-9]{8}" title="Debe contener 9 dígitos y comenzar con 9."
                                        maxlength="11"
                                        placeholder="Digite número del contacto. (Ej: 912345678)">
                                </div>

                                <!-- Estado Reserva -->
                                <div class="mb-3">
                                    <label for="id_editar_estado_reserva" class="form-label gestion-form-label">Estado de Reserva</label>
                                    <select id="id_editar_estado_reserva" class="form-select gestion-form-input" required>
                                        <option value="" disabled selected>Seleccione el estado</option>
                                        <option value="Pendiente">Pendiente</option>
                                        <option value="En proceso">En proceso</option>
                                        <option value="Finalizado">Finalizado</option>
                                        <option value="Cancelado">Cancelado</option>
                                        <option value="No completado">No completado</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Botón para guardar cambios -->
                        <div class="mt-4 text-end">
                            <button type="submit" class="gestion-boton-modal">
                                Editar 
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- scripr para modal de nueva reserva -->
    <script src="../../../public/js/validacionReservas.js"></script>
    <!-- Script para Select de Nombre de Usuario y Distrito -->
    <script src="../../../public/js/selectNombreyDistritos.js"></script>
    <!-- Script para select de Metodo de Pago -->
    <script src="../../../public/js/metodoPago.js"></script>
    <!-- Script para el RESERVA -->
    <script src="../../../public/js/reservasCrud.js"></script>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>