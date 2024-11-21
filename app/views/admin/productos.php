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

    <script>
        //Variables globales
        let textoModal = document.getElementById("textoDinamico");
        let tituloModal = document.getElementById("tituloModal");
        // Tabla Ajax
        $(document).ready(function() {
            // Inicializar DataTable
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
                        <button class="btn btn-warning btn-sm editar" data-id="${row.id_producto}" data-nombre="${row.nombre}" data-precio="${row.precio_hr}" data-stock="${row.stock}" data-foto="${row.foto}">
                            <i class="bi bi-pencil-square"></i>
                        </button>
                        <button class="btn btn-danger btn-sm eliminar" data-id="${row.id_producto}" data-nombre="${row.nombre}">
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
            $('#categoriasTable').on('click', '.eliminar', function() {
                const id = $(this).data('id');
                const nombre = $(this).data('nombre');

                // Confirmar eliminación
                swalWithBootstrapButtons.fire({
                    title: `¿Está seguro de eliminar la categoría ${nombre}?`,
                    text: "Esta acción no se puede revertir.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Sí, eliminar",
                    cancelButtonText: "Cancelar",
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Llamada AJAX para eliminar la categoría
                        $.post('crudCategorias.php?action=eliminarCategoria', {
                            id
                        }, function(response) {
                            const result = JSON.parse(response);
                            if (result.success) {
                                // Mostrar mensaje de éxito
                                swalWithBootstrapButtons.fire({
                                    title: "¡Eliminado!",
                                    text: `La categoría ${nombre} ha sido eliminada con éxito.`,
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

            // Editar categoría
            $('#categoriasTable').on('click', '.editar', function() {
                tituloModal.textContent = "Editar Categoría";
                textoModal.textContent = "Editar";
                const id = $(this).data('id');
                const nombre = $(this).data('nombre');
                const descripcion = $(this).data('descripcion');
                const imagen = $(this).data('foto'); // Ruta o nombre de la imagen

                // Rellenar los campos del modal
                $('#editarId').val(id);
                $('#editarNombre').val(nombre);
                $('#editarDescripcion').val(descripcion);
                $('#fotoExistente').val(imagen); // Guardar la ruta/nombre de la imagen actual

                // Mostrar el modal
                $('#modalEditar').modal('show');
            });

            // Actualizar o Agregar categoría
            $('#formEditar').submit(function(event) {
                event.preventDefault();

                // Obtener valores del formulario
                const id = $('#editarId').val();
                const nombre = $('#editarNombre').val();
                const descripcion = $('#editarDescripcion').val();
                const fotoNueva = $('#editarImagen')[0].files[0]; // Obtener archivo si se subió
                const fotoExistente = $('#fotoExistente').val(); // Ruta de la foto actual

                // Crear objeto FormData para enviar datos
                const formData = new FormData();
                formData.append('id', id);
                formData.append('nombre', nombre);
                formData.append('descripcion', descripcion);
                formData.append('fotoExistente', fotoExistente);

                // Verificar si el id es 0, significa que es una nueva categoría
                if (id == 0) {
                    // Si el id es 0 y no se ha subido una nueva foto
                    if (!fotoNueva) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Advertencia',
                            text: 'Debe ingresar una imagen para una nueva categoría.',
                            confirmButtonText: 'Aceptar'
                        });
                        return; // Detiene la ejecución si no hay imagen
                    }
                }

                // Adjuntar foto nueva solo si existe
                if (fotoNueva) {
                    formData.append('foto', fotoNueva);
                }

                // Determinar acción según el ID
                const action = id == 0 ? 'crearCategoria' : 'actualizarCategoria';

                // Enviar datos al servidor
                $.ajax({
                    url: `crudCategorias.php?action=${action}`,
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
                                confirmButtonText: 'Aceptar'
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: result.error,
                                confirmButtonText: 'Aceptar'
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Ocurrió un error en la comunicación con el servidor.',
                            confirmButtonText: 'Aceptar'
                        });
                    }
                });
            });

            // Limpiar datos para nueva categoría
            $('#btnNuevoCliente').click(function() {
                tituloModal.textContent = "Nueva Categoría";
                textoModal.textContent = "Agregar Categoría";
                limpiarDatos();
                $('#editarId').val(0);
                $('#modalEditar').modal('show');
            });

            function limpiarDatos() {
                $('#editarId').val("");
                $('#editarNombre').val("");
                $('#editarDescripcion').val("");
                $('#fotoExistente').val("");
            }

        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>



</body>

</html>