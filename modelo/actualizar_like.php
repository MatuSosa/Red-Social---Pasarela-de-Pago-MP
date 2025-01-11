<?php
session_start();
include_once '../modelo/config.php';

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['unique_id'])) {
    // Puedes redirigir o mostrar un mensaje de error si el usuario no ha iniciado sesión
    echo "error";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $estadoId = $_POST["estadoId"];
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

   // Consulta SQL para verificar si el usuario ya ha dado "Me gusta" al estado
   $sqlVerificarLike = "SELECT * FROM likes WHERE estado_id = ? AND user_id = ?";
   $stmtVerificarLike = $conn->prepare($sqlVerificarLike);
   $stmtVerificarLike->bind_param("ii", $estadoId, $user_id_sesion);
   $stmtVerificarLike->execute();
   $resultVerificarLike = $stmtVerificarLike->get_result();
   
   if ($resultVerificarLike->num_rows > 0) {
       // El usuario ya ha dado "Me gusta", eliminar el like
       $sqlEliminarLike = "DELETE FROM likes WHERE estado_id = ? AND user_id = ?";
       $stmtEliminarLike = $conn->prepare($sqlEliminarLike);
       $stmtEliminarLike->bind_param("ii", $estadoId, $user_id_sesion);
       if ($stmtEliminarLike->execute()) {
           echo "unliked";
       } else {
           echo "error";
       }
   } else {
       // El usuario no ha dado "Me gusta", registrar el like
       $sqlRegistrarLike = "INSERT INTO likes (estado_id, user_id) VALUES (?, ?)";
       $stmtRegistrarLike = $conn->prepare($sqlRegistrarLike);
       $stmtRegistrarLike->bind_param("ii", $estadoId, $user_id_sesion);
       if ($stmtRegistrarLike->execute()) {
        
           echo "liked";
       } else {
           echo "error";
       }
   }
} else {
   echo "error";
}
?>