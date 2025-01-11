<?php
$pageName = "SOBRE MI";
include_once ('../vistas/content_principal.php');
?>



        <div class="right_row">
            <div class="row border-radius">
                <div class="feed">
                    <div class="feed_title">
                        <span><a href="../vistas/profile.php" ><b>Perfil</b></a> | <a href="../vistas/about.php" id="select_profile_menu"><b>Sobre mi</b></a> | <a href="../vistas/photos.php"><b>Fotos</b></a> | <a  href="../vistas/cursos.php " ><b>Cursos</b></a></span>
                    </div>
                </div>
            </div>
            <div class="row border-radius">
                <div class="settings shadow">
                    <div class="settings_title">
                        <h3>Información personal</h3>

                    </div>
                    <div class="settings_content">
                
                        <style type="text/css">
                            .settings_content ul li p {padding:5px;padding-left: 20px}
                        </style>
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

    // Consulta SQL para obtener la información personal del usuario
    $sql = "SELECT * FROM user_info WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i",  $user_id_sesion);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Usuario encontrado en la tabla user_info, asigna los valores a las variables
        $row = $result->fetch_assoc();
        $aboutMe = $row['about_me'];
        $birthday = $row['birthday'];
        $birthplace = $row['birthplace'];
        $livesIn = $row['lives_in'];
        $occupation = $row['occupation'];
        $joinDate = $row['join_date'];
        $gender = $row['gender'];
        $status = $row['status'];
        $email = $row['email'];
        $website = $row['website'];
        $phoneNumber = $row['phone_number'];
        $hasUserInfo = true;
    } else {
        // Usuario no encontrado en la tabla user_info, inicializa las variables en blanco
        $aboutMe = 'sin datos de usuario';
        $birthday = 'sin datos de usuario';
        $birthplace = 'sin datos de usuario';
        $livesIn = 'sin datos de usuario';
        $occupation = 'sin datos de usuario';
        $joinDate = 'sin datos de usuario';
        $gender = 'sin datos de usuario';
        $status = 'sin datos de usuario';
        $email = 'sin datos de usuario';
        $website = 'sin datos de usuario';
        $phoneNumber = 'sin datos de usuario';
        $hasUserInfo = false;
    }
}
?>

                        <ul>
    <li>
        <p>
            <b>Acerca de mi:</b>
            <br>
            <?php echo isset($aboutMe) ? $aboutMe : ''; ?>
        </p>
    </li>
    <li>
        <p>
            <b>Mi nacimiento:</b>
            <br>
            <?php echo isset($birthday) ? $birthday : ''; ?>
        </p>
    </li>
    <li>
        <p>
            <b>Nací en:</b>
            <br>
            <?php echo isset($birthplace) ? $birthplace : ''; ?>
        </p>
    </li>
    <li>
        <p>
            <b>Vivo en:</b>
            <br>
            <?php echo isset($livesIn) ? $livesIn : ''; ?>
        </p>
    </li>
    <li>
        <p>
            <b>Mi ocupación:</b>
            <br>
            <?php echo isset($occupation) ? $occupation : ''; ?>
        </p>
    </li>
    <li>
        <p>
            <b>Desde el:</b>
            <br>
            <?php echo isset($joinDate) ? $joinDate : ''; ?>
        </p>
    </li>
    <li>
        <p>
            <b>Género:</b>
            <br>
            <?php echo isset($gender) ? $gender : ''; ?>
        </p>
    </li>
    <li>
        <p>
            <b>Estado civil:</b>
            <br>
            <?php echo isset($status) ? $status : ''; ?>
        </p>
    </li>
    <li>
        <p>
            <b>Email:</b>
            <br>
            <?php echo isset($email) ? $email : ''; ?>
        </p>
    </li>
    <li>
        <p>
            <b>Mi sitio web:</b>
            <br>
            <?php echo isset($website) ? $website : ''; ?>
        </p>
    </li>
    <li>
        <p>
            <b>Mi numero particular:</b>
            <br>
            <?php echo isset($phoneNumber) ? $phoneNumber : ''; ?>
        </p>
    </li>
</ul>

                    </div>

                </div><br>
                <?php
                if (!$hasUserInfo) {
                    echo '<button id="openPopup" style="float: center; border-radius: 5px; cursor: pointer; padding: 10px 20px; cursor: pointer;">Ingresar Info</button>';
                }
                ?>
            </div>
            
            </div>
          <!-- Ventana emergente para ingresar información -->

            <div id="popup" style="display: none; background-color: white; border: 2px solid #ff5e3a; border-radius: 10px; padding: 20px; width: 600px;">
    <button id="closePopup" style="float: right;">&times;</button>
    <form action="../modelo/guardar_info_user.php" method="POST">
        <div style="display: flex; justify-content: space-between;">
            <div style="flex: 1; padding: 10px;">
                <b><label for="aboutMe">Acerca de mi:</label></b>
                <input type="text" name="aboutMe" id="aboutMe" placeholder="Una descripcion personal" required>
                <br><b><label for="nacimiento">Nacimiento:</label></b>
                <input type="date" name="nacimiento" id="nacimiento" required>
                <br><br><b><label for="lugarnac">País:</label></b>
                <select name="lugarnac" id="lugarnac" required>
                    <option value="Argentina">Argentina</option>
                    <option value="Brasil">Brasil</option>
                    <option value="Chile">Chile</option>
                    <option value="Otro">Otro</option>
                </select>                
                <br><br><b><label for="lugarlive">Provincia:</label></b>
                <select name="lugarlive" id="lugarlive" required>
                    <option value="Córdoba">Córdoba</option>
                    <option value="Mendoza">Mendoza</option>
                    <option value="San Juan">San Juan</option>
                    <option value="Otro">Otro</option>
                </select>  
                <br><br><b><label for="ocupacion">Ocupación:</label></b>
                <input type="text" name="ocupacion" id="ocupacion" placeholder="En que trabajas" required>
                <br><br><b><label for="fechajob">Desde cuando:</label></b>
                <input type="date" name="fechajob" id="fechajob" required>
            </div>
            <div style="flex: 1; padding: 10px; border-left: 1px solid #ccc;">
                <b><label for="genero">Género:</label></b>
                <select name="genero" id="genero" required>
                    <option value="Masculino">Masculino</option>
                    <option value="Femenino">Femenino</option>
                    <option value="Prefiero no especificar">Prefiero no especificar</option>
                </select><br>
                <br><b><label for="estcivil">Estado civil:</label></b>
                <select name="estcivil" id="estcivil" required>
                    <option value="Casado">Casado</option>
                    <option value="Divorciado">Divorciado</option>
                    <option value="Soltero">Soltero</option>
                    <option value="Viudo">Viudo</option>
                </select><br>
                <br><b><label for="email">Correo de contacto:</label></b>
                <input type="text" name="email" id="email" placeholder="Indica tu email" required><br>
                <b><label for="web">Sitio web:</label></b>
                <input type="text" name="web" id="web" placeholder="Indica el link de tu web" required><br>
                <b><label for="telefono">Celular:</label></b>
                <input type="text" name="telefono" id="telefono" placeholder="Indica tu numero celular" required>
            </div>
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
        <script src="../js/modal_perfil.js"></script>
        
    
    </body>
    </html>
    