<?php
session_start();

include_once '../modelo/config.php';

if (isset($_SESSION['unique_id']) && isset($_POST['curso_id'])) {
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
    $cursoId = $_POST['curso_id'];

    // Consulta SQL para verificar que el usuario tiene permiso para editar el curso
    $sql = "SELECT user_id FROM cursos WHERE curso_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $cursoId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $user_id_curso = $row['user_id'];

        // Verifica que el usuario logueado sea el propietario del curso
        if ($user_id_sesion == $user_id_curso) {
            // Obtener los datos del formulario
            $titulo = $_POST["titulo"];
            $descripcion = $_POST["descripcion"];
            $tipo = $_POST["tipo"];
            $hora = $_POST["hora"];
            $categoria = $_POST["categoria"];
            $precio = isset($_POST["precio"]) ? $_POST["precio"] : null;
            $acepta_terminos = isset($_POST["acepto_terminos"]) && $_POST["acepto_terminos"] === "1";

            // Verifica si se proporcionaron nuevos archivos
            $logo = null;
            $contenido = null;

            if (isset($_FILES['logo']['name']) && $_FILES['logo']['name'] !== '') {
                $logo = $_FILES['logo']['name'];
                $logo_temp = $_FILES['logo']['tmp_name'];
                $logo_path = "../modelo/contenidocurso/fotocurso/" . $logo;

                if (!move_uploaded_file($logo_temp, $logo_path)) {
                    $_SESSION["error_message"] = "Error al cargar el archivo de logo.";
                    header("location: ../vistas/edit_page.php?curso_id=$cursoId");
                    exit;
                }
            }

            if (isset($_FILES['contenido']['name']) && $_FILES['contenido']['name'] !== '') {
                $contenido = $_FILES['contenido']['name'];
                $contenido_temp = $_FILES['contenido']['tmp_name'];
                $contenido_path = "../modelo/contenidocurso/" . $contenido;

                if (!move_uploaded_file($contenido_temp, $contenido_path)) {
                    $_SESSION["error_message"] = "Error al cargar el archivo de contenido.";
                    header("location: ../vistas/edit_page.php?curso_id=$cursoId");
                    exit;
                }
            }

            // Actualiza los datos en la base de datos
            $sql = "UPDATE cursos SET titulo = ?, descripcion = ?, tipo = ?, hora = ?, categoria = ?, precio = ?";
            $bindTypes = "ssssss";
            $bindValues = array($titulo, $descripcion, $tipo, $hora, $categoria, $precio);

            // Verifica si se proporcionó un nuevo archivo de logo
            if ($logo !== null) {
                $sql .= ", logo = ?";
                $bindTypes .= "s";
                $bindValues[] = $logo;
            }

            // Verifica si se proporcionó un nuevo archivo de contenido
            if ($contenido !== null) {
                $sql .= ", contenido = ?";
                $bindTypes .= "s";
                $bindValues[] = $contenido;
            }

            $sql .= " WHERE curso_id = ?";
            $bindTypes .= "i";
            $bindValues[] = $cursoId;

            $stmt = $conn->prepare($sql);

            // Hacer bind de las variables usando call_user_func_array
            $bindParams = array_merge(array($bindTypes), $bindValues);
            call_user_func_array(array($stmt, 'bind_param'), $bindParams);

            if ($stmt->execute()) {
                $_SESSION["status_message"] = "Curso actualizado correctamente";
                header("Location: ../vistas/create_page.php");
                exit;
            } else {
                $_SESSION["error_message"] = "Error al actualizar los datos.";
                header("Location: ../vistas/edit_page.php?curso_id=$cursoId");
                exit;
            }
        } else {
            // El usuario no tiene permiso para editar este curso
            $_SESSION["error_message"] = "No tiene permiso para editar este curso.";
            header("Location: ../vistas/edit_page.php?curso_id=$cursoId");
            exit;
        }
    } else {
        // No se encontró el curso con el ID proporcionado
        $_SESSION["error_message"] = "Curso no encontrado.";
        header("Location: ../vistas/edit_page.php?curso_id=$cursoId");
        exit;
    
} 

?>
