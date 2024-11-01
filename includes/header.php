<?php
session_start();
?>
<header class="body__header">
    <div class="hamburger" id="hamburger">
        <span class="bar"></span>
        <span class="bar"></span>
        <span class="bar"></span>
    </div>
    <nav class="navbar" id="navbar">
        <a href="../usuario/index.php" class="links__a">Inicio</a>
        <a href="../usuario/cotizacion.php" class="links__a">Paquetes</a>
        <a href="#" class="links__a">Cotizaciones</a>
        <a href="#" class="links__a">Reservas</a>
        <a href="#" class="links__a">FAQ</a>

        <?php
        if (isset($_SESSION['emailcliente'])) {
            echo '<a href="../usuario/logout.php" class="links__a">Logout</a>';
            echo '<span class="links__a">Hola ' . $_SESSION['emailcliente'] . '!</span>';
        } else {
            echo '<a href="../usuario/loginCliente.php" class="links__a">Login</a>';
            echo '<a href="../usuario/createUser.php" class="links__a">Create</a>';
        }
        ?>

    </nav>
</header>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Selecciona los elementos necesarios
    const hamburger = document.getElementById("hamburger");
    const navbar = document.getElementById("navbar");

    // Evento para alternar la visibilidad del menú al hacer clic en el botón de hamburguesa
    hamburger.addEventListener("click", function() {
        navbar.classList.toggle("active"); // Alternar clase activa para mostrar/ocultar
    });

    // Evento para cerrar el menú si la ventana se redimensiona a un tamaño mayor
    window.addEventListener("resize", function() {
        if (window.innerWidth > 768) { // Cambia el tamaño según tu media query
            navbar.classList.remove("active"); // Cierra el menú
        }
    });

    <?php
    if (isset($_SESSION['mensaje'])) {
        echo "Swal.fire({
            position: '" . $_SESSION['posision'] . "',
            icon: '" . $_SESSION['icono'] . "',
            title: '" . $_SESSION['tituloMensaje'] . "',
            text: '" . $_SESSION['mensaje'] . "',
            showConfirmButton: false,
            timer: 3500
        });";
        unset($_SESSION['posision']);
        unset($_SESSION['icono']);
        unset($_SESSION['tituloMensaje']);
        unset($_SESSION['mensaje']);
    }
    ?>
</script>