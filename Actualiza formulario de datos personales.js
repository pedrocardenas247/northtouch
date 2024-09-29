// Actualiza formulario de datos personales
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Referencias a los campos del formulario y la tarjeta de presentación
        var nameField = document.getElementById('form-field-name');
        var cardName = document.getElementById('cardname');
        var jobField = document.getElementById('form-field-job');
        var cardJob = document.getElementById('cardjob');
        var phoneField = document.getElementById('form-field-phone');
        var cardPhone = document.getElementById('cardphone');

        // Nuevos campos
        var emailField = document.getElementById('form-field-email');
        var cardEmail = document.getElementById('cardemail');
        var addressField = document.getElementById('form-field-address');
        var cardAddress = document.getElementById('cardaddress');
        var websiteField = document.getElementById('form-field-website');
        var cardWebsite = document.getElementById('cardweb');

        // Si hay datos guardados previamente, los mostramos en la tarjeta
        if (userData.custom_name) {
            cardName.textContent = userData.custom_name;
            nameField.value = userData.custom_name;
        }
        if (userData.custom_job) {
            cardJob.textContent = userData.custom_job;
            jobField.value = userData.custom_job;
        }
        if (userData.custom_phone) {
            cardPhone.textContent = userData.custom_phone;
            phoneField.value = userData.custom_phone;
        }
        if (userData.custom_email) {
            cardEmail.textContent = userData.custom_email;
            emailField.value = userData.custom_email;
        }
        if (userData.custom_address) {
            cardAddress.textContent = userData.custom_address;
            addressField.value = userData.custom_address;
        }
        if (userData.custom_website) {
            cardWebsite.textContent = userData.custom_website;
            websiteField.value = userData.custom_website;
        }

        // Actualiza el contenido de la tarjeta de presentación en tiempo real
        nameField.addEventListener('input', function() {
            cardName.textContent = nameField.value;
        });

        jobField.addEventListener('input', function() {
            cardJob.textContent = jobField.value;
        });

        phoneField.addEventListener('input', function() {
            cardPhone.textContent = phoneField.value;
        });

        emailField.addEventListener('input', function() {
            cardEmail.textContent = emailField.value;
        });

        addressField.addEventListener('input', function() {
            cardAddress.textContent = addressField.value;
        });

        websiteField.addEventListener('input', function() {
            cardWebsite.textContent = websiteField.value;
        });

        // Envía el formulario para guardar los datos en WooCommerce usando ACF
        document.getElementById('update-form').addEventListener('submit', function(e) {
            e.preventDefault();

            var nameValue = nameField.value;
            var jobValue = jobField.value;
            var phoneValue = phoneField.value;
            var emailValue = emailField.value;
            var addressValue = addressField.value;
            var websiteValue = websiteField.value;

            fetch('/wp-admin/admin-ajax.php?action=save_acf_custom_data', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({
                    'custom_name': nameValue,
                    'custom_job': jobValue,
                    'custom_phone': phoneValue,
                    'custom_email': emailValue,
                    'custom_address': addressValue,
                    'custom_website': websiteValue
                })
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    alert('Datos guardados correctamente');
                } else {
                    alert('Error al guardar los datos');
                }
            });
        });
    });
</script>
