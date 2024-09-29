// Actualiza formulario de imagenes
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('#formphotos');

    if (!form) {
        console.error('Form not found.');
        return;
    }

    // Previsualización del logo de la empresa
    const companyLogoInput = document.querySelector('#companylogo');
    const companyLogoPreview = document.querySelector('#preview-companylogo');

    companyLogoInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                companyLogoPreview.src = e.target.result;
                companyLogoPreview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        }
    });

    // Previsualización de la foto de perfil
    const profilePhotoInput = document.querySelector('#profilephoto');
    const profilePhotoPreview = document.querySelector('#preview-profilephoto');

    profilePhotoInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                profilePhotoPreview.src = e.target.result;
                profilePhotoPreview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        }
    });

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
                alert('Error saving images: ' + data.data);
            }
        })
        .catch(error => {
            console.error('Request error:', error);
            alert('There was an error processing the form.');
        });
    });
});
</script>


