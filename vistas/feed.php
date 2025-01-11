<?php
$pageName = "NOTICIAS";
include_once ('../vistas/content_principal.php');

?>



        <div class="right_row">
            <div class="row border-radius">
                <div class="feed">
                    <div class="feed_title">
                        <span><a href="../vistas/profile.php"><b>Perfil</b></a> | <a href="../vistas/about.php"><b>Sobre mi</b></a> | <a href="../vistas/photos.php"><b>Fotos</b></a>| <a href="../vistas/cursos.php"><b>Cursos</b></a></span>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="publish">
                    <div class="row_title">
                        <span><i class="fa fa-newspaper-o" aria-hidden="true"></i> Estado</span>
                    </div>

                    <form method="post" action="../modelo/procesar_estado.php" enctype="multipart/form-data">
    <input type="hidden" name="user_id" value="<?php echo $row['user_id']; ?>">
    <div class="publish_textarea">
        <img class="border-radius-image" src="../modelo/images/<?php echo $row['img']; ?>" alt="" />
        <textarea name="estado" placeholder="Comparte tu estado, <?php echo $row['fname']?>" style="resize: none;"></textarea>
        <input type="hidden" id="media-url" name="media_url">
        <div id="image-preview"></div>
    </div>
    <div class="publish_icons">
        <ul>
            <li>
                <label for="foto"><i class="fa fa-camera"></i></label>
                <input type="file" id="foto" name="foto" accept="image/jpeg, image/png" style="display: none;" onchange="handleFileSelect('foto', 'image-preview')">
            </li>
            <li>
                <label for="video"><i class="fa fa-video-camera"></i></label>
                <input type="file" id="video" name="video" accept="video/*" style="display: none;">
            </li>
          <li>
                <label for="ubicacion" ><i class="fa fa-map-marker" id="ubicacionButton"></i></label>
                <input type="text" name="ubicacionButton" style="display: none;">
            </li>
        </ul>
        <button type="submit">Publicar</button>
    </div>
</form>
</div>
<div id="ubicacionModal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Selecciona tu ubicación</h2>
        <input type="text" id="ubicacionInput" placeholder="Escribe el nombre de una ciudad..." autocomplete="off">
        <ul id="suggestion-list"></ul>
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
 // Consulta SQL para obtener todos los estados con comentarios y conteo de comentarios
 $sql = "SELECT estado.*, users.fname, users.lname, users.img,
 c.user_id AS comentario_user_id, c.contenido AS comentario_contenido, c.fecha_creacion AS comentario_fecha
FROM estado
INNER JOIN users ON estado.user_id = users.user_id
LEFT JOIN friends AS f1 ON estado.user_id = f1.friend_id AND f1.user_id = ?
LEFT JOIN friends AS f2 ON estado.user_id = f2.user_id AND f2.friend_id = ?
LEFT JOIN comentarios AS c ON estado.id = c.estado_id
WHERE (f1.status = 'accepted' OR f2.status = 'accepted' OR estado.user_id = ?)
GROUP BY estado.id
ORDER BY estado.created_at DESC
";

        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "iii", $user_id, $user_id, $user_id);
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
                <img src="../images/<?php echo $media_url; ?>" alt="" />
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
    <li class="hover-orange"><i class="fa fa-thumbs-up"></i> Me gusta </li></button>
<button style="background: none; border: none; padding: 0; color:#515365; flex: 1; text-align: center;" class="comment-button">
<li class="hover-orange"><i class="fa fa-comments-o"></i> Comentar</li></button>
<button style="background: none; border: none; padding: 0; color:#515365; flex: 1; text-align: center;" 
        onclick="compartirEstado(<?php echo $estado_id; ?>, '<?php echo addslashes($content); ?>', 
                                  '<?php echo addslashes($media_type); ?>', '<?php echo addslashes($media_url); ?>')">
    <li class="hover-orange"><i class="fa fa-share"></i> Compartir</li>
</button>
</div>
<div class="comment-input" style="display: none;">
<form class="formcoment" action="../modelo/add_comment.php" method="post">
    <input type="hidden" name="estado_id" value="<?php echo $estado_id; ?>">
    <input type="hidden" name="unique_id" value="<?php echo $_SESSION['unique_id']; ?>">
    <input type="hidden" name="comentario_padre_id" value="<?php echo $comentario_id; ?>"> 
    <input type="text" name="nuevo_comentario" placeholder="Añadir un comentario...">
    <button type="submit"><i class="fa fa-paper-plane"></i></button>
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
                    <span class="comment-container"><b><h5><?php echo $comentario_username; ?>:</h5></b><?php echo $comentario_content; ?></span>
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
        echo "<p>Aún no hay publicaciones.</p>";
       
    }
    echo '</div>';
    // Liberar el resultado y cerrar la conexión
    mysqli_free_result($result);
}
} else {
echo '<div class="row_contain">';
echo '<span>El usuario no ha iniciado sesion</span>';
echo '</div>';
}
?>
            
            

            <center>
                <a href=""><div class="loadmorefeed">
                    <i class="fa fa-ellipsis-h"></i>
                </div></a>
            </center>
        </div>
        <?php
        include_once ('../vistas/content_final.php');
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
            $.ajax({
                url: "../modelo/actualizar_like.php",
                method: "POST",
                data: { estadoId: estadoId },
                success: function (response) {
                    console.log(response);

                    if (response === "liked") {
                        button.classList.add("liked");
                    } else if (response === "unliked") {
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
<script>
    // Función para manejar la vista previa de la imagen
    function handleFileSelect(inputId, previewId) {
        const input = document.getElementById(inputId);
        const preview = document.getElementById(previewId);

        // Verifica si se seleccionó un archivo
        if (input.files && input.files[0]) {
            const reader = new FileReader();

            // Cuando se cargue el archivo, muestra la vista previa de la imagen
            reader.onload = function(e) {
                preview.innerHTML = ''; // Limpia cualquier vista previa anterior
                const img = document.createElement('img');
                img.src = e.target.result;
                img.alt = 'Image Preview';
                preview.appendChild(img);
            };

            // Lee el archivo como una URL de datos
            reader.readAsDataURL(input.files[0]);
        } else {
            // Si no se seleccionó un archivo, borra la vista previa
            preview.innerHTML = '';
        }
    }
</script>
<script>
    document.querySelectorAll('#share-button').forEach(function(button) {
    button.addEventListener('click', function() {
        // Encuentra el formulario relacionado al botón
        var estadoForm = this.parentNode.querySelector('.estado-form');

        // Envía el formulario
        estadoForm.submit();
    });
});

</script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    // Obtén el botón de ubicación y el modal de ubicación
    const ubicacionButton = document.getElementById('ubicacionButton');
    const ubicacionModal = document.getElementById('ubicacionModal');

    // Agrega un evento de clic al botón de ubicación para mostrar el modal
    ubicacionButton.addEventListener('click', function() {
        console.log('Botón de ubicación presionado');
        ubicacionModal.style.display = 'block';
    });

    // Obtén el input de ubicación y configura el autocompletar
    const ubicacionInput = document.getElementById('ubicacionInput');
    const autocomplete = new google.maps.places.Autocomplete(ubicacionInput);
    autocomplete.setTypes(['(cities)']);

    // Escucha el evento cuando se selecciona una ubicación
    google.maps.event.addListener(autocomplete, 'place_changed', function() {
        const place = autocomplete.getPlace();
        const ubicacion = place.formatted_address;
        const textarea = document.getElementsByName("estado")[0];
        textarea.value += "\nEstoy en " + ubicacion + " ✈️";
        ubicacionModal.style.display = 'none';
    });

    // Cierra el modal si el usuario hace clic en el botón de cerrar
    const closeButton = ubicacionModal.querySelector('.close');
    closeButton.addEventListener('click', function() {
        ubicacionModal.style.display = 'none';
    });

    // Cierra el modal si el usuario hace clic fuera del área del modal
    window.addEventListener('click', function(event) {
        if (event.target === ubicacionModal) {
            ubicacionModal.style.display = 'none';
        }
    });
});
</script>

<script>
// Obtén el input de ubicación
var ubicacionInput = document.getElementById('ubicacionInput');

// Crea un objeto de autocompletar para el input de ubicación
var autocomplete = new google.maps.places.Autocomplete(ubicacionInput);

// Establece los tipos de lugares que deseas autocompletar (por ejemplo, ciudades)
autocomplete.setTypes(['(cities)']);

// Escucha el evento cuando se selecciona una ubicación
google.maps.event.addListener(autocomplete, 'place_changed', function() {
    var place = autocomplete.getPlace();

    // Obtén la información de la ubicación seleccionada
    var ubicacion = place.formatted_address;

    // Agrega la ubicación al textarea o haz lo que desees con ella
    var textarea = document.getElementsByName("estado")[0];
    textarea.value += "\nUbicación: " + ubicacion;

    // Desvincula el evento de clic en el botón "Agregar"
    agregarButton.removeEventListener('click', agregarUbicacion);
});

// Escucha el evento cuando se presiona una tecla en el input de ubicación
ubicacionInput.addEventListener('input', function() {
    // Obtén las sugerencias de autocompletar
    var places = autocomplete.getPlaces();

    // Limpia las sugerencias anteriores
    var suggestionList = document.getElementById('suggestion-list');
    suggestionList.innerHTML = '';

    // Muestra las nuevas sugerencias
    places.forEach(function(place) {
        var suggestionItem = document.createElement('li');
        suggestionItem.textContent = place.description;
        suggestionList.appendChild(suggestionItem);
    });
});

</script>
<script>
function compartirEstado(estadoId, contenido, mediatype, mediaurl) {
    // Envia el contenido del estado al servidor utilizando AJAX
    var xhr = new XMLHttpRequest();
    xhr.open('POST', '../modelo/compartir_estado.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            location.reload();
        }
    };
    xhr.send('estado_id=' + estadoId + '&contenido=' + contenido + '&media_type=' + mediatype + '&media_url=' + mediaurl);
}

</script>
</body>
</html>
