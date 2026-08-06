<?php

require_once(__DIR__ . "/../conexion.php");

$id_lugar = isset($id_lugar) ? $id_lugar : 1;


// GUARDAR COMENTARIO
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $comentario = trim($_POST["comentario"]);

    $id_usuario = 3;

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
    }
}


// MOSTRAR COMENTARIOS

$sql = "SELECT comentario.comentario, usuario.nombre
        FROM comentario
        INNER JOIN usuario
        ON comentario.id_usuario = usuario.id_usuario
        WHERE comentario.id_lugar = ?
        ORDER BY id_comentario DESC";


$stmt = $conexion->prepare($sql);

$stmt->bind_param("i", $id_lugar);

$stmt->execute();

$resultado = $stmt->get_result();

?>


<section class="comentarios">

<h2>Comentarios</h2>


<form method="POST">

<textarea 
name="comentario"
placeholder="Escribe tu comentario..."
required></textarea>

<button type="submit">
Publicar
</button>

</form>


<?php while($fila = $resultado->fetch_assoc()) { ?>

<div class="comentario">

<strong>
<?php echo $fila["nombre"]; ?>
</strong>

<p>
<?php echo $fila["comentario"]; ?>
</p>

</div>

<?php } ?>


</section>