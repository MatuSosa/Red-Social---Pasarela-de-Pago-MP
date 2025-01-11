<?php
session_start();
// Incluye el archivo de conexión
include '../modelo/config.php';

// Verifica si el usuario ha iniciado sesión
if (!isset($_SESSION['unique_id'])) {
    // Redirigir o mostrar un mensaje de error si el usuario no ha iniciado sesión
    // ...
}

// Verifica si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST["user_id"];
    $content = $_POST["estado"];
    $media_type = "text"; // Predeterminado para texto
    $media_url = "";

    // Procesar los archivos de medios si se proporcionaron
    if ($_FILES["foto"]["size"] > 0) {
        // Subir la imagen
        $target_dir = "../images/";
        $target_file = $target_dir . basename($_FILES["foto"]["name"]);
        $media_type = "photo"; // Cambiar según el tipo de archivo
        $media_url = $target_file;

        if (move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file)) {
            // Imagen subida exitosamente
        } else {
            $_SESSION["error_message"] = "Error al subir la imagen.";
            header("Location: ../vistas/profile.php");
            exit();
        }
    } elseif (!empty($_FILES["video"]["name"])) {
        // Subir el video
        $target_dir = "../videos/";
        $target_file = $target_dir . basename($_FILES["video"]["name"]);
        $media_type = "video"; // Cambiar según el tipo de archivo
        $media_url = $target_file;

        if (move_uploaded_file($_FILES["video"]["tmp_name"], $target_file)) {
            // Video subido exitosamente
        } else {
            $_SESSION["error_message"] = "Error al subir el video.";
            header("Location: ../vistas/feed.php");
            exit();
        }
    } elseif (!empty($_POST["video_link"])) {
        // Enlace de video
        $media_type = "video";
        $media_url = $_POST["video_link"];
    }

   

    // Insertar el estado en la base de datos
    $sql = "INSERT INTO estado (user_id, content, media_type, media_url) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isss", $user_id, $content, $media_type, $media_url);

    if ($stmt->execute()) {
        $_SESSION["status_message"] = "Estado publicado con éxito.";
    } else {
        $_SESSION["error_message"] = "Lo siento, inténtalo nuevamente..";
    }

    $stmt->close();
    header("Location: ../vistas/feed.php");
    exit();
}
?>
