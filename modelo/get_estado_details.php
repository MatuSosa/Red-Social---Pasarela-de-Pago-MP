<?php
session_start();
// Incluye el archivo de conexión
include '../modelo/config.php';

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["estado_id"])) {
    // Obtén el estado_id desde la solicitud GET
    $estado_id = $_GET["estado_id"];

    // Consulta SQL para obtener los detalles del estado
    $sql = "SELECT * FROM estado WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $estado_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        // Obtén los detalles del estado
        $estadoDetails = array(
            "content" => $row["content"],
            "media_type" => $row["media_type"],
            "media_url" => $row["media_url"],
            // Agrega más campos si es necesario
        );

        // Devuelve los detalles del estado como respuesta JSON
        header("Content-Type: application/json");
        echo json_encode($estadoDetails);
    } else {
        // No se encontró el estado
        http_response_code(404);
        echo json_encode(array("message" => "Estado no encontrado"));
    }
} else {
    // Solicitud incorrecta
    http_response_code(400);
    echo json_encode(array("message" => "Solicitud incorrecta"));
}
?>
