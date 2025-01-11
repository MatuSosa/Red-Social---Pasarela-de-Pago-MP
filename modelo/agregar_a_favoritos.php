<?php
session_start();
include_once '../modelo/config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_SESSION['unique_id'])) {
        $unique_id_sesion = $_SESSION['unique_id'];

        // Consulta SQL para obtener el user_id basado en el unique_id
        $sql = "SELECT user_id FROM users WHERE unique_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $unique_id_sesion);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $user_id_sesion = $row['user_id'];
        }

        // Obtener el curso_id del formulario AJAX
        $cursoId = $_POST['curso_id'];

        // Consultar el user_id del creador del curso
        $sql = "SELECT user_id FROM cursos WHERE curso_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $cursoId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $user_id_creador = $row['user_id'];

            // Verificar si el usuario actual es el creador del curso
            if ($user_id_sesion === $user_id_creador) {
                // El creador del curso no puede agregarse a favoritos
                $_SESSION["error_message"] = "No puedes agregar tu propio curso a favoritos.";
                echo json_encode(['success' => false, 'redirect' => '../vistas/cursos.php']);
                exit;
            }

            // Verificar si el curso ya está en favoritos del usuario
            $sql = "SELECT * FROM cursos_favoritos WHERE user_id = ? AND curso_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii", $user_id_sesion, $cursoId);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                // El curso ya está en favoritos
                $_SESSION["error_message"] = "El curso ya está en tus favoritos.";
                echo json_encode(['success' => false, 'redirect' => '../vistas/cursos.php']);
                exit;
            } else {
                // Insertar el registro en la tabla cursos_favoritos
                $sql = "INSERT INTO cursos_favoritos (user_id, curso_id) VALUES (?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ii", $user_id_sesion, $cursoId);

                if ($stmt->execute()) {
                    $_SESSION["status_message"] = "Curso agregado a favoritos.";
                    echo json_encode(['success' => true, 'redirect' => '../vistas/cursos.php']);
                    exit;
                } else {
                    $_SESSION["error_message"] = "No se pudo agregar, intenta de nuevo.";
                    echo json_encode(['success' => false, 'redirect' => '../vistas/cursos.php']);
                    exit;
                }
            }
        } else {
            // Curso no encontrado
            $_SESSION["error_message"] = "Curso no encontrado.";
            echo json_encode(['success' => false, 'redirect' => '../vistas/cursos.php']);
            exit;
        }
    }
}

?>
