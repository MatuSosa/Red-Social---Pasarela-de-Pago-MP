<?php
session_start();
include '../modelo/config.php';

if (isset($_SESSION['unique_id'])) {
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
        $user_id = $row['user_id'];
    }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtén los datos del estado desde la solicitud POST
    $estado_id = $_POST['estado_id'];
    $contenido = $_POST['contenido'];
    $mediatype = $_POST['media_type'];
    $mediaurl = $_POST['media_url'];

    // Inserta el estado compartido en la base de datos
    include_once 'config.php'; // Incluye el archivo de configuración de la base de datos
    $sql = "INSERT INTO estado (user_id, content, media_type, media_url, created_at) VALUES (?, ?, ?, ?, NOW())"; // Corrección aquí
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'isss', $user_id, $contenido, $mediatype, $mediaurl);
    // $user_id debe ser el ID del usuario que está compartiendo el estado
    // Puedes obtener el ID del usuario desde la sesión o cualquier otro método de autenticación
    mysqli_stmt_execute($stmt);

    // Verifica si la inserción fue exitosa y envía una respuesta al cliente
    if (mysqli_stmt_affected_rows($stmt) > 0) {
        $_SESSION["status_message"] = "Estado publicado con éxito.";
        echo 'Estado compartido exitosamente.';
    } else {
        $_SESSION["error_message"] = "Lo siento, inténtalo nuevamente..";
        echo 'Error al compartir el estado.';
    }

    // Cierra la declaración y la conexión a la base de datos
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
} else {
    // Si la solicitud no es POST, responde con un error
    echo 'Método no permitido.';
}
}
?>
