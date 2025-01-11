<?php
$pageName = "EDITAR";
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

<div class="row border-radius">
                <center><div class="settings shadow">
                    <div class="settings_title">
                        <h3>Modificar cursos</h3>
                    </div>
                    <form action="../modelo/modificate_page.php" method="POST" enctype="multipart/form-data">

<?php
include_once '../modelo/config.php';
if (isset($_GET['curso_id'])) {
    $cursoId = $_GET['curso_id'];

   // Realiza la consulta SQL para obtener los datos del curso con el cursoId
$sql = "SELECT cursos.*, users.fname, users.lname, users.img FROM cursos
JOIN users ON cursos.user_id = users.user_id
WHERE cursos.curso_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $cursoId); // Usar cursoId de la URL
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

?>
<div class="pi-input pi-input-lgg">

    <span>Sube una foto descriptiva del contenido:</span>
    <input type="file" name="logo" id="logo" accept=".jpg, .jpeg, .png" value="<?php echo $logo; ?>">
</div>
<div class="pi-input pi-input-lgg">
    <span>Título del curso/tutorial:</span>
    <input type="text" name="titulo" id="titulo" value="<?php echo $titulo; ?>" required />
</div>
<div class="pi-input pi-input-lgg">
    <span>Una breve descripción del curso/tutorial:</span>
    <input type="text" name="descripcion" id="descripcion" value="<?php echo $descripcion; ?>" required />
</div>
<div class="pi-input pi-input-lg">
    <span>En qué formato lo subes:</span>
    <select name="tipo" id="tipo" required>
        <option value="Archivo PDF" <?php echo ($tipo === 'Archivo PDF') ? 'selected' : ''; ?>>Archivo PDF</option>
        <option value="Archivo ZIP" <?php echo ($tipo === 'Archivo ZIP') ? 'selected' : ''; ?>>Archivo ZIP</option>
        <option value="Video" <?php echo ($tipo === 'Video') ? 'selected' : ''; ?>>Video</option>
        <option value="Word" <?php echo ($tipo === 'Word') ? 'selected' : ''; ?>>Word</option>
        <option value="Otro" <?php echo ($tipo === 'Otro') ? 'selected' : ''; ?>>Otro</option>
    </select>
</div>
<div class="pi-input pi-input-lg">
    <span>Cuánto dura el curso/tutorial:</span>
    <input type="time" id="hora" name="hora" min="00:00" max="23:59" value="<?php echo $hora; ?>" required>
</div>
<div class="pi-input pi-input-lg">
    <span>Categoría del contenido:</span>
    <select name="categoria" id="categoria" required>
        <option value="Arte" <?php echo ($categoria === 'Arte') ? 'selected' : ''; ?>>Arte</option>
        <option value="Ciencias" <?php echo ($categoria === 'Ciencias') ? 'selected' : ''; ?>>Ciencias</option>
        <option value="Cocina" <?php echo ($categoria === 'Cocina') ? 'selected' : ''; ?>>Cocina</option>
        <option value="Deportes" <?php echo ($categoria === 'Deportes') ? 'selected' : ''; ?>>Deportes</option>
        <option value="Diseño" <?php echo ($categoria === 'Diseño') ? 'selected' : ''; ?>>Diseño</option>
        <option value="Educacion" <?php echo ($categoria === 'Educación') ? 'selected' : ''; ?>>Educación</option>
        <option value="Fotografia" <?php echo ($categoria === 'Fotografía') ? 'selected' : ''; ?>>Fotografía</option>
        <option value="Idiomas" <?php echo ($categoria === 'Idiomas') ? 'selected' : ''; ?>>Idiomas</option>
        <option value="Informática" <?php echo ($categoria === 'Informática') ? 'selected' : ''; ?>>Informática</option>
        <option value="Marketing" <?php echo ($categoria === 'Marketing') ? 'selected' : ''; ?>>Marketing</option>
        <option value="Negocios" <?php echo ($categoria === 'Negocios') ? 'selected' : ''; ?>>Negocios</option>
        <option value="Salud y Bienestar" <?php echo ($categoria === 'Salud y Bienestar') ? 'selected' : ''; ?>>Salud y Bienestar</option>
        <option value="Viajes y Cultura" <?php echo ($categoria === 'Viajes y Cultura') ? 'selected' : ''; ?>>Viajes y Cultura</option>
        <option value="Videojuegos" <?php echo ($categoria === 'Videojuegos') ? 'selected' : ''; ?>>Videojuegos</option>
        <option value="Otros" <?php echo ($categoria === 'Otros') ? 'selected' : ''; ?>>Otros...</option>
    </select>
</div>

<div class="pi-input pi-input-lg">
    <span>Precio (deja en blanco si es gratis):</span>
    <input type="number" name="precio" id="precio" min="0" step="0.01" value="<?php echo $precio; ?>">
</div>
<div class="pi-input pi-input-lgg">
    <span>Sube tu curso acá:</span>
    <input type="file" name="contenido" id="contenido" accept=".pdf, .zip, .mp4, .docx, .doc, .zip" value="<?php echo $contenido; ?>">
</div>
<div style="text-align: center;">
<input type="hidden" name="acepto_terminos" value="1">
<input type="hidden" name="curso_id" value="<?php echo $cursoId; ?>">
<button class="openEditPopup" style="float: center; border-radius: 5px; cursor: pointer; padding: 10px 20px; cursor: pointer;">Guardar cambios</button>
<a class="custom-button" style="float: center; border-radius: 5px; cursor: pointer; padding: 10px 20px; cursor: pointer;" href="../vistas/create_page.php">Volver</a><br><br>
</div>
<?php
}
}
?>
</form>



                    </div>
                </div></center>

                </div>


                <?php
        include_once ('../vistas/content_final.php');
?>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="http://code.jquery.com/jquery-latest.js"></script>
    <script src="../js/allscripts_pages.js"></script>
    <script src="../js/coments_script.js"></script>
    <script src="../js/modal_perfil.js"></script>


</body>
</html>
