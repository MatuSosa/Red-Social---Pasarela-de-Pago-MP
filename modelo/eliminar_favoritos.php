<?php
session_start();
include_once '../modelo/config.php';
if (isset($_SESSION['unique_id'])) {
    $loggedInUserId = $_SESSION['unique_id'];
    $cursoId = $_POST['curso_id'];

    // Consulta SQL para obtener el user_id basado en el unique_id
    $sql = "SELECT user_id FROM users WHERE unique_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $loggedInUserId); // Usar loggedInUserId aquí
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $user_id_sesion = $row['user_id'];
    }

    // Consulta SQL para eliminar el curso de favoritos
    $sql = "DELETE FROM cursos_favoritos WHERE user_id = ? AND curso_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $user_id_sesion, $cursoId);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Curso eliminado de favoritos correctamente.']);
        exit;
    } else {
        echo json_encode(['success' => false, 'message' => 'No se pudo eliminar el curso de favoritos.']);
        exit;
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Usuario no autenticado.']);
    exit;
}

?>
