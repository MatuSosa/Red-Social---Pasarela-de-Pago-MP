<?php
session_start();
include '../modelo/config.php';

if (isset($_SESSION['unique_id']) && isset($_POST['currentPassword']) && isset($_POST['newPassword']) && isset($_POST['confirmNewPassword'])) {
    $unique_id_sesion = $_SESSION['unique_id'];

    // Obtener el user_id del usuario actual
    $sql = "SELECT user_id, password FROM users WHERE unique_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $unique_id_sesion);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $user_id_sesion = $row['user_id'];
        $currentPasswordFromDB = $row['password'];

        // Validar la contraseña actual
        $currentPassword = $_POST['currentPassword'];
        if (password_verify($currentPassword, $currentPasswordFromDB)) {
            // Contraseña actual válida, continuar con la actualización
            $newPassword = $_POST['newPassword'];
            $confirmNewPassword = $_POST['confirmNewPassword'];

            if ($newPassword === $confirmNewPassword) {
                // Contraseña nueva y confirmación coinciden
                // Actualizar la contraseña en la base de datos
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                $updateQuery = "UPDATE users SET password = ? WHERE user_id = ?";
                $stmt = mysqli_prepare($conn, $updateQuery);
                mysqli_stmt_bind_param($stmt, "si", $hashedPassword, $user_id_sesion);

                if (mysqli_stmt_execute($stmt)) {
                    // Contraseña actualizada exitosamente
                    $_SESSION["status_message"] = "Contraseña actualizada exitosamente.";
                    header("location: ../vistas/Change-Password.php");
                    echo json_encode(['success' => true]);
                    exit;
                } else {
                    // Error al actualizar la contraseña
                    $_SESSION["error_message"] = "Error al actualizar la contraseña.";
                    header("location: ../vistas/Change-Password.php");
                    echo json_encode(['success' => false]);
                    exit;
                }
            } else {
                $_SESSION["error_message"] = "La contraseña nueva y la confirmación no coinciden.";
                echo json_encode(['success' => false, 'message' => 'La contraseña nueva y la confirmación no coinciden.']);
                header("location: ../vistas/Change-Password.php");
                exit;
            }
        } else {
            $_SESSION["error_message"] = "Contraseña actual inválida.";
            header("location: ../vistas/Change-Password.php");
            echo json_encode(['success' => false, 'message' => 'Contraseña actual inválida.']);
            exit;
        }
    } else {
        $_SESSION["error_message"] = "Usuario no encontrado.";
        header("location: ../vistas/Change-Password.php");
        echo json_encode(['success' => false, 'message' => 'Usuario no encontrado.']);
        exit;
    }
} else {
    $_SESSION["error_message"] = "Faltan parámetros.";
    header("location: ../vistas/Change-Password.php");
    echo json_encode(['success' => false, 'message' => 'Faltan parámetros.']);
    exit;
}
?>
