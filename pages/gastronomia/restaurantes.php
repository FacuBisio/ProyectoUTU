<?php
require_once("../../conexion.php");

$sql = "SELECT 
            LUGAR.ID_LUGAR,
            LUGAR.NOMBRE,
            LUGAR.DESCRIPCION,
            LUGAR.DIRECCION,
            LUGAR.IMAGEN
        FROM LUGAR
        INNER JOIN LUGAR_CATEGORIA
            ON LUGAR.ID_LUGAR = LUGAR_CATEGORIA.ID_LUGAR
        WHERE LUGAR_CATEGORIA.ID_CATEGORIA = 7";

$resultado = $conexion->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurantes</title>

    <link rel="stylesheet" href="<?php echo '../../assets/css/var.css'; ?>">
    <link rel="stylesheet" href="<?php echo '../../assets/css/styles.css'; ?>">
    <link rel="stylesheet" href="<?php echo '../../assets/css/componentes.css'; ?>">
    <link rel="stylesheet" href="<?php echo '../../assets/css/style-secciones.css'; ?>">
    <link rel="stylesheet" href="<?php echo '../../assets/css/slider.css'; ?>">
    <link rel="stylesheet" href="<?php echo '../../assets/css/comments.css'; ?>">

    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>

<body>

<main class="main-content pagina-interna">

<?php include("../../includes/navbar.php"); ?>

<section class="slider-container">

    <button class="slider-btn prev">❮</button>

    <?php
    $primero = true;

    while ($lugar = $resultado->fetch_assoc()) {
    ?>

        <div class="slide <?php echo $primero ? 'active' : ''; ?>">

            <img 
                src="../../<?php echo htmlspecialchars($lugar['IMAGEN']); ?>" 
                alt="<?php echo htmlspecialchars($lugar['NOMBRE']); ?>"
            >

            <div class="slide-info">

                <h2>
                    <?php echo htmlspecialchars($lugar['NOMBRE']); ?>
                </h2>

                <p>
                    <?php echo htmlspecialchars($lugar['DESCRIPCION']); ?>
                </p>

                <p>
                    <strong>Dirección:</strong>
                    <?php echo htmlspecialchars($lugar['DIRECCION']); ?>
                </p>

            </div>

        </div>

    <?php
        $primero = false;
    }
    ?>

    <button class="slider-btn next">❯</button>

    <div class="slider-dots">

        <?php
        $resultado->data_seek(0);

        $primer_dot = true;

        while ($lugar = $resultado->fetch_assoc()) {
        ?>

            <span class="dot <?php echo $primer_dot ? 'active' : ''; ?>"></span>

        <?php
            $primer_dot = false;
        }
        ?>

    </div>

</section>

<?php include("../../includes/comments.php"); ?>

</main>

<?php include("../../includes/footer.php"); ?>

<?php include("../../includes/chat-widget.php"); ?>

<script src="../../assets/js/slider.js"></script>

</body>
</html>