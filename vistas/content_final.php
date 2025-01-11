<button onclick="topFunction()" id="myBtn" title="Go to top"><i class="fa fa-arrow-up"></i></button>

<div class="suggestions_row">
            

        <div class="row shadow">
    <div class="row_title">
        <span>Ultimas fotos</span>
        <a href="../vistas/photos.php">ver todas..</a>
    </div>
    <div class="row_contain_profilephotos">
        <ul>
            <?php
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
                }           
            // Consulta SQL para obtener las últimas 12 fotos subidas por el usuario logueado
            $sql = "SELECT media_url FROM estado WHERE user_id = ? AND media_type = 'photo' ORDER BY created_at DESC LIMIT 12";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "i", $user_id_sesion); // Suponiendo que $logged_in_user_id contiene el ID del usuario logueado
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $media_url = $row['media_url'];
            ?>
                <li><a href="#"><img src="<?php echo $media_url; ?>" alt="" /></a></li>
            <?php
            }
        }else{
            echo '<div class="row_contain">';
            echo '<span>Aún no has subido fotos</span>';
            echo '</div>';
        }
            ?>
        </ul>
    </div>

</div>


           <div id="lista-solicitudes" class="row shadow">
    <div class="row_title">
    <span>Sugerencia de amigos</span>
     <a href="friends.php">ver todos</a>
    </div>
    <?php
include_once '../modelo/config.php';

// Verifica si el usuario ha iniciado sesión y obtiene su unique_id
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
        $user_id = $row['user_id'];
    }
}
// Consulta SQL para obtener todos los usuarios que no sean amigos del usuario logueado
$sql = "SELECT user_id, fname, lname, img FROM users WHERE user_id != ? AND user_id NOT IN (SELECT friend_id FROM friends WHERE user_id = ?)";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "ii", $user_id, $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Verificar si hay usuarios para mostrar
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $user_id = $row["user_id"];
        $fname = $row["fname"];
        $lname = $row["lname"];
        $img = $row["img"];
        echo '<div class="row_contain">';
        echo '<img src="../modelo/images/' . $img . '" alt="" />';
        echo '<a href="perfil.php?user_id="' . $user_id . '"><span><b>' . $fname . ' ' . $lname . '</b></span></a>';
        echo '<button class="add-friend" data-user-id="' . $user_id . '">+</button>';
        echo '</div>';
        
    }
} else {
    echo '<div class="row_contain">';
    echo '<span>No hay usuarios disponibles</span>';
    echo '</div>';
}

echo '</div>';
?>



            
            <div class="row shadow">
            <div class="row_title">
                    <span>Actividades Recientes</span>
                </div>
            <?php
// Consulta SQL para obtener las actividades recientes del usuario logueado
$sql = "SELECT users.img AS user_img, users.fname, users.lname, estado.media_type
        FROM estado
        INNER JOIN users ON estado.user_id = users.user_id
        WHERE estado.user_id = ?
        ORDER BY estado.created_at DESC
        LIMIT 5"; // Limitar a las últimas 5 actividades recientes, por ejemplo

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id_sesion);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Verificar si hay actividades recientes para mostrar
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $media_type = $row["media_type"];
        $user_img = $row["user_img"];
        $username = htmlspecialchars($row["fname"] . " " . $row["lname"]);

        // Generar actividad reciente en función del tipo de contenido
        $activity_text = '';

        if ($media_type === "text") {
            $activity_text = "$username compartió un estado.";
        } elseif ($media_type === "photo") {
            $activity_text = "$username compartió una foto.";
        } elseif ($media_type === "video") {
            $activity_text = "$username compartió un video.";
        }

        // Mostrar la actividad reciente
        echo '<div class="row_contain">';
        echo '<img src="../modelo/images/' . $user_img . '" alt="" />';
        echo '<span>' . $activity_text . '</span>';
        echo '</div>';
    }
} else {
    // Si no hay actividades recientes para mostrar
    echo '<div class="row_contain">';
    echo '<span>No hay actividades recientes.</span>';
    echo '</div>';
}
?>
</div>


<div class="row shadow">
            <div class="row_title">
        <span>Cursos favoritos</span>
        <a href="../vistas/cursos.php">ver todos..</a>
        </div>
    
                <?php
include_once '../modelo/config.php';

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

    // Consulta SQL para obtener los cursos favoritos del usuario con un límite de 5
    $sql = "SELECT cursos.*, users.fname, users.lname
            FROM cursos_favoritos
            JOIN cursos ON cursos_favoritos.curso_id = cursos.curso_id
            JOIN users ON cursos.user_id = users.user_id
            WHERE cursos_favoritos.user_id = ?
            LIMIT 5";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id_sesion);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
      

        while ($row = $result->fetch_assoc()) {
            $titulo = $row['titulo'];
            $logo = $row['logo'];
            $categoria = $row['categoria'];
            $precio = $row['precio'];
            $curso_id = $row['curso_id'];
            echo '    <div class="row_contain">';
            echo '<img src="../modelo/contenidocurso/fotocurso/' . $logo . '" alt="" />';
            echo '<span><a href=""><b>' . $titulo . '</b></a><br>' . $categoria . '<br>Precio: ' . $precio . '</span>';
            echo '<button class="delete-fav" data-curso-id="' . $curso_id . '">-</button>';
            echo '    </div>';
        }

       
        
    } else {
        echo '<div class="row_contain">';
            echo '<span>No tienes cursos favoritos</span>';
            echo '</div>';
    }
}
?>
</div> 
    </div>
    <button onclick="topFunction()" id="myBtn" title="Go to top"><i class="fa fa-arrow-up"></i></button>

    <div class="modal modal-notices">
                <div class="modal-icon-select"><i class="fa fa-sort-asc" aria-hidden="true"></i></div>
        <div class="modal-title">
                <span>Notificaciones</span>
                <a href="perfil.php"><i class="fa fa-ellipsis-h"></i></a>
        </div>
            <div class="modal-content">
                <ul>
                    <div id="birthdayslist">
                    </div>
                </ul>
            </div>
          
    </div>


    
    
        <!-- Modal Messages -->
        <div class="modal modal-comments">
        
            <div class="modal-icon-select"><i class="fa fa-sort-asc" aria-hidden="true"></i></div>
            <div class="modal-title">
                <span>Mensajes</span>
                <a href="mensajes.php"><i class="fa fa-ellipsis-h"></i></a>
            </div>
            <div class="modal-content">
                <ul>
                   <iframe src="mensajes.php" width="360" height="550" f></iframe>
                </ul>
            </div>
        </div>
    <!-- Modal Friends -->
    <div class="modal modal-friends">
        <div class="modal-icon-select"><i class="fa fa-sort-asc" aria-hidden="true"></i></div>
        <div class="modal-title">
            <span>Solicitudes de amistad</span>
             <a href="friends.php"><i class="fa fa-ellipsis-h"></i></a>
        </div>
       
        <div class="modal-content">
            <ul>
            <div id="friendRequests">
   <!-- Aquí se mostrarán las solicitudes de amistad pendientes -->
            </div>
            </ul>
        </div>
      
    </div>   
     <!-- Modal Profile -->
     <div class="modal modal-profile">
        <div class="modal-icon-select"><i class="fa fa-sort-asc" aria-hidden="true"></i></div>
        <div class="modal-title">
            <span>Mi cuenta</span>
             <a href="settings.php"><i class="fa fa-cogs"></i></a>
        </div>
        <div class="modal-content">
            <ul>
                <li>
                    <a href="settings.php">
                        <i class="fa fa-tasks" aria-hidden="true"></i>
                        <span><b>Configuración del perfil</b><br>editar configuración</span>
                    </a>
                </li>
                <li>
                <li><a href="terminos_y_condic.php"><i class="fa fa-file-text-o" aria-hidden="true"></i> <span><b>Terminos y Condiciones</b></a></li>
                <li><a href="privacidad_y_seguridad.php"><i class="fa fa-shield" aria-hidden="true"></i> <span><b>Privacidad y Seguridad</b></a></li>
                <li><a href="contactanos.php"><i class="fa fa-address-card-o" aria-hidden="true"></i> <span><b>Contactanos</b></a></li>
                <li>
                    <a href="../modelo/logout.php?logout_id=<?php echo $_SESSION['unique_id']; ?>">
                        <i class="fa fa-power-off" aria-hidden="true"></i>
                        <span><b>Salir</b><br>cerrar tu sesión</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
    
     <!-- NavMobile -->
     <div class="mobilemenu">
         <?php 
            $sql = mysqli_query($conn, "SELECT * FROM users WHERE unique_id = {$_SESSION['unique_id']}");
            if(mysqli_num_rows($sql) > 0){
              $row = mysqli_fetch_assoc($sql);
            }
          ?>
        <div class="mobilemenu_profile">
            <img id="mobilemenu_portada" src="../modelo/images/cover/<?php echo $row['cover_img']; ?>"
            onclick="showCoverInModal('../modelo/images/cover/<?php echo $row['cover_img']; ?>')" />
            <div class="mobilemenu_profile">
                <img id="mobilemenu_profile_pic" src="../modelo/images/<?php echo $row['img']; ?>" 
             onclick="showImageInModal('../modelo/images/<?php echo $row['img']; ?>')" /><br>
                <span><?php echo $row['fname']. " " . $row['lname'] ?></span>
            </div>
            <div class="mobilemenu_menu">
            <ul>        
                    <li><a href="profile.php" id="rowmenu-selected"><i class="fa fa-user"></i>Perfil</a></li>
                    <li><a href="feed.php" id="rowmenu-selected"><i class="fa fa-user"></i>Noticias</a></li>
                    <li><a href="friends.php" id="rowmenu-selected" ><i class="fa fa-users"></i>Amigos</a></li>
                    <li><a href="mensajes.php" id="rowmenu-selected"><i class="fa fa-comments-o"></i>Mensajes</a></li>
                    <li><a href="cursos.php" id="rowmenu-selected"><i class="fa fa-bookmark-o"></i>Cursos</a></li>
                    <li><a href="create_page.php" id="rowmenu-selected"><i class="fa fa-plus"></i>Crear curso</a></li>
                    <li><a href="mis_compras.php" id="rowmenu-selected"><i class="fa fa-bank"></i>Mis Compras</a></li>

                </ul>
                    <hr>
                <ul>
                <li><a href="settings.php"> Configuración del perfil</a></li>
                <li><a href="terminos_y_condic.php">Terminos y Condiciones</a></li>
                <li><a href="privacidad_y_seguridad.php">Privacidad y Seguridad</a></li>
                <li><a href="contactanos.php">Contactanos</a></li>
                <li> <a href="../modelo/logout.php?logout_id=<?php echo $_SESSION['unique_id']; ?>">Cerrar Sesion</a></li>
                </ul>
            </div>
        </div>
    </div>
    <script>
    $(document).ready(function() {
        $('.delete-fav').on('click', function() {
            var cursoId = $(this).data('curso-id');

            $.ajax({
                url: '../modelo/eliminar_favoritos.php',
                method: 'POST',
                data: { curso_id: cursoId },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        // Recargar la página o realizar otras acciones necesarias
                        location.reload();
                    } else {
                        // Mostrar mensajes de error si es necesario
                        console.log(response.message);
                    }
                },
                error: function(xhr, status, error) {
                    // Manejar errores de la solicitud AJAX si es necesario
                    console.error(error);
                }
            });
        });
    });
</script>
