<?php
session_start();
if (isset($_SESSION['unique_id'])) {
    include_once "config.php";
    $outgoing_id = $_SESSION['unique_id'];

    // Consulta SQL para contar los mensajes no leídos
    $sql = "SELECT COUNT(*) AS unread_message_count FROM messages WHERE incoming_msg_id = {$outgoing_id} AND read_status = 0";
    $query = mysqli_query($conn, $sql);

    if ($query) {
        $row = mysqli_fetch_assoc($query);
        $unread_message_count = $row['unread_message_count'];

        // Verifica si hay un nuevo mensaje no leído
        if (!isset($_SESSION['last_unread_message_count'])) {
            $_SESSION['last_unread_message_count'] = $unread_message_count;
            $newMessage = false; // No hay comparación anterior
        } else {
            $lastUnreadMessageCount = $_SESSION['last_unread_message_count'];
            if ($unread_message_count > $lastUnreadMessageCount) {
                $newMessage = true; // Hay nuevos mensajes no leídos
            } else {
                $newMessage = false; // No hay nuevos mensajes no leídos
            }
            $_SESSION['last_unread_message_count'] = $unread_message_count; // Actualiza la variable de sesión
        }

        // Devuelve el contador de mensajes no leídos y la bandera de nuevo mensaje en la respuesta JSON
        echo json_encode(['unread_message_count' => $unread_message_count, 'newMessage' => $newMessage]);
    } else {
        // Maneja los errores de la consulta, si es necesario
        echo json_encode(['unread_message_count' => -1, 'newMessage' => false]);
    }
} else {
    // El usuario no está autenticado
    echo json_encode(['unread_message_count' => 0, 'newMessage' => false]);
}
?>
