<?php
$pageName = "COMPRAS";
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
<?php
include_once '../modelo/config.php';
if (isset($_SESSION['unique_id'])) {
    $unique_id_sesion = $_SESSION['unique_id'];
     $sql = "SELECT user_id FROM users WHERE unique_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $unique_id_sesion);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $user_id_sesion = $row['user_id'];
    }
}
$sql = "SELECT cursos.*, users.fname, users.lname, users.img 
                FROM cursos
                JOIN pagos ON cursos.curso_id = pagos.curso_id
                JOIN users ON cursos.user_id = users.user_id
                WHERE pagos.status = 'approved' AND pagos.user_id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id_sesion);
    $stmt->execute();
    $result = $stmt->get_result();

    if (mysqli_num_rows($result) > 0) {
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

    // Crea el HTML para mostrar el curso
    
    echo '    <div class="feed">';
    echo '        <div class="feed_title">';
    echo '            <img src="../modelo/contenidocurso/fotocurso/' . $row['logo'] . '" alt="" />';
    echo '            <span><b>Compraste el curso de </b><b>' . $nombre_creador . '</b> <a href="profile.html">' . $titulo . '</a><br><p>desde el: ' . $creacion . '</p></span>';
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
    echo '<button  onclick="openReportModal(' . $curso_id . ', ' . $user_id_creador . ')">Denunciar Contenido</button>';
   echo '<a class="custom-button" href="../modelo/contenidocurso/' . $row['contenido'] . '" download><i class="fa fa-download"></i> Descargar Contenido</a>';
    echo '         </div>';
    echo '    </div>';
   

}
}else{
    echo '<p>No has realizado ninguna compra todavia</p>';
}
echo '</div>';
?>
<!-- Modal de Denuncia de Contenido -->
<div id="reportModal" style="display: none; background-color: white; border: 2px solid #ff5e3a; border-radius: 10px; padding: 20px; width: 600px;">
    <div class="modal-content">
    <span class="closeReport" onclick="closeReportModal()">&times;</span>
        <h2>Denunciar Contenido</h2>
        <h6>ten en cuenta que suspenderemos el pago al vendedor hasta que corroboremos todo el proceso de compra.</h6>
        <form id="reportForm" action="../modelo/report_content.php" method="POST">
            <input type="hidden" name="curso_id" value="<?php echo $curso_id; ?>" id="cursoId"><br>
            <input type="hidden" name="user_id_creador" value="<?php echo $user_id_creador; ?>">
            <label for="reason">Cuentanos el motivo de la denuncia:</label><br>
            <textarea name="reason" id="reason" rows="4" cols="70" required></textarea>
            <div class="publish_icons">
            <button type="submit">Enviar Denuncia</button> 
            </div>
            <br></form>
    </div>
</div>


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
    function openReportModal(cursoId, userCreadorId) {
        // Obtiene el modal por su ID
        var modal = document.getElementById('reportModal');

        // Muestra el modal
        modal.style.display = 'block';

        // Establece el valor del campo curso_id
        document.getElementById('cursoId').value = cursoId;

        // Establece el valor del campo user_id (creador del curso)
        document.getElementById('user_id').value = userCreadorId;
    }

    function closeReportModal() {
        // Obtiene el modal por su ID
        var modal = document.getElementById('reportModal');

        // Cierra el modal
        modal.style.display = 'none';
    }
</script>

</body>
</html>
