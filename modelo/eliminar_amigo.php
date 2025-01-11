<?php
session_start();
include '../modelo/config.php';

if (isset($_SESSION['unique_id'])) {
    $unique_id_sesion = $_SESSION['unique_id'];
    $sql = "SELECT user_id FROM users WHERE unique_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $unique_id_sesion);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $user_id_sesion = $row['user_id'];
    }

    // Obtén el user_id del usuario que se va a eliminar como amigo (enviado a través de la solicitud POST)
    if (isset($_POST['userIdToRemove'])) {
        $userIdToRemove = $_POST['userIdToRemove'];

        // Verifica si el usuario es amigo antes de eliminarlo
        $checkFriendshipQuery = "SELECT * FROM friends WHERE (user_id = ? AND friend_id = ?) OR (user_id = ? AND friend_id = ?)";
        $stmt = mysqli_prepare($conn, $checkFriendshipQuery);
        mysqli_stmt_bind_param($stmt, "iiii", $user_id_sesion, $userIdToRemove, $userIdToRemove, $user_id_sesion);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            // El usuario es amigo, así que elimínalo como amigo en ambas direcciones
            $deleteFriendQuery = "DELETE FROM friends WHERE (user_id = ? AND friend_id = ?) OR (user_id = ? AND friend_id = ?)";
            $stmt = mysqli_prepare($conn, $deleteFriendQuery);
            mysqli_stmt_bind_param($stmt, "iiii", $user_id_sesion, $userIdToRemove, $userIdToRemove, $user_id_sesion);
            mysqli_stmt_execute($stmt);

            if (mysqli_stmt_affected_rows($stmt) > 0) {
                // Eliminación exitosa
                $_SESSION["status_message"] = "Amigo eliminado correctamente.";
                echo json_encode(['success' => true]);
                exit;
            } else {
                // Error al eliminar amigo
                $_SESSION["error_message"] = "Error al eliminar amigo.";
                echo json_encode(['success' => false]);
                exit;
            }
        } else {
            // El usuario no es amigo o la solicitud está pendiente
            echo json_encode(['success' => false, 'message' => 'El usuario no es tu amigo']);
            exit;
        }
    }
}
?>
