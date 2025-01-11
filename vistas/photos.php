<?php
$pageName = "FOTOS";
include_once ('../vistas/content_principal.php');
?>


        <div class="right_row" style="width:80%;">
            <div class="row border-radius">
                <div class="feed">
                    <div class="feed_title">
                    <span><a href="../vistas/profile.php"><b>Perfil</b></a> | <a href="../vistas/about.php"><b>Sobre mi</b></a> | <a id="select_profile_menu" href="../vistas/photos.php" ><b>Fotos</b></a> | <a  href="../vistas/cursos.php " ><b>Cursos</b></a></span>
                    </div>
                </div>
            </div>

            <div class="row shadow">
                <div class="row_title">
                    <span>Fotos</span>
                </div>
                <div class="row_contain_profilephotospage">
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

            while ($row = mysqli_fetch_assoc($result)) {
                $media_url = $row['media_url'];
            ?>
                        <li><a href="#"><img src="<?php echo $media_url; ?>" alt="" /></a></li>
                        <?php
            }
            ?>
                    </ul>
                </div>
            </div>
            <center>
                <a href=""><div class="loadmorefeed">
                    <i class="fa fa-ellipsis-h"></i>
                </div></a>
            </center>

            
            

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
