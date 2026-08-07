<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once(__DIR__ . "/../conexion.php");

$id_lugar = isset($id_lugar) ? $id_lugar : 1;


// GUARDAR COMENTARIO
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["comentario"])) {

    // Verificar que el usuario haya iniciado sesión
    if (!isset($_SESSION["id_usuario"])) {
        die("Debes iniciar sesión para comentar.");
    }

    $comentario = trim($_POST["comentario"]);
    $id_usuario = $_SESSION["id_usuario"];

    if ($comentario != "") {

        $sql = "INSERT INTO comentario (id_usuario, id_lugar, comentario)
                VALUES (?, ?, ?)";

        $stmt = $conexion->prepare($sql);

        if (!$stmt) {
            die("Error en la consulta: " . $conexion->error);
        }

        $stmt->bind_param(
            "iis",
            $id_usuario,
            $id_lugar,
            $comentario
        );

        if (!$stmt->execute()) {
            die("Error al guardar: " . $stmt->error);
        }

       echo "<script>
        window.location.href = window.location.pathname;
        </script>";
        exit();
    }
}



// MOSTRAR COMENTARIOS

$sql = "SELECT
            comentario.id_comentario,
            comentario.comentario,
            usuario.nombre
        FROM comentario
        INNER JOIN usuario
        ON comentario.id_usuario = usuario.id_usuario
        WHERE comentario.id_lugar = ?
        ORDER BY comentario.id_comentario DESC";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id_lugar);
$stmt->execute();

$resultado = $stmt->get_result();

?>

<section class="comentarios">

    <h2>Comentarios</h2>

    <?php if(isset($_SESSION["id_usuario"])): ?>

        <form method="POST">

            <textarea
                name="comentario"
                placeholder="Escribe tu comentario..."
                required></textarea>

            <button type="submit">
                Publicar
            </button>

        </form>

    <?php else: ?>

        <p>
            Debes iniciar sesión para poder comentar.
        </p>

    <?php endif; ?>


    <?php while($fila = $resultado->fetch_assoc()) { ?>

        <div class="comentario">

            <strong>
                <?= htmlspecialchars($fila["nombre"]) ?>
            </strong>

            <p>
                <?= nl2br(htmlspecialchars($fila["comentario"])) ?>
            </p>

        </div>

    <?php } ?>

</section>