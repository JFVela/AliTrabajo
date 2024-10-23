<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="Styles/includes.css">
    <link rel="stylesheet" href="Styles/index.css">
</head>

<body>

    <?php
    include 'includes\header.php';
    include 'includes\carusel.php';
    ?>
    <div class="divisor">
        <h2>¡Descubre nuestros Servicios Exclusivos diseñados para hacer realidad tus ideas más increíbles! Desde soluciones tecnológicas innovadoras hasta un soporte cercano y personalizado, estamos aquí para llevar tu proyecto al siguiente nivel. 🚀✨ ¿Listo para vivir la experiencia que te mereces? ¡Con nosotros, lo imposible se hace posible! 🌟</h2>
    </div>
    <div id="servicios">
        <div class="card">
            <div class="card2" style="background-image: url('/img/index/diseño1.jpeg');">
                <div class="overlay">
                    <p><a class="link_Index" href="">Cotizar Aquí</a></p>
                </div>
            </div>
            <h3>Servicio 1</h3>
        </div>

        <div class="card">
            <div class="card2" style="background-image: url('/img/index/diseño2.jpeg');">
                <div class="overlay">
                    <p><a class="link_Index" href="">Cotizar Aquí</a></p>
                </div>
            </div>
            <h3>Servicio 2</h3>
        </div>

        <div class="card">
            <div class="card2" style="background-image: url('/img/index/diseño3.jpeg');">
                <div class="overlay">
                    <p><a class="link_Index" href="">Cotizar Aquí</a></p>
                </div>
            </div>
            <h3>Servicio 3</h3>
        </div>

        <div class="card">
            <div class="card2" style="background-image: url('/img/index/diseño4.jpeg');">
                <div class="overlay">
                    <p><a class="link_Index" href="">Cotizar Aquí</a></p>
                </div>
            </div>
            <h3>Servicio 4</h3>
        </div>

        <div class="card">
            <div class="card2" style="background-image: url('/img/index/diseño5.jpeg');">
                <div class="overlay">
                    <p><a class="link_Index" href="">Cotizar Aquí</a></p>
                </div>
            </div>
            <h3>Servicio 5</h3>
        </div>
    </div>

    <?php
    include 'includes\footer.php';
    ?>
</body>

</html>