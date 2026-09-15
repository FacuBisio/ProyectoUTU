<?php

require_once("../../conexion.php");

$sql = "SELECT 
            EVENTO.ID_EVENTO,
            EVENTO.NOMBRE,
            EVENTO.HORA_INI,
            EVENTO.HORA_FIN,
            EVENTO.DIA,
            EVENTO.MES,
            LUGAR.DIRECCION,
            LUGAR.IMAGEN
        FROM EVENTO
        INNER JOIN LUGAR 
            ON EVENTO.ID_LUGAR = LUGAR.ID_LUGAR
        ORDER BY EVENTO.ID_EVENTO DESC";

$resultado = $conexion->query($sql);

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Eventos</title>

    <link rel="stylesheet" href="<?php echo '../../assets/css/var.css'; ?>">
    <link rel="stylesheet" href="<?php echo '../../assets/css/styles.css'; ?>">
    <link rel="stylesheet" href="<?php echo '../../assets/css/componentes.css'; ?>">
    <link rel="stylesheet" href="../../assets/css/comentarios.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <link rel="stylesheet" href="../../assets/css/eventos.css">

</head>

<body>
  <?php include("../../includes/navbar.php"); ?>

    <h1>Eventos</h1>

    <div class="eventos-container">

        <?php while ($evento = $resultado->fetch_assoc()) { ?>

            <div class="evento">

                <img 
                    src="../../<?php echo $evento["IMAGEN"]; ?>" 
                    alt="<?php echo $evento["NOMBRE"]; ?>"
                >

                <div class="evento-info">

                    <h2>
                        <?php echo $evento["NOMBRE"]; ?>
                    </h2>

                    <p>
                        📍 <?php echo $evento["DIRECCION"]; ?>
                    </p>

                    <p>
                        📅 <?php echo $evento["DIA"]; ?>/<?php echo $evento["MES"]; ?>
                    </p>

                    <p>
                        🕐 <?php echo $evento["HORA_INI"]; ?> - <?php echo $evento["HORA_FIN"]; ?>
                    </p>

                </div>

            </div>

        <?php } ?>

    </div>
    
    <?php include("../../includes/footer.php"); ?>

<?php include("../../includes/chat-widget.php"); ?>

</body>

</html>