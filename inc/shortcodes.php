<?php

/**
 * ==========================================================
 * Horizonte Camper
 * Shortcodes personalizados
 * Archivo: inc/shortcodes.php
 * ==========================================================
 */


/**
 * ==========================================================
 * SHORTCODE: ÚLTIMOS ARTÍCULOS
 *
 * Uso:
 * [hc_ultimos_articulos]
 *
 * Muestra automáticamente las tres últimas entradas
 * publicadas del blog.
 * ==========================================================
 */

function hc_ultimos_articulos_shortcode() {

    /*
     * Configuración de la consulta.
     */
    $args = array(
        'post_type'           => 'post',
        'posts_per_page'      => 3,
        'post_status'         => 'publish',
        'ignore_sticky_posts' => true
    );

    /*
     * Ejecutar la consulta.
     */
    $query = new WP_Query($args);

    /*
     * HTML que devolverá el shortcode.
     */
    $output = '';

    /*
     * Comprobar si existen entradas.
     */
    if ($query->have_posts()) {

        /*
         * Contenedor general.
         */
        $output .= '<div class="hc-articulos">';


        /*
         * Recorrer las entradas encontradas.
         */
        while ($query->have_posts()) {

            $query->the_post();


            /*
             * Abrir tarjeta.
             */
            $output .= '<article class="hc-articulo">';


            /*
             * ==================================================
             * IMAGEN DESTACADA
             * ==================================================
             */

            if (has_post_thumbnail()) {

                $output .= '<a class="hc-articulo-imagen" href="' .
                    esc_url(get_permalink()) .
                    '" aria-label="' .
                    esc_attr(get_the_title()) .
                    '">';

                $output .= get_the_post_thumbnail(
                    get_the_ID(),
                    'large',
                    array(
                        'class'   => 'hc-articulo-imagen__foto',
                        'alt'     => esc_attr(get_the_title()),
                        'loading' => 'lazy'
                    )
                );

                $output .= '</a>';
            }


            /*
             * Abrir contenido de la tarjeta.
             */
            $output .= '<div class="hc-articulo-contenido">';


            /*
             * ==================================================
             * CATEGORÍA
             * ==================================================
             */

            $categorias = get_the_category();

            if (!empty($categorias)) {

                $output .= '<p class="hc-articulo-categoria">';

                $output .= esc_html(
                    $categorias[0]->name
                );

                $output .= '</p>';
            }


            /*
             * ==================================================
             * TÍTULO
             * ==================================================
             */

            $output .= '<h3 class="hc-articulo-titulo">';

            $output .= '<a href="' .
                esc_url(get_permalink()) .
                '">';

            $output .= esc_html(
                get_the_title()
            );

            $output .= '</a>';

            $output .= '</h3>';


            /*
             * ==================================================
             * EXTRACTO
             * ==================================================
             */

            $extracto = get_the_excerpt();

            if (!empty($extracto)) {

                $output .= '<p class="hc-articulo-extracto">';

                $output .= esc_html(
                    wp_trim_words(
                        $extracto,
                        24,
                        '…'
                    )
                );

                $output .= '</p>';
            }


            /*
             * ==================================================
             * LEER ARTÍCULO
             *
             * El texto y la brújula son elementos separados
             * para poder animarlos mediante CSS.
             * ==================================================
             */

            $output .= '<a class="hc-articulo-enlace" href="' .
                esc_url(get_permalink()) .
                '">';

            $output .= '<span class="hc-articulo-enlace__texto">';
            $output .= 'Leer artículo';
            $output .= '</span>';

            $output .= '<span class="hc-articulo-enlace__brujula" aria-hidden="true"></span>';

            $output .= '</a>';


            /*
             * Cerrar contenido.
             */
            $output .= '</div>';


            /*
             * Cerrar tarjeta.
             */
            $output .= '</article>';
        }


        /*
         * Cerrar contenedor general.
         */
        $output .= '</div>';


        /*
         * Restaurar la consulta principal de WordPress.
         */
        wp_reset_postdata();


        /*
         * Devolver HTML.
         */
        return $output;
    }


    /*
     * Si no existen entradas.
     */
    return '<p class="hc-articulos-vacio">No hay entradas publicadas.</p>';
}


/**
 * Registrar el shortcode.
 */
add_shortcode(
    'hc_ultimos_articulos',
    'hc_ultimos_articulos_shortcode'
);