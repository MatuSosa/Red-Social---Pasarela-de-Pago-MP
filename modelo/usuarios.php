<?php
    session_start();
include_once "../modelo/config.php";
$outgoing_id = $_SESSION['unique_id'];
$sql = "SELECT users.*, messages.read_status
        FROM users
        LEFT JOIN messages ON (users.unique_id = messages.incoming_msg_id AND messages.outgoing_msg_id = $outgoing_id)
        WHERE NOT users.unique_id = $outgoing_id
        GROUP BY users.unique_id
        ORDER BY messages.read_status DESC, users.user_id ASC";
$query = mysqli_query($conn, $sql);
$output = "";

if (mysqli_num_rows($query) == 0) {
    $output .= "No hay usuarios disponibles para chatear.";
} elseif (mysqli_num_rows($query) > 0) {
    include_once "data.php";
}

echo $output;

?>