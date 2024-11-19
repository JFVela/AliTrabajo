<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cotizaciones - Sistema Audio</title>
    <!-- CSS de DataTables y jQuery -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
</head>
<body>
Hola
<h2>Lista de Cotizaciones</h2>
<table id="cotizacionesTable" class="display">
    <thead>
        <tr>
            <th>ID Cotización</th>
            <th>ID Cliente</th>
            <th>Fecha Cotización</th>
            <th>Total</th>
            <th>ID Cliente</th>
            <th>Fecha Cotización</th>
            <th>Total</th>
        </tr>
    </thead>
</table>

<script>
$(document).ready(function() {
    $('#cotizacionesTable').DataTable({
        "ajax": "getCotizaciones.php", // Archivo PHP para obtener los datos
        "columns": [
            { "data": "id_cotizacion" },
            { "data": "nombre_categoria" },
            { "data": "nombre_producto" },
            { "data": "cantidad" },
            { "data": "horas_servicio" },
            { "data": "horas_alquiler" },
            { "data": "subtotal" }
        ],
        "language": {
            "url": "//cdn.datatables.net/plug-ins/2.1.8/i18n/es-ES.json" // Traducción al español
        }
    });
});
</script>

</body>
</html>
