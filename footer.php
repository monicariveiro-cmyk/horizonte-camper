<?php

/**
 * Footer global de Horizonte Camper.
 *
 * Renderiza el diseño creado con Elementor
 * en la página "Footer Horizonte Camper".
 */

if (!defined('ABSPATH')) {
    exit;
}


/**
 * ID de la página auxiliar creada con Elementor.
 */
$footer_elementor_id = 1439;


/**
 * Mostrar el footer diseñado con Elementor.
 */
if (
    did_action('elementor/loaded') &&
    class_exists('\Elementor\Plugin')
) {

    echo \Elementor\Plugin::instance()
        ->frontend
        ->get_builder_content_for_display(
            $footer_elementor_id,
            true
        );
}

?>


<?php wp_footer(); ?>


</body>
</html>