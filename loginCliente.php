<!DOCTYPE html>
<html lang="es-pe">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="Styles/login.css">
    <link rel="stylesheet" href="Styles/includes.css">
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
            <p class="titulo">Iniciar Sesión</p>
            <form id="formIniciarSesion" action="funcion/iniciarSesion.php" method="post">
                <ul>
                    <li>
                        <label for="email">Correo electrónico:</label>
                        <input type="email" id="email" name="user_email" class="formulario__input" placeholder="correo@ejemplo.com" required />
                    </li>
                    <li>
                        <label for="password">Contraseña:</label>
                        <input type="password" id="password" name="user_password" class="formulario__input" placeholder="********" required />
                    </li>
                    <li class="button__login">
                        <button type="submit">Iniciar sesión</button>
                    </li>
                </ul>
            </form>

            <p class="iniciar">¿No tienes cuenta? Regístrate <a class="linkInicio" href="createUser.php">Aquí</a></p>
        </div>

    </div>

    <?php
    include 'includes/footer.php';
    ?>

</body>

</html>