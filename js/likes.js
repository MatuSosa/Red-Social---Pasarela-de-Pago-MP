document.addEventListener("DOMContentLoaded", function () {
    const likeButtons = document.querySelectorAll(".like-button");

    likeButtons.forEach((button) => {
        button.addEventListener("click", function () {
            const estadoId = this.getAttribute("data-estado-id");
            console.log(estadoId);
            // Realizar una solicitud Ajax al servidor para actualizar el campo "like"
            // y cambiar el estado del botón
            $.ajax({
                url: "../modelo/actualizar_like.php",
                method: "POST",
                data: { estadoId: estadoId },
                success: function (response) {
                    console.log(response);

                    // Verificar la respuesta del servidor y actualizar el botón en consecuencia
                    if (response === "liked") {
                        // Al usuario le gusta el estado, cambia el estilo del botón
                        button.classList.add("liked");
                    } else if (response === "unliked") {
                        // El usuario ha deshecho su "Me gusta", cambia el estilo del botón
                        button.classList.remove("liked");
                    }

                    // Actualizar la información de Me gusta en el contenedor correspondiente
                    loadLikesInfo(estadoId);
                },
            });
        });
    });
    
    // Función para cargar la información de Me gusta en el contenedor específico
    function loadLikesInfo(estadoId) {
        $.ajax({
            url: '../modelo/actualizar_like_info.php',
            type: 'GET',
            data: { estadoId: estadoId },
            success: function(response) {
                console.log(response);
                $('#likes-container-' + estadoId).html(response);
            }
        });
    }
});
