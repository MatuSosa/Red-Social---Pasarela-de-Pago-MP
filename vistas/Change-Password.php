<?php
$pageName = "CONFIG";
include_once ('../vistas/content_principal.php');
?>



        

        <div class="right_row">
    <div class="row border-radius">
        <center>
            <div class="settings shadow">
                <div class="settings_title">
                    <h3>Cambio de contraseña</h3>
                </div>
                <div class="settings_content">
                    <form action="../modelo/change_password.php" method="POST">
                        <div class="pi-input pi-input-lgg">
                            <span>Contraseña actual</span>
                            <input type="password" name="currentPassword" placeholder="Ingresa contraseña actual" required>
                        </div>
                        <div class="pi-input pi-input-lg">
                            <span>Nueva contraseña</span>
                            <input type="password" name="newPassword" placeholder="Ingresa nueva contraseña" required>
                        </div>
                        <div class="pi-input pi-input-lg">
                            <span>Confirma nueva contraseña</span>
                            <input type="password" name="confirmNewPassword" placeholder="Confirma nueva contraseña" required>
                        </div>

                        <button type="submit">Guardar Cambios</button>
                    </form>
                </div>
            </div>
        </center>
    </div>
    <!-- Display status message if set -->
<?php if (!empty($status_message)) : ?>
        <div class="status-message" id="success-message"><?php echo $status_message; ?></div>
    <?php endif; ?>
    <?php if (!empty($error_message)) : ?>
    <div class="error-message" id="error-message"><?php echo $error_message; ?></div>
<?php endif; ?>

</div>


        <div class="suggestions_row">
            <div class="row shadow">
                <div class="row_title">
                    <span>Configuraciones del perfil</span>
                </div>
                <div class="menusetting_contain">
                    <ul>
                        <li>
                            <a href="personal-Information.php">Información Personal</a>
                        </li>
                        <li>
                            <a href="settings.php">Configuración de la cuenta</a>
                        </li>
                        <li>
                            <a href="Change-Password.php" id="settings-select">Cambiar contraseña</a>
                        </li>
                    </ul>
                </div>
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
            echo '    <div class="row_contain">';
            echo '<img src="../modelo/contenidocurso/fotocurso/' . $logo . '" alt="" />';
            echo '<span><a href=""><b>' . $titulo . '</b></a><br>' . $categoria . '<br>Precio: ' . $precio . '</span>';
            echo '<button class="delete-friend"><i class="fa fa-trash" aria-hidden="true"></i></button>';
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
    