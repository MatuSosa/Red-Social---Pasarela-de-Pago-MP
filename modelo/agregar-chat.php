<?php 
session_start();
if (isset($_SESSION['unique_id'])) {
    include_once "config.php";
    $outgoing_id = $_SESSION['unique_id'];
    $incoming_id = mysqli_real_escape_string($conn, $_POST['incoming_id']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);

    if (!empty($message)) {
        $sql = mysqli_query($conn, "INSERT INTO messages (incoming_msg_id, outgoing_msg_id, msg)
                                    VALUES ({$incoming_id}, {$outgoing_id}, '{$message}')") or die();
    }

    // Marcar el mensaje como leído
    $message_id = mysqli_insert_id($conn); // Obtiene el ID del mensaje recién insertado
    $sql_mark_read = "UPDATE messages SET read_status = 1 WHERE msg_id = {$message_id} AND incoming_msg_id = {$outgoing_id}";
    mysqli_query($conn, $sql_mark_read); // Marca el mensaje como leído
} else {
    header("location: ../vistas/login.php");
}

    
?>