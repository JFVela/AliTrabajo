<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Completa tus Datos</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="index.php">Inicio</a></li>
                <li><a href="#">Paquetes</a></li>
                <li><a href="#">Cotizaciones</a></li>
                <li><a href="#">Reservas</a></li>
                <li><a href="#">Preguntas frecuentes</a></li>
            </ul>
        </nav>
    </header>

    <section class="quote-section">
        <h1>Solicita una cotización</h1>
        <div class="steps">
            <div class="step">1. Elige tu Servicio</div>
            <div class="step">2. Selecciona el paquete de tu preferencia</div>
            <div class="step active">3. Completa tus datos</div>
        </div>

        <div class="form-section">
            <img src="servicio_seleccionado.jpg" alt="Servicio Seleccionado">
            <p>Paquete seleccionado</p>

            <form action="submit.php" method="post">
                <label for="nombre">Nombre y Apellidos:</label>
                <input type="text" id="nombre" name="nombre" required>

                <label for="dni">Tipo y Nro. de Documento:</label>
                <input type="text" id="dni" name="dni" required>

                <label for="email">Correo electrónico:</label>
                <input type="email" id="email" name="email" required>

                <label for="celular">Número de celular:</label>
                <input type="tel" id="celular" name="celular" required>

                <label for="fecha">Fecha de tu evento:</label>
                <input type="date" id="fecha" name="fecha" required>

                <p>Forma preferida de contacto:</p>
                <input type="checkbox" id="whatsapp" name="contacto[]" value="WhatsApp">
                <label for="whatsapp">WhatsApp</label>
                <input type="checkbox" id="llamada" name="contacto[]" value="Llamada">
                <label for="llamada">Llamada</label>
                <input type="checkbox" id="correo" name="contacto[]" value="Correo Electrónico">
                <label for="correo">Correo Electrónico</label>

                <button type="submit" class="button">Cotizar Servicio</button>
                <a href="cotizacion2.php" class="button">Volver</a>
            </form>
        </div>
    </section>

    <footer>
        <div class="social">
            <a href="#">WhatsApp</a>
            <a href="#">Facebook</a>
            <a href="#">Instagram</a>
        </div>
        <p>Términos y condiciones | Soporte técnico</p>
    </footer>
</body>
</html>