<?php
include_once '../modelo/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $query = $_GET['query'];

    // Realiza una consulta SQL para buscar usuarios por nombre o apellido
    $sql = "SELECT * FROM users WHERE fname LIKE ? OR lname LIKE ?";
    $stmt = $conn->prepare($sql);
    $queryParam = "%$query%";
    $stmt->bind_param("ss", $queryParam, $queryParam);
    $stmt->execute();
    $result = $stmt->get_result();

    // Construye la lista de resultados
    while ($row = $result->fetch_assoc()) {
        // Muestra cada usuario en el formato deseado
        $userId = $row['user_id'];
        $firstName = $row['fname'];
        $lastName = $row['lname'];
        $userImage = $row['img'];
        echo '<div class="result-item">';
        echo '<div class="friend">';
        echo '<div class="friend_title">';
        echo '<img src="../modelo/images/' . $userImage . '" alt="" />';
        echo '<span><b>' . $firstName . ' ' . $lastName . '</b><br>';
        echo '</span>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }
}
?>
