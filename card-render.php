<?php
// Iniciar el sistema de WordPress
require_once($_SERVER['DOCUMENT_ROOT'] . '/wp-load.php');
// Forzar HTTPS en los recursos
if ($_SERVER['HTTPS'] != "on") {
    $redirect = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header("Location:$redirect");
    exit;
}

// Verificar si se pasa un token en la URL
if (isset($_GET['token'])) {
    global $wpdb;
    
    $token = sanitize_text_field($_GET['token']);
    
    // Consultar la base de datos para obtener el ID de usuario asociado al token
    $user_id = $wpdb->get_var($wpdb->prepare(
        "SELECT user_id FROM {$wpdb->usermeta} WHERE meta_key = 'card_token' AND meta_value = %s", 
        $token
    ));
    
    // Si el token es válido, obtener y mostrar la información de la tarjeta
    if ($user_id) {
        // Obtener los valores de la base de datos
        $name = get_user_meta($user_id, 'business_card', true);
        $job = get_user_meta($user_id, 'job', true);
        $phone = get_user_meta($user_id, 'card-phone', true);
        $email = get_user_meta($user_id, 'card-email', true);
        $address = get_user_meta($user_id, 'card-address', true);
        $website = get_user_meta($user_id, 'card-web', true);
        $linkedin = get_user_meta($user_id, 'card_link', true);
        $instagram = get_user_meta($user_id, 'card_inst', true);
        $logo = get_user_meta($user_id, 'card-companylogo', true);
        $profilephoto = get_user_meta($user_id, 'card-profilephoto', true);

        // Generar la tarjeta digital
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Digital Card</title>
            <style>
:root {
    /* Colores */
    --color-white: white;
    --color-black: black;
    --color-gray: #a6a4a4;
    
    /* Espaciado */
    --spacing-xs: 5px;
    --spacing-sm: 15px;
    --spacing-md: 20px;
    --spacing-lg: 30px;
    --spacing-xl: 35px;
    
    /* Tamaños */
    --max-width: 450px;
    --icon-size: 38px;
    --border-radius: 8px;
    
    /* Sombras */
    --shadow-card: 0 4px 24px rgba(0, 0, 0, 0.1);
    
    /* Transiciones */
    --transition-fast: 0.2s ease;
}

/* Estilos base */
body {
    font-family: 'Plus Jakarta Sans', sans-serif;
    margin: 0;
    padding: 0;
    background: #f5f5f5;
    color: var(--color-white);
    min-height: 100vh;
    -webkit-font-smoothing: antialiased;
    display: flex;
    justify-content: center;
    align-items: center;
}

.card-container {
    width: 100%;
    max-width: var(--max-width);
    display: flex;
    flex-direction: column;
    position: relative;
    overflow: hidden;
    background-color: white;
    border-radius: 16px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15); /* Sombra más pronunciada */
    margin: 20px;
}

.info-container {
    max-width: var(--max-width);
    padding: 0 var(--spacing-xl);
    box-shadow: var(--shadow-card);
    font-weight: 400;
    color: var(--color-white);
    background-color: black;
	z-index:20;
}

.container_imagenes {
    display: flex;
    justify-content: space-between;
    background: var(--color-white);
    z-index: 1;
    position: absolute;
    width: 100%;
    max-height: 180px;
    top: 0;
    margin: 0;
    padding: 0;
    font-size: 0;
    line-height: 0;
    border-bottom: none;
}

.logo_imagenes {
    display: flex;
    flex: 0 0 70%;
    text-align: center;
    justify-content: center;
    align-items: center;
    overflow: hidden;
    aspect-ratio: 16/9;
}

.logo_imagenes img {
    max-width: 100%;
    max-height: 100%;
    width: auto;
    height: auto;
    object-fit: contain;
    object-position: center;
}

.profile_imagenes {
    display: flex;
    flex: 0 0 30%;
    text-align: center;
    justify-content: center;
    align-items: flex-start;
    overflow: hidden;
    max-height: 180px;
}

.profile_imagenes img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    max-height: 180px;
}

.main_card_image {
    padding: 120px 0 0 0;
    z-index: 10;
    position: relative;
}

.main_card_image svg {
    display: block;
    margin-bottom: -3px;
    vertical-align: top;
}

.info-row {
    display: flex;
    align-items: center;
    margin: var(--spacing-sm) 0;
}

.info-row-name {
    text-align: right;
    font-size: clamp(1.1rem, 2vw, 1.3rem);
    font-weight: 600;
}

.info-row-job {
    text-align: right;
    font-size: clamp(0.9rem, 1.5vw, 1rem);
    font-weight: 600;
    color: var(--color-gray);
    margin-bottom: var(--spacing-sm);
}

.info-row-job .info-value {
    font-size: 18px;
}

.info-icono {
    width: var(--icon-size);
    height: var(--icon-size);
    margin-right: var(--spacing-sm);
    flex-shrink: 0;
}

.info-icono svg {
    width: 100%;
    height: 100%;
}

.info-value {
    flex: 1;
    text-align: right;
    border-bottom: 1px solid var(--color-white);
    padding-bottom: var(--spacing-xs);
}

#socialIconsDisplay {
    display: flex;
    justify-content: center;
    gap: var(--spacing-md);
    margin-top: var(--spacing-lg);
    padding-bottom: 40px; /* Agregar padding inferior */
}

.card_social_icons {
    width: var(--icon-size);
    height: var(--icon-size);
    transition: transform var(--transition-fast);
}

.card_social_icons:hover {
    transform: translateY(-3px);
}

.card_social_icons a {
    display: block;
    width: 100%;
    height: 100%;
}

/* Media queries para móviles */
@media (max-width: 480px) {
    body {
        background: var(--color-black);
    }

    .card-container {
        margin: 0;
        border-radius: 0;
        box-shadow: none;
    }

    .container_imagenes {
        max-height: 120px;
    }
    
    .logo_imagenes {
        max-height: 120px;
        padding: 10px;
    }

    .logo_imagenes img {
        max-width: 100%;
        max-height: 100%;
        transform: scale(1.2);
    }
    
    .profile_imagenes {
        max-height: 120px;
    }
    
    .profile_imagenes img {
        max-height: 120px;
    }

    .main_card_image {
        padding: 140px 0 0 0;
    }

    .info-row-name {
        font-size: 1.43rem;
    }
    
    .info-row-job {
        font-size: 1.17rem;
    }
    
    .info-value {
        font-size: 1.2rem;
    }

    .info-row {
        margin: var(--spacing-md) 0;
    }

    .info-container {
        padding: 0 var(--spacing-lg);
    }

    .info-icono {
        width: calc(var(--icon-size) * 1.2);
        height: calc(var(--icon-size) * 1.2);
    }

    .card_social_icons {
        width: calc(var(--icon-size) * 1.2);
        height: calc(var(--icon-size) * 1.2);
    }
	#socialIconsDisplay {
        padding-bottom: 30px; /* Un poco menos de padding en móviles */
    }
}

/* Soporte para modo oscuro del sistema */
@media (prefers-color-scheme: dark) {
    :root {
        --shadow-card: 0 0 10px rgba(255, 255, 255, 0.05);
    }
}			
            </style>
        </head>
        <body>
            <div class="card-container">
                <!-- Logo y Foto -->
                <div class="container_imagenes">
                    <div class="logo_imagenes">
                        <img src="<?php echo esc_url(wp_get_attachment_url($logo)); ?>" alt="Company Logo">
                    </div>
                    <div class="profile_imagenes">
                        <img src="<?php echo esc_url(wp_get_attachment_url($profilephoto)); ?>" alt="Profile Photo">
                    </div>
					
                </div>
<div class="main_card_image">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 588.32 246.69"><g data-name="Layer 2"><g data-name="Layer 1"><path d="M587.27 246.43V64.92h-5.41V49.1h-25.95v16.96h-27.58v124.32h-7.57v-88.16h-22.71v-9.04h-19.47v97.76h-12.44v-66.11h-46.51v88.72h-8.65V88.09h-5.95V76.23h-22.71v14.12h-24.33v124.33h-7.3v-70.36l-12.17-12.71h-33.53l-11.35 11.86v72.9h-40.56V44.58h-3.25v-5.65h-43.26v139.58h-14.06v12.43h-15.14V76.79h-5.95V48.54h-14.6v12.43h-10.27v17.52h-27.58v89.29H71.38v71.2H61.11v-138.6H47.59V90.21H22.17v10.73H0l.82 145.4 586.45.09z" style="fill:#dcddde"/><path d="M0 246.69h588.32v-44.88h-15.94l-9.86-4.22v-2.09h-4.86l-71.33-30.61v-34.08h-29.12v.19l-5.21 1.4v17.74l-25.32-10.87v-4h-9.25l-150.6-64.58-19.53-8.82c.34-40.73-.55-70.23-1.88-59.73-.55 4.44-4.08 31-8 60.94l-.2-.19-80.54 75.59v-16l-16.94-1.83-5.75-.62-.83-9.9h-9.64l-8.54-19-.1-.21-.62-16.28-3.54-1.19v-27.2h-3.3v4.24h-1.91V51h-2.3v-5.9h-1.37v25.72H100a30.24 30.24 0 0 1 0-3.82h-1.52l-1.74 23.9-.06.42L93 119.07l-11.85 2.61a37.16 37.16 0 0 0-7.85-1.07c-11.13 0-17.67 12.85-20.43 19.9l-11.79 13 4.25 22.23-1.11 2.66H31.35v-13h-14.3v13h-1.72V198H4.1v1H0m169.31-36v-4.43l59.92-70-1.89 4.29L170.69 163Zm63.49-82.54-1.19 2.72-62.3 69.39v-4.36Zm-63.49 66.65v-3L232.21 80Zm61.32-61.72-.35.79-61 71.24v-3.71Zm-59 77.62 54.53-67.52v.07L174.57 163Zm5.14 0h-1.28l49.34-64.53-4.82 11-38.42 54.86Zm7.13 1.89-1.54-.41L218.47 113l-2.41 5.45-32 47.63Zm.24 2.34 30.18-44.89-16.3 37-12.79 19.15Zm3.38 15.84-1.76.93-.42-4.38 10.92-16.37-8.73 19.82Zm3-1.55-1.86 1 10.07-22.86 23.37-35-3.69 9.57L190.8 181Zm1.25-.66 25-42.45-14.16 36.74Zm31.55 10.49v-.1Zm1.49-33.9-9.62 11.12-4.48 2.36 18.72-48c-2.71 20.06-4.65 34.52-4.65 34.52Zm5-37.69-20.15 51.7-6 3.16 15.47-40.15 11.29-19.14c-.22 1.51-.42 2.98-.6 4.43Zm.84-6.32-9.92 16.81 3.27-8.49 6.92-10.35Zm.49-3.72-5.9 8.84 7.22-18.76q-.66 5.06-1.31 9.92Zm1.67-12.59h-.11l-9.37 24.33-22.88 34.25 16.27-36.9 16.48-24.5Zm.62-4.54-15 22.25 2.23-5.06 12.9-18.42c-.01.42-.07.82-.12 1.23Zm.24-1.8-.4-.28-11 15.72 4.54-10.31 7.48-9.78c-.2 1.56-.41 3.11-.61 4.65Zm.92-6.93-5.43 6.72 1.77-4 3.9-4.56Zm.43-3.22-3 3.54.27-.62 2.79-3.09a.88.88 0 0 0-.08.17Zm.19-1.5-2 2.22 1.13-2.58 1-1.12c.01.49-.05.98-.12 1.48Zm.8-6-1.49 3.39-66.19 67.5H165l71.1-71.46c-.03.2-.1.42-.1.59Zm.24-1.83-72.34 72.72h-3.61l76-72.78a.13.13 0 0 0-.04.08Zm.17-1.25-77.23 74h-2.57v-.36l79.91-74.5c-.03.29-.06.58-.1.88Zm.28-2.07-80.07 74.65v-3.17l80.63-75.66c-.19 1.41-.37 2.81-.56 4.18Zm4.82 100.55-3.75-10.81s1.17-6.23 2.69-16.21l5 29.74Zm4.8 3.31-5.5-32.73c1.57-10.53 3.42-24.64 4.74-39.84l24.79 89.07Zm35.24 12.41v17.51h-6.7l-.2-.72v-9.73l-3.35-2.3-25.56-91.86c.11-1.45.23-2.9.34-4.36l40.6 91.49Zm5.95 0L246.2 91.22v-.32l45 93.4Zm13.71-1.16V187h-6.75v-2.7h-2.42l-45.72-94.92.06-1 61.81 94.73Zm7.88 0-62.63-96v-.5l67.86 96.49Zm6.51 3.81v-2l1.44 2Zm1.8-11.54v10.75l-1.8-2.56v-.46h-.32l-68.71-97.7.09-1.66 78.74 91.63Zm20.93 17.5h-4.76v-4.08h-2.17V187h.09v-4.46l6.84 8Zm0-3.61-6.84-8v-5.92h-5.1l-79.67-92.66a.31.31 0 0 1 0-.1l91.6 98.93Zm0-8.88-91.55-98.85c0-.32 0-.64.05-1l91.5 85.52Zm0-15.36L246.88 79.6c0-.34 0-.68.05-1l91.41 79.84Zm0-7.63L247 77.63v-.8l91.34 69.78Zm0-11.77L247 75.91l.06-1.63L338.34 140Zm0-12.45v5.87l-91.22-65.7v-.13l92.65 60Zm2.81 0-94-60.84v-.69l105.43 61.53Zm12.93 0-106.9-62.4v-.94l113.62 63.34Zm21 68.6v-1.38l1.52 1.41Zm10.13 0h-7.51l-2.62-2.44v-8.88l10.13 8.84Zm0-3.48-10.13-8.85v-14.8l10.13 7.74Zm0-22.05v5.15l-10.13-7.72v-7.26l13.66 9.83Zm12.55 0H390l-14.94-10.75v-2.92h-1.33v-7.43l24 15.54Zm0-6.45-24-15.55v-8.74l24 14Zm0-11.15-24-14v-4.27l24 13.4Zm0-15.32v9.56l-24-13.38v-6.33h-11.4L247.22 69v-2.38a2.17 2.17 0 0 0 0-.26l151.51 77Zm2.7 0-114.12-58 119 58Zm12.63 0H407L247.27 65.51v-2.39l19.23 8.26 146.56 66.22Zm0-8.06v1.47L302.88 87l112.59 48.3Zm13.58 5.4v-.61l1.41.61Zm23.67 50v6.45H440l-.3-.19v-6.14h-9.49l-1.19-.76v-12.2l22.12 12.9Zm1.65-.4L429 177v-5.71L452 184Zm0-7.17L429 170.38v-11.65l23 11.65Zm0-13.62L429 157.89V155l23 11.12Zm0-4.26L429 154.11v-9.3l23 10.36Zm0-10.93L429 144v-2.85l23 9.85Zm38 41.2h-2.09v-6.93l2.09 1.06Zm0-6.71-2.09-1.06v-4.16l2.09 1Zm17.36 9.83h-2.54v-1.46l2.54 1.29Zm0-1-2.54-1.28v-4.51l2.54 1.23Zm18.6-2.15v3.17h-5v-11.87h-13.63v5.5l-2.54-1.26v-11.4H490v4.17l-2.09-1v-11.4l33.2 15 20.15 9.1Zm17.12 0-55.17-24.93v-.06h-.14l-1.4-.63v-4.16l69.41 29.78Zm19.48 6.31v-3.4l7.94 3.4Z"/></g></g></svg>
</div>

                <!-- Información Personal -->
                <div class="info-container">
                    <div class="info-row-name">
                        <div><?php echo esc_html($name); ?></div>
                    </div>
                    <div class="info-row-job">
                        <div><?php echo esc_html($job); ?></div>
                    </div>
                    
                    <!-- Teléfono -->
                    <div class="info-row">
                        <div class="info-icono">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 80 80" fill="none" stroke="white" stroke-width="5">
                                <rect x="4" y="4" width="72" height="72" rx="5" ry="5" fill="transparent" stroke="white"/>
                                <rect x="27" y="15" width="26" height="50" rx="4" ry="4" fill="white" stroke="white"/>
                                <rect x="29" y="18" width="22" height="34" rx="2" ry="2" fill="black" stroke="white"/>
                                <circle cx="40" cy="58" r="1.5" fill="black" stroke="black" stroke-width="2.5"/>
                            </svg>
                        </div>
                        <div class="info-value"><?php echo esc_html($phone); ?></div>
                    </div>

                    <!-- Email -->
                    <div class="info-row">
                        <div class="info-icono">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 90 90" fill="none" stroke="white" stroke-width="5">
                                <rect x="6" y="6" width="78" height="78" rx="5" ry="5" fill="transparent" stroke="white"/>
                                <path d="M25 32 h40 a2 2 0 0 1 2 2 v24 a2 2 0 0 1 -2 2 h-40 a2 2 0 0 1 -2 -2 v-24 a2 2 0 0 1 2 -2 z" fill="white" stroke="white"/>
                                <path d="M25 32 l20 16 l20 -16" fill="none" stroke="white" stroke-width="6"/>
                                <path d="M25 32 l20 16 l20 -16" fill="none" stroke="black" stroke-width="2"/>
                            </svg>
                        </div>
                        <div class="info-value"><?php echo esc_html($email); ?></div>
                    </div>

                    <!-- Dirección -->
                    <div class="info-row">
                        <div class="info-icono">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 90 90" fill="none" stroke="white" stroke-width="6">
                                <rect x="6" y="6" width="78" height="78" rx="5" ry="5" fill="transparent" stroke="white"/>
                                <path d="M45 20 C52 20, 58 26, 58 32 C58 45, 45 70, 45 70 C45 70, 32 45, 32 32 C32 26, 38 20, 45 20 Z" fill="white" stroke="white"/>
                                <circle cx="45" cy="36" r="10" fill="black"/>
                            </svg>
                        </div>
                        <div class="info-value"><?php echo esc_html($address); ?></div>
                    </div>

                    <!-- Website -->
                    <div class="info-row">
                        <div class="info-icono">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 70 70" fill="none" stroke="white" stroke-width="2">
                                <rect x="6" y="6" width="58" height="58" rx="10" ry="10" fill="transparent" stroke="white" stroke-width="5"/>
                                <g transform="translate(17, 17) scale(1.5)">
                                    <circle cx="12" cy="12" r="10" stroke="white" fill="none"></circle>
                                    <path d="M2 12h20" stroke="white" fill="none"></path>
                                    <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z" stroke="white" fill="none"></path>
                                    <path d="M12 2a15.3 15.3 0 0 0-4 10 15.3 15.3 0 0 0 4 10 15.3 15.3 0 0 0 4-10 15.3 15.3 0 0 0-4-10z" stroke="white" fill="none"></path>
                                </g>
                            </svg>
                        </div>
                        <div class="info-value"><?php echo esc_html($website); ?></div>
                    </div>

                    <!-- Redes Sociales -->
                    <div id="socialIconsDisplay">
                        <?php if (!empty($linkedin)): ?>
                        <div class="card_social_icons" id="linkedinIcon">
                            <a id="linkedinLink" href="<?php echo esc_url($linkedin); ?>" target="_blank">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 110 110">
                                    <rect x="5" y="5" width="100" height="100" rx="15" ry="15" fill="none" stroke="white" stroke-width="4"/>
                                    <rect x="15" y="15" width="80" height="80" rx="18" ry="18" fill="black"/>
                                    <g transform="translate(25, 25) scale(2.5)">
                                        <rect x="4" y="8" width="5" height="16" fill="white"/>
                                        <circle cx="6.5" cy="5" r="2.5" fill="white"/>
                                        <path d="M28 24h-5v-8.5c0-2.5-1-4.5-3.5-4.5-2 0-3.5 1.5-3.5 4.5V24h-5V8h5v2.5c1-1.5 2.5-2.5 5-2.5 4 0 7 2.5 7 7.5V24z" fill="white"/>
                                    </g>
                                </svg>
                            </a>
                        </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($instagram)): ?>
                        <div class="card_social_icons" id="instagramIcon">
                            <a id="instagramLink" href="<?php echo esc_url($instagram); ?>" target="_blank">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 110 110">
                                    <rect x="5" y="5" width="100" height="100" rx="15" ry="15" fill="none" stroke="white" stroke-width="4"/>
                                    <rect x="15" y="15" width="80" height="80" rx="18" ry="18" fill="black"/>
                                    <rect x="25" y="25" width="60" height="60" rx="15" ry="15" fill="none" stroke="white" stroke-width="6"/>
                                    <circle cx="55" cy="55" r="15" fill="none" stroke="white" stroke-width="6"/>
                                    <circle cx="55" cy="55" r="1" fill="white"/>
                                    <circle cx="75" cy="35" r="4" fill="white"/>
                                </svg>
                            </a>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </body>
        </html>
        <?php
    } else {
        echo '<p>Token inválido. No se pudo encontrar la tarjeta.</p>';
    }
} else {
    echo '<p>No se proporcionó ningún token.</p>';
}
?>
