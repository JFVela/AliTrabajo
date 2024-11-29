<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración - SoundRent</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../../../public/css/admin/Dashboard.css">
</head>

<body>

    <?php include '../../../includes/headerAdmin.php'; ?>

    <div class="dashboard-container">
        <main>
            <div class="dashboard-cards">
                <a href="categorias.php" class="card category-card text-decoration-none">
                    <div class="card-body">
                        <i class="bi bi-grid-3x3-gap"></i>
                        <h2 class="card-title">Categorías</h2>
                        <p class="card-text">Gestiona las categorías de equipos y servicios</p>
                    </div>
                </a>
                <a href="cotizaciones.php" class="card quote-card text-decoration-none">
                    <div class="card-body">
                        <i class="bi bi-file-earmark-text"></i>
                        <h2 class="card-title">Cotizaciones</h2>
                        <p class="card-text">Administra las cotizaciones de los clientes</p>
                    </div>
                </a>
                <a href="listaClientes.php" class="card client-card text-decoration-none">
                    <div class="card-body">
                        <i class="bi bi-people"></i>
                        <h2 class="card-title">Clientes</h2>
                        <p class="card-text">Gestiona la información de tus clientes</p>
                    </div>
                </a>
                <a href="productos.php" class="card service-card text-decoration-none">
                    <div class="card-body">
                        <i class="bi bi-music-note-beamed"></i>
                        <h2 class="card-title">Servicios</h2>
                        <p class="card-text">Administra los servicios ofrecidos</p>
                    </div>
                </a>
                <a href="reservas.php" class="card booking-card text-decoration-none">
                    <div class="card-body">
                        <i class="bi bi-calendar-check"></i>
                        <h2 class="card-title">Reservas</h2>
                        <p class="card-text">Gestiona las reservas de equipos y servicios</p>
                    </div>
                </a>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>