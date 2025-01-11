<?php 
 session_start();
 if (isset($_SESSION['unique_id'])) {
     include_once "config.php";
     $outgoing_id = $_SESSION['unique_id'];
     $incoming_id = mysqli_real_escape_string($conn, $_POST['incoming_id']);
     $output = "";
 
   // Consulta SQL para obtener y marcar los mensajes como leídos
$sql = "SELECT messages.*, users.img FROM messages 
LEFT JOIN users ON users.unique_id = messages.outgoing_msg_id
WHERE (outgoing_msg_id = {$outgoing_id} AND incoming_msg_id = {$incoming_id})
OR (outgoing_msg_id = {$incoming_id} AND incoming_msg_id = {$outgoing_id}) 
ORDER BY read_status ASC, msg_id";

 
     $query = mysqli_query($conn, $sql);
 
     while ($row = mysqli_fetch_assoc($query)) {
         if ($row['outgoing_msg_id'] === $outgoing_id) {
             $output .= '<div class="chat outgoing">
                         <div class="details">
                             <p>' . $row['msg'] . '</p>
                         </div>
                     </div>';
         } else {
             $output .= '<div class="chat incoming">
                         <img src="../modelo/images/' . $row['img'] . '" alt="">
                         <div class="details">
                             <p>' . $row['msg'] . '</p>
                         </div>
                     </div>';
             // Marcar el mensaje como leído si el estado es 0
             if ($row['read_status'] == 0) {
                 $message_id = $row['msg_id'];
                 mysqli_query($conn, "UPDATE messages SET read_status = 1 WHERE msg_id = $message_id");
             }
         }
     }
 
     if (mysqli_num_rows($query) === 0) {
         $output .= '<div class="text">No hay mensajes disponibles. Una vez que envíe el mensaje, aparecerán aquí.</div>';
     }
 
     echo $output;
 } else {
     header("location: ../vistas/login.php");
 }
 
?>