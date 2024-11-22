<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Productos</title>
    <!-- CSS de DataTables y jQuery -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/public/css/admin/estilos.css">
</head>

<body>
    <!-- Tabla en html -->
    <div class="container gestion-container">
        <h2 class="gestion-titulo">Gestión de Productos</h2>
        <button type="button" id="btnProductoNuevo" class="gestion-boton-agregar">
            Agregar <i class="bi bi-box-arrow-in-up-right"></i>
        </button>
        <br>
        <br>
        <div class="table-responsive">
            <table id="productoTable" class="gestion-tabla">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Proveedor</th>
                        <th>Categoria</th>
                        <th>Nombre</th>
                        <th>Precio/hr</th>
                        <th>Stock</th>
                        <th>Foto</th>
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

    <!-- Modal para Editar PRODUCTOS -->
    <div class="modal fade" id="modalEditar" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header gestion-modal-header">
                    <h5 class="modal-title gestion-modal-titulo"><span id="tituloModal"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formNuevoProducto" enctype="multipart/form-data">
                        <input type="hidden" id="idProducto">
                        <input type="hidden" id="idCategoria">
                        <input type="hidden" id="idProveedor">

                        <!-- Nombre -->
                        <div class="mb-3">
                            <label for="nombreProducto" class="form-label gestion-form-label">Nombre</label>
                            <input type="text" id="nombreProducto" class="form-control gestion-form-input" required
                                pattern="^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ\s]+$"
                                title="El nombre puede contener letras, números y espacios"
                                maxlength="100">
                            <small class="form-text text-muted">
                                Máximo 100 caracteres.
                                <span id="nombreProductoCounter">0/100</span>
                            </small>
                        </div>

                        <!-- Descripción -->
                        <div class="mb-3">
                            <label for="descripcionProducto" class="form-label gestion-form-label">Descripción</label>
                            <textarea id="descripcionProducto" class="form-control gestion-form-input" rows="3"
                                title="Agregue una descripción" maxlength="2500" required></textarea>
                            <small class="form-text text-muted">Máximo 500 palabras.</small>
                        </div>

                        <!-- Categoría (Select) -->
                        <div class="mb-3">
                            <label for="categoriaProducto" class="form-label gestion-form-label">Categoría</label>
                            <select id="categoriaProducto" class="form-control gestion-form-input" required>
                                <!-- Opción predeterminada (esto se mantendrá) -->
                                <option value="" disabled selected>Seleccione una categoría</option>
                            </select>
                        </div>

                        <!-- Proveedor (Select) -->
                        <div class="mb-3">
                            <label for="proveedorProducto" class="form-label gestion-form-label">Proveedor</label>
                            <select id="proveedorProducto" class="form-control gestion-form-input" required>
                                <!-- Aquí se llenarán las opciones con un AJAX o de forma estática -->
                                <option value="" disabled selected>Seleccione un proveedor</option>
                            </select>
                        </div>

                        <!-- Precio por hora -->
                        <div class="mb-3">
                            <label for="precioProducto" class="form-label gestion-form-label">Precio por hora</label>
                            <input type="number" id="precioProducto" class="form-control gestion-form-input" required
                                step="0.01" min="0" max="999.99" title="Precio por hora, máximo 3 cifras">
                        </div>

                        <!-- Stock -->
                        <div class="mb-3">
                            <label for="stockProducto" class="form-label gestion-form-label">Stock</label>
                            <input type="number" id="stockProducto" class="form-control gestion-form-input" required
                                min="1" max="100" title="Stock debe ser entre 1 y 100">
                        </div>

                        <!-- Foto existente -->
                        <input type="hidden" id="fotoExistente">

                        <!-- Foto (Nueva) -->
                        <div class="mb-3">
                            <label for="imagenProducto" class="form-label gestion-form-label">Foto</label>
                            <input type="file" id="imagenProducto" class="form-control gestion-form-input"
                                accept="image/*"
                                title="Seleccione una imagen">
                            <small class="form-text text-muted">Formatos permitidos: JPG, PNG, GIF.</small>
                        </div>

                        <!-- Botón para crear producto -->
                        <button type="submit" class="gestion-boton-modal">
                            <span id="textoDinamico"></span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        //Variables globales
        let textoModal = document.getElementById("textoDinamico");
        let tituloModal = document.getElementById("tituloModal");

        // Función genérica para cargar opciones en un select
        function cargarOpciones(url, selectId, selectedId = null, placeholder = "Seleccione una opción") {
            // Definir las claves dinámicas para ID y Nombre según el selectId
            let idKey, nameKey;

            if (selectId === '#categoriaProducto') {
                idKey = 'id_categoria';
                nameKey = 'nombre_categoria';
            } else if (selectId === '#proveedorProducto') {
                idKey = 'id_proveedor';
                nameKey = 'nomb_empresa';
            }

            // Llamado AJAX para cargar las opciones
            $.ajax({
                url: url, // Ruta para obtener los datos
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    let opcionesHtml = `<option value="" disabled selected>${placeholder}</option>`;
                    response.data.forEach(function(item) {
                        // Usar las claves dinámicas para acceder a las propiedades
                        opcionesHtml += `<option value="${item[idKey]}" ${item[idKey] == selectedId ? 'selected' : ''}>${item[nameKey]}</option>`;
                    });
                    $(selectId).html(opcionesHtml);
                }
            });
        }


        $('#categoriaProducto').on('change', function() {
            $('#idCategoria').val($(this).val());
        });

        $('#proveedorProducto').on('change', function() {
            $('#idProveedor').val($(this).val());
        });

        // Tabla Ajax
        $(document).ready(function() {
            // Inicializar DataTable
            const table = $('#productoTable').DataTable({
                "ajax": "crudProductos.php?action=listarProductos", // Ruta ajustada para obtener productos
                "columns": [{
                        "data": "id_producto"
                    },
                    {
                        "data": "nomb_empresa", // Proveedor
                    },
                    {
                        "data": "nombre_categoria", // Categoria
                    },
                    {
                        "data": "nombre", // Nombre del producto
                    },
                    {
                        "data": "precio_hr", // Precio por hora
                    },
                    {
                        "data": "stock", // Stock disponible
                    },
                    {
                        "data": "foto", // Foto del producto
                        "render": function(data, type, row) {
                            return `
                        <img src="../../../uploads/productos/${data}" alt="${data}" class="gestionImagen img-thumbnail">
                    `;
                        }
                    },
                    {
                        "data": null, // Detalle
                        "render": function(data, type, row) {
                            return `
                        <a href="detalleProducto.php?id=${row.id_producto}" class="btn btn-info btn-sm">
                            <i class="bi bi-list-check"></i>
                        </a>
                    `;
                        }
                    },
                    {
                        "data": null, // Imprimir
                        "render": function(data, type, row) {
                            return `
                        <a href="../fpdp/imprimir.php?accion=imprimirProducto&id=${row.id_producto}" target="_blank" class="btn btn-dark btn-sm">
                            <i class="bi bi-file-earmark-pdf-fill"></i>
                        </a>
                    `;
                        }
                    },
                    {
                        "data": null, // Acción (editar/eliminar)
                        "render": function(data, type, row) {
                            return `
                        <button class="btn btn-warning btn-sm editar" 
                            data-id="${row.id_producto}" 
                            data-idproveedor="${row.id_proveedor}"
                            data-idcategoria="${row.id_categoria}"
                            data-nombre="${row.nombre}"
                            data-descripcion="${row.desc}"
                            data-precio="${row.precio_hr}"
                            data-stock="${row.stock}"
                            data-foto="${row.foto}"
                            data-nombrecategoria="${row.nombre_categoria}"
                            data-nombreproveedor="${row.nomb_empresa}">
                            <i class="bi bi-pencil-square"></i>
                        </button>
                        <button class="btn btn-danger btn-sm eliminar" 
                            data-id="${row.id_producto}" 
                            data-nombre="${row.nombre}" 
                            data-foto="${row.foto}">
                            <i class="bi bi-trash-fill"></i>
                        </button>
                    `;
                        }
                    }
                ],
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/2.1.8/i18n/es-ES.json"
                }
            });
            // Inicializar SweetAlert con botones personalizados
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: "btn btn-success mx-2",
                    cancelButton: "btn btn-danger mx-2"
                },
                buttonsStyling: false
            });

            // Eliminar categoría
            $('#productoTable').on('click', '.eliminar', function() {
                const id = $(this).data('id');
                const nombre = $(this).data('nombre');
                const foto = $(this).data('foto');

                // Confirmar eliminación con SweetAlert
                swalWithBootstrapButtons.fire({
                    title: `¿Está seguro de eliminar el producto ${nombre}?`,
                    text: "Esta acción no se puede revertir.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Sí, eliminar",
                    cancelButtonText: "Cancelar",
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Llamada AJAX para eliminar el producto
                        $.post('crudProductos.php?action=eliminarProducto', {
                            id: id,
                            foto: foto
                        }, function(response) {
                            const result = JSON.parse(response);
                            if (result.success) {
                                // Mostrar mensaje de éxito
                                swalWithBootstrapButtons.fire({
                                    title: "¡Eliminado!",
                                    text: `El producto ${nombre} ha sido eliminado con éxito.`,
                                    icon: "success"
                                });
                                table.ajax.reload(); // Recargar la tabla
                            } else {
                                // Mostrar mensaje de error con el código o descripción del error
                                Swal.fire({
                                    icon: "error",
                                    title: "Error al eliminar",
                                    text: result.error
                                });
                            }
                        }).fail(function(jqXHR) {
                            // Manejar errores de la llamada AJAX
                            Swal.fire({
                                icon: "error",
                                title: "Error del servidor",
                                text: `No se pudo completar la operación. Código de error: ${jqXHR.status}`
                            });
                        });
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        // Cancelación de la acción
                        swalWithBootstrapButtons.fire({
                            title: "Cancelado",
                            text: "La eliminación ha sido cancelada.",
                            icon: "error"
                        });
                    }
                });
            });

            // Editar Producto
            $('#productoTable').on('click', '.editar', function() {
                tituloModal.textContent = "Editar Producto";
                textoModal.textContent = "Editar";

                // Obtener los datos del producto seleccionado
                const id = $(this).data('id');
                const nombre = $(this).data('nombre');
                const descripcion = $(this).data('descripcion');
                const precio = $(this).data('precio');
                const stock = $(this).data('stock');
                const categoriaId = $(this).data('idcategoria'); // ID de la categoría
                const proveedorId = $(this).data('idproveedor'); // ID del proveedor
                const imagen = $(this).data('foto');

                // Rellenar los campos del formulario con los datos del producto
                $('#idProducto').val(id);
                $('#nombreProducto').val(nombre);
                $('#descripcionProducto').val(descripcion);
                $('#precioProducto').val(precio);
                $('#stockProducto').val(stock);
                $('#idCategoria').val(categoriaId);
                $('#idProveedor').val(proveedorId);
                $('#fotoExistente').val(imagen);

                // Cargar las categorías y los proveedores seleccionando la opción correcta
                cargarOpciones('crudCategorias.php?action=llamarCategorias', '#categoriaProducto', categoriaId, 'Seleccione una categoría');
                cargarOpciones('crudProveedor.php?action=listarProveedor', '#proveedorProducto', proveedorId, 'Seleccione un proveedor');

                // Mostrar el modal
                $('#modalEditar').modal('show');
            });

            // Actualizar o Agregar Producto
            $('#formNuevoProducto').submit(function(event) {
                event.preventDefault();

                // Obtener valores del formulario
                const id = $('#idProducto').val();
                const idCategoria = $('#idCategoria').val();
                const idProveedor = $('#idProveedor').val();
                const nombre = $('#nombreProducto').val();
                const descripcion = $('#descripcionProducto').val();
                const precio = $('#precioProducto').val();
                const stock = $('#stockProducto').val();
                const fotoNueva = $('#imagenProducto')[0].files[0]; // Obtener archivo si se subió
                const fotoExistente = $('#fotoExistente').val(); // Ruta de la foto actual

                // Crear objeto FormData para enviar datos
                const formData = new FormData();
                formData.append('id', id);
                formData.append('id_categoria', idCategoria);
                formData.append('id_proveedor', idProveedor);
                formData.append('nombre', nombre);
                formData.append('descripcion', descripcion);
                formData.append('precio_hr', precio);
                formData.append('stock', stock);
                formData.append('fotoExistente', fotoExistente);

                // Verificar si el id es 0, significa que es un nuevo producto
                if (id == 0) {
                    if (!fotoNueva) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Advertencia',
                            text: 'Debe ingresar una imagen para un nuevo producto.',
                            confirmButtonText: 'Aceptar',
                        });
                        return; // Detiene la ejecución si no hay imagen
                    }
                }

                // Adjuntar foto nueva solo si existe
                if (fotoNueva) {
                    formData.append('foto', fotoNueva);
                }

                // Determinar acción según el ID
                const action = id == 0 ? 'crearProducto' : 'actualizarProducto';

                // Enviar datos al servidor
                $.ajax({
                    url: `crudProductos.php?action=${action}`,
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        const result = JSON.parse(response);

                        if (result.success) {
                            $('#modalEditar').modal('hide');
                            table.ajax.reload();
                            Swal.fire({
                                icon: 'success',
                                title: '¡Éxito!',
                                text: 'Operación realizada correctamente.',
                                confirmButtonText: 'Aceptar',
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: result.error,
                                confirmButtonText: 'Aceptar',
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Ocurrió un error en la comunicación con el servidor.',
                            confirmButtonText: 'Aceptar',
                        });
                    },
                });
                limpiarDatos();
            });

            // Limpiar datos para nueva categoría
            $('#btnProductoNuevo').click(function() {
                tituloModal.textContent = "Nueva Categoría";
                textoModal.textContent = "Agregar Categoría";
                limpiarDatos();
                $('#idProducto').val(0);
                $('#idCategoria').val(0);
                $('#idProveedor').val(0);
                $('#modalEditar').modal('show');

                // Cargar Categorías y Proveedores al inicio
                //Asi evitamos que se quede pegado en editar
                cargarOpciones('crudCategorias.php?action=llamarCategorias', '#categoriaProducto', null, 'Seleccione una categoría');
                cargarOpciones('crudProveedor.php?action=listarProveedor', '#proveedorProducto', null, 'Seleccione un proveedor');
            });

            function limpiarDatos() {
                $('#idProducto').val("");
                $('#nombreProducto').val("");
                $('#descripcionProducto').val("");
                $('#precioProducto').val("");
                $('#stockProducto').val("");
                $('#idCategoria').val("");
                $('#idProveedor').val("");
                $('#fotoExistente').val("");
            }

        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>

</html>