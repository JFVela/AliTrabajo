<?php
// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "audio_system";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $idReserva = $_GET['id'];

    // Consulta los datos de la reserva
    $query = $conn->prepare("   SELECT 
                                r.id_reserva,r.direccion AS direccion_reserva,r.fecha_reserva,
                                r.hora_reserva,r.ampm,r.telefonoContacto,r.estado_reserva,
                                c.id_cotizacion,c.fecha_cotizacion,c.total,cl.nombre_cliente,
                                cl.telefono AS telefono_cliente,cl.email,
                                cl.direccion AS direccion_cliente,cl.dni,cp.capturaPago_url,
                                cp.estadoPago,dp.iconoPago,dp.metodoPago,dp.descripcionPago,
                                d.Distrito,p.Provincia,dept.Departamento
                                FROM reservas r 
                                JOIN cotizaciones c ON r.id_cotizacion = c.id_cotizacion 
                                JOIN clientes cl ON c.id_cliente = cl.id_cliente 
                                JOIN capturapago cp ON r.idCapt = cp.idCapt 
                                JOIN descripcionpago dp ON cp.idPago = dp.idPago 
                                JOIN distritos d ON r.idDist = d.idDist 
                                JOIN provincias p ON d.idProv = p.idProv 
                                JOIN departamentos dept ON p.idDepa = dept.idDepa 
                                WHERE r.id_reserva = ?");
    $query->bind_param("i", $idReserva);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows > 0) {
        $reserva = $result->fetch_assoc(); // Obtener los datos
    } else {
        echo "No se encontró la reserva.";
        exit;
    }
} else {
    echo "No se proporcionó un ID válido.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle de Reserva</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../../public/css/admin/detalleReserva.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body>
    <?php include '../../../includes/headerAdmin.php'; ?>

    <div class="container my-5">
        <h1 class="text-center mb-5">Detalle de Reserva</h1>
        <!-- Botones: Volver y Actualizar -->
        <div class="button-group mb-4">
            <button class="btn btn-back" type="button" onclick="window.history.back();">
                <i class="bi bi-arrow-left"></i> Volver
            </button>
            <button class="btn btn-refresh" type="button" onclick="location.reload();">
                <i class="bi bi-arrow-clockwise"></i> Actualizar Información
            </button>
        </div>
        <div class="row">
            <!-- Información de la Reserva -->
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-header bg-primary text-white">
                        <h2 class="card-title h5 mb-0">Información de la Reserva</h2>
                    </div>
                    <div class="card-body">
                        <div class="detalle-item">
                            <span class="fw-bold">ID Reserva:</span>
                            <span><?php echo $reserva['id_reserva']; ?></span>
                        </div>
                        <div class="detalle-item">
                            <span class="fw-bold">Dirección de Reserva:</span>
                            <span><?php echo $reserva['direccion_reserva']; ?></span>
                        </div>
                        <div class="detalle-item">
                            <span class="fw-bold">Fecha de Reserva:</span>
                            <span><?php echo $reserva['fecha_reserva']; ?></span>
                        </div>
                        <div class="detalle-item">
                            <span class="fw-bold">Hora de Reserva:</span>
                            <span><?php echo $reserva['hora_reserva'] . ' ' . $reserva['ampm']; ?></span>
                        </div>
                        <div class="detalle-item">
                            <span class="fw-bold">Estado de la Reserva:</span>
                            <span class="badge bg-info"><?php echo $reserva['estado_reserva']; ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Información del Cliente -->
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-header bg-success text-white">
                        <h2 class="card-title h5 mb-0">Información del Cliente</h2>
                    </div>
                    <div class="card-body">
                        <div class="detalle-item">
                            <span class="fw-bold">Nombre del Cliente:</span>
                            <span><?php echo $reserva['nombre_cliente']; ?></span>
                        </div>
                        <div class="detalle-item">
                            <span class="fw-bold">Teléfono:</span>
                            <span><?php echo $reserva['telefono_cliente']; ?></span>
                        </div>
                        <div class="detalle-item">
                            <span class="fw-bold">Email:</span>
                            <span><?php echo $reserva['email']; ?></span>
                        </div>
                        <div class="detalle-item">
                            <span class="fw-bold">Dirección:</span>
                            <span><?php echo $reserva['direccion_cliente']; ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Información de Pago -->
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-header bg-warning">
                        <h2 class="card-title h5 mb-0">Información de Pago</h2>
                    </div>
                    <div class="card-body">
                        <div class="detalle-item">
                            <span class="fw-bold">Método de Pago:</span>
                            <span><?php echo $reserva['descripcionPago']; ?></span>
                        </div>
                        <div class="detalle-item">
                            <span class="fw-bold">Estado de Pago:</span>
                            <span class="badge bg-success"><?php echo $reserva['estadoPago']; ?></span>
                        </div>
                        <div class="detalle-item">
                            <span class="fw-bold">Descripción del Pago:</span>
                            <span><?php echo $reserva['metodoPago']; ?></span>
                        </div>
                        <div class="detalle-item">
                            <span class="fw-bold">Captura de Pago:</span><br>
                            <span>
                                <img src="../../../uploads/comprobantes/<?php echo $reserva['capturaPago_url']; ?>" alt="Captura de Pago" class="img-thumbnail mt-2" style="max-height: 200px;">
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ubicación -->
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-header bg-info text-white">
                        <h2 class="card-title h5 mb-0">Ubicación</h2>
                    </div>
                    <div class="card-body">
                        <div class="detalle-item">
                            <span class="fw-bold">Distrito:</span>
                            <span><?php echo $reserva['Distrito']; ?></span>
                        </div>
                        <div class="detalle-item">
                            <span class="fw-bold">Provincia:</span>
                            <span><?php echo $reserva['Provincia']; ?></span>
                        </div>
                        <div class="detalle-item">
                            <span class="fw-bold">Departamento:</span>
                            <span><?php echo $reserva['Departamento']; ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>