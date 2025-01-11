<?php
$pageName = "CREAR";
include_once ('../vistas/content_principal.php');
?>


        <div class="right_row">
            <div class="row border-radius">
                <div class="feed">
                    <div class="feed_title">
                        <span><a href="../vistas/profile.php"><b>Perfil</b></a> | <a href="../vistas/about.php"><b>Sobre mi</b></a> | <a href="../vistas/photos.php"><b>Fotos</b></a></span>
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

            <div class="row">
       
            <div class="feed_title">
        <span>Bienvenido a la sección de creación de cursos</span>
    </div>  
    <div class="row_title">
        <span>Aquí puedes compartir tu contenido con otros usuarios, ya sea de forma gratuita o para obtener una remuneración. Por favor, asegúrate de que tu contenido sea respetuoso y no ofensivo para otros usuarios.</span><br><br>
        
        <input type="checkbox" id="acceptTerms"><span>Lee y acepta nuestros <a href="terminos_y_condic.php" id="termsLink">Términos y Condiciones</a> para crear contenido.</span> 
</div>  
<button id="openPopup" style="float: center; border-radius: 5px; cursor: pointer; padding: 10px 20px; cursor: pointer;">Crear ahora</button>
</div>

<?php
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

 // Realiza la consulta SQL para obtener los datos de los cursos con la información del creador (users)
$sql = "SELECT cursos.*, users.fname, users.lname, users.img, COUNT(pagos.curso_id) AS ventas,
(SELECT COUNT(*) FROM denuncias WHERE denuncias.curso_id = cursos.curso_id AND denuncias.aprobada = 1) AS denuncias_aprobadas
FROM cursos
JOIN users ON cursos.user_id = users.user_id
LEFT JOIN pagos ON cursos.curso_id = pagos.curso_id
WHERE cursos.user_id = ?
GROUP BY cursos.curso_id";


    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i",  $user_id_sesion);
    $stmt->execute();
    $result = $stmt->get_result();

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
        $ventas = $row['ventas'];
        $denuncias_aprobadas = $row['denuncias_aprobadas'];

        // Determina el estado del curso para cobrar
        $estado_cobro = ($denuncias_aprobadas > 0) ? 'Pendiente de revisión' : 'Correcto para cobrar';
        $cursos_correctos_para_cobrar = $ventas - $denuncias_aprobadas;

        // Crea el HTML para mostrar el curso
        echo '<div class="row border-radius">';
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
        echo '           <span><b>Ventas:</b> <p>' . $ventas . '</p></span>';
        if ($ventas == 0) {
            echo '           <span><b>Estado:</b> <p>Aún no hay ventas de este curso</p></span>';
        } else {
            // Determina el estado del curso para cobrar
            $estado_cobro = ($denuncias_aprobadas > 0) ? 'Pendiente de revisión' : 'Correcto para cobrar';
            echo '           <span><b>Cursos Correctos para Cobrar:</b> <p>' . $cursos_correctos_para_cobrar . '</p></span>';
            echo '           <span><b>Denuncias Pendientes de Revisión:</b> <p>' . $denuncias_aprobadas . '</p></span>';
        }
        echo '            </div>';
        echo '        </div>';
        echo '        <div class="publish_icons">';
        echo '<button class="openEditPopup" data-course-id="' . $curso_id . '" style=" border-radius: 5px; cursor: pointer; padding: 10px 20px; cursor: pointer;">Modificar curso</button><br><br>';
        echo '<button class="custom-button" id="abrirPopup" style=" height: 33px;">Eliminar Curso</button>';
        echo '         </div>';
        echo '    </div>';
        echo '</div>';
    }
}
?>

</div>
<div id="popupdelete" style="display: none; background-color: white; border: 2px solid #ff5e3a; border-radius: 10px; padding: 20px; width: 600px;">
    <button id="closepopupdelete" style="float: right;">&times;</button>
    <form action="../modelo/delete_page.php" method="POST">
    <b><label for="aboutMe">Seguro que deseas eliminar el curso?</label></b>
    <input type="hidden" name="curso_id" value="<?php echo $curso_id; ?>">
    <div class="publish_icons">
        <button type="submit" class="confirm-button">Confirmar</button>
    </div>
</form>
</div>

     <!---Modal para cargar un nuevo curso--->
     <div id="popup" style="display: none; background-color: white; border: 2px solid #ff5e3a; border-radius: 10px; padding: 20px; width: 600px;">
    <button class="closeEditPopup" id="closePopup" style="float: right; background-color: #ff5e3a; border: none; font-size: 20px;">&times;</button>
    <form action="../modelo/create_page.php" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="acepto_terminos" value="1">
    <div class="pi-input pi-input-lgg">
        <span>Sube una foto descriptiva del contenido:</span>
        <input type="file" name="logo" id="logo" accept=".jpg, .jpeg, .png" required>
    </div>
    <div class="pi-input pi-input-lgg">
        <span>Título del curso/tutorial:</span>
        <input type="text" name="titulo" id="titulo" required/>
    </div>
    <div class="pi-input pi-input-lgg">
        <span>Una breve descripción del curso/tutorial:</span>
        <input type="text" name="descripcion" id="descripcion" required/>
    </div>
    <div class="pi-input pi-input-lg">
        <span>En qué formato lo subes:</span>
        <select name="tipo" id="tipo" required>
            <option value="Archivo PDF">Archivo PDF</option>
            <option value="Archivo ZIP">Archivo ZIP</option>
            <option value="Video">Video</option>
            <option value="Word">Word</option>
            <option value="Otro">Otro</option>
        </select>
    </div>
    <div class="pi-input pi-input-lg">
        <span>Cuánto dura el curso/tutorial:</span>
        <input type="time" id="hora" name="hora" min="00:00" max="23:59" required>
    </div>
    <div class="pi-input pi-input-lg">
        <span>Categoría del contenido:</span>
        <select name="categoria" id="categoria" required>
            <option value="Arte">Arte</option>
            <option value="Ciencias">Ciencias</option>
            <option value="Cocina">Cocina</option>
            <option value="Deportes">Deportes</option>
            <option value="Diseño">Diseño</option>
            <option value="Educacion">Educación</option>
            <option value="Fotografia">Fotografía</option>
            <option value="Idiomas">Idiomas</option>
            <option value="Informática">Informática</option>
            <option value="Marketing">Marketing</option>
            <option value="Negocios">Negocios</option>
            <option value="Salud y Bienestar">Salud y Bienestar</option>
            <option value="Viajes y Cultura">Viajes y Cultura</option>
            <option value="Videojuegos">Videojuegos</option>
            <option value="Otros">Otros...</option>
        </select>
    </div>
    <div class="pi-input pi-input-lg">
        <span>Precio (deja en blanco si es gratis):</span>
        <input type="number" name="precio" id="precio" min="0" step="0.01" oninput="toggleInfoPago()">
    </div>
    <div id="infoPago" style="display: none;">
    <h6>Indicanos donde deseas que hagamos el pago.</h6>
    <div class="pi-input pi-input-lg">
        <span>CBU/CVU:</span>
        <input type="text" name="cbu_cvu" id="cbu_cvu"/>
    </div>
    <div class="pi-input pi-input-lg">
        <span>Alias de la cuenta:</span>
        <input type="text" name="alias" id="alias"/>
    </div>
    <div class="pi-input pi-input-lg">
        <span>Titular de la cuenta:</span>
        <input type="text" name="titular_cuenta" id="titular_cuenta" />
    </div>
</div>
    <div class="pi-input pi-input-lgg">
        <span>Sube tu curso acá:</span>
        <input type="file" name="contenido" id="contenido" accept=".pdf, .zip, .mp4, .docx, .doc, .zip">
        
    </div>
    <div style="text-align: center;">
        <input type="submit" value="Guardar" style="border-radius: 5px; cursor: pointer;">
    </div>
</form>
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
    // Obtener los elementos del DOM
    const acceptTermsCheckbox = document.getElementById('acceptTerms');
    const openPopupButton = document.getElementById('openPopup');
    const termsLink = document.getElementById('termsLink');
    const closePopupButton = document.getElementById('closePopup');
    // Ocultar el botón inicialmente
    openPopupButton.style.display = 'none';

    closePopupButton.addEventListener('click', function() {
        popup.style.display = 'none';
    });
    // Mostrar el botón cuando se acepten los términos
    acceptTermsCheckbox.addEventListener('change', function () {
        openPopupButton.style.display = this.checked ? 'block' : 'none';
    });

    // Enlazar los términos y condiciones a una URL
    termsLink.href = '../vistas/terminos_y_condic.php';
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const openPopupButtons = document.querySelectorAll('.openEditPopup');
    const popup = document.querySelector('.EditPopup');
    const closePopupButton = document.querySelector('.closeEditPopup');
    const cursoIdInput = document.getElementById('curso_id');

    openPopupButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            const courseId = button.getAttribute('data-course-id');
            cursoIdInput.value = courseId; // Establece el valor del curso_id en el formulario
            popup.style.display = 'block';
        });
    });

    closePopupButton.addEventListener('click', function() {
        popup.style.display = 'none';
    });

    // Agrega un evento para ocultar la ventana emergente cuando se envía el formulario (guardar).
    const form = document.querySelector('form');
    form.addEventListener('submit', function() {
        popup.style.display = 'none';
    });
});
</script>
<script>
document.addEventListener("DOMContentLoaded", function () {
  const editButtons = document.querySelectorAll(".openEditPopup");

  editButtons.forEach(function (button) {
    button.addEventListener("click", function () {
      const cursoId = button.getAttribute("data-course-id");
      // Redirige a la página de edición con el curso_id como parámetro GET
      window.location.href = "edit_page.php?curso_id=" + cursoId;
    });
  });
});
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
    const openPopupButton = document.getElementById('abrirPopup');
    const popup = document.getElementById('popupdelete');
    const closePopupButton = document.getElementById('closepopupdelete');
    const form = document.querySelector('form'); // Obtén el formulario

    openPopupButton.addEventListener('click', function() {
        popup.style.display = 'block';
    });

    closePopupButton.addEventListener('click', function() {
        popup.style.display = 'none';
    });

    // Agrega un evento para ocultar la ventana emergente cuando se envía el formulario (guardar).
    form.addEventListener('submit', function() {
        const cursoIdInput = document.querySelector('input[name="curso_id"]'); // Obtén el campo de entrada oculto
        cursoIdInput.value = <?php echo $curso_id; ?>; // Establece el valor del curso_id en el campo oculto
        popup.style.display = 'none';
    });
});

</script>
<script>
function toggleInfoPago() {
    const precioInput = document.getElementById('precio');
    const infoPagoContainer = document.getElementById('infoPago');

    if (precioInput.value === '') {
        infoPagoContainer.style.display = 'none';
    } else {
        infoPagoContainer.style.display = 'block';
    }
}
</script>

</body>
</html>
