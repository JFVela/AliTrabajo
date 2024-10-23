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

    <?php
    include 'includes/header.php';
    ?>

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
                        <input type="text" id="nombre" name="user_nombre" class="formulario__input" placeholder="Nombre(s) y Apellido(s)" required />
                    </li>
                    <li>
                        <label for="telefono">Teléfono:</label>
                        <input type="tel" id="telefono" name="user_telefono" class="formulario__input" placeholder="Ingrese 9 dígitos" required />
                    </li>
                    <li>
                        <label for="direccion">Dirección:</label>
                        <input type="text" id="direccion" name="user_direccion" class="formulario__input" placeholder="Departamento/Distrito/Calle" required />
                    </li>
                    <li>
                        <label for="email">Correo electrónico:</label>
                        <input type="email" id="email" name="user_email" class="formulario__input" placeholder="correo@ejemplo.com" required />
                    </li>
                    <li>
                        <label for="dni">DNI:</label>
                        <input type="number" id="dni" name="user_dni" class="formulario__input" placeholder="Ingrese 8 dígitos" required />
                    </li>
                    <li>
                        <label for="password">Contraseña:</label>
                        <input type="password" id="password" name="user_password" class="formulario__input" placeholder="Tanto números y letras" required />
                    </li>
                    <li class="button__login">
                        <button type="button" id="crearUsuarioBtn">Crear usuario</button>
                    </li>
                </ul>
            </form>
            <p class="iniciar">Ya tienes cuenta? Inicia sesión <a class="linkInicio" href="loginCliente.php">Aquí</a></p>
        </div>
    </div>

    <?php
    include 'includes/footer.php';
    ?>

</body>
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