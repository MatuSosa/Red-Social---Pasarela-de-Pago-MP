<?php
session_start();
include_once '../modelo/config.php';

if (isset($_SESSION['unique_id'])) {
    $loggedInUserId = $_SESSION['unique_id'];
     // Consulta SQL para obtener el user_id basado en el unique_id
     $sql = "SELECT user_id FROM users WHERE unique_id = ?";
     $stmt = $conn->prepare($sql);
     $stmt->bind_param("i", $loggedInUserId); 
     $stmt->execute();
     $result = $stmt->get_result();
 
     if ($result->num_rows > 0) {
         $row = $result->fetch_assoc();
         $user_id_sesion = $row['user_id'];
     }
 

    // Consulta SQL para contar las solicitudes de amistad pendientes
    $sql_solicitud = "SELECT COUNT(*) AS total_solicitudes FROM friends WHERE friend_id = ? AND status = 'pending'";
    $stmt_solicitud = mysqli_prepare($conn, $sql_solicitud);
    mysqli_stmt_bind_param($stmt_solicitud, "i", $user_id_sesion);
    mysqli_stmt_execute($stmt_solicitud); 
    $result_solicitudes = mysqli_stmt_get_result($stmt_solicitud);
    $solicitud_count = mysqli_fetch_assoc($result_solicitudes)["total_solicitudes"];

    if ($solicitud_count !== $_SESSION['last_solicitud_count']) {
        // Actualiza el último total de solicitudes en la sesión
        $_SESSION['last_solicitud_count'] = $solicitud_count;
        echo json_encode(['success' => true, 'total_solicitudes' => $solicitud_count, 'new_request' => true]);
    } else {
        echo json_encode(['success' => false, 'total_solicitudes' => $solicitud_count, 'new_request' => false]);
    }
} else {
    echo json_encode(['success' => false]);
}

?>
