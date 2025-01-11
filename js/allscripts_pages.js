
    // Modals
    $(document).ready(function(){


        $("#messagesmodal").hover(function(){
            $(".modal-comments").toggle();
        });
        $(".modal-comments").hover(function(){
            $(".modal-comments").toggle();
        });

        $("#noticesmodal").hover(function(){
            $(".modal-notices").toggle();
        });
        $(".modal-notices").hover(function(){
            $(".modal-notices").toggle();
        });

        $("#friendsmodal").hover(function(){
            $(".modal-friends").toggle();
        });
        $(".modal-friends").hover(function(){
            $(".modal-friends").toggle();
        });


        $("#profilemodal").hover(function(){
            $(".modal-profile").toggle();
        });
        $(".modal-profile").hover(function(){
            $(".modal-profile").toggle();
        });


        $("#navicon").click(function(){
            $(".mobilemenu").fadeIn();
        });
        $(".all").click(function(){
            $(".mobilemenu").fadeOut();
        });
    });
        window.onscroll = function() {scrollFunction()};

        function scrollFunction() {
            if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
                document.getElementById("myBtn").style.display = "block";
            } else {
                document.getElementById("myBtn").style.display = "none";
            }
        }

        function topFunction() {
            document.body.scrollTop = 0;
            document.documentElement.scrollTop = 0;
        }
        
    
    
$(document).ready(function () {
    $('.add-friend').click(function () {
        const userIdToAdd = $(this).data('user-id');

        // Realizar una solicitud AJAX para agregar al usuario como amigo
        $.ajax({
            url: '../modelo/agregar_amigo.php',
            method: 'POST',
            data: { userIdToAdd: userIdToAdd },
            success: function(response){
               location.reload();
                },
                error: function(error){
                location.reload();
             }
        });
    });
});

$(document).ready(function () {
    $('.delete-friend').click(function () {
        const userIdToRemove = $(this).data('user-id');

        // Realizar una solicitud AJAX para agregar al usuario como amigo
        $.ajax({
            url: '../modelo/eliminar_amigo.php',
            type: 'POST',
            data: {userIdToRemove: userIdToRemove},
            success: function(response){
               location.reload();
                },
                error: function(error){
                location.reload();
             }

        });
    });
});




        document.addEventListener("DOMContentLoaded", function() {
            var statusMessage = document.getElementById("success-message");

            if (statusMessage) {
                setTimeout(function() {
                    statusMessage.style.display = "none";
                }, 3000); 
            }
            
        });
        
           
        document.addEventListener("DOMContentLoaded", function() {
            var errorMessage = document.getElementById("error-message");

            if (errorMessage) {
                setTimeout(function() {
                    errorMessage.style.display = "none";
                }, 3000); 
            }
        });

    
    $(document).ready(function() {
        
    
        // Función para cargar las solicitudes de amistad
        function loadFriendRequests() {
            $.ajax({
                url: '../modelo/actualizar_listado_solicitudes.php',
                type: 'GET',
                success: function(response) {
                    $('#friendRequests').html(response);
    
                    // Agrega un evento clic para el botón de "Aceptar"
                    $('.modal-content-accept').click(function() {
                        var requestId = $(this).data('request-id');
                        acceptFriendRequest(requestId);
                    });
    
                    // Agrega un evento clic para el botón de "Rechazar"
                    $('.modal-content-decline').click(function() {
                        var requestId = $(this).data('request-id');
                        declineFriendRequest(requestId);
                    });
                }
            });
        }
        

        
    
        // Función para aceptar una solicitud de amistad
        function acceptFriendRequest(requestId) {
            // Realizar una solicitud AJAX para aceptar la solicitud de amistad
            $.ajax({
                url: '../modelo/aceptar_solicitud.php',
                method: 'POST',
                data: { requestId: requestId },
                success: function(response) {
                    location.reload();
                },
                error: function(error) {
                    location.reload();
                }
            });
        }
    
        // Función para rechazar una solicitud de amistad
        function declineFriendRequest(requestId) {
            $.ajax({
                url: '../modelo/rechazar_solicitud.php',
                method: 'POST',
                data: { requestId: requestId },
                success: function(response) {
                    location.reload();
                },
                error: function(error) {
                    location.reload();
                }
            });
        }
    
        setInterval(loadFriendRequests, 10000); 
    });
    
    
    $(document).ready(function() {
        var totalSolicitudesAntes = 0; // Almacena el número total de solicitudes antes de la operación
    
        // Función para actualizar el contador de solicitudes de amistad
        function updateFriendRequestsCount() {
            $.ajax({
                url: '../modelo/actualizar_solicitudes.php', 
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $('.updated').text(response.total_solicitudes);
    
                        // Compara el número total de solicitudes antes y después de la operación
                        if (response.total_solicitudes > totalSolicitudesAntes) {
                            playNotificationSound();
                        }
    
                        // Actualiza el número total de solicitudes antes de la próxima operación
                        totalSolicitudesAntes = response.total_solicitudes;
                    } 
                }
            });
        }
    
        function playNotificationSound() {
            var audio = new Audio('../modelo/sounds/iphone-notificacion.mp3'); 
            audio.play();
        }
    
        setInterval(updateFriendRequestsCount, 10000); 
    });
    
    
   
    $(document).ready(function() {
        // Función para actualizar el contador de mensajes no leídos y reproducir un sonido
        function updateUnreadMessageCount() {
            $.ajax({
                url: '../modelo/actualizar_mensajes.php', // Ruta a tu archivo PHP que obtiene el contador
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.unread_message_count >= 0) {
                        // Actualiza el contenido del elemento #notification con el contador de mensajes no leídos
                        $('.noread').text(response.unread_message_count);
    
                        // Verifica si hay un nuevo mensaje antes de reproducir el sonido
                        if (response.newMessage) {
                            console.log(response),
                            playMessageReceivedSound();
                        }
                    }
                }
            });
        }
    
        // Función para reproducir el sonido cuando se recibe un mensaje
        function playMessageReceivedSound() {
            var audio = new Audio('../modelo/sounds/messenger-tono-mensaje-.mp3'); // Cambia la ruta al archivo de sonido
            audio.play();
        }
    
        setInterval(updateUnreadMessageCount, 1000); 
    });

        document.addEventListener('DOMContentLoaded', function() {
            const openPopupButton = document.getElementById('openPopup');
            const popup = document.getElementById('popup');
            const closePopupButton = document.getElementById('closePopup');

            openPopupButton.addEventListener('click', function() {
                popup.style.display = 'block';
            });

            closePopupButton.addEventListener('click', function() {
                popup.style.display = 'none';
            });

            // Agrega un evento para ocultar la ventana emergente cuando se envía el formulario (guardar).
            const form = document.querySelector('form');
            form.addEventListener('submit', function() {
                popup.style.display = 'none';
            });
        });
  
 var previousBirthdayCount = 0; // Variable para almacenar el conteo anterior de cumpleaños

function updateBirthdayCounter() {
    $.ajax({
        url: '../modelo/birthday_notifications.php', // Ruta al archivo PHP que verifica los cumpleaños
        method: 'GET',
        success: function (data) {
            var birthdayCount = parseInt(data);
            $('.birthday-counter').text(birthdayCount);

            // Reproducir el sonido solo si hay nuevos cumpleaños
            if (birthdayCount > previousBirthdayCount) {
                playMessageReceivedSound();
            }

            // Actualizar el valor anterior
            previousBirthdayCount = birthdayCount;
        }
    });
}

// Función para reproducir el sonido cuando se recibe un mensaje
function playMessageReceivedSound() {
    var audio = new Audio('../modelo/sounds/iphone-notificacion.mp3'); 
    audio.play();
}

setInterval(updateBirthdayCounter, 10000); 

    
        //funcion para actualizar listado de cumpleaños
        function loadbirthdayslist() {
            $.ajax({
                url: '../modelo/actualizar_listado_cumpleaños.php',
                method: 'GET',
                success: function(response) {
                    $('#birthdayslist').html(response);
    
                }
            });
        
        }
        setInterval(loadbirthdayslist, 10000);

      