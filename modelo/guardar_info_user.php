<?php
session_start();
include_once '../modelo/config.php';

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

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Verifica si el formulario se envió por POST.

        // Obtén los valores del formulario.
    
        $website = $_POST['web'];
        $birthday = $_POST['nacimiento'];
        $phoneNumber = $_POST['telefono'];
        $birthplace = $_POST['lugarnac'];
        $livesIn = $_POST['lugarlive'];
        $gender = $_POST['genero'];
        $email = $_POST['email'];
        $status = $_POST['estcivil'];
        $occupation = $_POST['ocupacion'];
        $joinDate = $_POST['fechajob'];
        $aboutMe = $_POST['aboutMe'];


        // Actualiza la información del usuario en la tabla user_info.
        $sql2 = "INSERT INTO user_info (user_id, about_me, birthday, birthplace, lives_in, occupation, join_date, gender, status, email, website, phone_number)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt2 = $conn->prepare($sql2);
        $stmt2->bind_param(
            "isssssssssss",
            $user_id_sesion,
            $aboutMe,
            $birthday,
            $birthplace,
            $livesIn,
            $occupation,
            $joinDate,
            $gender,
            $status,
            $email,
            $website,
            $phoneNumber
        );

        // Ejecuta ambas consultas de actualización.
        $stmt2->execute();

        if ($stmt2->affected_rows > 0) {
            $_SESSION["status_message"] = "Información actualizada correctamente";
            header("location: ../vistas/about.php");
            echo json_encode(['success' => true]);
            exit;
        } else {
            // Error al insertar los datos.
            $_SESSION["error_message"] = "No se pudo actualizar";
            header("location: ../vistas/about.php");
            echo json_encode(['success' => false]);
            exit;
        }
    }
} else {
    $_SESSION["error_message"] = "Acceso no autorizado";
    header("location: ../vistas/login.php");
    echo json_encode(['success' => false]);
    exit;
}

?>
