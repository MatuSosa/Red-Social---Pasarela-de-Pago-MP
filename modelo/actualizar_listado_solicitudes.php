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

        // Consulta SQL para obtener las solicitudes de amistad pendientes para el usuario logueado
        $sql = "SELECT users.user_id, users.fname, users.lname, users.img, friends.id
                FROM friends
                INNER JOIN users ON friends.user_id = users.user_id
                WHERE friends.friend_id = ? AND friends.status = 'pending'";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $user_id_sesion);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $friendUserId = $row["user_id"];
                $fname = $row["fname"];
                $lname = $row["lname"];
                $img = $row["img"];

                echo '<li>';
                echo '<a href="#">';
                echo '<img src="../modelo/images/' . $img . '" alt="" />';
                echo '<span><b>' . $fname . ' ' . $lname . '</b></span>';
                echo '<button class="modal-content-accept" data-request-id="' .  $friendUserId . '">Aceptar</button>';
                echo '<button class="modal-content-decline" data-request-id="' .  $friendUserId . '">Rechazar</button>';
                echo '</a>';
                echo '</li>';
                
            }
        } else {
            echo '<h5>No tienes solicitudes de amistad</h5>';
        }
        ?>   