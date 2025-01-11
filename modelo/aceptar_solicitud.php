<?php
session_start();
include '../modelo/config.php';

if (isset($_SESSION['unique_id']) && isset($_POST['requestId'])) {
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
    $requestId = $_POST['requestId'];

    // Actualizar el estado de la solicitud de amistad a 'accepted' en la base de datos
    $updateQuery = "UPDATE friends SET status = 'accepted' WHERE user_id = ? AND friend_id = ? AND status = 'pending'";
    $stmt = mysqli_prepare($conn, $updateQuery);
    mysqli_stmt_bind_param($stmt, "ii", $requestId, $user_id_sesion);

    if (mysqli_stmt_execute($stmt)) {
          // Éxito al enviar solicitud de amistad
          $_SESSION["status_message"] = "Solicitud aceptada, ahora son amigos";
          echo json_encode(['success' => true]);
          exit;
      } else {
          // Error al enviar solicitud de amistad
          $_SESSION["error_message"] = "Ops..! error al aceptar solicitud";
          echo json_encode(['success' => false]);
          exit;
      }
  } else {
      // El usuario ya es amigo o la solicitud está pendiente
      echo json_encode(['success' => false, 'message' => 'El usuario ya es tu amigo o la solicitud está pendiente']);
      exit;
}
