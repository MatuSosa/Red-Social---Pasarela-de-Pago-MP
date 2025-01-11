<?php
include_once '../modelo/config.php';

date_default_timezone_set("America/Argentina/Buenos_Aires");
$currentDate = date("m-d");


// Consulta SQL para obtener la cantidad de usuarios que cumplen años hoy
$sql = "SELECT COUNT(*) as birthday_count
        FROM users u
        INNER JOIN user_info ui ON u.user_id = ui.user_id
        WHERE DATE_FORMAT(ui.birthday, '%m-%d') = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $currentDate);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $birthdayCount = $row['birthday_count'];
    echo $birthdayCount; // Devuelve la cantidad de cumpleaños
} else {
    echo '0'; // Si no hay cumpleaños, devuelve 0
}
?>
