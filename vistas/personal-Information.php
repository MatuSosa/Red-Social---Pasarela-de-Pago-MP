<?php
$pageName = "PERSONAL";
include_once ('../vistas/content_principal.php');
?>



        

        <div class="right_row">
        <?php if (!empty($status_message)) : ?>
        <div class="status-message" id="success-message"><?php echo $status_message; ?></div>
    <?php endif; ?>
    <?php if (!empty($error_message)) : ?>
    <div class="error-message" id="error-message"><?php echo $error_message; ?></div>
<?php endif; ?>
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

   $sql = "SELECT u.fname, u.lname, u.img, u.cover_img, u.email, ui.* FROM users u
        LEFT JOIN user_info ui ON u.user_id = ui.user_id
        WHERE u.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i",  $user_id_sesion);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Usuario encontrado en la tabla user_info, asigna los valores a las variables
    $row = $result->fetch_assoc();
    $fname = $row['fname'];
    $lname = $row['lname'];
    $img = $row['img'];
    $portada = $row['cover_img'];
    $email = $row['email'];
    $aboutMe = $row['about_me'];
    $birthday = $row['birthday'];
    $birthplace = $row['birthplace'];
    $livesIn = $row['lives_in'];
    $occupation = $row['occupation'];
    $joinDate = $row['join_date'];
    $gender = $row['gender'];
    $status = $row['status'];
    $website = $row['website'];
    $phoneNumber = $row['phone_number'];
    $hasUserInfo = true;
} else {
    // Usuario no encontrado en la tabla user_info, inicializa las variables en blanco
    $fname = 'sin datos de usuario';
    $lname = 'sin datos de usuario';
    $img = 'sin datos de usuario';
    $portada = 'sin datos de usuario';
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

            <div class="row border-radius">
                <center><div class="settings shadow">
                    <div class="settings_title">
                        <h3>Información Personal</h3>
                    </div>
                    <div class="settings_content">
                    <form action="../modelo/update_user_info.php" method="POST" enctype="multipart/form-data">
                            <div class="pi-input pi-input-lg">
                                <span>Tu Nombre</span>
                                <input type="text" name="fname" id="fname" value="<?php echo isset($fname) ? $fname : ''; ?>" />
                            </div>
                            <div class="pi-input pi-input-lg">
                                <span>Tu apellido:</span>
                                <input type="text" name="lname" id="lname" value="<?php echo isset($lname) ? $lname : ''; ?>" />
                            </div>
                            <div class="pi-input pi-input-lg">
                            <span>Tu foto de perfil:</span>
                             <input type="file" name="profileImage" id="profileImage" accept="image/*" />
                            </div>
                            <div class="pi-input pi-input-lg">
                            <span>Tu foto de portada:</span>
                             <input type="file" name="coverImage" id="coverImage" accept="image/*" />
                            </div>
                            <div class="pi-input pi-input-lg">
                                <span>Tu email:</span><br>
                                <span><?php echo isset($email) ? $email : ''; ?></span>
                            </div>
                            <div class="pi-input pi-input-lg">
                                <span>tu sitio web</span>
                                <input type="text" name="web" id="web" value="<?php echo isset($website) ? $website : ''; ?>" />
                            </div>
                            <div class="pi-input pi-input-lg">
                                <span>Tu fecha de nacimiento</span>
                                <input type="date" name="nacimiento" id="nacimiento" value="<?php echo isset($birthday) ? $birthday : ''; ?>" />
                            </div>
                            <div class="pi-input pi-input-lg">
                                <span>Tu número de teléfono</span>
                                <input type="text" name="telefono" id="telefono" value="<?php echo isset($phoneNumber) ? $phoneNumber : ''; ?>"/>
                            </div>
                            <div class="pi-input pi-input-lg">
                                <span>Tu país</span>
                                <select name="lugarnac" id="lugarnac" selected><?php echo isset($birthplace) ? $birthplace : ''; ?>>
                                <option value="Argentina" <?php echo ($birthplace === 'Argentina') ? 'selected' : ''; ?>>Argentina</option>
                                <option value="Brasil" <?php echo ($birthplace === 'Brasil') ? 'selected' : ''; ?>>Brasil</option>
                                <option value="Chile" <?php echo ($birthplace === 'Chile') ? 'selected' : ''; ?>>Chile</option>
                                <option value="Otro" <?php echo ($birthplace === 'Otro') ? 'selected' : ''; ?>>Otro</option>
                                 </select>
                            </div>
                            <div class="pi-input pi-input-lg">
                                <span>Tu provincia</span>
                                <select  name="lugarlive" id="lugarlive" selected><?php echo isset($livesIn) ? $livesIn : ''; ?>>
                                <option value="Mendoza" <?php echo ($livesIn === 'Mendoza') ? 'selected' : ''; ?>>Mendoza</option>
                                <option value="Córdoba" <?php echo ($livesIn === 'Córdoba') ? 'selected' : ''; ?>>Córdoba</option>
                                <option value="San Juan" <?php echo ($livesIn === 'San Juan') ? 'selected' : ''; ?>>San Juan</option>
                                <option value="Otro" <?php echo ($livesIn === 'Otro') ? 'selected' : ''; ?>>Otro</option>
                                 </select>
                            </div>
                            <div class="pi-input pi-input-lg">
                                <span>Tu Género</span>
                                <select  name="genero" id="genero"<?php echo isset($gender) ? $gender : ''; ?>>
                                <option value="Masculino" <?php echo ($livesIn === 'Masculino') ? 'selected' : ''; ?>>Masculino</option>
                                <option value="Femenino" <?php echo ($livesIn === 'Femenino') ? 'selected' : ''; ?>>Femenino</option>
                                <option value="Prefiero no especificar" <?php echo ($livesIn === 'Prefiero no especificar') ? 'selected' : ''; ?>>Prefiero no especificar</option>
                                                            </select>
                            </div>
                            <div class="pi-input pi-input-lg">
                                <span>Estado Civil</span>
                                <select name="estcivil" id="estcivil" <?php echo isset($status) ? $status : ''; ?>>
                                <option value="Casado" <?php echo ($status === 'Casado') ? 'selected' : ''; ?>>Casado</option>
                                <option value="Soltero" <?php echo ($status === 'Soltero') ? 'selected' : ''; ?>>Soltero</option>
                                <option value="Divorciado" <?php echo ($status === 'Divorciado') ? 'selected' : ''; ?>>Divorciado</option>
                                <option value="Viudo" <?php echo ($status === 'Viudo') ? 'selected' : ''; ?>>Viudo</option>
                                </select>
                            </div>
                            <div class="pi-input pi-input-lg">
                                <span>Tu ocupación</span>
                                <input type="text" name="ocupacion" id="ocupacion" value="<?php echo isset($occupation) ? $occupation : ''; ?>" />
                            </div>
                            <div class="pi-input pi-input-lg">
                                <span>Desde cuando trabajas allí?</span>
                                <input type="date" name="fechajob" id="fechajob" value="<?php echo isset($joinDate) ? $joinDate : ''; ?>" />
                            </div>
                            <div class="pi-input pi-input-lgg">
                                <span>Una breve descripción tuya</span>
                                <input type="text" name="aboutMe" id="aboutMe" value="<?php echo isset($aboutMe) ? $aboutMe : ''; ?>" />
                            </div>

                            <button>Guardar cambios</button>
                        </form>
                    </div>
                </div></center>
 
            </div>
        </div>

        <div class="suggestions_row">
            <div class="row shadow">
                <div class="row_title">
                    <span>Configurar perfil</span>
                </div>
                <div class="menusetting_contain">
                    <ul>
                        <li>
                            <a href="Personal-Information.php" id="settings-select">Información personal</a>
                        </li>
                        <li>
                            <a href="settings.php">Configurar cuenta</a>
                        </li>
                        <li>
                            <a href="Change-Password.php">Cambiar contraseña</a>
                        </li>

                    </ul>
                </div>
            </div>

            <?php
        include_once ('../vistas/content_final_other.php');
?>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="http://code.jquery.com/jquery-latest.js"></script>
    <script src="../js/modal_perfil.js"></script>
    <script src="../js/allscripts_pages.js"></script>
    
</body>
</html>
