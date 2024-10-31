<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selecciona un Paquete</title>
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
            <div class="step active">2. Selecciona el paquete de tu preferencia</div>
            <div class="step">3. Completa tus datos</div>
        </div>

        <div class="package-selection">
            <img src="servicio_seleccionado.jpg" alt="Servicio Seleccionado">
            <p>Servicio Seleccionado</p>

            <select name="paquete" id="paquete">
                <option value="paquete1">Paquete 1</option>
                <option value="paquete2">Paquete 2</option>
                <option value="paquete3">Paquete 3</option>
            </select>
        </div>

        <a href="cotizacion3.php" class="button">Continuar</a>
        <a href="cotizacion1.php" class="button">Volver</a>
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