<button onclick="topFunction()" id="myBtn" title="Go to top"><i class="fa fa-arrow-up"></i></button>

    <div class="modal modal-notices">
        <div class="modal-icon-select"><i class="fa fa-sort-asc" aria-hidden="true"></i></div>
        <div class="modal-title">
        <span>Notificaciones</span>
        <a href="perfil.php"><i class="fa fa-ellipsis-h"></i></a>
    </div>
    <div class="modal-content">
                <ul>
                <div id="birthdayslist">
                </div>
                </ul>
            </div>
          
        </div>


    
    
        <!-- Modal Messages -->
        <div class="modal modal-comments">
        
            <div class="modal-icon-select"><i class="fa fa-sort-asc" aria-hidden="true"></i></div>
            <div class="modal-title">
                <span>Mensajes</span>
                <a href="mensajes.php"><i class="fa fa-ellipsis-h"></i></a>
            </div>
            <div class="modal-content">
                <ul>
                   <iframe src="mensajes.php" width="340" height="490"></iframe>
                </ul>
            </div>
        </div>

    

   <!-- Modal Friends -->
   <div class="modal modal-friends">
            <div class="modal-icon-select"><i class="fa fa-sort-asc" aria-hidden="true"></i></div>
            <div class="modal-title">
                <span>Solicitudes de amistad</span>
                 <a href="friends.php"><i class="fa fa-ellipsis-h"></i></a>
            </div>
           
            <div class="modal-content">
                <ul>
                <div id="friendRequests">
       <!-- Aquí se mostrarán las solicitudes de amistad pendientes -->
                </div>
                </ul>
            </div>
          
        </div>   
        <!-- Modal Profile -->
     <div class="modal modal-profile">
        <div class="modal-icon-select"><i class="fa fa-sort-asc" aria-hidden="true"></i></div>
        <div class="modal-title">
            <span>Mi cuenta</span>
             <a href="settings.php"><i class="fa fa-cogs"></i></a>
        </div>
        <div class="modal-content">
            <ul>
                <li>
                    <a href="settings.php">
                        <i class="fa fa-tasks" aria-hidden="true"></i>
                        <span><b>Configuración del perfil</b><br>editar configuración</span>
                    </a>
                </li>
                <li>
                <a href="create_page.php">
                        <i class="fa fa-star-o" aria-hidden="true"></i>
                        <span><b>Crear un curso</b><br>Crear tu curso</span>
                    </a>
                </li>
                <li>
                    <a href="../modelo/logout.php?logout_id=<?php echo $_SESSION['unique_id']; ?>">
                        <i class="fa fa-power-off" aria-hidden="true"></i>
                        <span><b>Salir</b><br>cerrar tu sesión</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
    
  <!-- NavMobile -->
  <div class="mobilemenu">
         <?php 
            $sql = mysqli_query($conn, "SELECT * FROM users WHERE unique_id = {$_SESSION['unique_id']}");
            if(mysqli_num_rows($sql) > 0){
              $row = mysqli_fetch_assoc($sql);
            }
          ?>
        <div class="mobilemenu_profile">
            <img id="mobilemenu_portada" src="../images/portada.jpg" />
            <div class="mobilemenu_profile">
                <img id="mobilemenu_profile_pic" src="../modelo/images/<?php echo $row['img']; ?>" /><br>
                <span><?php echo $row['fname']. " " . $row['lname'] ?><br><p>150k followers / 50 follow</p></span>
            </div>
            <div class="mobilemenu_menu">
                <ul>
                <li><a href="feed.php" id="rowmenu-selected"><i class="fa fa-globe"></i>Noticias</a></li>
                    <li><a href="profile.php" id="rowmenu-selected"><i class="fa fa-user"></i>Perfil</a></li>
                    <li><a href="mensajesphp" id="rowmenu-selected"><i class="fa fa-comments-o"></i>Mensajes</a></li>
                    <li><a href="cursos.php" id="rowmenu-selected"><i class="fa fa-bookmark-o"></i>Cursos</a></li>
                    <li><a href="create_page.php" id="rowmenu-selected"><i class="fa fa-plus"></i>Crear curso</a></li>
                    <li><a href="mis_compras.php" id="rowmenu-selected"><i class="fa fa-bank"></i>Mis Compras</a></li>

                </ul>
                    <hr>
                    <ul>
                <li><a href="settings.php"> Configuración del perfil</a></li>
                <li><a href="terminos_y_condic.php">Terminos y Condiciones</a></li>
                <li><a href="privacidad_y_seguridad.php">Privacidad y Seguridad</a></li>
                <li><a href="contactanos.php">Contactanos</a></li>
                <li> <a href="../modelo/logout.php?logout_id=<?php echo $_SESSION['unique_id']; ?>">Cerrar Sesion</a></li>
                </ul>
            </div>
        </div>
    </div>