<?php
include_once '../modelo/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificar si se recibió el curso_id en el formulario
    if (isset($_POST['curso_id'])) {
        $curso_id = $_POST['curso_id'];

        // Consulta SQL para eliminar el curso
        $sql = "DELETE FROM cursos WHERE curso_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $curso_id);

        if ($stmt->execute()) {
            $_SESSION["status_message"] = "Curso eliminado correctamente";
                header("location: ../vistas/create_page.php");
                echo json_encode(['success' => true, 'message' => 'Curso eliminado correctamente.']);

        } else {
            $_SESSION["error_message"] = "No se pudo eliminar el curso";
            header("location: ../vistas/create_page.php");
            echo json_encode(['success' => false, 'message' => 'No se pudo eliminar el curso.']);
        }

        // Cerrar la conexión
        $stmt->close();
    } else {
        // El curso_id no se recibió en el formulario
        echo json_encode(['success' => false, 'message' => 'Falta el curso_id en el formulario.']);
    }
} else {
    // Método de solicitud no válido
    echo json_encode(['success' => false, 'message' => 'Método de solicitud no válido.']);
}
?>
