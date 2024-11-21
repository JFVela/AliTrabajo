<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Categorias</title>
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
    <div class="container gestion-clientes-container">
        <h2 class="gestion-clientes-titulo">Gestión de Categorias</h2>
        <button type="button" id="btnNuevoCliente" class="gestion-clientes-boton-agregar">
            Agregar <i class="bi bi-box-arrow-in-up-right"></i>
        </button>
        <br>
        <br>
        <div class="table-responsive">
            <table id="categoriasTable" class="gestion-clientes-tabla">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Imagen</th>
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


    <!-- Modal para Editar CATEGORIAS -->
    <div class="modal fade" id="modalEditar" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header gestion-clientes-modal-header">
                    <h5 class="modal-title gestion-clientes-modal-titulo"><span id="tituloModal"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formEditar">
                        <input type="hidden" id="editarId">

                        <!-- Nombre -->
                        <div class="mb-3">
                            <label for="editarNombre" class="form-label gestion-form-label">Nombre</label>
                            <input type="text" id="editarNombre" class="form-control gestion-form-input" required
                                pattern="^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$"
                                title="El nombre no puede contener números ni caracteres especiales"
                                maxlength="100">
                            <small class="form-text text-muted">
                                Máximo 100 caracteres.
                                <span id="nombreCounter">0/100</span>
                            </small>
                        </div>

                        <!-- Descripción -->
                        <div class="mb-3">
                            <label for="editarDescripcion" class="form-label gestion-form-label">Descripción</label>
                            <textarea id="editarDescripcion" class="form-control gestion-form-input" rows="3"
                                title="Agregue una descripción"></textarea>
                        </div>

                        <!-- Foto -->
                        <div class="mb-3">
                            <label for="editarImagen" class="form-label gestion-form-label">Foto</label>
                            <input type="file" id="editarImagen" class="form-control gestion-form-input"
                                accept="image/*"
                                title="Seleccione una imagen">
                            <small class="form-text text-muted">Formatos permitidos: JPG, PNG, GIF.</small>
                        </div>

                        <!-- Vista previa de la imagen -->
                        <div class="mb-3">
                            <label class="form-label gestion-form-label">Vista previa de la imagen</label>
                            <div id="imagePreviewContainer" style="display: flex; justify-content: flex-start; align-items: center;">
                                <img id="imagePreview" src="" alt="Vista previa de la imagen" style="max-width: 200px; display: none; margin-left: 20px;" />
                            </div>
                        </div>

                        <!-- Botón Actualizar -->
                        <button type="submit" class="gestion-boton-modal">
                            <span id="textoDinamico"></span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Mostrar vista previa de la imagen
        document.getElementById('editarImagen').addEventListener('change', function(event) {
            const file = event.target.files[0];
            const imagePreview = document.getElementById('imagePreview');

            if (file) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    imagePreview.style.display = 'block'; // Mostrar la imagen
                };

                reader.readAsDataURL(file);
            } else {
                imagePreview.style.display = 'none'; // Ocultar la imagen si no hay archivo
            }
        });
    </script>

    <script>
        //Variables globales
        let textoModal = document.getElementById("textoDinamico");
        let tituloModal = document.getElementById("tituloModal");
        // Tabla Ajax
        $(document).ready(function() {
            // Inicializar DataTable
            const table = $('#categoriasTable').DataTable({
                "ajax": "crudCategorias.php?action=llamarCategorias", // Ruta ajustada
                "columns": [{
                        "data": "id_categoria"
                    },
                    {
                        "data": "nombre_categoria"
                    },
                    {
                        "data": "descripcion_categoria"
                    },
                    {
                        "data": "foto_categoria",
                        "render": function(data, type, row) {
                            return `
                        <img src="../uploads/categorias/${data}" alt="Imagen" class="img-thumbnail" width="100">
                    `;
                        }
                    },
                    {
                        "data": null,
                        "render": function(data, type, row) {
                            return `
                    <a href="detalleCategoria.php?id=${row.id_categoria}" 
                        class="btn btn-info btn-sm">
                        <i class="bi bi-list-check"></i>
                    </a>`;
                        }
                    },
                    {
                        "data": null,
                        "render": function(data, type, row) {
                            return `
                    <a href="../fpdp/imprimir.php?accion=imprimirCategoria&id=${row.id_categoria}" target="_blank" 
                        class="btn btn-dark btn-sm">
                        <i class="bi bi-file-earmark-pdf-fill"></i>
                    </a>`;
                        }
                    },
                    {
                        "data": null,
                        "render": function(data, type, row) {
                            return `
                        <button 
                            class="btn btn-warning btn-sm editar" 
                            data-id="${row.id_categoria}" 
                            data-nombre="${row.nombre_categoria}" 
                            data-descripcion="${row.descripcion_categoria}" 
                            data-foto="${row.foto_categoria}">
                            <i class="bi bi-pencil-square"></i>
                        </button>
                        <button 
                            class="btn btn-danger btn-sm eliminar" 
                            data-id="${row.id_categoria}" 
                            data-nombre="${row.nombre_categoria}">
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
                const imagen = $(this).data('imagen');

                // Rellenar los campos del modal
                $('#editarId').val(id);
                $('#editarNombre').val(nombre);
                $('#editarDescripcion').val(descripcion);
                $('#editarImagen').val(imagen);

                // Mostrar el modal
                $('#modalEditar').modal('show');
            });

            // Actualizar o Agregar categoría
            $('#formEditar').submit(function(event) {
                // Prevenir el comportamiento predeterminado del formulario
                event.preventDefault();

                // Validar formulario
                if (!this.checkValidity()) {
                    return; // Si el formulario no es válido, no continúa
                }

                // Obtener valores del formulario
                const id = $('#editarId').val();
                const nombre = $('#editarNombre').val();
                const descripcion = $('#editarDescripcion').val();
                const imagen = $('#editarImagen').val();

                // Determinar acción según el ID
                const action = id == 0 ? 'crearCategoria' : 'actualizarCategoria';

                // Crear objeto de datos
                const datosCategoria = {
                    nombre,
                    descripcion,
                    imagen
                };

                // Si no es una nueva categoría, incluir el ID
                if (id != 0) {
                    datosCategoria.id = id;
                }

                // Enviar datos al servidor
                $.post(`crudCategorias.php?action=${action}`, datosCategoria, function(response) {
                    // Manejar la respuesta del servidor
                    const result = JSON.parse(response);

                    if (result.success) {
                        // Ocultar el modal y recargar la tabla primero
                        $('#modalEditar').modal('hide');
                        table.ajax.reload();
                        // Mostrar mensaje de éxito después
                        Swal.fire({
                            icon: 'success',
                            title: '¡Éxito!',
                            text: 'Operación realizada correctamente.',
                            confirmButtonText: 'Aceptar'
                        });
                    } else {
                        // Mostrar mensaje de error
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: result.error,
                            confirmButtonText: 'Aceptar'
                        });
                    }
                }).fail(function() {
                    // Manejo de errores en la solicitud AJAX
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Ocurrió un error en la comunicación con el servidor.',
                        confirmButtonText: 'Aceptar'
                    });
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
                $('#editarImagen').val("");
            }

        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>

</html>