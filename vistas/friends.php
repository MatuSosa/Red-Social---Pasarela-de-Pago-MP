<?php
$pageName = "AMIGOS";
include_once ('../vistas/content_principal.php');
?>


        
           

        <div class="right_row" style="width:80%;">
        <div class="row border-radius">
                <div class="feed">
                    <div class="feed_title">
                    <span><a href="../vistas/profile.php"><b>Perfil</b></a> | <a href="../vistas/about.php"><b>Sobre mi</b></a> | <a href="../vistas/photos.php"><b>Fotos</b></a>| <a href="../vistas/cursos.php"><b>Cursos</b></a></span>
                    </div>
                </div>
            </div>
        <?php if (!empty($status_message)) : ?>
        <div class="status-message" id="success-message"><?php echo $status_message; ?></div>
    <?php endif; ?>
    <?php if (!empty($error_message)) : ?>
    <div class="error-message" id="error-message"><?php echo $error_message; ?></div>
<?php endif; ?>
            <div class="row border-radius">
                    
                <?php
            include_once '../modelo/config.php';

// Verifica si el usuario ha iniciado sesión y obtiene su unique_id
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
        $user_id = $row['user_id'];

        $sql = "SELECT users.*, f1.status AS status1, f2.status AS status2
        FROM users 
        LEFT JOIN friends AS f1
        ON users.user_id = f1.friend_id 
        AND f1.user_id = ? 
        LEFT JOIN friends AS f2
        ON users.user_id = f2.user_id
        AND f2.friend_id = ?
        WHERE users.user_id != ? ";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param($stmt, "iii", $user_id, $user_id, $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Verificar si hay usuarios para mostrar
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $other_user_id = $row["user_id"];
        
        
        $fname = $row["fname"];
        $lname = $row["lname"];
        $img = $row["img"];
        $status1 = $row["status1"];
        $status2 = $row["status2"];

        // No mostrar al usuario logueado en la lista de usuarios
        if ($other_user_id == $user_id) {
            continue;
        }

        echo '<div class="friend">';
        echo '<div class="friend_title">';
        echo '<img src="../modelo/images/' . $img . '" alt="" />';
        echo '<span><b>' . $fname . ' ' . $lname . '</b><br>';
        echo '</span>';

        if ($status1 === "accepted" && $status2 === "accepted") {
            // Mostrar el botón "Eliminar amigo" si ambas direcciones están en estado "accepted"
            echo '<button class="delete-friend" data-user-id="' . $other_user_id . '">Eliminar</button>';
        } elseif ($status1 === "pending" || $status2 === "pending") {
            
           
// Mostrar el botón "Cancelar solicitud" si alguna de las direcciones está en estado "pending"
            echo '<button class="cancel-friend-request" data-user-id="' . $other_user_id . '">Cancelar solicitud</button>';
        } else {
            // Mostrar el botón "Agregar amigo" si no hay solicitud de amistad
            echo '<button class="add-friend" data-user-id="' . $other_user_id . '">Agregar</button>';
        }

        
       
echo '</div>';
        echo '</div>';
    }
} else {
    echo '<div class="row_contain">';
    echo '<span>No hay usuarios disponibles</span>';
    echo '</div>';
}

    } 
} else {
    echo '<div class="row_contain">';
            echo '<span>El usuario no ha iniciado sesion</span>';
            echo '</div>';
}
echo '</div>';
?>



            

            <center>
                <a href=""><div class="loadmorefeed">
                    <i class="fa fa-ellipsis-h"></i>
                </div></a>
            </center>


        </div>
    </div>
    
    <?php
        include_once ('../vistas/content_final_other.php');
?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="http://code.jquery.com/jquery-latest.js"></script>
    <script src="../js/allscripts_pages.js"></script>
    <script src="../js/modal_perfil.js"></script>
</body>
</html>