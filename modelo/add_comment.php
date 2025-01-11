<?php
session_start();
include '../modelo/config.php';
// Verifica si el usuario ha iniciado sesión
if (!isset($_SESSION['unique_id'])) {
    // Redirigir o mostrar un mensaje de error si el usuario no ha iniciado sesión
    // ...
}

// Obtener el user_id basado en el unique_id de la sesión
$unique_id_sesion = $_SESSION['unique_id'];

// Consulta SQL para obtener el user_id basado en el unique_id
$sql = "SELECT user_id FROM users WHERE unique_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $unique_id_sesion);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $user_id_sesion = $row['user_id'];
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $unique_id = $_POST["unique_id"]; // Usuario que escribió el comentario
    $estado_id = $_POST["estado_id"];
    $nuevo_comentario = $_POST["nuevo_comentario"];
    $comentario_padre_id = $_POST["comentario_padre_id"];
    // Validar que el comentario no esté vacío
    if (empty($nuevo_comentario)) {
        $_SESSION["error_message"] = "El comentario no puede estar vacío.";
            header("Location: ../vistas/feed.php"); 
       exit();
       
    }

    // Insertar el comentario en la base de datos
    $sql = "INSERT INTO comentarios (estado_id, unique_id, user_id, contenido, fecha_creacion, comentario_padre_id) VALUES (?, ?, ?, ?, NOW(), ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiisi", $estado_id, $unique_id, $user_id_sesion, $nuevo_comentario, $comentario_padre_id);


    if ($stmt->execute()) {
        $_SESSION["status_message"] = "comentario enviado";
    } else {
        $_SESSION["error_message"] = "Ops..! error al enviar comentario";
    }

    $stmt->close();
    header("Location: ../vistas/feed.php");
} else {
    header("Location: ../vistas/feed.php");
        exit();
}

?>
