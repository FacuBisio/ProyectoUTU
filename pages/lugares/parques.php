<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parques</title>
    
    <link rel="stylesheet" href="<?php echo '../../assets/css/var.css'; ?>">
    <link rel="stylesheet" href="<?php echo '../../assets/css/styles.css'; ?>">
    <link rel="stylesheet" href="<?php echo '../../assets/css/componentes.css'; ?>">
    <link rel="stylesheet" href="<?php echo '../../assets/css/style-secciones.css'; ?>">
    <link rel="stylesheet" href="<?php echo '../../assets/css/slider.css'; ?>">  

    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>
<body>

<main class="main-content pagina-interna">

<!-- NAVBAR -->
<?php include("../../includes/navbar.php"); ?>

<!-- SLIDER -->
<?php
// include("../../includes/slider.php");
?>

<!-- SLIDER -->

<section class="slider-container">

    <button class="slider-btn prev">&#10094;</button>

    <div class="slide active">

        <img src="../../assets/img/parqueharriague.jpg" alt="Parque Harriague">

        <div class="slide-info">
            <h2>Parque Harriague</h2>
            <p>
                Un espacio verde ideal para relajarse, hacer ejercicio y disfrutar de la naturaleza. 
                El parque cuenta con áreas de picnic, senderos para caminar y zonas de juegos para niños.
            </p>
        </div>

    </div>

    <div class="slide">

        <img src="../../assets/img/parquesolari.png" alt="Parque Solari">

        <div class="slide-info">
            <h2>Parque Solari</h2>
            <p>
                Este parque es perfecto para familias y personas que buscan disfrutar de la naturaleza. 
                El parque ofrece instalaciones para deportes y áreas de descanso.
            </p>
        </div>

    </div>

    <div class="slide">

        <img src="../../assets/img/parqueindigena.jpg" alt="Parque Indigena Vaimaca Pirú">

        <div class="slide-info">
            <h2>Parque Indigena Vaimaca Pirú</h2>
            <p>
                Un espacio verde y acogedor, perfecto para familias y personas que buscan disfrutar de la naturaleza. 
                El parque ofrece instalaciones para deportes y áreas de descanso.
            </p>
        </div>

    </div>

    <button class="slider-btn next">&#10095;</button>

    <div class="slider-dots">
        <span class="dot active"></span>
        <span class="dot"></span>
        <span class="dot"></span>
    </div>

</section>

<!-- SECCION COMENTARIOS -->
<?php include("../../includes/comments.php"); ?>

</main>

<!-- FOOTER -->
<?php include("../../includes/footer.php"); ?>

<!-- CHAT PERSONAS -->
<?php include("../../includes/chat-widget.php"); ?>


<script src="../../assets/js/slider.js"></script>

</body>
</html>