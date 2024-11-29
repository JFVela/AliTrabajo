<?php
// Obtén el nombre del archivo actual
$current_page = basename($_SERVER['PHP_SELF']);
?>
<style>
    /* Estilo del enlace activo */
    .active-link {
        color: white !important;
        /* Cambia el color del texto */
        background-color: red !important;
        /* Fondo rojo */
        border-radius: 5px;
        /* Opcional, para darle un poco de forma */
    }
</style>
<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
        <a class="navbar-brand" href="Dashboard.php">Navbar</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo02"
            aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarTogglerDemo02">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link <?php echo $current_page == 'Dashboard.php' ? 'active-link' : ''; ?>" href="Dashboard.php">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $current_page == 'categorias.php' ? 'active-link' : ''; ?>" href="categorias.php">Categorias</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $current_page == 'cotizaciones.php' ? 'active-link' : ''; ?>" href="cotizaciones.php">Cotizaciones</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $current_page == 'listaClientes.php' ? 'active-link' : ''; ?>" href="listaClientes.php">Clientes</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $current_page == 'productos.php' ? 'active-link' : ''; ?>" href="productos.php">Servicios</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $current_page == 'reservas.php' ? 'active-link' : ''; ?>" href="reservas.php">Reservas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link disabled" aria-disabled="true">Bienvenido!</a>
                </li>
            </ul>
        </div>
    </div>
</nav>