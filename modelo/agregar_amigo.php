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
        $user_id_sesion = $row['user_id'];
    }

    // Obtén el user_id del usuario que se va a agregar como amigo (enviado a través de la solicitud POST)
    if (isset($_POST['userIdToAdd'])) {
        $userIdToAdd = $_POST['userIdToAdd'];

        // Verifica si el usuario ya es amigo antes de agregarlo
        $checkFriendshipQuery = "SELECT * FROM friends WHERE user_id = ? AND friend_id = ?";
        $stmt = mysqli_prepare($conn, $checkFriendshipQuery);
        mysqli_stmt_bind_param($stmt, "ii", $user_id_sesion, $userIdToAdd);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) == 0) {
            // El usuario no es amigo, así que agrégalo como amigo con estado 'pending'
            $addFriendQuery = "INSERT INTO friends (user_id, friend_id, status) VALUES (?, ?, 'pending')";
            $addFriendsQuery = "INSERT INTO friends (user_id, friend_id, status) VALUES (?, ?, 'accepted')";
            $stmt1 = mysqli_prepare($conn, $addFriendQuery);
            $stmt2 = mysqli_prepare($conn, $addFriendsQuery);
            mysqli_stmt_bind_param($stmt1, "ii", $user_id_sesion, $userIdToAdd);
            mysqli_stmt_bind_param($stmt2, "ii", $userIdToAdd, $user_id_sesion);

            // Ejecutar ambas inserciones
            mysqli_stmt_execute($stmt1);
            mysqli_stmt_execute($stmt2);
            if (mysqli_stmt_affected_rows($stmt1) > 0 && mysqli_stmt_affected_rows($stmt2) > 0) {
                // Éxito al enviar solicitud de amistad
                $_SESSION["status_message"] = "Solicitud enviada";
                echo json_encode(['success' => true]);
                exit;
            } else {
                // Error al enviar solicitud de amistad
                $_SESSION["error_message"] = "Ops..! error al enviar solicitud";
                echo json_encode(['success' => false]);
                exit;
            }
        } else {
            // El usuario ya es amigo o la solicitud está pendiente
            echo json_encode(['success' => false, 'message' => 'El usuario ya es tu amigo o la solicitud está pendiente']);
            exit;
        }
 
    }
}
?>