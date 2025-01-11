$(document).ready(function () {
    // Agrega un evento clic a todos los botones para mostrar/ocultar comentarios
    $(".toggle-comments-button").click(function () {
        // Encuentra el contenedor de comentarios correspondiente
        var commentsContainer = $(this).closest(".feed").find(".comments-container");

        // Verifica si los comentarios ya están visibles
        if (commentsContainer.is(":visible")) {
            // Oculta el contenedor de comentarios
            commentsContainer.hide();
        } else {
            // Muestra el contenedor de comentarios
            commentsContainer.show();

            // Clona el contenedor de comentarios y agrégalo al contenedor general
            var clonedContainer = commentsContainer.clone();
            $(".comments-container-wrapper").empty().append(clonedContainer);

            // Muestra el contenedor clonado de comentarios
            clonedContainer.show();
        }
    });

    // Agrega un evento clic a todos los botones de Comentar
    $(".comment-button").click(function () {
        // Encuentra el campo de comentario correspondiente
        var commentInput = $(this).closest(".feed").find(".comment-input");

        // Verifica si el campo de comentario ya está visible
        if (commentInput.is(":visible")) {
            // Oculta el campo de comentario
            commentInput.hide();
        } else {
            // Muestra el campo de comentario
            commentInput.show();
        }
    });
});