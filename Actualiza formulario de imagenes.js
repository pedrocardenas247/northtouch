<script>
//Actualiza las imagenes
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('#formphotos');

    if (!form) {
        console.error('Form not found.');
        return;
    }

    // Función para manejar la previsualización de imágenes
    function handleImagePreview(inputSelector, previewSelector) {
        const input = document.querySelector(inputSelector);
        const preview = document.querySelector(previewSelector);

        input.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        });
    }

    handleImagePreview('#companylogo', '#preview-companylogo');
    handleImagePreview('#profilephoto', '#preview-profilephoto');

    // Envío del formulario
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(form);
        formData.append('action', 'save_acf_images_update_photos');  // Acción de Ajax

        fetch('/wp-admin/admin-ajax.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Images saved successfully.');
            } else {
                alert('Error saving images: ' + (data.data || 'Unknown error.'));
            }
        })
        .catch(error => {
            console.error('Request error:', error);
            alert('There was an error processing the form.');
        });
    });
});
</script>


