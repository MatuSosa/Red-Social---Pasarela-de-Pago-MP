    // Agrega esto en tu JavaScript para mostrar y ocultar el modal
    function showImageInModal(imageSrc) {
        var modal = document.getElementById('imageModal');
        var fullImage = document.getElementById('fullImage');

        fullImage.src = imageSrc;
        modal.style.display = 'block';
    }
   


    // Establece un evento "onchange" para el input tipo "file"
    fileInput.addEventListener('change', function() {
        var selectedFile = fileInput.files[0];
        if (selectedFile) {
            // Por ejemplo, podrías mostrar la nueva imagen en lugar de la original.
            var fullImage = document.getElementById('fullImage');
        }
    });

    document.body.appendChild(fileInput);

    function closeImageModal() {
        var modal = document.getElementById('imageModal');
        modal.style.display = 'none';
    }
    function showCoverInModal(imageSrc) {
  var modal = document.getElementById('coverModal');
  var modalImg = document.getElementById('coverImgModal');
  modal.style.display = 'block';
  modalImg.src = imageSrc;
}

// Función para cerrar el modal
function closeCoverModal() {
  var modal = document.getElementById('coverModal');
  modal.style.display = 'none';
}