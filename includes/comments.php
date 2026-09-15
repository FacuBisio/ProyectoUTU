<?php
require_once(__DIR__ . "/../conexion.php");

$sql = "SELECT 
            COMENTARIO.COMENTARIO,
            USUARIO.NOMBRE,
            COMENTARIO.FECHA
        FROM COMENTARIO
        INNER JOIN USUARIO
            ON COMENTARIO.ID_USUARIO = USUARIO.ID_USUARIO
        ORDER BY COMENTARIO.ID_COMENTARIO DESC";

$resultado = $conexion->query($sql);
?>

<section class="comentarios">

    <h2>Comentarios sobre Salto</h2>

    <?php while ($comentario = $resultado->fetch_assoc()) { ?>

        <div class="comentario">

            <h3>
                <?php echo htmlspecialchars($comentario['NOMBRE']); ?>
            </h3>

            <p>
                <?php echo htmlspecialchars($comentario['COMENTARIO']); ?>
            </p>

            <small>
                <?php echo $comentario['FECHA']; ?>
            </small>

        </div>

    <?php } ?>

</section>