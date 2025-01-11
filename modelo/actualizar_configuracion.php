<?php
session_start();
include_once '../modelo/config.php';
if (!isset($_SESSION['unique_id'])) {
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
    $user_id = $row['user_id'];


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $configName = $_POST["config_name"];
    $valor = $_POST["valor"];

    // Actualizar la base de datos
    $sql = "UPDATE configuracion_notificaciones SET $configName = ? WHERE usuario_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $valor, $user_id);

    if ($stmt->execute()) {
        echo "Configuración actualizada con éxito";
    } else {
        echo "Error al actualizar la configuración";
    }
}
}
?>
