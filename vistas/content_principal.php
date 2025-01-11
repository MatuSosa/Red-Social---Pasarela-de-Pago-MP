<?php 
  session_start();
  include_once "../modelo/config.php";
  if(!isset($_SESSION['unique_id'])){
    header("location: login.php");
    
  }
  
  $status_message = "";
if (isset($_SESSION["status_message"])) {
    $status_message = $_SESSION["status_message"];
    unset($_SESSION["status_message"]);
}
$error_message = "";
if (isset($_SESSION["error_message"])) {
    $error_message = $_SESSION["error_message"];
    unset($_SESSION["error_message"]);
}
?>
   
<!DOCTYPE html>
<html lang="es-AR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
 <?php
 $sql = mysqli_query($conn, "SELECT * FROM users WHERE unique_id = {$_SESSION['unique_id']}");
            if(mysqli_num_rows($sql) > 0){
              $row = mysqli_fetch_assoc($sql);
            }
            ?>
    <title><?php echo $row['fname']. " " . $row['lname'] ?> - Edu-Connect</title>
    <link rel="shortcut icon" href="../images/logo.png" type="image/x-icon">


    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="../css/style.css">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <!-- Icons FontAwesome 4.7.0 -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"  type="text/css" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCpVyIxJ0ktG9pTbehp3In22pLQwym9sew&libraries=places"></script>



</head>
<body>
    <div class="navbar">
        <div class="navbar_menuicon" id="navicon">
            <i class="fa fa-navicon"></i>
        </div>
        <div class="navbar_logo">
            <img src="../images/round.png" alt="" />
        </div>
        <div class="navbar_page">
    <span><?php echo $pageName; ?></span>
</div>
<div class="navbar_search">
<input type="text" id="searchInput" placeholder="Buscar usuarios..." autocomplete="off" />
    <div id="searchResultsContainer">
    <div id="searchResults" class="results-list"></div>
</div>
</div>
        
<div class="navbar_icons">
<ul>
    <li id="friendsmodal"><i class="fa fa-user-o"></i><span class="updated" id="notification">0</span></li>
    <li id="messagesmodal"><i class="fa fa-comments-o"></i><span class="noread" id="notification">0</span></li>
    <li id="noticesmodal"><i class="fa fa-globe"></i><span class="birthday-counter" id="notification">0</span></li>
</ul>
</div>

        <?php 
            $sql = mysqli_query($conn, "SELECT * FROM users WHERE unique_id = {$_SESSION['unique_id']}");
            if(mysqli_num_rows($sql) > 0){
              $row = mysqli_fetch_assoc($sql);
            }
          ?>
        <div class="navbar_user" id="profilemodal" style="cursor:pointer">
            <img src="../modelo/images/<?php echo $row['img']; ?>" alt="" />
            <span id="navbar_user_top"><?php echo $row['fname']. " " . $row['lname'] ?><br></span><i class="fa fa-angle-down"></i>
        </div>
    </div>

    <div class="all">

        <div class="rowfixed"></div>
        <div class="left_row">
        <div class="left_row_profile">
    <img id="portada" src="../modelo/images/cover/<?php echo $row['cover_img']; ?>"
    onclick="showCoverInModal('../modelo/images/cover/<?php echo $row['cover_img']; ?>')" />
    <div class="left_row_profile">
        <img id="profile_pic" src="../modelo/images/<?php echo $row['img']; ?>" 
             onclick="showImageInModal('../modelo/images/<?php echo $row['img']; ?>')" />
        <span><?php echo $row['fname']. " " . $row['lname'] ?><br><p></p></span>
    </div>
</div>
<div id="imageModal" style="display: none; background-color: white; border: 2px solid #ff5e3a; border-radius: 10px; padding: 20px; width: 600px;">
    <span class="closeModal" onclick="closeImageModal()">&times;</span>
    <img class="modal-content" id="fullImage">
    <h5><b>Puedes cambiar la foto en configuraciones</b></h5>
</div>
<!-- Modal de imagen de portada -->
<div id="coverModal" style="display: none; background-color: white; border: 2px solid #ff5e3a; border-radius: 10px; padding: 20px; width: 600px;">
  <span class="closecoverModal" onclick="closeCoverModal()">&times;</span>
  <img class="modal-content" id="coverImgModal">
  <h5><b>Puedes cambiar la foto en configuraciones</b></h5>
</div>


            
            <div class="rowmenu">
                <ul>
                <li><a href="feed.php" id="rowmenu-selected"><i class="fa fa-globe"></i>Noticias</a></li>
                    <li><a href="profile.php" id="rowmenu-selected"><i class="fa fa-user"></i>Perfil</a></li>
                    <li><a href="friends.php" id="rowmenu-selected"><i class="fa fa-users"></i>Amigos</a></li>
                    <li><a href="mensajes.php" id="rowmenu-selected"><i class="fa fa-comments-o"></i>Mensajes</a></li>
                    <li><a href="cursos.php" id="rowmenu-selected"><i class="fa fa-bookmark-o"></i>Cursos</a></li>
                    <li><a href="create_page.php" id="rowmenu-selected"><i class="fa fa-plus"></i>Crear curso</a></li>
                    <li><a href="mis_compras.php" id="rowmenu-selected"><i class="fa fa-bank"></i>Mis Compras</a></li>


                </ul>
            </div>
        </div>
        <script>
   // JavaScript y AJAX para realizar la búsqueda en tiempo real
const searchInput = document.getElementById('searchInput');
const searchResultsContainer = document.getElementById('searchResultsContainer');
const searchResults = document.getElementById('searchResults');

searchInput.addEventListener('input', function() {
    const query = searchInput.value;

    // Realiza la búsqueda cuando se ingresa texto en el campo de búsqueda
    if (query.length >= 3) {
        // Realiza una solicitud AJAX al archivo PHP que maneja la búsqueda
        const xhr = new XMLHttpRequest();
        xhr.open('GET', `../modelo/buscar_usuarios.php?query=${query}`, true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                // Actualiza el contenido de searchResults con los resultados de la búsqueda
                searchResults.innerHTML = xhr.responseText;
            }
        };
        const inputRect = searchInput.getBoundingClientRect();
        searchResultsContainer.style.top = inputRect.bottom + 'px';
        searchResultsContainer.style.left = inputRect.left + 'px';
        
        // Muestra el contenedor de resultados
        searchResultsContainer.style.display = 'block';
        xhr.send();
    } else {
        // Limpia los resultados si el campo de búsqueda está vacío
        searchResults.innerHTML = '';
        searchResultsContainer.style.display = 'none';
    }
});

// Agrega un controlador de eventos al documento para cerrar el contenedor de resultados al hacer clic fuera del área del buscador
document.addEventListener('click', function(event) {
    // Verifica si el clic no ocurrió dentro del contenedor de resultados ni dentro del campo de entrada de búsqueda
    if (!searchResultsContainer.contains(event.target) && event.target !== searchInput) {
        // Cierra el contenedor de resultados
        searchResultsContainer.style.display = 'none';
    }
});

</script>
