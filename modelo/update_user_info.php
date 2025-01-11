<?php
session_start();
include_once '../modelo/config.php';

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

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Verifica si el formulario se envió por POST.

        // Obtén los valores del formulario.
        $fname = $_POST['fname'];
        $lname = $_POST['lname'];
        $website = $_POST['web'];
        $birthday = $_POST['nacimiento'];
        $phoneNumber = $_POST['telefono'];
        $birthplace = $_POST['lugarnac'];
        $livesIn = $_POST['lugarlive'];
        $gender = $_POST['genero'];
        $status = $_POST['estcivil'];
        $occupation = $_POST['ocupacion'];
        $joinDate = $_POST['fechajob'];
        $aboutMe = $_POST['aboutMe'];
// Comprueba si se ha seleccionado una nueva imagen de perfil
if (!empty($_FILES['profileImage']['name'])) {
    // Ruta donde se guardarán las imágenes de perfil
    $profileImageDir = "../modelo/images/";

    // Nombre del archivo de destino para la imagen de perfil
    $profileImage = basename($_FILES["profileImage"]["name"]);

    // Ruta completa del archivo de destino para la imagen de perfil
    $profileImageFilePath = $profileImageDir . $profileImage;

    // Extensión de archivo permitida
    $fileType = pathinfo($profileImageFilePath, PATHINFO_EXTENSION);

    // Genera un nombre de archivo único para evitar conflictos
    $profileImageName = uniqid() . '.' . $fileType;

    // Ruta completa del nuevo archivo de imagen de perfil
    $newProfileImageFilePath = $profileImageDir . $profileImageName;

    // Sube la nueva imagen de perfil si es válida
    if (move_uploaded_file($_FILES["profileImage"]["tmp_name"], $newProfileImageFilePath)) {
        // Actualiza el campo de imagen de perfil en la base de datos
        $sqlUpdateProfileImage = "UPDATE users SET img = ? WHERE user_id = ?";
        $stmtUpdateProfileImage = $conn->prepare($sqlUpdateProfileImage);
        $stmtUpdateProfileImage->bind_param("si", $profileImageName, $user_id_sesion);
        $stmtUpdateProfileImage->execute();

        // Elimina la imagen de perfil anterior si existe
        if (isset($profileImg) && !empty($profileImg)) {
            unlink($profileImageDir . $profileImg);
        }
    } else {
        // Error al cargar la imagen de perfil
        $_SESSION["error_message"] = "Error al cargar la nueva imagen de perfil.";
        header("location: ../vistas/personal-Information.php");
        exit;
    }
}

        // Comprueba si se ha seleccionado una nueva imagen de portada
        if (!empty($_FILES['coverImage']['name'])) {
            // Ruta donde se guardarán las imágenes de portada
            $targetDir = "../modelo/images/cover/";

            // Nombre del archivo de destino
            $coverImage = basename($_FILES["coverImage"]["name"]);

            // Ruta completa del archivo de destino
            $targetFilePath = $targetDir . $coverImage;

            // Extensión de archivo permitida
            $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

            // Genera un nombre de archivo único para evitar conflictos
            $coverImageName = uniqid() . '.' . $fileType;

            // Ruta completa del nuevo archivo de imagen de portada
            $newTargetFilePath = $targetDir . $coverImageName;

            // Sube la nueva imagen de portada si es válida
            if (move_uploaded_file($_FILES["coverImage"]["tmp_name"], $newTargetFilePath)) {
                // Actualiza el campo de imagen de portada en la base de datos
                $sqlUpdateCoverImage = "UPDATE users SET cover_img = ? WHERE user_id = ?";
                $stmtUpdateCoverImage = $conn->prepare($sqlUpdateCoverImage);
                $stmtUpdateCoverImage->bind_param("si", $coverImageName, $user_id_sesion);
                $stmtUpdateCoverImage->execute();

                // Elimina la imagen de portada anterior si existe
                if (isset($coverImg) && !empty($coverImg)) {
                    unlink($targetDir . $coverImg);
                }
            } else {
                // Error al cargar la imagen de portada
                $_SESSION["error_message"] = "Error al cargar la nueva imagen de portada.";
                header("location: ../vistas/personal-Information.php");
                exit;
            }
        }

        // Ejecuta las actualizaciones de texto después de las actualizaciones de imagen
$sql1 = "UPDATE users SET fname = ?, lname = ? WHERE user_id = ?";
$stmt1 = $conn->prepare($sql1);
$stmt1->bind_param("ssi", $fname, $lname, $user_id_sesion);
$stmt1->execute();

$sql2 = "UPDATE user_info SET
         about_me = ?, birthday = ?, birthplace = ?, lives_in = ?, occupation = ?, join_date = ?,
         gender = ?, status = ?, website = ?, phone_number = ?
         WHERE user_id = ?";
$stmt2 = $conn->prepare($sql2);
$stmt2->bind_param("ssssssssssi", $aboutMe, $birthday, $birthplace, $livesIn, $occupation, $joinDate,
    $gender, $status, $website, $phoneNumber, $user_id_sesion);
$stmt2->execute();

// Verifica si alguna de las consultas afectó filas en la base de datos
if (($stmt1->affected_rows > 0 || $stmt2->affected_rows > 0) ||
    (!empty($_FILES['profileImage']['name']) || !empty($_FILES['coverImage']['name']))) {
    $_SESSION["status_message"] = "Información actualizada correctamente";
    header("location: ../vistas/personal-Information.php");
    echo json_encode(['success' => true]);
    exit;
} else {
    $_SESSION["error_message"] = "No se pudo actualizar";
    header("location: ../vistas/personal-Information.php");
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

?>
