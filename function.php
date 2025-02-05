// Hook para generar y almacenar el token al registrar un nuevo cliente
add_action('user_register', 'generate_user_tokens', 10, 1);

function generate_user_tokens($user_id) {
    // Generar Token de Cuenta
    $account_token = wp_generate_uuid4();
    update_user_meta($user_id, 'account_token', $account_token);

    // Generar Token de Tarjeta
    $card_token = wp_generate_uuid4();
    update_user_meta($user_id, 'card_token', $card_token);

    // Generar el enlace de tarjeta con el token
    $card_url = home_url('/digital-card/') . '?token=' . $card_token;
    update_user_meta($user_id, 'card_url', $card_url);
}

// Mostrar los tokens y el enlace en la página de perfil de usuario en el administrador de WooCommerce
add_action('show_user_profile', 'show_user_tokens_in_admin');
add_action('edit_user_profile', 'show_user_tokens_in_admin');

function show_user_tokens_in_admin($user) {
    // Obtener los valores de los tokens y del enlace desde la base de datos
    $account_token = get_user_meta($user->ID, 'account_token', true);
    $card_token = get_user_meta($user->ID, 'card_token', true);
    $card_url = get_user_meta($user->ID, 'card_url', true);

    ?>
    <h3>Tokens del Usuario y Enlace de Tarjeta</h3>
    
    <table class="form-table">
        <tr>
            <th><label for="account_token">Token de Cuenta</label></th>
            <td>
                <input type="text" id="account_token" name="account_token" value="<?php echo esc_attr($account_token); ?>" class="regular-text" readonly />
            </td>
        </tr>
        <tr>
            <th><label for="card_token">Token de Tarjeta</label></th>
            <td>
                <input type="text" id="card_token" name="card_token" value="<?php echo esc_attr($card_token); ?>" class="regular-text" readonly />
            </td>
        </tr>
        <tr>
            <th><label for="card_url">Enlace para Compartir la Tarjeta</label></th>
            <td>
                <input type="text" id="card_url" name="card_url" value="<?php echo esc_url($card_url); ?>" class="regular-text" readonly />
            </td>
        </tr>
    </table>
    <?php
}

// Guardar tokens y enlace si se edita el perfil (en caso de que sea necesario)
add_action('personal_options_update', 'save_user_tokens_in_admin');
add_action('edit_user_profile_update', 'save_user_tokens_in_admin');

function save_user_tokens_in_admin($user_id) {
    if (!current_user_can('edit_user', $user_id)) {
        return false;
    }
    update_user_meta($user_id, 'account_token', sanitize_text_field($_POST['account_token']));
    update_user_meta($user_id, 'card_token', sanitize_text_field($_POST['card_token']));
    update_user_meta($user_id, 'card_url', esc_url_raw($_POST['card_url']));
}




// Cargar los valores de los campos ACF para el usuario logueado
add_action('wp_enqueue_scripts', 'cargar_datos_tarjeta_virtual');
function cargar_datos_tarjeta_virtual() {
    if (is_user_logged_in()) {
        $user_id = get_current_user_id();
        $custom_name = get_field('business_card', 'user_' . $user_id); // Campo para el nombre
        $custom_job = get_field('job', 'user_' . $user_id); // Campo para el puesto de trabajo
        $custom_phone = get_field('card-phone', 'user_' . $user_id); // Campo para el teléfono
        $custom_email = get_field('card-email', 'user_' . $user_id); // Campo para el email
        $custom_address = get_field('card-address', 'user_' . $user_id); // Campo para la dirección
        $custom_website = get_field('card-web', 'user_' . $user_id); // Campo para el website

        // Enviar los datos personalizados al JavaScript
        wp_localize_script('elementor-frontend', 'userData', array(
            'custom_name' => $custom_name,
            'custom_job' => $custom_job,
            'custom_phone' => $custom_phone,
            'custom_email' => $custom_email,
            'custom_address' => $custom_address,
            'custom_website' => $custom_website,
        ));
    }
}

// Manejar la solicitud AJAX para guardar los datos en ACF
add_action('wp_ajax_save_acf_custom_data', 'guardar_datos_acf');
function guardar_datos_acf() {
    if (!is_user_logged_in()) {
        wp_send_json_error(array('message' => 'Usuario no logueado.'));
        return;
    }

    $user_id = get_current_user_id();

    // Guardar el campo de nombre si está presente
    if (isset($_POST['custom_name'])) {
        $custom_name = sanitize_text_field($_POST['custom_name']);
        update_field('business_card', $custom_name, 'user_' . $user_id);
    }

    // Guardar el campo de puesto de trabajo si está presente
    if (isset($_POST['custom_job'])) {
        $custom_job = sanitize_text_field($_POST['custom_job']);
        update_field('job', $custom_job, 'user_' . $user_id);
    }

    // Guardar el campo de número de teléfono si está presente
    if (isset($_POST['custom_phone'])) {
        $custom_phone = sanitize_text_field($_POST['custom_phone']);  // Sanitizar el número
        update_field('card-phone', $custom_phone, 'user_' . $user_id);
    }

    // Guardar el campo de email si está presente
    if (isset($_POST['custom_email'])) {
        $custom_email = sanitize_email($_POST['custom_email']);  // Usa sanitización de email
        update_field('card-email', $custom_email, 'user_' . $user_id);
    }

    // Guardar el campo de dirección si está presente
    if (isset($_POST['custom_address'])) {
        $custom_address = sanitize_text_field($_POST['custom_address']);  // Usa sanitización de texto
        update_field('card-address', $custom_address, 'user_' . $user_id);
    }

    // Guardar el campo de website si está presente
	 if (isset($_POST['custom_website'])) {
        $custom_website = sanitize_text_field($_POST['custom_website']);  // Usa sanitización de texto
        update_field('card-web', $custom_website, 'user_' . $user_id);
    }

    // Verificar que al menos uno de los campos esté presente
    if (!isset($_POST['custom_name']) && !isset($_POST['custom_job']) && !isset($_POST['custom_phone']) && !isset($_POST['custom_email']) && !isset($_POST['custom_address']) && !isset($_POST['custom_website'])) {
        wp_send_json_error(array('message' => 'No se recibieron datos.'));
        return;
    }

    wp_send_json_success();
}

// SHORTCODE de la pagina de creacion de la tarjeta
function page_shortcode($atts) {
    // Extraer el atributo 'id' del shortcode y asignar un valor predeterminado si no se proporciona
    $atts = shortcode_atts(
        array(
            'id' => 0, // Valor predeterminado: 0 si no se pasa un ID
        ),
        $atts
    );

    // Verifica si se ha proporcionado un ID válido
    $page_id = intval($atts['id']);
    if ($page_id === 0) {
        return 'No se ha proporcionado un ID de página válido.';
    }

    // Obtén la página por su ID
    $page = get_post($page_id);

    // Verifica si la página existe
    if (!$page || $page->post_status !== 'publish') {
        return 'La página no existe o no está publicada.';
    }

    // Si la página fue construida con Elementor, usa la función de Elementor para procesar su contenido
    if ( did_action( 'elementor/loaded' ) ) {
        return \Elementor\Plugin::$instance->frontend->get_builder_content( $page_id );
    }

    // Si no fue hecha con Elementor, muestra el contenido normal
    return apply_filters('the_content', $page->post_content);
}

// Registrar el shortcode
add_shortcode('page', 'page_shortcode');




/**
 * Setup Flyweb Child Theme's textdomain.
 *
 * Declare textdomain for this child theme.
 * Translations can be filed in the /languages/ directory.
 */
function flyweb_child_theme_setup() {
	load_child_theme_textdomain( 'flyweb-child', get_stylesheet_directory() . '/languages' );
}
add_action( 'after_setup_theme', 'flyweb_child_theme_setup' );


function flyweb_enqueue_styles() {

    $parent_style = 'parent-style';

    wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array( $parent_style )
    );
}
add_action( 'wp_enqueue_scripts', 'flyweb_enqueue_styles' );




// Manejar la solicitud AJAX para guardar imágenes
add_action('wp_ajax_save_acf_images_update_photos', 'guardar_imagenes_acf');
function guardar_imagenes_acf() {
    if (!is_user_logged_in()) {
        error_log('User not logged in.');
        wp_send_json_error(array('message' => 'User not logged in.'));
        return;
    }

    $user_id = get_current_user_id();
    error_log('Current user ID: ' . $user_id);

    // Función para manejar la subida de imágenes
    function handle_image_upload($file, $field_name, $user_id) {
        error_log('Handling image upload for field: ' . $field_name);

        // Validar tipos de archivos permitidos
        $allowed_types = array('image/jpeg', 'image/png', 'image/gif');
        if (!in_array($file['type'], $allowed_types)) {
            error_log('Invalid file type: ' . $file['type']);
            return 'Invalid file type.';
        }

        $upload = wp_handle_upload($file, array('test_form' => false));
        if (!$upload || isset($upload['error'])) {
            error_log('Upload error: ' . print_r($upload, true));
            return $upload['error'];
        }

        $attachment_id = wp_insert_attachment(array(
            'post_mime_type' => $upload['type'],
            'post_title' => sanitize_file_name($file['name']),
            'post_content' => '',
            'post_status' => 'inherit',
            'post_author' => $user_id
        ), $upload['file']);

        require_once(ABSPATH . 'wp-admin/includes/image.php');
        $attachment_data = wp_generate_attachment_metadata($attachment_id, $upload['file']);
        wp_update_attachment_metadata($attachment_id, $attachment_data);

        // Actualizar el campo ACF con la URL de la imagen
        update_field($field_name, $attachment_id, 'user_' . $user_id);

        error_log('Image uploaded successfully for field: ' . $field_name);
        return null; // Si no hay errores
    }

    // Manejar la subida del logotipo de la empresa
    if (!empty($_FILES['companylogo']['name'])) {
        $company_logo_error = handle_image_upload($_FILES['companylogo'], 'card-companylogo', $user_id);
        if ($company_logo_error) {
            error_log('Error uploading company logo: ' . $company_logo_error);
            wp_send_json_error(array('message' => 'Error uploading company logo: ' . $company_logo_error));
            return;
        }
    }

    // Manejar la subida de la foto de perfil
    if (!empty($_FILES['profilephoto']['name'])) {
        $profile_photo_error = handle_image_upload($_FILES['profilephoto'], 'card-profilephoto', $user_id);
        if ($profile_photo_error) {
            error_log('Error uploading profile photo: ' . $profile_photo_error);
            wp_send_json_error(array('message' => 'Error uploading profile photo: ' . $profile_photo_error));
            return;
        }
    }

    error_log('Images uploaded successfully.');
    wp_send_json_success(array('message' => 'Images uploaded successfully.'));
}

function mostrar_logo_empresa() {
    $user_id = get_current_user_id();
    $company_logo_id = get_field('card-companylogo', 'user_' . $user_id);
    $company_logo_url = $company_logo_id ? wp_get_attachment_url($company_logo_id) : 'path/to/default-logo.png'; // Agregar un placeholder

    if ($company_logo_url) {
        return '<div><img src="' . esc_url($company_logo_url) . '" alt="Company Logo" style="max-width: 100px;"></div>';
    }
    return '';
}
add_shortcode('mostrar_logo_empresa', 'mostrar_logo_empresa');

function mostrar_foto_perfil() {
    $user_id = get_current_user_id();
    $profile_photo_id = get_field('card-profilephoto', 'user_' . $user_id);
    $profile_photo_url = $profile_photo_id ? wp_get_attachment_url($profile_photo_id) : 'path/to/default-profile.png'; // Agregar un placeholder

    if ($profile_photo_url) {
        return '<div><img src="' . esc_url($profile_photo_url) . '" alt="Profile Photo" style="max-width: 100px;"></div>';
    }
    return '';
}
add_shortcode('mostrar_foto_perfil', 'mostrar_foto_perfil');

function custom_user_data($atts, $content = null) {
    $a = shortcode_atts(array(
        'field' => '',
        'default' => 'No disponible',
    ), $atts);

    $userData = get_user_meta(get_current_user_id());

    if (!empty($userData[$a['field']]) && !empty($userData[$a['field']][0])) {
        return esc_html($userData[$a['field']][0]);
    }

    return esc_html($a['default']);
}
add_shortcode('user_data', 'custom_user_data');


// Formulario social media
// Manejar la solicitud para guardar enlaces sociales
function handle_save_social_links() {
    if (!is_user_logged_in()) {
        wp_send_json_error('User not logged in');
        return;
    }
    $user_id = get_current_user_id();
    
    if (isset($_POST['card_link'])) {
        $linkedin_url = sanitize_text_field($_POST['card_link']);
        update_user_meta($user_id, 'card_link', $linkedin_url);
    }
    
    if (isset($_POST['card_inst'])) {
        $instagram_url = sanitize_text_field($_POST['card_inst']);
        update_user_meta($user_id, 'card_inst', $instagram_url);
    }
    
    wp_send_json_success('Social links saved successfully');
}
add_action('wp_ajax_save_social_links', 'handle_save_social_links');
add_action('wp_ajax_nopriv_save_social_links', 'handle_save_social_links');

// Obtener los enlaces sociales del usuario
function handle_get_user_social_links() {
    if (!is_user_logged_in()) {
        wp_send_json_error('User not logged in');
        return;
    }
    $user_id = get_current_user_id();
    $links = array(
        'linkedin' => get_user_meta($user_id, 'card_link', true),
        'instagram' => get_user_meta($user_id, 'card_inst', true)
    );
    
    wp_send_json_success($links);
}
add_action('wp_ajax_get_user_social_links', 'handle_get_user_social_links');
add_action('wp_ajax_nopriv_get_user_social_links', 'handle_get_user_social_links');

// Agrega share-tab al menu
function load_my_account_tabs_styles() {
    if (is_account_page()) {
        wp_enqueue_style('plus-jakarta-sans', 'https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap');
        
        // Usar get_stylesheet_directory_uri() para apuntar al tema hijo
        $css_path = get_stylesheet_directory_uri() . '/inc/my-account/css/my-account-tabs.css';
        wp_enqueue_style('my-account-tabs', $css_path, array(), time()); // Usando time() para evitar caché durante desarrollo
        
        // Debug
        error_log('CSS Path: ' . $css_path);
    }
}
add_action('wp_enqueue_scripts', 'load_my_account_tabs_styles');
