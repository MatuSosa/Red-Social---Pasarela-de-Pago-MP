<?php 
include_once "../modelo/config.php";

if (isset($_GET['user_id'])) {
    // Obtén el user_id de la URL
    $user_id = $_GET['user_id'];

    // Consulta SQL para obtener la información del usuario basándote en el user_id
    $sql = mysqli_query($conn, "SELECT * FROM users WHERE user_id = $user_id");

    if(mysqli_num_rows($sql) > 0) {
        $row = mysqli_fetch_assoc($sql);
        // Aquí tienes toda la información del usuario basada en el user_id de la URL
  
?>
   
<!DOCTYPE html>
<html lang="es-AR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
 <?php
 $sql = mysqli_query($conn, "SELECT * FROM users WHERE user_id= $user_id");
            if(mysqli_num_rows($sql) > 0){
              $row = mysqli_fetch_assoc($sql);
            }
            ?>
    <title><?php echo $row['fname']. " " . $row['lname'] ?> - Edu-Connect</title>
    <link rel="shortcut icon" href="../images/logo.png" type="image/x-icon">


    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="../css/style.css">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <!-- Icons FontAwesome 4.7.0 -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"  type="text/css" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>



</head>
<body>
    <div class="navbar">
        <div class="navbar_menuicon" id="navicon">
            <i class="fa fa-navicon"></i>
        </div>
        <div class="navbar_logo">
            <img src="../images/round.png" alt="" />
        </div>
        <div class="navbar_page">
</div>
<div class="navbar_search">
<input type="text" id="searchInput" placeholder="Buscar usuarios..." autocomplete="off" />
    <div id="searchResultsContainer">
    <div id="searchResults" class="results-list"></div>
</div>
</div>
        
<div class="navbar_icons">
<ul>
    <li id="friendsmodal"><i class="fa fa-user-o"></i><span class="updated" id="notification">0</span></li>
    <li id="messagesmodal"><i class="fa fa-comments-o"></i><span class="noread" id="notification">0</span></li>
    <li id="noticesmodal"><i class="fa fa-globe"></i><span class="birthday-counter" id="notification">0</span></li>
</ul>
</div>

        <?php 
            $sql = mysqli_query($conn, "SELECT * FROM users WHERE user_id = $user_id");
            if(mysqli_num_rows($sql) > 0){
              $row = mysqli_fetch_assoc($sql);
            }
          ?>
        <div class="navbar_user" id="profilemodal" style="cursor:pointer">
            <img src="../modelo/images/<?php echo $row['img']; ?>" alt="" />
            <span id="navbar_user_top"><?php echo $row['fname']. " " . $row['lname'] ?><br></span><i class="fa fa-angle-down"></i>
        </div>
    </div>

    <div class="all">

        <div class="rowfixed"></div>
        <div class="left_row">
        <div class="left_row_profile">
    <img id="portada" src="../modelo/images/cover/<?php echo $row['cover_img']; ?>"
    onclick="showCoverInModal('../modelo/images/cover/<?php echo $row['cover_img']; ?>')" />
    <div class="left_row_profile">
        <img id="profile_pic" src="../modelo/images/<?php echo $row['img']; ?>" 
             onclick="showImageInModal('../modelo/images/<?php echo $row['img']; ?>')" />
        <span><?php echo $row['fname']. " " . $row['lname'] ?><br><p></p></span>
    </div>
</div>
<div id="imageModal" style="display: none; background-color: white; border: 2px solid #ff5e3a; border-radius: 10px; padding: 20px; width: 600px;">
    <span class="closeModal" onclick="closeImageModal()">&times;</span>
    <img class="modal-content" id="fullImage">
    <h5><b>Puedes cambiar la foto en configuraciones</b></h5>
</div>
<!-- Modal de imagen de portada -->
<div id="coverModal" style="display: none; background-color: white; border: 2px solid #ff5e3a; border-radius: 10px; padding: 20px; width: 600px;">
  <span class="closecoverModal" onclick="closeCoverModal()">&times;</span>
  <img class="modal-content" id="coverImgModal">
  <h5><b>Puedes cambiar la foto en configuraciones</b></h5>
</div>


            
            <div class="rowmenu">
                <ul>
                <li><a href="feed.php" id="rowmenu-selected"><i class="fa fa-globe"></i>Noticias</a></li>
                    <li><a href="profile.php" id="rowmenu-selected"><i class="fa fa-user"></i>Perfil</a></li>
                    <li><a href="friends.php" id="rowmenu-selected"><i class="fa fa-users"></i>Amigos</a></li>
                    <li><a href="mensajes.php" id="rowmenu-selected"><i class="fa fa-comments-o"></i>Mensajes</a></li>
                    <li><a href="cursos.php" id="rowmenu-selected"><i class="fa fa-bookmark-o"></i>Cursos</a></li>
                    <li><a href="create_page.php" id="rowmenu-selected"><i class="fa fa-plus"></i>Crear curso</a></li>
                    <li><a href="mis_compras.php" id="rowmenu-selected"><i class="fa fa-bank"></i>Mis Compras</a></li>


                </ul>
            </div>
        </div>
        
           
        <div class="right_row">
        <div class="row border-radius">
                <div class="feed">
                    <div class="feed_title">
                    <span><a href="../vistas/profile.php" id="select_profile_menu" ><b>Perfil</b></a> | <a href="../vistas/about.php"><b>Sobre mi</b></a> | <a href="../vistas/photos.php" ><b>Fotos</b></a> | <a  href="../vistas/cursos.php " ><b>Cursos</b></a></span>
                    </div>
                </div>
            </div>
                        <!-- Display status message if set -->
   <?php if (!empty($status_message)) : ?>
        <div class="status-message" id="success-message"><?php echo $status_message; ?></div>
    <?php endif; ?>
    <?php if (!empty($error_message)) : ?>
    <div class="error-message" id="error-message"><?php echo $error_message; ?></div>
<?php endif; ?>

<div class="row border-radius">
            <?php
include_once '../modelo/config.php';
   $sql = "SELECT user_id FROM users WHERE user_id = ?";
   $stmt = $conn->prepare($sql);
   $stmt->bind_param("i", $user_id_logueado); // Usar loggedInUserId aquí
   $stmt->execute();
   $result = $stmt->get_result();

   if ($result->num_rows > 0) {
       $row = $result->fetch_assoc();
       $user_id_sesion = $row['user_id'];
   }

// Consulta SQL para obtener los estados del usuario logueado con comentarios y conteo de comentarios
$sql = "SELECT estado.*, users.fname, users.lname, users.img,
       c.user_id AS comentario_user_id, c.contenido AS comentario_contenido, c.fecha_creacion AS comentario_fecha
FROM estado
INNER JOIN users ON estado.user_id = users.user_id
LEFT JOIN comentarios AS c ON estado.id = c.estado_id
WHERE estado.user_id = ?
GROUP BY estado.id
ORDER BY estado.created_at DESC";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id_sesion);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);



// Verificar si hay estados para mostrar
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $content = htmlspecialchars($row["content"]);
        $media_type = $row["media_type"];
        $media_url = $row["media_url"];
        $img = $row["img"];
        $username = htmlspecialchars($row["fname"] . " " . $row["lname"]);
        $created_at = strtotime($row["created_at"]);
        $estado_id = $row["id"]; 
    
?>
        
                <div class="feed">
                        <div class="feed_title">
                         <img src="../modelo/images/<?php echo $img; ?>" alt="" />
                         <span><b><?php echo $username; ?></b> compartió un estado<br><p>el <?php echo date("d/m/Y H:i:s", strtotime($row["created_at"])); ?></p></span>
                        </div>
                    <div class="feed_content">
                            <div class="feed_content_text">
                                &nbsp&nbsp<?php echo $content; ?>
                             </div>
                             <?php if ($media_type === "photo" || $media_type === "video") { ?>
        <div class="feed_content_image">
            <?php if ($media_type === "photo") { ?>  
                <a href="feed.php"><img src="../images/<?php echo $media_url; ?>" alt="" /></a>
            <?php } elseif ($media_type === "video") { ?>
                <div class="video-container">
                    <video controls>
                        <source src="../videos/<?php echo $media_url; ?>" type="video/mp4">
                        <!-- Añadir otros formatos de video aquí si es necesario -->
                        Tu navegador no soporta el elemento de video.
                    </video>
                </div>
            <?php } ?>
        </div>
    <?php } ?>
                    </div>
                    <?php
include_once '../modelo/config.php';

// Dentro del bucle while para cada estado
$estado_id = $row["id"];

// Consulta SQL para obtener la cantidad de likes
$sqlLikesCount = "SELECT COUNT(*) AS likes_count FROM likes WHERE estado_id = ?";
$stmtLikesCount = mysqli_prepare($conn, $sqlLikesCount);
mysqli_stmt_bind_param($stmtLikesCount, "i", $estado_id);
mysqli_stmt_execute($stmtLikesCount);
$resultLikesCount = mysqli_stmt_get_result($stmtLikesCount);

if ($rowLikesCount = mysqli_fetch_assoc($resultLikesCount)) {
    $likes_count = $rowLikesCount["likes_count"];
} else {
    $likes_count = 0;
}

// Consulta SQL para obtener el nombre del último usuario que dio like
$sqlLastLiker = "SELECT users.fname AS liker_fname, users.lname AS liker_lname
                 FROM likes
                 LEFT JOIN users ON likes.user_id = users.user_id
                 WHERE likes.estado_id = ?
                 ORDER BY likes.id DESC
                 LIMIT 1";

$stmtLastLiker = mysqli_prepare($conn, $sqlLastLiker);
mysqli_stmt_bind_param($stmtLastLiker, "i", $estado_id);
mysqli_stmt_execute($stmtLastLiker);
$resultLastLiker = mysqli_stmt_get_result($stmtLastLiker);

if ($rowLastLiker = mysqli_fetch_assoc($resultLastLiker)) {
    $liker_fname = htmlspecialchars($rowLastLiker["liker_fname"]);
    $liker_lname = htmlspecialchars($rowLastLiker["liker_lname"]);
} else {
    $liker_fname = "";
    $liker_lname = "";
}

echo '<div class="feed_footer">';
echo '<ul class="feed_footer_left">';
echo '<div id="likes-container-' . $estado_id . '">';
echo '<li class="hover-orange selected-orange"><i class="fa fa-heart"></i> ' . $likes_count . '</li>';

if (!empty($liker_fname) && !empty($liker_lname)) {
    echo '<li><span><b>' . $liker_fname . ' ' . $liker_lname . '</b></span></li>';
}

if ($likes_count > 1) {
    echo '<li><span>y ' . ($likes_count - 1) . ' mas..</span></li>';
}
echo '</div>';
echo '</ul>';

?>

                        <?php
include_once '../modelo/config.php';

// Consulta SQL para contar todos los comentarios del estado
$sql_comentarios = "SELECT COUNT(*) AS total_comentarios FROM comentarios WHERE estado_id = ?";
$stmt_comentarios = mysqli_prepare($conn, $sql_comentarios);
mysqli_stmt_bind_param($stmt_comentarios, "i", $estado_id);
mysqli_stmt_execute($stmt_comentarios);
$result_comentarios = mysqli_stmt_get_result($stmt_comentarios);
$comentario_count = mysqli_fetch_assoc($result_comentarios)["total_comentarios"];
?>
                        <ul class="feed_footer_right">
                            <li>
                                <button style="background: none; border: none; padding: 0; color:#515365;" class="toggle-comments-button" type="button">
                                <li class="hover-orange"><i class="fa fa-comments-o"></i> <?php echo $comentario_count; ?> comentarios</li>
                                </button>
                            </li>
                            
                        </ul>
                    </div>
<div class="feed_footer_right" style="display: flex; justify-content: space-between; width: 100%;">
<button style="background: none; border: none; padding: 0; color:#515365; flex: 1; text-align: center;" class="like-button" data-estado-id="<?php echo $estado_id; ?>" >
    <li class="hover-orange"><i class="fa fa-thumbs-up"></i> Me gusta </li>
</button>

    <button style="background: none; border: none; padding: 0; color:#515365; flex: 1; text-align: center;" class="comment-button">
    <li class="hover-orange"><i class="fa fa-comments-o"></i> Comentar</li></button>
    <button class="hover-orange" style="background: none; border: none; padding: 0; color:#515365; flex: 1; text-align: center;" id="share-button">
    <li class="hover-orange"><i class="fa fa-share"></i> Compartir</li></button>
</div> 
<div class="comment-input" style="display: none;">
<form class="formcoment" action="../modelo/add_comment.php" method="post">
    <input type="hidden" name="estado_id" value="<?php echo $estado_id; ?>">
    <input type="hidden" name="unique_id" value="<?php echo $user_id; ?>">
    <input type="hidden" name="comentario_padre_id" value="<?php echo $comentario_id; ?>"> 
    <input type="text" name="nuevo_comentario" placeholder="Añadir un comentario...">
    <button type="submit">&#x1F4DD;</button> 
</form>
</div>
              <!-- Mostrar comentarios -->
              <div class="comments-container" style="display: none;">
    <div class="comentarios">
        <?php
        // Consulta para obtener los comentarios de este estado
        $sql_comentarios = "SELECT comentarios.*, users.fname AS comentario_fname, users.lname AS comentario_lname, users.img AS comentario_img
        FROM comentarios
        INNER JOIN users ON comentarios.user_id = users.user_id
        WHERE comentarios.estado_id = ?
        ORDER BY comentarios.fecha_creacion ASC";
        $stmt_comentarios = mysqli_prepare($conn, $sql_comentarios);
        mysqli_stmt_bind_param($stmt_comentarios, "i", $estado_id);
        mysqli_stmt_execute($stmt_comentarios);
        $result_comentarios = mysqli_stmt_get_result($stmt_comentarios);

        if (mysqli_num_rows($result_comentarios) > 0) {
            while ($row_comentario = mysqli_fetch_assoc($result_comentarios)) {
                $comentario_content = htmlspecialchars($row_comentario["contenido"]);
                $comentario_username = htmlspecialchars($row_comentario["comentario_fname"] . " " . $row_comentario["comentario_lname"]);
                $comentario_created_at = strtotime($row_comentario["fecha_creacion"]);
                $comentario_img = $row_comentario["comentario_img"]; // Aquí obtenemos la imagen de perfil del usuario que hizo el comentario
        ?>
                <div class="feed_title">
                    <img src="../modelo/images/<?php echo $comentario_img; ?>" alt="" />
                    <span><b><?php echo $comentario_username; ?>:</b> <?php echo $comentario_content; ?></span>
                </div>
                
        <?php
            }
        } else {
            echo "<p>No hay comentarios.</p>";
        }
        ?>
    </div>
</div>
</div>

                <?php
                
                       
        }
        
    } else {
        echo "<p>Aún no tienes publicaciones.</p>";
        
    }
    echo '</div>';
    // Liberar el resultado y cerrar la conexión
    mysqli_free_result($result);
    ?>
            
   
            
            <center>
                <a href=""><div class="loadmorefeed">
                    <i class="fa fa-ellipsis-h"></i>
                </div></a>
            </center>
            
        </div>
          
        <div class="suggestions_row">
            <div class="row shadow">
                <div class="row_title">
                    <span>Sugerencia de amigos</span>
                    <a href="friends.php">ver todos</a>
                </div>
                <?php
include_once '../modelo/config.php';

// Verifica si el usuario ha iniciado sesión y obtiene su unique_id
if (isset($user_id['user_id'])) {
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
        echo '<span><b>' . $fname . ' ' . $lname . '</b><br>Friends in Common</span>';
        echo '<button class="add-friend" data-user-id="' . $user_id . '">+</button>';
        echo '</div>';
        
    }
} else {
    echo '<p>No hay usuarios disponibles.</p>';
}

echo '</div>';
?>




            <div class="row shadow">
                <div class="row_title">
                    <span>Tus actividades</span>
                </div>
                <?php
// Consulta SQL para obtener las actividades recientes de todos los usuarios
$sql = "SELECT users.img AS user_img, users.fname, users.lname, estado.media_type
        FROM estado
        INNER JOIN users ON estado.user_id = users.user_id
        GROUP BY estado.id
        ORDER BY estado.created_at DESC
        LIMIT 8"; // Limitar a las últimas 5 actividades recientes, por ejemplo

$result = mysqli_query($conn, $sql);

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

if (isset($user_id['user_id'])) {

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
            echo '    <div class="row_contain">';
            echo '<img src="../modelo/contenidocurso/fotocurso/' . $logo . '" alt="" />';
            echo '<span><a href=""><b>' . $titulo . '</b></a><br>' . $categoria . '<br>Precio: ' . $precio . '</span>';
            echo '    </div>';
        }

       
        
    } else {
        echo '<p>No tienes cursos favoritos.</p>';
    }
}
?>
</div>    
            </div>
        </div>
        <?php
        include_once ('../vistas/content_final_other.php');
?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="http://code.jquery.com/jquery-latest.js"></script>
    <script src="../js/allscripts_pages.js"></script>
    <script src="../js/coments_script.js"></script>
    <script src="../js/modal_perfil.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
    const likeButtons = document.querySelectorAll(".like-button");

    likeButtons.forEach((button) => {
        button.addEventListener("click", function () {
            const estadoId = this.getAttribute("data-estado-id");
            console.log("EstadoID:", estadoId);
            // Realizar una solicitud Ajax al servidor para actualizar el campo "like"
            // y cambiar el estado del botón
            $.ajax({
                url: "../modelo/actualizar_like.php",
                method: "POST",
                data: { estadoId: estadoId },
                success: function (response) {
                    console.log(response);

                    // Verificar la respuesta del servidor y actualizar el botón en consecuencia
                    if (response === "liked") {
                        // Al usuario le gusta el estado, cambia el estilo del botón
                        button.classList.add("liked");
                    } else if (response === "unliked") {
                        // El usuario ha deshecho su "Me gusta", cambia el estilo del botón
                        button.classList.remove("liked");
                    }

                    // Actualizar la información de Me gusta en el contenedor correspondiente
                    loadLikesInfo(estadoId);
                },
            });
        });
    });
    
    // Función para cargar la información de Me gusta en el contenedor específico
    function loadLikesInfo(estadoId) {
        $.ajax({
            url: '../modelo/actualizar_like_info.php',
            type: 'GET',
            data: { estadoId: estadoId },
            success: function(response) {
                console.log(response);
                $('#likes-container-' + estadoId).html(response);
            }
        });
    }
});
</script>
        </body>
        </html>
        <?php
    } else {
        header("Location: error.php");
        exit();
    }
} else {
    // No se proporcionó ningún user_id en la URL
    // Puedes manejar esto como desees, por ejemplo, redirigiendo al usuario a una página de error
    header("Location: error.php");
    exit();
}
?>