<?php
session_start();
include_once '../modelo/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['unique_id'])) {
    $loggedInUserId = $_SESSION['unique_id'];

    // Consulta SQL para obtener el user_id basado en el unique_id
    $sql = "SELECT user_id FROM users WHERE unique_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $loggedInUserId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $user_id = $row['user_id'];
    }

    $cursoId = $_POST['curso_id'];
    $reason = $_POST['reason'];
    $user_id_creador = $_POST['user_id_creador'];

    // Inserta la denuncia en la tabla de denuncias
    $sql = "INSERT INTO denuncias (user_id_reporter, user_id_seller, curso_id, fecha_denuncia, motivo)
            VALUES (?, ?, ?, NOW(), ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiis", $user_id, $user_id_creador, $cursoId, $reason);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        // Actualiza el campo "aprobada" a 1 en la tabla de cursos
        $sql = "UPDATE denuncias SET aprobada = 1 WHERE curso_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $cursoId);
        $stmt->execute();

        // Denuncia exitosa
        $_SESSION["status_message"] = "Hemos recibido la denuncia, nos pondremos en contacto a la brevedad";
    } else {
        // Error al denunciar
        $_SESSION["error_message"] = "No se pudo denunciar el contenido";
    }

    header("location: ../vistas/mis_compras.php");
    exit;
}

?>
