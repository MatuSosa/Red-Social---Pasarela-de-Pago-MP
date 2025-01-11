<?php
session_start();
include_once '../modelo/config.php';

// Verificar si el usuario ha iniciado sesión
if (isset($_SESSION['unique_id'])) {
    $loggedInUserId = $_SESSION['unique_id'];

    // Consulta SQL para obtener el user_id basado en el unique_id
    $sql = "SELECT user_id FROM users WHERE unique_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $loggedInUserId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $user_id_sesion = $row['user_id'];
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $titulo = $_POST["titulo"];
    $descripcion = $_POST["descripcion"];
    $tipo = $_POST["tipo"];
    $hora = $_POST["hora"];
    $categoria = $_POST["categoria"];
    $precio = isset($_POST["precio"]) ? $_POST["precio"] : null;
    $acepta_terminos = isset($_POST["acepto_terminos"]) && $_POST["acepto_terminos"] === "1";
    $cbu_cvu = isset($_POST["cbu_cvu"]) ? $_POST["cbu_cvu"] : null;
    $alias = isset($_POST["alias"]) ? $_POST["alias"] : null;
    $titular_cuenta = isset($_POST["titular_cuenta"]) ? $_POST["titular_cuenta"] : null;
    if ($cbu_cvu && $alias && $titular_cuenta) {
        $sqlPago = "INSERT INTO informacion_pago (user_id, cbu_cvu, alias, titular_cuenta) VALUES (?, ?, ?, ?)";
        $stmtPago = $conn->prepare($sqlPago);
        $stmtPago->bind_param("isss", $user_id_sesion, $cbu_cvu, $alias, $titular_cuenta);
    
        if ($stmtPago->execute()) {
            // Datos de información de pago insertados correctamente
        } else {
            // Error al insertar datos de información de pago
            $_SESSION["error_message"] = "Error al registrar la información de pago";
            header("location: ../vistas/create_page.php");
            exit;
        }
    }
    // Validar los datos
    if (empty($titulo) || empty($descripcion) || empty($tipo) || empty($hora) || empty($categoria) || $precio === null  || !$acepta_terminos) {
        $_SESSION["error_message"] = "Por favor, complete todos los campos.";
        header("location: ../vistas/create_page.php");
        exit;
    }

    // Procesar la imagen del logo
    $ruta_destino_logo = '../modelo/contenidocurso/fotocurso/';
    $nombre_archivo_logo = $_FILES['logo']['name'];
    $archivo_temporal_logo = $_FILES['logo']['tmp_name'];
    $ruta_completa_logo = $ruta_destino_logo . $nombre_archivo_logo;

    if (move_uploaded_file($archivo_temporal_logo, $ruta_completa_logo)) {
        // El archivo del logo se ha movido y almacenado correctamente

        // Aquí agregamos la lógica para mover y almacenar el archivo principal
        $ruta_destino = '../modelo/contenidocurso/';
        $nombre_archivo = $_FILES['contenido']['name'];
        $archivo_temporal = $_FILES['contenido']['tmp_name'];
        $ruta_completa = $ruta_destino . $nombre_archivo;

        if (move_uploaded_file($archivo_temporal, $ruta_completa)) {
            // El archivo se ha movido y almacenado correctamente

            // Consulta SQL para insertar los datos en la tabla de cursos/tutoriales
            $sql = "INSERT INTO cursos (titulo, descripcion, tipo, hora, categoria, precio, contenido, acepta_terminos, user_id, logo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssssssis", $titulo, $descripcion, $tipo, $hora, $categoria, $precio, $nombre_archivo, $acepta_terminos, $user_id_sesion, $nombre_archivo_logo);

            if ($stmt->execute()) {
                $_SESSION["status_message"] = "Curso creado correctamente";
                header("location: ../vistas/create_page.php");
                exit;
            } else {
                // Error al insertar los datos.
                $_SESSION["error_message"] = "No se pudo crear el curso";
                header("location: ../vistas/create_page.php");
                exit;
            }
        } else {
            $_SESSION["error_message"] = "Error al mover el archivo principal";
            header("location: ../vistas/create_page.php");
            exit;
        }
    } else {
        $_SESSION["error_message"] = "Error al mover el archivo de logo";
        header("location: ../vistas/create_page.php");
        exit;
    }
} else {
    echo "error";
}
?>
