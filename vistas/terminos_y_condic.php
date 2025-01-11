<?php
$pageName = "TÉRM Y CONDIC";
include_once ('../vistas/content_principal.php');
?>



        

        <div class="right_row">

            <div class="row border-radius">
                <div class="settings shadow">
                    <div class="settings_title">
                        <h3>Términos y Condiciones de Uso de EduConnect</h3>
                    </div>
                    <div class="settings_content">
                        <ul>
                            <li>
                                <p><b>Fecha de vigencia: Desde el 21/09/23 al 21/09/26</b><br>
                                <br>Bienvenido a EduConnect, una plataforma de red social y educación en línea. Antes de utilizar nuestros servicios, te pedimos que leas detenidamente estos Términos y Condiciones de Uso, ya que establecen las reglas y regulaciones para el uso de nuestra plataforma. Al registrarte y utilizar EduConnect, aceptas cumplir con estos términos. Si no estás de acuerdo con estos términos, por favor, no utilices EduConnect.</p>
                            </li>
                            <li>
                                <p><b>1. Privacidad y Protección de Datos</b>
                                <br>1.1. EduConnect recopila y almacena cierta información personal de los usuarios. Para obtener más información sobre cómo recopilamos, usamos y protegemos tus datos personales, consulta nuestra Política de Privacidad.</p>
                            </li>
                            <li>
                                <p><b>2. Conducta del Usuario</b><br>
                                <br>2.1. Los usuarios de EduConnect deben comportarse de manera respetuosa y ética. No se permite el uso de lenguaje ofensivo, acoso, suplantación de identidad o cualquier otro comportamiento inapropiado.<br><br>
                                2.2. EduConnect se reserva el derecho de bloquear o eliminar cuentas de usuarios que violen estos términos y condiciones.</p>
                            </li>
                            <li>
                                <p><b>3. Creación de Cursos</b>
                               <br> <br>3.1. Los usuarios de EduConnect pueden crear cursos y venderlos en la plataforma.<br><br>
                                3.2. Para recibir pagos por la venta de cursos, los usuarios deben proporcionar la información necesaria, como el número de CBU (Clave Bancaria Uniforme) y los datos de la cuenta bancaria. EduConnect se encargará de transferir el dinero a la cuenta del vendedor dentro de los 3 días hábiles posteriores a la transacción.<br><br>
                                3.3. Los compradores de cursos recibirán acceso inmediato al contenido del curso después de realizar el pago. Una vez que el comprador confirme la recepción y esté satisfecho con el curso, EduConnect realizará la transferencia de dinero al vendedor.</p>
                            </li>
                            <li>
                                <p><b>4. Cambios en los Términos y Condiciones</b><br>
                                <br>4.1. EduConnect se reserva el derecho de modificar estos Términos y Condiciones en cualquier momento. Se notificará a los usuarios sobre cualquier cambio importante en los términos. Si continúas utilizando la plataforma después de la notificación de cambios, se considerará que has aceptado los nuevos términos.</p>
                            </li>
                            <li>
                                <p><b>5. Contacto</b><br>
                                <br>5.1. Para ponerse en contacto con EduConnect o informar sobre cualquier violación de estos términos, por favor, envía un correo electrónico a matisosa55@hotmail.com.</p>
        </li><br>
        <li> <a class="custom-button" href="../vistas/create_page.php">Volver</a></li>
                        </ul>
                       
                    </div>
                  
                </div>
                
            </div>
        </div>

        <div class="suggestions_row">
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
        <script src="../js/modal_perfil.js"></script>
    </body>
    </html>
    