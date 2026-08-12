<?php

/**
 * ==========================================================
 * Horizonte Camper
 * Tema hijo de Hello Elementor
 * Archivo: functions.php
 * ==========================================================
 */


/**
 * Cargar estilos y scripts del tema hijo.
 */
function horizonte_camper_enqueue_styles() {

    // Estilos del tema padre Hello Elementor.
    wp_enqueue_style(
        'hello-elementor-parent',
        get_template_directory_uri() . '/style.css'
    );

    // Estilos del tema hijo.
    wp_enqueue_style(
        'hello-elementor-child',
        get_stylesheet_uri(),
        array('hello-elementor-parent'),
        wp_get_theme()->get('Version')
    );

    // JavaScript personalizado de Horizonte Camper.
    wp_enqueue_script(
        'horizonte-camper-js',
        get_stylesheet_directory_uri() . '/js/horizonte-camper.js',
        array(),
        wp_get_theme()->get('Version'),
        true
    );
}

add_action(
    'wp_enqueue_scripts',
    'horizonte_camper_enqueue_styles'
);


/**
 * ==========================================================
 * CARGAR SHORTCODES PERSONALIZADOS
 * ==========================================================
 */

require_once get_stylesheet_directory() . '/inc/shortcodes.php';


/**
 * ==========================================================
 * BOTÓN FLOTANTE DE WHATSAPP
 * ==========================================================
 */

function horizonte_camper_whatsapp_flotante() {
    ?>
    <a
        class="whatsapp-flotante"
        href="https://wa.me/610730305?text=Hola%2C%20quiero%20informaci%C3%B3n%20sobre%20una%20camperizaci%C3%B3n"
        target="_blank"
        rel="noopener noreferrer"
        aria-label="Escríbenos por WhatsApp"
    >
        <span class="whatsapp-flotante__icono" aria-hidden="true">
            <svg viewBox="0 0 32 32">
                <path
                    fill="currentColor"
                    d="M16.04 3C8.86 3 3.02 8.82 3.02 16c0 2.29.6 4.52 1.73 6.49L3 29l6.67-1.75A13 13 0 0 0 16.04 29C23.22 29 29 23.18 29 16S23.22 3 16.04 3Zm0 23.8a10.78 10.78 0 0 1-5.5-1.5l-.39-.23-3.96 1.04 1.06-3.86-.25-.4A10.77 10.77 0 1 1 16.04 26.8Zm5.91-8.07c-.32-.16-1.91-.94-2.21-1.05-.3-.11-.52-.16-.74.16-.21.32-.84 1.05-1.03 1.27-.19.21-.38.24-.7.08-.32-.16-1.36-.5-2.59-1.6-.96-.85-1.61-1.9-1.8-2.22-.19-.32-.02-.49.14-.65.15-.14.32-.38.49-.57.16-.19.21-.32.32-.54.11-.21.05-.4-.03-.57-.08-.16-.74-1.78-1.01-2.44-.27-.64-.54-.55-.74-.56h-.63c-.22 0-.57.08-.87.4-.3.32-1.14 1.11-1.14 2.71 0 1.59 1.17 3.13 1.33 3.34.16.21 2.3 3.51 5.57 4.92.78.34 1.39.54 1.87.69.78.25 1.49.21 2.05.13.63-.09 1.91-.78 2.18-1.54.27-.76.27-1.41.19-1.54-.08-.14-.3-.22-.62-.38Z"
                />
            </svg>
        </span>

        <span class="whatsapp-flotante__texto">Escríbenos</span>
    </a>
    <?php
}

add_action(
    'wp_footer',
    'horizonte_camper_whatsapp_flotante'
);


/**
 * ==========================================================
 * BOTÓN VOLVER ARRIBA
 * ==========================================================
 */

function horizonte_camper_boton_volver_arriba() {
    ?>
    <a
        id="volver-arriba"
        href="#"
        aria-label="Volver al inicio de la página"
    >
        ↑
    </a>
    <?php
}

add_action(
    'wp_footer',
    'horizonte_camper_boton_volver_arriba'
);