document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('#formphotos');
    const submitButton = document.getElementById('submit-button');
    const spinner = document.getElementById('spinner');
    const companyLogoInput = document.getElementById('companylogo');
    const profilePhotoInput = document.getElementById('profilephoto');

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

    // Función para validar archivos
    function validateFile(file) {
        const allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        const maxSize = 1048576; // 1 MB en bytes

        if (!file) return false; // No hay archivo seleccionado
        if (!allowedTypes.includes(file.type)) {
            return false;
        }
        if (file.size > maxSize) {
            return false;
        }
        return true;
    }

    // Función para chequear la validación
    function checkValidation() {
        const companyLogo = companyLogoInput.files[0];
        const profilePhoto = profilePhotoInput.files[0];

        const isCompanyLogoValid = validateFile(companyLogo);
        const isProfilePhotoValid = validateFile(profilePhoto);

        if (
            (isCompanyLogoValid && !profilePhoto) || 
            (isProfilePhotoValid && !companyLogo) || 
            (isCompanyLogoValid && isProfilePhotoValid)
        ) {
            submitButton.disabled = false;
            submitButton.classList.remove('button-disabled');
        } else {
            submitButton.disabled = true;
            submitButton.classList.add('button-disabled');
        }
    }

    handleImagePreview('#companylogo', '#preview-companylogo');
    handleImagePreview('#profilephoto', '#preview-profilephoto');

    companyLogoInput.addEventListener('change', checkValidation);
    profilePhotoInput.addEventListener('change', checkValidation);

    // Envío del formulario
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        const companyLogo = companyLogoInput.files[0];
        const profilePhoto = profilePhotoInput.files[0];

        if (!validateFile(companyLogo) || !validateFile(profilePhoto)) {
            // Si la validación falla, no permitir la carga
            return;
        }

        // Mostrar el spinner y deshabilitar el botón de envío
        spinner.style.display = 'inline-block';
        submitButton.classList.add('button-disabled');

        // Deshabilitar los campos del formulario
        const formElements = form.elements;
        for (let i = 0; i < formElements.length; i++) {
            formElements[i].disabled = true;
        }

        // Usar AJAX para enviar el formulario y manejar la respuesta
        const xhr = new XMLHttpRequest();
        xhr.open("POST", form.action, true);
        xhr.onload = function() {
            // Ocultar el spinner y habilitar el botón de envío
            spinner.style.display = 'none';
            submitButton.classList.remove('button-disabled');

            // Habilitar los campos del formulario
            for (let i = 0; i < formElements.length; i++) {
                formElements[i].disabled = false;
            }

            if (xhr.status === 200) {
                // Recargar la página automáticamente después de una carga exitosa
                window.location.reload();
            } else {
                alert("There was a problem with the image upload. Please try again.");
            }
        };
        xhr.onerror = function() {
            // Ocultar el spinner y habilitar el botón de envío
            spinner.style.display = 'none';
            submitButton.classList.remove('button-disabled');

            // Habilitar los campos del formulario
            for (let i = 0; i < formElements.length; i++) {
                formElements[i].disabled = false;
            }

            alert("There was a problem with the image upload. Please try again.");
        };

        // Enviar el formulario a través de AJAX
        const formData = new FormData(form);
        xhr.send(formData);
    });
});
