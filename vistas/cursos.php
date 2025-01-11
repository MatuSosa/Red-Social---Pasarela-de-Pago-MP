<?php
$pageName = "CURSOS";
include_once ('../vistas/content_principal.php');
?>



        <div class="right_row">
            <div class="row border-radius">
                <div class="feed">
                    <div class="feed_title">
                    <span><a href="../vistas/profile.php"><b>Perfil</b></a> | <a href="../vistas/about.php"><b>Sobre mi</b></a> | <a href="../vistas/photos.php" ><b>Fotos</b></a> | <a id="select_profile_menu" href="../vistas/cursos.php " ><b>Cursos</b></a></span>
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
<label for="filtro-categoria">Filtrar por Categorías: </label>
<select id="filtro-categoria">
    <option value="todos">Todos</option>
    <option value="arte">Arte</option>
    <option value="ciencias">Ciencias</option>
    <option value="Cocina">Cocina</option>
    <option value="Deportes">Deportes</option>
    <option value="Diseño">Diseño</option>
    <option value="Educación">Educación</option>
    <option value="Fotografia">Fotografia</option>
    <option value="Idioma">Idioma</option>
    <option value="Informática">Informática</option>
    <option value="Marketing">Marketing</option>
    <option value="Negocios">Negocios</option>
    <option value="Salud y Bienestar">Salud y Bienestar</option>
    <option value="Viajes y Cultura">Viajes y Cultura</option>
    <option value="Video Juegos">Video Juegos</option>
    <option value="Otros">Otros</option>
</select>
</div>
<div class="row border-radius cursos-container">
<?php
include_once '../modelo/config.php';
require '../vendor/autoload.php';
MercadoPago\SDK :: setAccessToken('TEST-1514196726511565-091916-b03ecc12ee50c9c6de8feb63d769d371-501996312');

// Realiza la consulta SQL para obtener los datos de los cursos con la información del creador (users)
$sql = "SELECT cursos.*, users.fname, users.lname, users.img FROM cursos
        JOIN users ON cursos.user_id = users.user_id";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
if (mysqli_num_rows($result) > 0) {
// Recorre los resultados y muestra cada curso en el formato deseado
while ($row = $result->fetch_assoc()) {
    // Extrae los datos del curso y del 
    $titulo = $row['titulo'];
    $logo = $row['logo'];
    $descripcion = $row['descripcion'];
    $categoria = $row['categoria'];
    $precio = $row['precio'];
    $hora = $row['hora'];
    $nombre_creador = $row['fname'] . ' ' . $row['lname'];
    $creacion = $row['fecha_creacion'];
    $tipo = $row['tipo'];
    $curso_id = $row['curso_id'];
    $user_id_creador = $row['user_id'];

    // Crea un nuevo item para el curso en MercadoPago
$item = new MercadoPago\Item();
$item->id = $curso_id;
$item->title = $titulo;
$item->quantity = 1;
$item->unit_price = $precio;
$item->currency_id = "ARS";

  // Crea un array de items y asigna el item al array
  $itemsArray = array($item);

  // Crea la preferencia y asigna el array de items
  $preference = new MercadoPago\Preference();
  $preference->items = $itemsArray;
  
  
  $preference->back_urls = array(
    "success" => "http://localhost/tesis/modelo/captura_pago.php?curso_id=" . $curso_id . "&user_id=" . $user_id_creador,
    "failure" => "http://localhost/tesis/vistas/cursos.php"
);

$preference->auto_return = "approved";
$preference->binary_mode = true;
$preference->save();
    // Crea el HTML para mostrar el curso
    echo '    <div class="feed">';
    echo '        <div class="feed_title">';
    echo '            <img src="../modelo/contenidocurso/fotocurso/' . $row['logo'] . '" alt="" />';
    echo '            <span><b>' . $nombre_creador . '</b> ofrece: <a href="profile.html">' . $titulo . '</a><br><p>desde el: ' . $creacion . '</p></span>';
    echo '        </div>';
    echo '        <div class="feed_content">';
    echo '            <div class="feed_content_image">';
    echo '            <span><b>En este curso:</b> <p>' . $descripcion . '</p></span>';
    echo '           <span><b>Categoria del curso:</b> <p>' . $categoria . '</p></span>';
    echo '           <span><b>Precio del curso:</b> <p>' . $precio . '</p></span>';   
    echo '           <span><b>El contenido del curso está en:</b> <p>' . $tipo . '</p></span>'; 
    echo '           <span><b>El contenido tiene una hora aproximada de: </b> <p>' . $hora . '</p></span>'; 
    echo '            </div>';
    echo '        </div>';
    echo '        <div class="publish_icons">';
    echo '            <button class="addFavoriteButton" data-course-id="' . $curso_id . '">Agregar a favoritos</button>';
    if ($precio == 0.00) {
        echo '<a class="custom-button" href="../modelo/contenidocurso/' . $row['contenido'] . '" download><i class="fa fa-download"></i> Descargar Contenido</a>';
    } else {
        // Si el precio no es 0.00, muestra el botón para ir a pagar
        echo '<a class="custom-button" href="' . $preference->sandbox_init_point . '">Ir a pagar <i class="fa fa-cc-visa"></i></a>';
    }
    echo '         </div>';
    echo '    </div>';
    

}
} else {
    echo '<div class="row_contain">';
            echo '<span>Aún no hay cursos cargados</span>';
            echo '</div>';
}
echo '</div>';
?>



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
    $(document).ready(function() {
    // Manejar el clic en el botón "Agregar a favoritos"
    $(".addFavoriteButton").click(function() {
        // Obtener el curso_id del botón
        var cursoId = $(this).data("course-id");
        console.log(cursoId);
        // Realizar la solicitud AJAX al servidor
        $.ajax({
            type: "POST",
            url: "../modelo/agregar_a_favoritos.php", 
            data: { curso_id: cursoId },
            success: function(response){
                location.reload();
                },
                error: function(error){
                location.reload();
             }
        });
    });
});

</script>
<script>
    const mp = new MercadoPago('TEST-aa22bd54-927e-4221-974e-e892b7a928ee', {
        locale: 'es-AR'
    });

    mp.checkout({
        preference: {
            id: '<?php echo $preference->id; ?>'
        },
        render:{
            container: '.checkout-btn',
            label: 'Comprar con MP'
        }
    })
 </script>
 <script>
document.getElementById('filtro-categoria').addEventListener('change', function() {
    var categoriaSeleccionada = this.value.toLowerCase().trim();

    var cursos = document.querySelectorAll('.cursos-container .feed'); 
    var cursosEncontrados = false;
    cursos.forEach(function(curso) {
        var categoriaElement = curso.querySelector('.feed_content span:nth-child(2) p');
        var categoriaCurso = categoriaElement ? categoriaElement.textContent.toLowerCase().trim() : '';
        
        if (categoriaSeleccionada === 'todos' || categoriaSeleccionada === categoriaCurso) {
            curso.style.display = 'block';
            cursosEncontrados = true; // Se encontró al menos un curso con la categoría seleccionada
        } else {
            curso.style.display = 'none';
        }
    });

    // Mostrar mensaje si no se encontraron cursos con la categoría seleccionada
    if (!cursosEncontrados) {
        var mensaje = document.createElement('div');
        mensaje.textContent = 'No se encontraron cursos con esta categoría.';
        mensaje.className = 'no-courses-message';
        
        // Verificar si ya hay un mensaje y reemplazarlo
        var mensajeExistente = document.querySelector('.no-courses-message');
        if (mensajeExistente) {
            mensajeExistente.textContent = 'No se encontraron cursos con esta categoría.';
        } else {
            // Agregar el mensaje al contenedor de cursos
            var contenedorCursos = document.querySelector('.cursos-container');
            contenedorCursos.appendChild(mensaje);
        }
    } else {
        // Ocultar el mensaje si se encontraron cursos
        var mensajeExistente = document.querySelector('.no-courses-message');
        if (mensajeExistente) {
            mensajeExistente.remove();
        }
    }
});


</script>
</body>
</html>
