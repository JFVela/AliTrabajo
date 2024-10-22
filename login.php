<!DOCTYPE html>
<html lang="es-pe">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="Styles/login.css">
    <link rel="stylesheet" href="Styles/includes.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="body__login">
    <header class="body__header">
        <div class="hamburger" id="hamburger">
            <span class="bar"></span>
            <span class="bar"></span>
            <span class="bar"></span>
        </div>
        <nav class="navbar" id="navbar">
            <a href="#" class="links__a">Inicio</a>
            <a href="#" class="links__a">Paquetes</a>
            <a href="#" class="links__a">Cotización</a>
            <a href="#" class="links__a">Reservas</a>
            <a href="#" class="links__a">FAQ</a>
        </nav>
    </header>



    <div class="contenido__inicioSecion">
        <div class="imagenLogin">
            <img src="img/session.jpeg" alt="imagenSesion">
        </div>
        <div class="iniciarsesion">
            <p class="titulo">Crear Usuario</p>
            <form id="formCrearUsuario" action="funcion/crearUsuario.php" method="post">
                <ul>
                    <li>
                        <label for="nombre">Nombre completo:</label>
                        <input type="text" id="nombre" name="user_nombre" class="formulario__input" required />
                    </li>
                    <li>
                        <label for="telefono">Teléfono:</label>
                        <input type="tel" id="telefono" name="user_telefono" class="formulario__input" required />
                    </li>
                    <li>
                        <label for="direccion">Dirección:</label>
                        <input type="text" id="direccion" name="user_direccion" class="formulario__input" required />
                    </li>
                    <li>
                        <label for="email">Correo electrónico:</label>
                        <input type="email" id="email" name="user_email" class="formulario__input" required />
                    </li>
                    <li>
                        <label for="dni">DNI:</label>
                        <input type="text" id="dni" name="user_dni" class="formulario__input" required />
                    </li>
                    <li>
                        <label for="password">Contraseña:</label>
                        <input type="password" id="password" name="user_password" class="formulario__input" required />
                    </li>
                    <li class="button__login">
                        <button type="submit">Crear usuario</button>
                    </li>
                </ul>
            </form>

            <p class="iniciar">Ya tienes cuenta? Inicia sesión <a class="linkInicio" href="">Aquí</a></p>
        </div>
    </div>

    <footer class="footer__secion">
        <div class="parte1">
            <div class="texto">
                <h1 class="tituloEmpresa">Company Name</h1>
                <p class="parrafoFooter">
                    Lorem ipsum dolor sit amet, consectetur
                    adipiscing elit, sed do eiusmod tempor
                    incididunt ut labore et dolore magna aliqua.
                </p>
                <div class="iconos">
                    <a href="#" class="links__iconos">
                        <i class="bi bi-facebook"></i>
                    </a>
                    <a href="" class="links__iconos">
                        <i class="bi bi-twitter-x"></i>
                    </a>
                    <a href="" class="links__iconos">
                        <i class="bi bi-instagram"></i>
                    </a>
                    <a href="" class="links__iconos">
                        <i class="bi bi-tiktok"></i>
                    </a>
                </div>
            </div>

            <div class="links__footer">
                <ul class="footer__menu">
                    <li><a href="#">Inicio</a></li>
                    <li><a href="#">Acerca de</a></li>
                    <li><a href="#">Servicios</a></li>
                    <li><a href="#">Contacto</a></li>
                </ul>
            </div>
        </div>
        <div class="parte2">
            <img src="img/logo.jpeg" alt="logo">
            <p>© 2023 Lorem Ipsum. All Rights Reserved.</p>
        </div>
    </footer>
</body>

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
</script>
<script>
    document.getElementById('formCrearUsuario').addEventListener('submit', function(event) {
        event.preventDefault();

        // Validar nombre (sin números)
        const nombre = document.getElementById('nombre').value;
        if (/\d/.test(nombre)) {
            Swal.fire('Error', 'El nombre no debe contener números.', 'error');
            return;
        }

        // Validar teléfono (9 dígitos)
        const telefono = document.getElementById('telefono').value;
        if (!/^\d{9}$/.test(telefono)) {
            Swal.fire('Error', 'El teléfono debe contener 9 dígitos.', 'error');
            return;
        }

        // Validar DNI (8 dígitos)
        const dni = document.getElementById('dni').value;
        if (!/^\d{8}$/.test(dni)) {
            Swal.fire('Error', 'El DNI debe contener 8 dígitos.', 'error');
            return;
        }

        // Validar contraseña (letras y números)
        const password = document.getElementById('password').value;
        if (!/(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]/.test(password)) {
            Swal.fire('Error', 'La contraseña debe contener tanto letras como números.', 'error');
            return;
        }

        // Si todas las validaciones son correctas, se envía el formulario
        this.submit();
    });
</script>

</html>