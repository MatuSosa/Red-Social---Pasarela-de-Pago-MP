<?php
session_start();
include '../modelo/config.php';
if(isset($_POST['categoria'])) {

    // Obtiene la categoría seleccionada desde la solicitud POST
    $categoria = $_POST['categoria'];

    // Prepara la consulta SQL para seleccionar los cursos de la categoría especificada
    $sql = "SELECT cursos.*, users.fname, users.lname, users.img FROM cursos
            JOIN users ON cursos.user_id = users.user_id
            WHERE cursos.categoria = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $categoria);
    $stmt->execute();
    $result = $stmt->get_result();

    // Crea un array para almacenar los cursos filtrados
    $cursosFiltrados = array();

    // Verifica si se encontraron cursos y agrégales al array de cursos filtrados
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $cursosFiltrados[] = $row;
        }
    }

    // Convierte el array de cursos filtrados a formato JSON y envíalo al frontend
    echo json_encode($cursosFiltrados);
} else {
    // Si no se recibió la categoría desde el frontend, devuelve un mensaje de error
    echo "Error: No se ha proporcionado la categoría.";
}
?>
