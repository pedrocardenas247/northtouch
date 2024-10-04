<script>
    // Spinner
document.addEventListener('DOMContentLoaded', function() {
    var form = document.getElementById('formphotos');
    var submitButton = document.getElementById('submit-button');
    var spinner = document.getElementById('spinner');

    if (form && submitButton && spinner) {
        form.addEventListener('submit', function(event) {
            event.preventDefault(); // Prevenir el envío predeterminado del formulario

            // Mostrar el spinner y deshabilitar el botón de envío
            spinner.style.display = 'inline-block';
            submitButton.classList.add('button-disabled');

            // Deshabilitar los campos del formulario
            var formElements = form.elements;
            for (var i = 0; i < formElements.length; i++) {
                formElements[i].disabled = true;
            }

            // Use AJAX to submit the form and handle the response
            var xhr = new XMLHttpRequest();
            xhr.open("POST", form.action, true);
            xhr.onload = function() {
                // Ocultar el spinner y habilitar el botón de envío
                spinner.style.display = 'none';
                submitButton.classList.remove('button-disabled');

                // Habilitar los campos del formulario
                for (var i = 0; i < formElements.length; i++) {
                    formElements[i].disabled = false;
                }

                if (xhr.status !== 200) {
                    alert("There was a problem with the image upload. Please try again.");
                }
            };
            xhr.onerror = function() {
                // Ocultar el spinner y habilitar el botón de envío
                spinner.style.display = 'none';
                submitButton.classList.remove('button-disabled');

                // Habilitar los campos del formulario
                for (var i = 0; i < formElements.length; i++) {
                    formElements[i].disabled = false;
                }

                alert("There was a problem with the image upload. Please try again.");
            };

            // Enviar el formulario a través de AJAX
            var formData = new FormData(form);
            xhr.send(formData);
        });
    }
});
</script>
