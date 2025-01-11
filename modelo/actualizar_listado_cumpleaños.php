<?php
session_start();
include_once '../modelo/config.php';

// Verifica si el usuario ha iniciado sesión y obtiene su unique_id
if (isset($_SESSION['unique_id'])) {
    $loggedInUserId = $_SESSION['unique_id'];

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
}
date_default_timezone_set("America/Argentina/Buenos_Aires");
$currentDate = date("m-d");


// Consulta SQL para obtener los usuarios que cumplen años hoy
$sql = "SELECT u.user_id, u.fname, u.lname, u.img
        FROM users u
        INNER JOIN user_info ui ON u.user_id = ui.user_id
        WHERE DATE_FORMAT(ui.birthday, '%m-%d') = ?";


$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $currentDate);
$stmt->execute();
$result = $stmt->get_result();

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $user_id = $row["user_id"];
        $fname = $row["fname"];
        $lname = $row["lname"];
        $img = $row["img"];

        echo '<li>';
        echo '<a href="#">';
        echo '<img src="../modelo/images/' . $img . '" alt="" />';
        if ($user_id === $user_id_sesion) {
            echo '<span><b>Feliz Cumpleaños ' . $fname . ' ' . $lname . ' </b></span>';
        } else {
            echo '<span><b>' . $fname . ' ' . $lname . '</b><br>Cumple años hoy</span>';
        }
        echo '</a>';
        echo '</li>';
    }
} else {
    echo '<h5>No tienes notificaciones</h5>';
}
?>