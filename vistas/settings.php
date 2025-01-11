<?php
$pageName = "CONFIG";
include_once ('../vistas/content_principal.php');
?>



        

        <div class="right_row">

            <div class="row border-radius">
                <div class="settings shadow">
                    <div class="settings_title">
                        <h3>Configuración de la cuenta</h3>
                    </div>
                    <div class="settings_content">
                        <ul>
                            <li>
                                <p><b>Sonidos de notificación</b><br>No recibirás ningún sonido de notificación</p>
                                <label class="switch"><input type="checkbox" class="primary" id="sonidos_notificacion" data-config="sonidos_notificacion"><span class="slider round"></span></label>
                            </li>
                            <li>
                                <p><b>Cumpleaños de amigos</b><br>Notificarme del cumpleaños de un amigo</p>
                                <label class="switch"><input type="checkbox" class="primary" id="cumpleanios_amigos" data-config="cumpleanios_amigos"><span class="slider round"></span></label>
                            </li>
                            <li>
                                <p><b>Sonido de mensajes</b><br>Si desactivas, no recibirás sonidos de mensajes</p>
                                <label class="switch"><input type="checkbox" class="primary" id="sonido_mensajes" data-config="sonido_mensajes"><span class="slider round"></span></label>
                            </li>
                            <li>
                                <p><b>Sonido de solicitudes de amistad</b><br>Si desactivas, no recibirás sonidos de solicitudes de amistad</p>
                                <label class="switch"><input type="checkbox" class="primary" id="sonido_solicitudes_amistad" data-config="sonido_solicitudes_amistad"><span class="slider round"></span></label>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
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
                            <a href="settings.php" id="settings-select">Configuración de la cuenta</a>
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
        <script src="../js/allscripts_pages.js"></script>
        <script src="../js/modal_perfil.js"></script>
    <script>
  $(document).ready(function() {
    $("input[type='checkbox']").change(function() {
        var isChecked = $(this).prop("checked");
        var configName = $(this).data("config");

        $.ajax({
            url: "../modelo/actualizar_configuracion.php",
            type: "POST",
            data: {
                config_name: configName,
                valor: isChecked ? 1 : 0
            },
            success: function(response) {
                console.log(response);
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    });
});
    </script>
    <?php

include '../modelo/config.php';
// Verifica si el usuario ha iniciado sesión
if (!isset($_SESSION['unique_id'])) {
}

// Obtener el user_id basado en el unique_id de la sesión
$unique_id_sesion = $_SESSION['unique_id'];

// Consulta SQL para obtener el user_id basado en el unique_id
$sql = "SELECT user_id FROM users WHERE unique_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $unique_id_sesion);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $user_id = $row['user_id'];
}

// Consulta para obtener las configuraciones de notificaciones del usuario
$sql = "SELECT sonidos_notificacion, cumpleanios_amigos, sonido_mensajes, sonido_solicitudes_amistad FROM configuracion_notificaciones WHERE usuario_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($sonidos_notificacion, $cumpleanios_amigos, $sonido_mensajes, $sonido_solicitudes_amistad);
$stmt->fetch();
$stmt->close();
?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var sonidosNotificacionCheckbox = document.getElementById('sonidos_notificacion');
        var cumpleaniosAmigosCheckbox = document.getElementById('cumpleanios_amigos');
        var sonidosMensajesCheckbox = document.getElementById('sonido_mensajes');
        var sonidoSolicitudesAmistadCheckbox = document.getElementById('sonido_solicitudes_amistad');

        // Establece el estado de los checkboxes según los valores obtenidos de la base de datos
        sonidosNotificacionCheckbox.checked = <?php echo $sonidos_notificacion ? 'true' : 'false'; ?>;
        cumpleaniosAmigosCheckbox.checked = <?php echo $cumpleanios_amigos ? 'true' : 'false'; ?>;
        sonidosMensajesCheckbox.checked = <?php echo $sonido_mensajes ? 'true' : 'false'; ?>;
        sonidoSolicitudesAmistadCheckbox.checked = <?php echo $sonido_solicitudes_amistad ? 'true' : 'false'; ?>;
    });
</script>







    </body>
    </html>
    