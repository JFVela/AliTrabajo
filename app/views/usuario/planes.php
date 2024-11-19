<?php
include '../../../includes/header.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="/public/css/usuario/planes.css">
    <link rel="stylesheet" href="/public/css/usuario/includes.css">
    <link rel="stylesheet" href="/public/css/usuario/Reservas.css">
    <link rel="stylesheet" href="/public/css/usuario/metodoPago.css">
</head>

<body>

    <?php

    if (!isset($_SESSION['emailcliente'])) { ?>
        <div class="mensaje-sesion">
            <h2>Iniciar Sesión Requerido</h2>
            <p>Para continuar con la cotización, por favor inicie sesión en su cuenta.</p>
            <a href="loginCliente.php" class="boton-iniciar">Iniciar Sesión</a>
            <a href="createUser.php" class="boton-crear">Crear Cuenta</a>
        </div>
    <?php
        include '../../../includes/footer.php';
        return;
    }
    include '../../../config/conexionDatos.php';

    $id_categoria = isset($_GET['id_categoria']) ? intval($_GET['id_categoria']) : 0;
    $query = "SELECT * FROM productos WHERE id_categoria = $id_categoria";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        echo '<div class="container">';
        echo '<div class="row">';

        while ($producto = $result->fetch_assoc()) {
            echo '<div class="card">';
            echo '<img src="../../../' . htmlspecialchars($producto['foto']) . '" class="fotito" alt="' . htmlspecialchars($producto['nombre']) . '">';
            echo '<div class="card-body">';
            echo '<h5 class="card-title">' . htmlspecialchars($producto['nombre']) . '</h5>';
            echo '<p class="card-text">Precio: $' . htmlspecialchars($producto['precio_hr']) . '</p>';
            echo '<input type="number" class="input-cantidad" min="1"  max="5" value="1" placeholder="Cantidad" id="cantidad_' . $producto['id_producto'] . '">';
            echo '<button class="btn-agregar" onclick="agregarProducto(' . $producto['id_producto'] . ', document.getElementById(\'cantidad_' . $producto['id_producto'] . '\').value, ' . htmlspecialchars($producto['precio_hr']) . ')">Agregar</button>';
            echo '</div>';
            echo '</div>';
        }

        echo '</div>';
        echo '</div>';
    } else {
        echo "No hay productos disponibles para esta categoría.";
    }

    $conn->close();
    ?>

    <div class="resultado-container">
        <h4>Productos Agregados:</h4>
        <div id="resultado"></div>
        <form class="hidden" id="form-finalizar">
            <br>
            <p class="mensajePequeño">*Por favor ingrese cuantas horas de servicio requiere para poder continuar</p>
            <p class="mensajePequeño">*No debe exceder mas de 10 hrs su evento</p>
            <label for="">Número de Horas:</label>
            <input type="number" class="horasAlquiladas" min="1" max="10" value="1" placeholder="Horas de alquiler" id="HorasAlquiladas">

            <button type="button" class="btn-agregar calcular" onclick="calcularCosto()">Calcular</button>
            <br><br>
            <div id="costoTotalServicio"></div>
            <br>
            <button type="button" class="btn-agregar" id="botonSiguiente" style="display: none;">Siguiente</button>
        </form>
    </div>

    <div class="reservaOculta" id="reservaOculta">
        <section class="sectionReserva">
            <form class="formularioReserva" action="/submit-reservation" method="post">
                <label for="fecha">Fecha del servicio:</label>
                <input type="date" id="fecha" name="fecha" required>

                <label for="hora">Hora del servicio:</label>
                <div class="hora-servicio">
                    <input type="number" id="hora" name="hora" min="1" max="12" placeholder="12hr" required>
                    <label for="am">AM</label>
                    <input type="radio" id="am" name="ampm" value="AM" required>
                    <label for="pm">PM</label>
                    <input type="radio" id="pm" name="ampm" value="PM" required>
                </div>

                <label for="duracion">Duración del alquiler (horas):</label>
                <input type="number" id="duracion" name="duracion" min="1" max="10" readonly>

                <label for="departamento">Departamento:</label>
                <select id="departamento" name="departamento" required>
                    <option value="">Seleccione un departamento</option>
                </select>

                <label for="provincia">Provincia:</label>
                <select id="provincia" name="provincia" required>
                    <option value="">Seleccione una provincia</option>
                </select>

                <label for="distrito">Distrito:</label>
                <select id="distrito" name="distrito" required>
                    <option value="">Seleccione un distrito</option>
                </select>

                <label for="ubicacion">Dirección:</label>
                <input type="text" id="ubicacion" name="ubicacion" placeholder="Utiliza referencias" required>

                <label for="telefono">Teléfono de contacto:</label>
                <input type="tel" id="telefono" name="telefono" placeholder="Número de teléfono" pattern="9[0-9]{8}"
                    required>

                <input id="abrirModal" type="submit" value="Reservar">
            </form>
        </section>
        <p id="contador-dias"></p>
    </div>

    <div id="idModal" class="modalPago">
        <section class="metodoDePago">
            <button id="cerrarModal">X</button>
            <form action="../../controllers/insert_cotizacion.php" method="POST" method="post" enctype="multipart/form-data">
                <!-- Obtenido del primer formulario -->
                <input type="hidden" name="total" id="total" value="0">
                <input type="hidden" name="productos" id="productos" value=''>
                <input type="hidden" name="email" value="<?php echo $_SESSION['emailcliente']; ?>">

                <!-- Almacenamos valores ocultos para el segundo formulario -->
                <input type="hidden" id="fechaReservaOculto" name="fechaReservaOculto" value="">
                <input type="hidden" id="horaReservaOculto" name="horaReservaOculto" value="">
                <input type="hidden" id="ampmReservaOculto" name="ampmReservaOculto" value="">
                <input type="hidden" id="duracionReservaOculto" name="duracionReservaOculto" value="">
                <input type="hidden" id="distritoReservaOculto" name="distritoReservaOculto" value="">
                <input type="hidden" id="ubicacionReservaOculto" name="ubicacionReservaOculto" value="">
                <input type="hidden" id="telefonoReservaOculto" name="telefonoReservaOculto" value="">
                <!-- Fin de almacenamiento oculto -->


                <h1 class="titulo">Método de Pago</h1>
                <p class="descripcion">Selecciona su metodo de pago y sube tu comprobante.</p>
                <br>
                <div class="metodoDePago__lista"></div> <!-- Aquí se cargarán los métodos de pago -->
                <br>
                <div class="subirComprobante">
                    <label for="comprobante">Subir Comprobante de Pago</label>
                    <div class="inputGroup">
                        <input type="file" id="comprobante" name="comprobante" accept=".jpg, .jpeg, .png" required
                            style="display: none;">
                        <span id="nombreArchivo">Seleccionar archivo</span>
                        <button type="button" id="subirBtn">
                            <i class="bi bi-upload"></i> Subir
                        </button>
                    </div>
                </div>
                <br>
                <input type="submit" class="enviarBtn" value="Enviar" id="enviarBtn">
            </form>
        </section>
    </div>

    <?php
    include '../../../includes/footer.php';
    ?>
    <!-- Hace visible al formulario -->
    <script>
        // Hacer visible el formulario cuando se hace clic en "Finalizar"
        document.querySelector('.btn-agregar.cotizar').onclick = function() {
            document.querySelector('form').submit();
        }
    </script>
    <!-- Metdos , funciones y calculos con el servicio -->
    <script>
        // Arrays para almacenar los detalles de los productos agregados
        var ArrayID = [];
        var IDCantidad = [];
        var PrecioTotal = [];
        var totalFinal = 0;

        function agregarProducto(id, cantidad, precio) {
            // Validar que la cantidad esté entre 1 y 5
            cantidad = parseInt(cantidad);
            if (cantidad < 1) cantidad = 0;
            if (cantidad > 5) cantidad = 5;

            // Verificar si el producto ya existe en la lista
            const index = ArrayID.indexOf(id);

            if (index !== -1) {
                // Producto ya existe
                if (cantidad === 0) {
                    // Si la cantidad es 0, eliminar el producto de la lista
                    ArrayID.splice(index, 1);
                    IDCantidad.splice(index, 1);
                    PrecioTotal.splice(index, 1);
                } else {
                    // Actualizar la cantidad y subtotal si el producto ya existe
                    IDCantidad[index] = cantidad;
                    PrecioTotal[index] = precio * cantidad;
                }
            } else if (cantidad > 0) {
                // Agregar nuevo producto si no existe y la cantidad es mayor a 0
                ArrayID.push(id);
                IDCantidad.push(cantidad);
                PrecioTotal.push(precio * cantidad);
            }

            // Actualizar el total y mostrar la lista de productos
            actualizarListaProductos();
        }

        function actualizarListaProductos() {
            let output = '';
            totalFinal = 0;

            for (let i = 0; i < ArrayID.length; i++) {
                output += 'ID Producto: ' + ArrayID[i] + ', Cantidad: ' + IDCantidad[i] + ', Subtotal: $' + PrecioTotal[i].toFixed(2) + '<br>';
                totalFinal += PrecioTotal[i];
            }

            output += '<strong>Costo Total por Hora: $' + totalFinal.toFixed(2) + '</strong>';
            document.getElementById('resultado').innerHTML = output;

            // Log arrays to console for debugging
            console.log("ArrayID:", ArrayID);
            console.log("IDCantidad:", IDCantidad);
            console.log("PrecioTotal:", PrecioTotal);

            document.getElementById('total').value = totalFinal.toFixed(2);

            const formFinalizar = document.getElementById('form-finalizar');
            if (totalFinal > 0) {
                formFinalizar.classList.remove('hidden'); // Mostrar el formulario
            } else {
                formFinalizar.classList.add('hidden'); // Ocultar el formulario
            }

            document.getElementById('productos').value = JSON.stringify(ArrayID.map((id, index) => ({
                id_producto: id,
                cantidad: IDCantidad[index],
                subtotal: PrecioTotal[index]
            })));
        }

        // Función para calcular el Costo Total del Servicio con base en las horas seleccionadas
        function calcularCosto() {
            // Obtener el número de horas desde el input
            const horasAlquiladas = parseInt(document.getElementById('HorasAlquiladas').value);

            // Insertar ese valor en el input del segundo formulario
            document.getElementById('duracion').value = horasAlquiladas;
            if (horasAlquiladas > 0) {
                const costoTotalServicio = totalFinal * horasAlquiladas;
                document.getElementById('costoTotalServicio').innerHTML = '<strong>Costo Total del Servicio: $' + costoTotalServicio.toFixed(2) + '</strong>';

                // Habilitar el botón "Siguiente" después de calcular el costo
                document.getElementById('botonSiguiente').style.display = 'inline-block';
            } else {
                document.getElementById('costoTotalServicio').innerHTML = '<strong>Por favor, ingresa una cantidad válida de horas.</strong>';
            }
        }

        document.getElementById('botonSiguiente').onclick = function() {
            // Mostrar el div reservaOculta y hacer scroll
            const reservaOculta = document.getElementById('reservaOculta');
            reservaOculta.style.display = 'block';

            // Desplazarse hasta el div reservaOculta
            reservaOculta.scrollIntoView({
                behavior: 'smooth'
            });
        };
    </script>
    <!-- Validar subida -->
    <script>
        document.getElementById('subirBtn').addEventListener('click', function() {
            document.getElementById('comprobante').click(); // Activa el campo de carga de archivo al hacer clic en el botón
        });

        document.getElementById('comprobante').addEventListener('change', function() {
            const archivo = this.files[0]; // Obtiene el archivo seleccionado
            const nombreArchivo = document.getElementById('nombreArchivo');
            nombreArchivo.textContent = archivo ? archivo.name : 'Seleccionar archivo';
            // No se muestra mensaje de error aquí
        });

        // Validación antes de enviar el formulario
        document.getElementById('enviarBtn').addEventListener('click', function(event) {
            const archivo = document.getElementById('comprobante').files[0];

            var cantidaServicios = ArrayID.length;
            console.log(cantidaServicios)

            if (!archivo) {
                event.preventDefault(); // Evita el envío del formulario
                alert('Por favor, llene todo los datos.'); // Muestra un alerta si no se ha seleccionado un archivo
                return;
            }
            //Valor guradado se manda al campo oculto
            document.getElementById("fechaReservaOculto").value = fechaReserva;
            document.getElementById("horaReservaOculto").value = horaReserva;
            document.getElementById("ampmReservaOculto").value = ampm;
            document.getElementById("duracionReservaOculto").value = duracionReserva;
            document.getElementById("distritoReservaOculto").value = distritoReserva;
            document.getElementById("ubicacionReservaOculto").value = ubicacionReserva;
            document.getElementById("telefonoReservaOculto").value = telefonoReserva;
            limpiarInputs();
        })

        function limpiarInputs() {
            document.getElementById("HorasAlquiladas").value = "1";
            document.getElementById("fecha").value = "";
            document.getElementById("hora").value = "";
            document.getElementById("am").value = "";
            document.getElementById("pm").value = "";
            document.getElementById("duracion").value = "";
            document.getElementById("ubicacion").value = "";
            document.getElementById("telefono").value = "";
            document.querySelectorAll(".input-cantidad").forEach(input => input.value = "1");
        };
    </script>
    <!-- Validaciones -->
    <script>
        document.getElementById('fecha').setAttribute('min', new Date().toISOString().split('T')[0]);

        document.getElementById('fecha').addEventListener('change', function() {
            const fechaSeleccionada = new Date(this.value);
            const fechaActual = new Date();
            const diferenciaTiempo = fechaSeleccionada - fechaActual;
            const diferenciaDias = Math.ceil(diferenciaTiempo / (1000 * 60 * 60 * 24));
            let mensaje = '';

            if (diferenciaDias === 0) {
                mensaje = "El evento será mañana.";
            } else if (diferenciaDias < 7) {
                const diasSemana = ['lunes', 'martes', 'miércoles', 'jueves', 'viernes', 'sábado', 'domingo'];
                mensaje = `Este ${diasSemana[fechaSeleccionada.getDay()]} se reservará el evento.`;
            } else if (diferenciaDias < 14) {
                const diasSemana = ['lunes', 'martes', 'miércoles', 'jueves', 'viernes', 'sábado', 'domingo'];
                mensaje = `El ${diasSemana[fechaSeleccionada.getDay()]} de la siguiente semana será la reserva.`;
            } else {
                mensaje = `Faltan ${diferenciaDias} días para el evento.`;
            }

            document.getElementById('contador-dias').textContent = mensaje;
        });

        document.getElementById('duracion').addEventListener('input', function() {
            if (this.value > 10) {
                this.value = 10;
            }
        });
    </script>
    <!-- Obtener datos Geograficos -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            fetch('get_departamentos.php')
                .then(response => response.json())
                .then(data => {
                    const departamentoSelect = document.getElementById('departamento');
                    data.forEach(departamento => {
                        const option = document.createElement('option');
                        option.value = departamento.idDepa;
                        option.textContent = departamento.Departamento;
                        departamentoSelect.appendChild(option);
                    });
                });

            document.getElementById('departamento').addEventListener('change', function() {
                const departamentoId = this.value;
                fetch(`get_provincias.php?idDepa=${departamentoId}`)
                    .then(response => response.json())
                    .then(data => {
                        const provinciaSelect = document.getElementById('provincia');
                        provinciaSelect.innerHTML = '<option value="">Seleccione una provincia</option>';
                        data.forEach(provincia => {
                            const option = document.createElement('option');
                            option.value = provincia.idProv;
                            option.textContent = provincia.Provincia;
                            provinciaSelect.appendChild(option);
                        });
                    });
            });

            document.getElementById('provincia').addEventListener('change', function() {
                const provinciaId = this.value;
                fetch(`get_distritos.php?idProv=${provinciaId}`)
                    .then(response => response.json())
                    .then(data => {
                        const distritoSelect = document.getElementById('distrito');
                        distritoSelect.innerHTML = '<option value="">Seleccione un distrito</option>';
                        data.forEach(distrito => {
                            const option = document.createElement('option');
                            option.value = distrito.idDist;
                            option.textContent = distrito.Distrito;
                            distritoSelect.appendChild(option);
                        });
                    });
            });

            // Evento para imprimir el id del distrito seleccionado
            document.getElementById('distrito').addEventListener('change', function() {
                const distritoId = this.value;
                console.log("ID del distrito seleccionado:", distritoId);
            });
        });
    </script>
    <!-- modal -->
    <script>
        // Obtener elementos
        const formulario = document.querySelector('.formularioReserva'); // Seleccionamos el formulario
        const abrirModal = document.getElementById('abrirModal');
        const modal = document.getElementById('idModal');
        const cerrarModal = document.getElementById('cerrarModal');

        //Variables que almacenan el name del primer fomrulario.
        let fechaReserva;
        let horaReserva;
        let ampm;
        let duracionReserva;
        let distritoReserva;
        let ubicacionReserva;
        let telefonoReserva;

        // Validación del formulario
        formulario.addEventListener('submit', function(e) {
            e.preventDefault(); // Evitar que el formulario se envíe inmediatamente

            // Validaciones personalizadas (puedes agregar más si lo necesitas)
            const fecha = document.getElementById('fecha');
            const hora = document.getElementById('hora');
            const duracion = document.getElementById('duracion');
            const departamento = document.getElementById('departamento');
            const provincia = document.getElementById('provincia');
            const distrito = document.getElementById('distrito');
            const ubicacion = document.getElementById('ubicacion');
            const telefono = document.getElementById('telefono');

            // Comprobamos si los campos requeridos están vacíos
            if (!fecha.value || !hora.value || !duracion.value || !departamento.value || !provincia.value || !distrito.value || !ubicacion.value || !telefono.value) {
                alert("Por favor, complete todos los campos.");
                return;
            }

            //Almacena el name del primer formaulario
            fechaReserva = fecha.value;
            horaReserva = hora.value;
            ampm = document.querySelector('input[name="ampm"]:checked').value;
            duracionReserva = duracion.value;
            distritoReserva = distrito.value;
            ubicacionReserva = ubicacion.value;
            telefonoReserva = telefono.value;

            // Si todo está bien, mostramos el modal
            modal.style.display = 'flex'; // Mostrar el modal
        });

        // Cerrar el modal
        cerrarModal.addEventListener('click', function() {
            modal.style.display = 'none'; // Ocultar el modal
        });
    </script>
    <!-- Metodo Pago -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Cargar métodos de pago desde la base de datos
            fetch('get_metodos_pago.php')
                .then(response => response.json())
                .then(data => {
                    const listaMetodos = document.querySelector('.metodoDePago__lista');

                    data.forEach(metodo => {
                        // Crear el elemento de cada método de pago
                        const metodoDiv = document.createElement('div');
                        metodoDiv.classList.add('metodoDePago__item'); // Cambio para cada método

                        // Input radio
                        const input = document.createElement('input');
                        input.type = 'radio';
                        input.name = 'metodoPago';
                        input.classList.add('ui-checkbox');
                        input.value = metodo.idPago; // Ajuste de nombre
                        input.required = true;

                        // Imagen del ícono
                        const img = document.createElement('img');
                        img.classList.add('iconoPago');
                        img.src = metodo.iconoPago; // Ajuste de nombre
                        img.alt = `icono de ${metodo.descripcionPago}`;

                        // Nombre y descripción
                        const nombre = document.createElement('h3');
                        nombre.classList.add('metodoDePago__texto');
                        nombre.textContent = metodo.metodoPago;

                        const descripcion = document.createElement('p');
                        descripcion.classList.add('descripcionMetodo');
                        descripcion.textContent = metodo.descripcionPago;

                        // Agregar elementos al div
                        metodoDiv.appendChild(input);
                        metodoDiv.appendChild(img);
                        metodoDiv.appendChild(nombre);
                        metodoDiv.appendChild(descripcion);

                        // Agregar el método de pago al contenedor
                        listaMetodos.appendChild(metodoDiv);

                        // Añadir el evento 'change' al input radio
                        input.addEventListener('change', function() {
                            // Ocultar todos los textos de los otros métodos de pago
                            const allTextElements = document.querySelectorAll('.metodoDePago__texto');
                            allTextElements.forEach(text => {
                                text.style.visibility = 'hidden'; // Ocultar todos los textos
                            });

                            // Mostrar el texto del método seleccionado
                            if (input.checked) {
                                nombre.style.visibility = 'visible'; // Mostrar el nombre cuando esté marcado

                                // Variable para obtener el ID del radio marcado
                                const ObtnerId = input.value; // Obtener el ID del método de pago

                                // Mostrar el ID en la consola
                                console.log('ID del método de pago seleccionado:', ObtnerId);
                            }
                        });
                    });
                })
                .catch(error => console.error('Error al cargar métodos de pago:', error));
        });
    </script>
</body>


</html>