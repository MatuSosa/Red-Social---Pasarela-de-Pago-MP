<?php
session_start();
include '../modelo/config.php';

// Verificar si el usuario está logueado
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
        $user_id = $row['user_id'];
    }

    // Obtener otros datos del pago desde la URL
    $payment = $_GET['payment_id'];
    $status = $_GET['status'];
    $payment_type = $_GET['payment_type'];
    $order_id = $_GET['merchant_order_id'];
    $curso_id = $_GET['curso_id'];
    $creator_user_id = $_GET['user_id']; // Agrega esta línea para obtener el user_id del creador del curso

    // Establecer la zona horaria a Buenos Aires
    date_default_timezone_set('America/Argentina/Buenos_Aires');
    $fecha_registro = date('Y-m-d H:i:s'); // Obtener la fecha y hora actual

    // Verificar si el pago es exitoso
    if ($status === 'approved') {
        // Insertar los datos en la tabla
        $sql = "INSERT INTO pagos (user_id, creator_user_id, payment_id, status, payment_type, merchant_order_id, curso_id, fecha_registro) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssss", $user_id, $creator_user_id, $payment, $status, $payment_type, $order_id, $curso_id, $fecha_registro);

        if ($stmt->execute()) {
            $_SESSION["status_message"] = "Pago exitoso, recibirás al correo los datos de la transacción.";
            header("location: ../vistas/cursos.php");
            exit;
        } else {
            // Error al registrar el pago
            $_SESSION["error_message"] = "No se pudo registrar el pago.";
            header("location: ../vistas/cursos.php");
            exit;
        }

        // Cerrar la conexión
        $stmt->close();
    } else {
        echo "<h3>Pago no exitoso</h3>";
        echo "El pago no se ha aprobado.";
    }
} 
?>
