<?php

/* =========================================================
   STRIPE · AUTOLOAD
   ========================================================= */

$hc_stripe_autoload = get_stylesheet_directory() . '/vendor/autoload.php';

if ( file_exists( $hc_stripe_autoload ) ) {
    require_once $hc_stripe_autoload;
}

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


/* =========================================================
   FORMULARIO SOLICITA INFORMACIÓN - HORIZONTE CAMPER
   ========================================================= */

/* Usuario conectado */
add_action( 'admin_post_hc_enviar_formulario', 'hc_procesar_formulario' );

/* Usuario no conectado */
add_action( 'admin_post_nopriv_hc_enviar_formulario', 'hc_procesar_formulario' );


function hc_procesar_formulario() {

    /* -----------------------------------------------------
       1. COMPROBAR QUE EL FORMULARIO SE ENVÍA POR POST
       ----------------------------------------------------- */

    if ( $_SERVER['REQUEST_METHOD'] !== 'POST' ) {
        wp_die( 'Método de envío no válido.' );
    }


    /* -----------------------------------------------------
       2. COMPROBAR EL NONCE DE SEGURIDAD
       ----------------------------------------------------- */

    if (
        ! isset( $_POST['hc_nonce'] ) ||
        ! wp_verify_nonce(
            sanitize_text_field( wp_unslash( $_POST['hc_nonce'] ) ),
            'hc_formulario_info'
        )
    ) {
        wp_die( 'La comprobación de seguridad del formulario ha fallado.' );
    }


    /* -----------------------------------------------------
       3. RECOGER Y SANITIZAR LOS DATOS
       ----------------------------------------------------- */

    $nombre = isset( $_POST['nombre'] )
        ? sanitize_text_field( wp_unslash( $_POST['nombre'] ) )
        : '';

    $apellidos = isset( $_POST['apellidos'] )
        ? sanitize_text_field( wp_unslash( $_POST['apellidos'] ) )
        : '';

    $telefono = isset( $_POST['telefono'] )
        ? sanitize_text_field( wp_unslash( $_POST['telefono'] ) )
        : '';

    $email = isset( $_POST['email'] )
        ? sanitize_email( wp_unslash( $_POST['email'] ) )
        : '';

    $mensaje = isset( $_POST['mensaje'] )
        ? sanitize_textarea_field( wp_unslash( $_POST['mensaje'] ) )
        : '';

    $privacidad = isset( $_POST['privacidad'] );


    /* -----------------------------------------------------
       4. VALIDAR LOS DATOS EN EL SERVIDOR
       ----------------------------------------------------- */

    if ( empty( $nombre ) || empty( $apellidos ) ) {
        wp_die( 'Nombre y apellidos son obligatorios.' );
    }

    if ( ! preg_match( '/^[0-9]{9}$/', $telefono ) ) {
        wp_die( 'El teléfono debe contener exactamente 9 números.' );
    }

    if ( ! is_email( $email ) ) {
        wp_die( 'El correo electrónico no es válido.' );
    }

    if ( ! $privacidad ) {
        wp_die( 'Debes aceptar la política de privacidad.' );
    }


    /* -----------------------------------------------------
       5. PREPARAR EL CORREO
       ----------------------------------------------------- */

    $destinatario = get_option( 'admin_email' );

    $asunto = 'Nueva solicitud de información - Horizonte Camper';

    $cuerpo  = "Nueva solicitud recibida desde la web de Horizonte Camper\n\n";
    $cuerpo .= "Nombre: " . $nombre . "\n";
    $cuerpo .= "Apellidos: " . $apellidos . "\n";
    $cuerpo .= "Teléfono: " . $telefono . "\n";
    $cuerpo .= "Correo electrónico: " . $email . "\n\n";
    $cuerpo .= "Mensaje:\n";
    $cuerpo .= $mensaje . "\n";


    /* -----------------------------------------------------
       6. CABECERAS DEL CORREO
       ----------------------------------------------------- */

    $cabeceras = array(
        'Content-Type: text/plain; charset=UTF-8',
        'Reply-To: ' . $nombre . ' ' . $apellidos . ' <' . $email . '>',
    );


    /* -----------------------------------------------------
       7. ENVIAR EL CORREO
       ----------------------------------------------------- */

    $enviado = wp_mail(
        $destinatario,
        $asunto,
        $cuerpo,
        $cabeceras
    );


    /* -----------------------------------------------------
       8. COMPROBAR EL RESULTADO
       ----------------------------------------------------- */

if ( $enviado ) {

    $url_retorno = add_query_arg(
        'formulario',
        'enviado',
        wp_get_referer()
    );

    wp_safe_redirect( $url_retorno );
    exit;

} else {

    $url_retorno = add_query_arg(
        'formulario',
        'error',
        wp_get_referer()
    );

    wp_safe_redirect( $url_retorno );
    exit;
}

}

/* =========================================================
   SHORTCODE FORMULARIO SOLICITA INFORMACIÓN
   ========================================================= */

function hc_shortcode_formulario_info() {

    ob_start();
    ?>

    <?php
if (
    isset( $_GET['formulario'] ) &&
    $_GET['formulario'] === 'enviado'
) {
    ?>
    <div class="hc-form-mensaje hc-form-mensaje-exito">
        Gracias por contactar con nosotros. Hemos recibido tu solicitud.
    </div>
    <?php
}
?>

    <form
        class="hc-form-info"
        id="hc-form-info-shortcode"
        method="post"
        action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>"
    >

        <h2>Solicita información</h2>

        <input
            type="hidden"
            name="action"
            value="hc_enviar_formulario"
        >

        <?php wp_nonce_field( 'hc_formulario_info', 'hc_nonce' ); ?>

        <div class="hc-form-campo">
            <label for="hc-nombre">Nombre</label>
            <input
                type="text"
                id="hc-nombre"
                name="nombre"
                pattern="[A-Za-zÁÉÍÓÚÜáéíóúüÑñ\s]+"
                required
            >
        </div>

        <div class="hc-form-campo">
            <label for="hc-apellidos">Apellidos</label>
            <input
                type="text"
                id="hc-apellidos"
                name="apellidos"
                pattern="[A-Za-zÁÉÍÓÚÜáéíóúüÑñ\s]+"
                required
            >
        </div>

        <div class="hc-form-campo">
            <label for="hc-telefono">Teléfono</label>
            <input
                type="tel"
                id="hc-telefono"
                name="telefono"
                pattern="[0-9]{9}"
                maxlength="9"
                required
            >
        </div>

        <div class="hc-form-campo">
            <label for="hc-email">Correo electrónico</label>
            <input
                type="email"
                id="hc-email"
                name="email"
                required
            >
        </div>

        <div class="hc-form-campo">
            <label for="hc-mensaje">
                Cuéntanos qué furgoneta tienes y qué idea tienes en mente
            </label>

            <textarea
                id="hc-mensaje"
                name="mensaje"
                rows="5"
                maxlength="500"
            ></textarea>
        </div>

        <div class="hc-form-privacidad">

        <p class="hc-form-demo">
    <strong>Formulario de demostración.</strong>
    Utiliza datos ficticios. Durante las pruebas locales,
    los mensajes se capturan en Mailpit y no se envían
    al correo de contacto.
</p>

            <p>
                Mediante el envío de mis datos personales confirmo que he leído y acepto la
                <a href="<?php echo esc_url( home_url( '/privacy-policy/' ) ); ?>">
                    política de privacidad
                </a>.
            </p>

            <label>
                <input
                    type="checkbox"
                    name="privacidad"
                    value="1"
                    required
                >
                Acepto la política de privacidad
            </label>

        </div>

        <button type="submit" class="hc-form-enviar">
            <span>Enviar</span>
            <span class="hc-form-brujula" aria-hidden="true"></span>
        </button>

    </form>

    <script>
    document.getElementById("hc-form-info-shortcode")
        .addEventListener("submit", function(event) {

            const email = document.getElementById("hc-email").value;

            const confirmar = confirm(
                "¿Confirmas que este es tu correo electrónico?\n\n" + email
            );

            if (!confirmar) {
                event.preventDefault();
            }
        });
    </script>

    <?php

    return ob_get_clean();
}

add_shortcode( 'hc_formulario_info', 'hc_shortcode_formulario_info' );


/* ==========================================================
   CONFIGURADOR CAMPER - DATOS DEL FORMULARIO
========================================================== */

function hc_configurador_meta_shortcode() {

    $html  = '<div class="hc-config-meta"';
    $html .= ' data-action="' . esc_url( admin_url( 'admin-post.php' ) ) . '"';
    $html .= ' style="display:none;">';

    $html .= '<input type="hidden" name="action" value="hc_enviar_configurador">';

    $html .= wp_nonce_field(
        'hc_configurador_camper',
        'hc_config_nonce',
        true,
        false
    );

    $html .= '</div>';

    return $html;
}

add_shortcode(
    'hc_configurador_meta',
    'hc_configurador_meta_shortcode'
);



/* ==========================================================
   CONFIGURADOR CAMPER - PROCESAR FORMULARIO
========================================================== */

function hc_procesar_configurador() {

    /* ------------------------------------------------------
       1. Comprobar método
    ------------------------------------------------------ */

    if ( $_SERVER['REQUEST_METHOD'] !== 'POST' ) {
        wp_die( 'Método no permitido.' );
    }


    /* ------------------------------------------------------
       2. Verificar nonce
    ------------------------------------------------------ */

    if (
        ! isset( $_POST['hc_config_nonce'] ) ||
        ! wp_verify_nonce(
            sanitize_text_field( wp_unslash( $_POST['hc_config_nonce'] ) ),
            'hc_configurador_camper'
        )
    ) {
        wp_die( 'No se pudo verificar la solicitud.' );
    }


    /* ------------------------------------------------------
       3. Funciones auxiliares
    ------------------------------------------------------ */

    $texto = function( $campo ) {

        return isset( $_POST[ $campo ] )
            ? sanitize_text_field( wp_unslash( $_POST[ $campo ] ) )
            : '';
    };


    $lista = function( $campo ) {

        if (
            ! isset( $_POST[ $campo ] ) ||
            ! is_array( $_POST[ $campo ] )
        ) {
            return array();
        }

        return array_map(
            'sanitize_text_field',
            wp_unslash( $_POST[ $campo ] )
        );
    };


    /* ------------------------------------------------------
       4. Vehículo
    ------------------------------------------------------ */

    $vehiculo_modelo     = $texto( 'vehiculo_modelo' );
    $vehiculo_longitud   = $texto( 'vehiculo_longitud' );
    $vehiculo_altura     = $texto( 'vehiculo_altura' );
    $vehiculo_disponible = $texto( 'vehiculo_disponible' );


    /* ------------------------------------------------------
       5. Viajeros y plazas
    ------------------------------------------------------ */

    $viajeros = $texto( 'viajeros' );
    $plazas   = $lista( 'plazas' );


    /* ------------------------------------------------------
       6. Descanso
    ------------------------------------------------------ */

    $plazas_dormir = $texto( 'plazas_dormir' );
    $descanso      = $lista( 'descanso' );


    /* ------------------------------------------------------
       7. Cocina
    ------------------------------------------------------ */

    $cocina = $lista( 'cocina' );


    /* ------------------------------------------------------
       8. Baño y agua
    ------------------------------------------------------ */

    $agua = $lista( 'agua' );


    /* ------------------------------------------------------
       9. Energía
    ------------------------------------------------------ */

    $autonomia = $texto( 'autonomia' );
    $energia   = $lista( 'energia' );


    /* ------------------------------------------------------
       10. Confort
    ------------------------------------------------------ */

    $confort = $lista( 'confort' );


    /* ------------------------------------------------------
       11. Exterior
    ------------------------------------------------------ */

    $exterior = $lista( 'exterior' );


    /* ------------------------------------------------------
       12. Acabados
    ------------------------------------------------------ */

    $estilo_interior = $texto( 'estilo_interior' );
    $acabados        = $lista( 'acabados' );

    $ideas_camper = isset( $_POST['ideas_camper'] )
        ? sanitize_textarea_field(
            wp_unslash( $_POST['ideas_camper'] )
        )
        : '';


    /* ------------------------------------------------------
       13. Datos de contacto
    ------------------------------------------------------ */

    $nombre    = $texto( 'nombre' );
    $apellidos = $texto( 'apellidos' );

    $email = isset( $_POST['email'] )
        ? sanitize_email(
            wp_unslash( $_POST['email'] )
        )
        : '';

    $telefono = $texto( 'telefono' );
    $localidad = $texto( 'localidad' );
    $inicio_proyecto = $texto( 'inicio_proyecto' );


    /* ------------------------------------------------------
       14. Validaciones mínimas
    ------------------------------------------------------ */

    if (
        empty( $nombre ) ||
        empty( $apellidos ) ||
        empty( $email ) ||
        ! is_email( $email ) ||
        empty( $telefono )
    ) {

        $redirect = add_query_arg(
            'configurador',
            'error',
            wp_get_referer()
        );

        wp_safe_redirect( $redirect );
        exit;
    }


    if ( empty( $_POST['privacidad'] ) ) {

        $redirect = add_query_arg(
            'configurador',
            'privacidad',
            wp_get_referer()
        );

        wp_safe_redirect( $redirect );
        exit;
    }


    /* ------------------------------------------------------
       15. Construir correo
    ------------------------------------------------------ */

    $mensaje  = "SOLICITUD DE CAMPERIZACIÓN\n\n";


    $mensaje .= "VEHÍCULO\n";
    $mensaje .= "Modelo: " . $vehiculo_modelo . "\n";
    $mensaje .= "Longitud: " . $vehiculo_longitud . "\n";
    $mensaje .= "Altura: " . $vehiculo_altura . "\n";
    $mensaje .= "¿Ya tiene furgoneta?: " . $vehiculo_disponible . "\n\n";


    $mensaje .= "VIAJEROS Y PLAZAS\n";
    $mensaje .= "Viajeros habituales: " . $viajeros . "\n";

    if ( ! empty( $plazas ) ) {
        $mensaje .= "- " . implode( "\n- ", $plazas ) . "\n";
    }

    $mensaje .= "\n";


    $mensaje .= "DESCANSO\n";
    $mensaje .= "Plazas para dormir: " . $plazas_dormir . "\n";

    if ( ! empty( $descanso ) ) {
        $mensaje .= "- " . implode( "\n- ", $descanso ) . "\n";
    }

    $mensaje .= "\n";


    $mensaje .= "COCINA\n";

    if ( ! empty( $cocina ) ) {
        $mensaje .= "- " . implode( "\n- ", $cocina ) . "\n";
    }

    $mensaje .= "\n";


    $mensaje .= "BAÑO Y AGUA\n";

    if ( ! empty( $agua ) ) {
        $mensaje .= "- " . implode( "\n- ", $agua ) . "\n";
    }

    $mensaje .= "\n";


    $mensaje .= "ENERGÍA Y AUTONOMÍA\n";
    $mensaje .= "Autonomía: " . $autonomia . "\n";

    if ( ! empty( $energia ) ) {
        $mensaje .= "- " . implode( "\n- ", $energia ) . "\n";
    }

    $mensaje .= "\n";


    $mensaje .= "CONFORT\n";

    if ( ! empty( $confort ) ) {
        $mensaje .= "- " . implode( "\n- ", $confort ) . "\n";
    }

    $mensaje .= "\n";


    $mensaje .= "EXTERIOR\n";

    if ( ! empty( $exterior ) ) {
        $mensaje .= "- " . implode( "\n- ", $exterior ) . "\n";
    }

    $mensaje .= "\n";


    $mensaje .= "ACABADOS\n";
    $mensaje .= "Estilo interior: " . $estilo_interior . "\n";

    if ( ! empty( $acabados ) ) {
        $mensaje .= "- " . implode( "\n- ", $acabados ) . "\n";
    }

    if ( ! empty( $ideas_camper ) ) {
        $mensaje .= "\nIdeas del cliente:\n";
        $mensaje .= $ideas_camper . "\n";
    }

    $mensaje .= "\n";


    $mensaje .= "DATOS DEL CLIENTE\n";
    $mensaje .= "Nombre: " . $nombre . " " . $apellidos . "\n";
    $mensaje .= "Email: " . $email . "\n";
    $mensaje .= "Teléfono: " . $telefono . "\n";
    $mensaje .= "Localidad: " . $localidad . "\n";
    $mensaje .= "Inicio del proyecto: " . $inicio_proyecto . "\n";


    /* ------------------------------------------------------
       16. Enviar correo
    ------------------------------------------------------ */

    $destinatario = get_option( 'admin_email' );

    $asunto = 'Nueva solicitud de camperización';

    $cabeceras = array(
        'Reply-To: ' . $nombre . ' ' . $apellidos . ' <' . $email . '>',
    );

    $enviado = wp_mail(
        $destinatario,
        $asunto,
        $mensaje,
        $cabeceras
    );


    /* ------------------------------------------------------
       17. Redirección
    ------------------------------------------------------ */

    $estado = $enviado ? 'enviado' : 'error';

    $redirect = add_query_arg(
        'configurador',
        $estado,
        wp_get_referer()
    );

    wp_safe_redirect( $redirect );
    exit;
}


/* Usuario conectado */
add_action(
    'admin_post_hc_enviar_configurador',
    'hc_procesar_configurador'
);


/* Usuario no conectado */
add_action(
    'admin_post_nopriv_hc_enviar_configurador',
    'hc_procesar_configurador'
);


/* ==========================================================
   CONFIGURADOR CAMPER - MENSAJES DE ESTADO
========================================================== */

function hc_configurador_mensaje_shortcode() {

    if ( ! isset( $_GET['configurador'] ) ) {
        return '';
    }

    $estado = sanitize_text_field(
        wp_unslash( $_GET['configurador'] )
    );

    if ( $estado === 'enviado' ) {

        return '
            <div class="hc-config-mensaje hc-config-mensaje-exito">
                Gracias. Hemos recibido tu solicitud de camperización.
                Revisaremos la configuración y nos pondremos en contacto contigo.
            </div>
        ';
    }

    if ( $estado === 'privacidad' ) {

        return '
            <div class="hc-config-mensaje hc-config-mensaje-error">
                Debes aceptar la política de privacidad para enviar la solicitud.
            </div>
        ';
    }

    if ( $estado === 'error' ) {

        return '
            <div class="hc-config-mensaje hc-config-mensaje-error">
                No hemos podido enviar la solicitud. Revisa los datos e inténtalo de nuevo.
            </div>
        ';
    }

    return '';
}

add_shortcode(
    'hc_configurador_mensaje',
    'hc_configurador_mensaje_shortcode'
);


/* ==========================================================
   FOOTER - ÚLTIMAS ENTRADAS DINÁMICAS
========================================================== */

function hc_footer_ultimas_entradas_shortcode() {

    $query = new WP_Query( array(
        'post_type'           => 'post',
        'post_status'         => 'publish',
        'posts_per_page'      => 3,
        'ignore_sticky_posts' => true,
        'orderby'             => 'date',
        'order'               => 'DESC',
    ) );

    if ( ! $query->have_posts() ) {
        return '';
    }

    ob_start();
    ?>

    <div class="hc-footer-posts">

        <?php while ( $query->have_posts() ) : $query->the_post(); ?>

            <article class="hc-footer-post">

                <a
                    class="hc-footer-post-img"
                    href="<?php echo esc_url( get_permalink() ); ?>"
                    aria-label="<?php echo esc_attr( get_the_title() ); ?>"
                >

                    <?php if ( has_post_thumbnail() ) : ?>

                        <?php
                        the_post_thumbnail(
                            'thumbnail',
                            array(
                                'alt'     => esc_attr( get_the_title() ),
                                'loading' => 'lazy',
                            )
                        );
                        ?>

                    <?php endif; ?>

                </a>


                <div class="hc-footer-post-info">

                    <a href="<?php echo esc_url( get_permalink() ); ?>">
                        <?php echo esc_html( get_the_title() ); ?>
                    </a>

                </div>

            </article>

        <?php endwhile; ?>

    </div>

    <?php

    wp_reset_postdata();

    return ob_get_clean();
}

add_shortcode(
    'hc_ultimas_entradas_footer',
    'hc_footer_ultimas_entradas_shortcode'
);


/* =========================================================
   HORIZONTE CAMPER · FORMULARIO DE RESERVA
   Shortcode: [hc_formulario_reserva]
   ========================================================= */

function hc_formulario_reserva_shortcode() {

    // =====================================================
    // ESTADO DE LA RESERVA
    // =====================================================

    $estado_reserva = isset($_GET['reserva'])
        ? sanitize_key(wp_unslash($_GET['reserva']))
        : '';


    // =====================================================
    // ESTADO DEL PAGO
    // =====================================================

    $estado_pago = isset($_GET['pago'])
        ? sanitize_key(wp_unslash($_GET['pago']))
        : '';

    $session_id = isset($_GET['session_id'])
        ? sanitize_text_field(wp_unslash($_GET['session_id']))
        : '';

    $pago_confirmado = false;


    // Si Stripe devuelve pago OK,
    // comprobamos que la sesión existe y está pagada.

    if (
        $estado_pago === 'ok' &&
        ! empty($session_id)
    ) {

        $sesion_stripe =
            hc_confirmar_pago_stripe($session_id);

        if ($sesion_stripe) {

    $pago_confirmado = true;

    hc_enviar_correo_reserva_pagada(
        $sesion_stripe
    );
}

    }


    ob_start();
    ?>


    <?php if ($estado_reserva === 'ok') : ?>

        <div class="hc-reserva-aviso hc-reserva-aviso-ok">

            <strong>
                Solicitud enviada correctamente.
            </strong>

            <p>
                Hemos recibido los datos de tu reserva.
                Revisaremos la disponibilidad y nos pondremos
                en contacto contigo para confirmarla.
            </p>

        </div>


    <?php elseif ($estado_reserva === 'error') : ?>

        <div class="hc-reserva-aviso hc-reserva-aviso-error">

            <strong>
                No hemos podido enviar la solicitud.
            </strong>

            <p>
                Revisa los datos e inténtalo de nuevo.
                Si el problema continúa, ponte en contacto con nosotros.
            </p>

        </div>

    <?php endif; ?>


    <?php if ($pago_confirmado) : ?>

    <div class="hc-pago-modal" id="hc-pago-modal">

        <div class="hc-pago-modal-contenido">

            <button
                type="button"
                class="hc-pago-modal-cerrar"
                aria-label="Cerrar"
            >
                ×
            </button>

            <span class="hc-reserva-eyebrow">
                RESERVA CONFIRMADA
            </span>

            <h2>
                Pago realizado correctamente
            </h2>

            <p>
                Hemos recibido la señal de tu reserva.
                Nos pondremos en contacto contigo para confirmar
                los últimos detalles del viaje.
            </p>

            <button
                type="button"
                class="hc-pago-modal-boton"
            >
                Entendido
            </button>

        </div>

    </div>


    <?php elseif ($estado_pago === 'cancelado') : ?>

        <div class="hc-reserva-aviso hc-reserva-aviso-error">

            <strong>
                El pago no se ha completado.
            </strong>

            <p>
                Puedes volver a intentarlo cuando quieras.
                Tu reserva no se ha confirmado todavía.
            </p>

        </div>

    <?php endif; ?>


    <section class="hc-reserva-formulario" hidden>

        <div class="hc-reserva-formulario-cabecera">

            <span class="hc-reserva-eyebrow">
                DATOS DE RESERVA
            </span>

            <h2>Completa tu solicitud</h2>

            <p>
                Déjanos tus datos y revisaremos la disponibilidad
                antes de confirmar la reserva.
            </p>

        </div>


        <form
            class="hc-form-reserva"
            id="hc-form-reserva"
            method="post"
            action="<?php echo esc_url(admin_url('admin-post.php')); ?>"
        >

            <input
                type="hidden"
                name="action"
                value="hc_pago_stripe"
            >


            <?php
            wp_nonce_field(
                'hc_reserva_nonce_action',
                'hc_reserva_nonce'
            );
            ?>


            <div class="hc-form-grid">

                <div class="hc-form-campo">

                    <label for="hc-nombre">
                        Nombre
                    </label>

                    <input
                        type="text"
                        id="hc-nombre"
                        name="nombre"
                        required
                    >

                </div>


                <div class="hc-form-campo">

                    <label for="hc-apellidos">
                        Apellidos
                    </label>

                    <input
                        type="text"
                        id="hc-apellidos"
                        name="apellidos"
                        required
                    >

                </div>


                <div class="hc-form-campo">

                    <label for="hc-email">
                        Correo electrónico
                    </label>

                    <input
                        type="email"
                        id="hc-email"
                        name="email"
                        required
                    >

                </div>


                <div class="hc-form-campo">

                    <label for="hc-telefono">
                        Teléfono
                    </label>

                    <input
                        type="tel"
                        id="hc-telefono"
                        name="telefono"
                        required
                    >

                </div>


                <div class="hc-form-campo">

                    <label for="hc-viajeros">
                        Número de viajeros
                    </label>

                    <input
                        type="number"
                        id="hc-viajeros"
                        name="viajeros"
                        min="1"
                        max="4"
                        required
                    >

                </div>


                <div class="hc-form-campo">

                    <label for="hc-mensaje">
                        Observaciones
                    </label>

                    <textarea
                        id="hc-mensaje"
                        name="mensaje"
                        rows="4"
                        placeholder="Cuéntanos cualquier detalle que debamos tener en cuenta."
                    ></textarea>

                </div>

            </div>


            <!-- Datos generados por el sistema de reserva -->

            <input
                type="hidden"
                id="hc-form-vehiculo"
                name="vehiculo"
            >

            <input
                type="hidden"
                id="hc-form-inicio"
                name="fecha_inicio"
            >

            <input
                type="hidden"
                id="hc-form-fin"
                name="fecha_fin"
            >

            <input
                type="hidden"
                id="hc-form-dias"
                name="dias"
            >

            <input
                type="hidden"
                id="hc-form-precio"
                name="precio_estimado"
            >


                <p class="hc-form-demo">
                <strong>Reserva de demostración.</strong>
                Utiliza datos ficticios y tarjetas de prueba.
                No se realizan cobros reales ni se reserva
                ningún vehículo.
            </p>

            <label class="hc-form-privacidad">
                <input
                    type="checkbox"
                    name="privacidad"
                    value="1"
                    required
                >

                <span>
                    He leído y acepto la
                    <a
                        href="<?php echo esc_url( home_url( '/privacy-policy/' ) ); ?>"
                        target="_blank"
                        rel="noopener noreferrer"
                        style="text-decoration: underline;"
                    >política de privacidad</a>.
                </span>
            </label>

            <button
                type="submit"
                class="hc-form-enviar"
            >
                Pagar señal y reservar
            </button>

        </form>

    </section>

    <?php

    return ob_get_clean();

}

add_shortcode(
    'hc_formulario_reserva',
    'hc_formulario_reserva_shortcode'
);
/* =========================================================
   HORIZONTE CAMPER · PROCESAR RESERVA
   ========================================================= */

function hc_procesar_reserva() {

    // Comprobación de seguridad.
    if (
        ! isset($_POST['hc_reserva_nonce']) ||
        ! wp_verify_nonce(
            sanitize_text_field(
                wp_unslash($_POST['hc_reserva_nonce'])
            ),
            'hc_reserva_nonce_action'
        )
    ) {
        wp_die('Error de seguridad al enviar la reserva.');
    }


    // =====================================================
    // RECOGER Y SANITIZAR DATOS
    // =====================================================

    $nombre = isset($_POST['nombre'])
        ? sanitize_text_field(wp_unslash($_POST['nombre']))
        : '';

    $apellidos = isset($_POST['apellidos'])
        ? sanitize_text_field(wp_unslash($_POST['apellidos']))
        : '';

    $email = isset($_POST['email'])
        ? sanitize_email(wp_unslash($_POST['email']))
        : '';

    $telefono = isset($_POST['telefono'])
        ? sanitize_text_field(wp_unslash($_POST['telefono']))
        : '';

    $viajeros = isset($_POST['viajeros'])
        ? absint($_POST['viajeros'])
        : 0;

    $mensaje = isset($_POST['mensaje'])
        ? sanitize_textarea_field(wp_unslash($_POST['mensaje']))
        : '';

    $vehiculo = isset($_POST['vehiculo'])
        ? sanitize_text_field(wp_unslash($_POST['vehiculo']))
        : '';

    $fecha_inicio = isset($_POST['fecha_inicio'])
        ? sanitize_text_field(wp_unslash($_POST['fecha_inicio']))
        : '';

    $fecha_fin = isset($_POST['fecha_fin'])
        ? sanitize_text_field(wp_unslash($_POST['fecha_fin']))
        : '';

    $dias = isset($_POST['dias'])
        ? absint($_POST['dias'])
        : 0;

    $precio = isset($_POST['precio_estimado'])
        ? floatval($_POST['precio_estimado'])
        : 0;

    $privacidad = isset($_POST['privacidad'])
        ? absint($_POST['privacidad'])
        : 0;


    // =====================================================
    // VALIDACIÓN
    // =====================================================

    $hay_error = false;

    if (
        empty($nombre) ||
        empty($apellidos) ||
        empty($email) ||
        empty($telefono) ||
        empty($vehiculo) ||
        empty($fecha_inicio) ||
        empty($fecha_fin) ||
        $dias < 1 ||
        $viajeros < 1 ||
        $viajeros > 4 ||
        ! is_email($email) ||
        $privacidad !== 1
    ) {
        $hay_error = true;
    }


    // =====================================================
    // REDIRECCIÓN SI HAY ERROR
    // =====================================================

    if ($hay_error) {

        $url = wp_get_referer();

        if (! $url) {
            $url = home_url('/');
        }

        $url = add_query_arg(
            'reserva',
            'error',
            $url
        );

        wp_safe_redirect($url);
        exit;
    }


    // =====================================================
    // PREPARAR EMAIL
    // =====================================================

    $destinatario = get_option('admin_email');

    $asunto =
        'Nueva solicitud de reserva · ' .
        $vehiculo;


    $contenido  = "NUEVA SOLICITUD DE RESERVA\n\n";

    $contenido .= "DATOS DEL CLIENTE\n";
    $contenido .= "-------------------------\n";

    $contenido .=
        "Nombre: " .
        $nombre . ' ' . $apellidos . "\n";

    $contenido .=
        "Email: " .
        $email . "\n";

    $contenido .=
        "Teléfono: " .
        $telefono . "\n";

    $contenido .=
        "Viajeros: " .
        $viajeros . "\n\n";


    $contenido .= "DATOS DE LA RESERVA\n";
    $contenido .= "-------------------------\n";

    $contenido .=
        "Vehículo: " .
        $vehiculo . "\n";

    $contenido .=
        "Recogida: " .
        $fecha_inicio . "\n";

    $contenido .=
        "Devolución: " .
        $fecha_fin . "\n";

    $contenido .=
        "Días: " .
        $dias . "\n";

    $contenido .=
        "Precio estimado: " .
        number_format($precio, 2, ',', '.') .
        " €\n\n";


    if (! empty($mensaje)) {

        $contenido .= "OBSERVACIONES\n";
        $contenido .= "-------------------------\n";
        $contenido .= $mensaje . "\n";

    }


    // =====================================================
    // CABECERAS
    // =====================================================

    $cabeceras = array(
        'Content-Type: text/plain; charset=UTF-8',
        'Reply-To: ' . $nombre . ' ' . $apellidos . ' <' . $email . '>'
    );


    // =====================================================
    // ENVIAR
    // =====================================================

    $enviado = wp_mail(
        $destinatario,
        $asunto,
        $contenido,
        $cabeceras
    );


    // =====================================================
    // REDIRECCIÓN FINAL
    // =====================================================

    $url = wp_get_referer();

    if (! $url) {
        $url = home_url('/');
    }

    $url = add_query_arg(
        'reserva',
        $enviado ? 'ok' : 'error',
        $url
    );

    wp_safe_redirect($url);
    exit;
}



/* =========================================================
   HORIZONTE CAMPER · CALCULAR PRECIO EN SERVIDOR
   ========================================================= */

function hc_calcular_precio_reserva(
    $vehiculo,
    $fecha_inicio,
    $fecha_fin
) {

    $tarifas = array(

        'Volkswagen T6' => array(
            'baja'  => 95,
            'media' => 110,
            'alta'  => 125,
        ),

        'Fiat Ducato L2H2' => array(
            'baja'  => 110,
            'media' => 125,
            'alta'  => 145,
        ),

        'Fiat Ducato L3H2' => array(
            'baja'  => 125,
            'media' => 145,
            'alta'  => 165,
        ),

        'Mercedes Sprinter L3H2' => array(
            'baja'  => 145,
            'media' => 165,
            'alta'  => 190,
        ),

    );


    if (! isset($tarifas[$vehiculo])) {
        return false;
    }


    try {

        $inicio = new DateTime($fecha_inicio);
        $fin    = new DateTime($fecha_fin);

    } catch (Exception $e) {

        return false;

    }


    if ($fin <= $inicio) {
        return false;
    }


    $precio_total = 0;
    $dias = 0;

    $fecha = clone $inicio;


    while ($fecha < $fin) {

        $mes = (int) $fecha->format('n');
        $dia = (int) $fecha->format('j');

        $temporada = 'media';


        // TEMPORADA ALTA

        if (
            ($mes === 12 && $dia >= 21) ||
            ($mes === 1 && $dia <= 7) ||
            ($mes === 6 && $dia >= 15) ||
            ($mes >= 7 && $mes <= 8) ||
            ($mes === 9 && $dia <= 15)
        ) {

            $temporada = 'alta';

        }


        // TEMPORADA BAJA

        elseif (
            ($mes === 1 && $dia >= 8) ||
            ($mes === 2) ||
            ($mes === 3) ||
            ($mes === 11) ||
            ($mes === 12 && $dia <= 20)
        ) {

            $temporada = 'baja';

        }


        $precio_total +=
            $tarifas[$vehiculo][$temporada];

        $dias++;

        $fecha->modify('+1 day');

    }


    return array(
        'precio_total' => $precio_total,
        'dias'         => $dias,
        'senal'        => round($precio_total * 0.25, 2),
    );

}



/* =========================================================
   HORIZONTE CAMPER · CREAR SESIÓN DE PAGO STRIPE
   ========================================================= */

function hc_crear_sesion_stripe() {

    // =====================================================
    // SEGURIDAD
    // =====================================================

    if (
        ! isset($_POST['hc_reserva_nonce']) ||
        ! wp_verify_nonce(
            sanitize_text_field(
                wp_unslash($_POST['hc_reserva_nonce'])
            ),
            'hc_reserva_nonce_action'
        )
    ) {
        wp_die('Error de seguridad al procesar el pago.');
    }


    if (
        ! defined('HC_STRIPE_SECRET_KEY') ||
        empty(HC_STRIPE_SECRET_KEY)
    ) {
        wp_die('Stripe no está configurado correctamente.');
    }


    // =====================================================
    // DATOS DE LA RESERVA
    // =====================================================

    $nombre = isset($_POST['nombre'])
        ? sanitize_text_field(wp_unslash($_POST['nombre']))
        : '';

    $apellidos = isset($_POST['apellidos'])
        ? sanitize_text_field(wp_unslash($_POST['apellidos']))
        : '';

    $email = isset($_POST['email'])
        ? sanitize_email(wp_unslash($_POST['email']))
        : '';

    $telefono = isset($_POST['telefono'])
        ? sanitize_text_field(wp_unslash($_POST['telefono']))
        : '';

    $viajeros = isset($_POST['viajeros'])
        ? absint($_POST['viajeros'])
        : 0;

    $vehiculo = isset($_POST['vehiculo'])
        ? sanitize_text_field(wp_unslash($_POST['vehiculo']))
        : '';

    $fecha_inicio = isset($_POST['fecha_inicio'])
        ? sanitize_text_field(wp_unslash($_POST['fecha_inicio']))
        : '';

    $fecha_fin = isset($_POST['fecha_fin'])
        ? sanitize_text_field(wp_unslash($_POST['fecha_fin']))
        : '';

    $privacidad = isset($_POST['privacidad'])
        ? absint($_POST['privacidad'])
        : 0;


    // =====================================================
    // VALIDACIÓN
    // =====================================================

    if (
        empty($nombre) ||
        empty($apellidos) ||
        ! is_email($email) ||
        empty($telefono) ||
        empty($vehiculo) ||
        empty($fecha_inicio) ||
        empty($fecha_fin) ||
        $viajeros < 1 ||
        $viajeros > 4 ||
        $privacidad !== 1
    ) {
        wp_die('Los datos de la reserva no son válidos.');
    }


    // =====================================================
    // CALCULAR PRECIO EN EL SERVIDOR
    // =====================================================

    $calculo = hc_calcular_precio_reserva(
        $vehiculo,
        $fecha_inicio,
        $fecha_fin
    );


    if (! $calculo) {
        wp_die('No se ha podido calcular el precio de la reserva.');
    }


    $precio_total = $calculo['precio_total'];
    $dias         = $calculo['dias'];
    $senal        = $calculo['senal'];


    // Stripe trabaja en céntimos.
    $importe_stripe = (int) round($senal * 100);


    // =====================================================
    // PÁGINA A LA QUE VOLVER
    // =====================================================

    $pagina_alquiler = get_permalink(
    get_page_by_path('alquiler-de-campers-en-asturias')
);

if (! $pagina_alquiler) {
    $pagina_alquiler = home_url('/');
}


    $url_ok = add_query_arg(
    'pago',
    'ok',
    $pagina_alquiler
);

$url_cancelado = add_query_arg(
    'pago',
    'cancelado',
    $pagina_alquiler
);


    // =====================================================
    // STRIPE
    // =====================================================

    $stripe = new \Stripe\StripeClient(
        HC_STRIPE_SECRET_KEY
    );


    try {

        $sesion = $stripe->checkout->sessions->create([

            'mode' => 'payment',

            'customer_email' => $email,

            'line_items' => [[

                'price_data' => [

                    'currency' => 'eur',

                    'product_data' => [

                        'name' =>
                            'Señal reserva · ' .
                            $vehiculo,

                        'description' =>
                            $fecha_inicio .
                            ' → ' .
                            $fecha_fin,

                    ],

                    'unit_amount' => $importe_stripe,

                ],

                'quantity' => 1,

            ]],


            // Guardamos los datos para utilizarlos
            // posteriormente al confirmar el pago.

            'metadata' => [

                'vehiculo'      => $vehiculo,
                'fecha_inicio'  => $fecha_inicio,
                'fecha_fin'     => $fecha_fin,
                'dias'          => (string) $dias,
                'precio_total'  => (string) $precio_total,
                'senal'         => (string) $senal,
                'nombre'        => $nombre,
                'apellidos'     => $apellidos,
                'telefono'      => $telefono,
                'viajeros'      => (string) $viajeros,

            ],


            'success_url' =>
                $url_ok .
                '&session_id={CHECKOUT_SESSION_ID}',

            'cancel_url' =>
                $url_cancelado,

            'locale' => 'es',

        ]);


        wp_redirect(
            $sesion->url
        );

        exit;


    } catch (\Exception $e) {

        wp_die(
            'Error al conectar con Stripe: ' .
            esc_html($e->getMessage())
        );

    }

}

$session_id = isset($_GET['session_id'])
    ? sanitize_text_field(wp_unslash($_GET['session_id']))
    : '';


/* =========================================================
   HORIZONTE CAMPER · CONFIRMAR PAGO STRIPE
   ========================================================= */

function hc_confirmar_pago_stripe($session_id) {

    if (
        ! defined('HC_STRIPE_SECRET_KEY') ||
        empty(HC_STRIPE_SECRET_KEY) ||
        empty($session_id)
    ) {
        return false;
    }

    try {

        $stripe = new \Stripe\StripeClient(
            HC_STRIPE_SECRET_KEY
        );

        $sesion = $stripe->checkout->sessions->retrieve(
            $session_id
        );

        if ($sesion->payment_status !== 'paid') {
            return false;
        }

        return $sesion;

    } catch (\Exception $e) {

        return false;

    }

}


add_action(
    'admin_post_nopriv_hc_pago_stripe',
    'hc_crear_sesion_stripe'
);

add_action(
    'admin_post_hc_pago_stripe',
    'hc_crear_sesion_stripe'
);




/* =========================================================
   HORIZONTE CAMPER · EMAIL TRAS PAGO CONFIRMADO
   ========================================================= */

function hc_enviar_correo_reserva_pagada($sesion) {

    if (! $sesion || empty($sesion->id)) {
        return false;
    }


    // Evita enviar el mismo correo varias veces
    // si el usuario recarga la página de confirmación.
    $clave_envio =
        'hc_reserva_pagada_' .
        md5($sesion->id);

    if (get_option($clave_envio)) {
        return true;
    }


    // =====================================================
    // DATOS GUARDADOS EN STRIPE
    // =====================================================

    $vehiculo =
        $sesion->metadata->vehiculo ?? '';

    $fecha_inicio =
        $sesion->metadata->fecha_inicio ?? '';

    $fecha_fin =
        $sesion->metadata->fecha_fin ?? '';

    $dias =
        $sesion->metadata->dias ?? '';

    $precio_total =
        $sesion->metadata->precio_total ?? '';

    $senal =
        $sesion->metadata->senal ?? '';

    $nombre =
        $sesion->metadata->nombre ?? '';

    $apellidos =
        $sesion->metadata->apellidos ?? '';

    $telefono =
        $sesion->metadata->telefono ?? '';

    $viajeros =
        $sesion->metadata->viajeros ?? '';


    // Email del cliente.
    $email = '';

    if (
        isset($sesion->customer_details) &&
        ! empty($sesion->customer_details->email)
    ) {

        $email =
            sanitize_email(
                $sesion->customer_details->email
            );

    } elseif (! empty($sesion->customer_email)) {

        $email =
            sanitize_email(
                $sesion->customer_email
            );
    }


    // =====================================================
    // PREPARAR CORREO
    // =====================================================

    $destinatario =
        get_option('admin_email');


    $asunto =
        'Reserva pagada · ' .
        $vehiculo;


    $contenido  =
        "RESERVA CON SEÑAL PAGADA\n\n";


    $contenido .=
        "DATOS DEL CLIENTE\n";

    $contenido .=
        "-------------------------\n";

    $contenido .=
        "Nombre: " .
        $nombre . ' ' . $apellidos . "\n";

    $contenido .=
        "Email: " .
        $email . "\n";

    $contenido .=
        "Teléfono: " .
        $telefono . "\n";

    $contenido .=
        "Viajeros: " .
        $viajeros . "\n\n";


    $contenido .=
        "DATOS DE LA RESERVA\n";

    $contenido .=
        "-------------------------\n";

    $contenido .=
        "Vehículo: " .
        $vehiculo . "\n";

    $contenido .=
        "Recogida: " .
        $fecha_inicio . "\n";

    $contenido .=
        "Devolución: " .
        $fecha_fin . "\n";

    $contenido .=
        "Días: " .
        $dias . "\n";

    $contenido .=
        "Precio total: " .
        number_format(
            (float) $precio_total,
            2,
            ',',
            '.'
        ) .
        " €\n";

    $contenido .=
        "Señal pagada: " .
        number_format(
            (float) $senal,
            2,
            ',',
            '.'
        ) .
        " €\n\n";


    $contenido .=
        "Stripe Session ID: " .
        $sesion->id .
        "\n";


    // =====================================================
    // CABECERAS
    // =====================================================

    $cabeceras = array(
        'Content-Type: text/plain; charset=UTF-8'
    );

    if ($email) {

        $cabeceras[] =
            'Reply-To: ' .
            $nombre . ' ' .
            $apellidos .
            ' <' . $email . '>';

    }


    // =====================================================
    // ENVIAR
    // =====================================================

    $enviado = wp_mail(
        $destinatario,
        $asunto,
        $contenido,
        $cabeceras
    );


    // Solo marcamos como enviado
    // si wp_mail() ha funcionado.
    if ($enviado) {

        add_option(
            $clave_envio,
            current_time('mysql'),
            '',
            false
        );

    }


    return $enviado;
}






// Usuario no conectado.
add_action(
    'admin_post_nopriv_hc_enviar_reserva',
    'hc_procesar_reserva'
);


// Usuario conectado.
add_action(
    'admin_post_hc_enviar_reserva',
    'hc_procesar_reserva'
);

/* ==========================================================
   HORIZONTE CAMPER · SCHEMA FAQ
   Página: Preguntas frecuentes
========================================================== */

function hc_schema_faq_page() {

    // Solo cargar este schema en la página FAQ.
    if ( ! is_page( 'preguntas-frecuentes-camperizacion-alquiler' ) ) {
        return;
    }

    $faq = array(

        /* ==================================================
           CAMPERIZACIÓN
        ================================================== */

        array(
            '@type' => 'Question',
            'name'  => '¿Qué tipo de furgonetas camperizáis?',
            'acceptedAnswer' => array(
                '@type' => 'Answer',
                'text'  => 'Trabajamos con diferentes modelos y tamaños de furgoneta, desde vehículos compactos hasta grandes volúmenes. Antes de comenzar estudiamos el vehículo, el uso que quieres darle y las necesidades de cada viaje para plantear una distribución y un equipamiento adecuados.'
            ),
        ),

        array(
            '@type' => 'Question',
            'name'  => '¿Puedo camperizar una furgoneta que ya tengo?',
            'acceptedAnswer' => array(
                '@type' => 'Answer',
                'text'  => 'Sí. Podemos trabajar sobre una furgoneta que ya tengas siempre que su estado y características permitan realizar el proyecto con seguridad. Antes de comenzar revisamos el vehículo y valoramos contigo qué soluciones encajan mejor.'
            ),
        ),

        array(
            '@type' => 'Question',
            'name'  => '¿También vendéis furgonetas ya camperizadas?',
            'acceptedAnswer' => array(
                '@type' => 'Answer',
                'text'  => 'Sí. Además de camperizar vehículos de clientes, podemos disponer de proyectos propios ya preparados o en proceso de construcción. La disponibilidad puede variar, por lo que conviene consultar los modelos disponibles en cada momento.'
            ),
        ),

        array(
            '@type' => 'Question',
            'name'  => '¿Puedo elegir la distribución interior?',
            'acceptedAnswer' => array(
                '@type' => 'Answer',
                'text'  => 'Sí. La distribución se diseña teniendo en cuenta el número de viajeros, el tipo de viajes que haces, tus prioridades de descanso, cocina, almacenaje o baño y las posibilidades reales del vehículo.'
            ),
        ),

        array(
            '@type' => 'Question',
            'name'  => '¿Qué nivel de personalización puedo tener?',
            'acceptedAnswer' => array(
                '@type' => 'Answer',
                'text'  => 'Cada proyecto puede adaptarse en distribución, acabados, materiales, iluminación, almacenaje y equipamiento. Nuestro objetivo es que el interior sea funcional y coherente con tu forma de viajar.'
            ),
        ),

        array(
            '@type' => 'Question',
            'name'  => '¿Qué instalaciones y equipamientos puedo añadir?',
            'acceptedAnswer' => array(
                '@type' => 'Answer',
                'text'  => 'Podemos integrar soluciones de energía, agua, climatización, cocina, almacenaje, iluminación y equipamiento exterior, entre otras. El proyecto se configura en función de tus necesidades y del espacio disponible.'
            ),
        ),

        array(
            '@type' => 'Question',
            'name'  => '¿Trabajáis con placas solares, baterías, calefacción y agua?',
            'acceptedAnswer' => array(
                '@type' => 'Answer',
                'text'  => 'Sí. Diseñamos e instalamos sistemas de autonomía eléctrica, placas solares, baterías auxiliares, calefacción estacionaria, depósitos de agua, bombas, tomas y otros componentes habituales en una camper.'
            ),
        ),

        array(
            '@type' => 'Question',
            'name'  => '¿Qué materiales utilizáis en la camperización?',
            'acceptedAnswer' => array(
                '@type' => 'Answer',
                'text'  => 'Seleccionamos materiales pensados para soportar el uso, las vibraciones y los cambios de temperatura propios de un vehículo. Buscamos equilibrio entre resistencia, peso, mantenimiento, estética y durabilidad.'
            ),
        ),

        array(
            '@type' => 'Question',
            'name'  => '¿La camperización incluye la homologación?',
            'acceptedAnswer' => array(
                '@type' => 'Answer',
                'text'  => 'Cuando el proyecto requiere homologación, se planifica teniendo en cuenta la normativa aplicable desde el principio. Te informamos de los elementos que deben legalizarse y coordinamos el proceso necesario para que la transformación quede correctamente documentada.'
            ),
        ),

        array(
            '@type' => 'Question',
            'name'  => '¿Cuánto tiempo tarda una camperización?',
            'acceptedAnswer' => array(
                '@type' => 'Answer',
                'text'  => 'Depende del alcance del proyecto, del modelo de furgoneta y del equipamiento elegido. Antes de comenzar te indicamos una previsión de plazos adaptada al trabajo que vayamos a realizar.'
            ),
        ),

        array(
            '@type' => 'Question',
            'name'  => '¿Puedo hacer la camperización por fases?',
            'acceptedAnswer' => array(
                '@type' => 'Answer',
                'text'  => 'Sí, siempre que el proyecto pueda dividirse de forma lógica y segura. Podemos plantear una primera fase esencial y dejar previstas futuras instalaciones para evitar rehacer trabajos más adelante.'
            ),
        ),

        array(
            '@type' => 'Question',
            'name'  => '¿Cómo se calcula el presupuesto?',
            'acceptedAnswer' => array(
                '@type' => 'Answer',
                'text'  => 'El presupuesto depende del vehículo, la distribución, los materiales, las instalaciones y el nivel de equipamiento. Por eso primero necesitamos conocer tu idea y tus prioridades antes de preparar una propuesta.'
            ),
        ),

        array(
            '@type' => 'Question',
            'name'  => '¿Hay un presupuesto inicial sin compromiso?',
            'acceptedAnswer' => array(
                '@type' => 'Answer',
                'text'  => 'Sí. Puedes contarnos qué vehículo tienes y qué necesitas mediante nuestro configurador o formulario de contacto. Con esa información podemos hacer una primera valoración del proyecto antes de avanzar.'
            ),
        ),

        array(
            '@type' => 'Question',
            'name'  => '¿Puedo traer ideas, planos o referencias de otros proyectos?',
            'acceptedAnswer' => array(
                '@type' => 'Answer',
                'text'  => 'Por supuesto. Fotografías, bocetos, planos o referencias nos ayudan a entender lo que buscas. Después valoramos qué ideas son viables en tu vehículo y cómo adaptarlas para que funcionen correctamente.'
            ),
        ),

        array(
            '@type' => 'Question',
            'name'  => '¿Trabajáis con clientes de fuera de Asturias?',
            'acceptedAnswer' => array(
                '@type' => 'Answer',
                'text'  => 'Sí. Trabajamos con proyectos de distintos puntos de España. Podemos organizar contigo la entrega, el seguimiento del proyecto y la recogida del vehículo para que la distancia no sea un problema.'
            ),
        ),

        array(
            '@type' => 'Question',
            'name'  => '¿Qué ocurre si durante el proyecto quiero hacer cambios?',
            'acceptedAnswer' => array(
                '@type' => 'Answer',
                'text'  => 'Si el estado del proyecto lo permite, podemos estudiar modificaciones. Antes de realizar cualquier cambio que afecte al precio, a los materiales o a los plazos, lo revisamos contigo.'
            ),
        ),

        array(
            '@type' => 'Question',
            'name'  => '¿Qué garantía tienen los trabajos realizados?',
            'acceptedAnswer' => array(
                '@type' => 'Answer',
                'text'  => 'Los trabajos e instalaciones realizados cuentan con la garantía correspondiente según su naturaleza y la normativa aplicable. Los componentes instalados mantienen además las garantías ofrecidas por sus fabricantes.'
            ),
        ),

        array(
            '@type' => 'Question',
            'name'  => '¿Hacéis también instalaciones sueltas o mejoras sobre una camper ya hecha?',
            'acceptedAnswer' => array(
                '@type' => 'Answer',
                'text'  => 'Sí. No es necesario realizar una camperización completa. Podemos estudiar instalaciones concretas, ampliaciones de autonomía, mejoras de confort o renovación de determinados elementos de una camper existente.'
            ),
        ),

        array(
            '@type' => 'Question',
            'name'  => '¿Puedo ver trabajos anteriores antes de decidirme?',
            'acceptedAnswer' => array(
                '@type' => 'Answer',
                'text'  => 'Sí. En la web iremos mostrando algunos de nuestros últimos trabajos para que puedas conocer diferentes soluciones, distribuciones y acabados realizados en otros proyectos.'
            ),
        ),

        array(
            '@type' => 'Question',
            'name'  => '¿Cómo empiezo el proceso para camperizar mi furgoneta?',
            'acceptedAnswer' => array(
                '@type' => 'Answer',
                'text'  => 'El primer paso es contarnos qué vehículo tienes, cuántas personas viajarán y qué tipo de uso quieres darle. Puedes hacerlo desde nuestro configurador de camperización o mediante el formulario de contacto. A partir de ahí estudiamos contigo las posibilidades del proyecto.'
            ),
        ),


        /* ==================================================
           ALQUILER
        ================================================== */

        array(
            '@type' => 'Question',
            'name'  => '¿Qué necesito para alquilar una camper?',
            'acceptedAnswer' => array(
                '@type' => 'Answer',
                'text'  => 'Necesitarás presentar un documento de identidad válido y un permiso de conducir en vigor. Antes de confirmar la reserva revisamos contigo las condiciones aplicables y los datos de las personas que conducirán el vehículo.'
            ),
        ),

        array(
            '@type' => 'Question',
            'name'  => '¿Qué permiso de conducir necesito?',
            'acceptedAnswer' => array(
                '@type' => 'Answer',
                'text'  => 'Para nuestras campers es suficiente el permiso de conducir B, siempre que esté vigente y cumpla los requisitos establecidos en las condiciones de alquiler.'
            ),
        ),

        array(
            '@type' => 'Question',
            'name'  => '¿Cómo se calculan los días de alquiler?',
            'acceptedAnswer' => array(
                '@type' => 'Answer',
                'text'  => 'El alquiler se calcula desde la fecha de recogida hasta la fecha de devolución seleccionadas durante la reserva. El precio final depende del número de días, el modelo elegido y la temporada correspondiente.'
            ),
        ),

        array(
            '@type' => 'Question',
            'name'  => '¿Cómo se confirma una reserva?',
            'acceptedAnswer' => array(
                '@type' => 'Answer',
                'text'  => 'La reserva queda confirmada cuando se completa el proceso de reserva y se abona la señal indicada. Después recibirás la confirmación y los datos necesarios para preparar la recogida.'
            ),
        ),

        array(
            '@type' => 'Question',
            'name'  => '¿Cuál es la política de cancelaciones?',
            'acceptedAnswer' => array(
                '@type' => 'Answer',
                'text'  => 'Las condiciones de cancelación dependen de la antelación con la que se comunique la anulación. Antes de reservar podrás consultar las condiciones aplicables y los posibles importes reembolsables.'
            ),
        ),

        array(
            '@type' => 'Question',
            'name'  => '¿Puedo realizar cambios en mi reserva?',
            'acceptedAnswer' => array(
                '@type' => 'Answer',
                'text'  => 'Sí, siempre que exista disponibilidad. Podemos estudiar cambios de fechas, vehículo u otros datos de la reserva y te informaremos si implican alguna modificación en el precio.'
            ),
        ),

        array(
            '@type' => 'Question',
            'name'  => '¿Hay que pagar una fianza?',
            'acceptedAnswer' => array(
                '@type' => 'Answer',
                'text'  => 'Sí. La fianza se entrega antes de iniciar el viaje y se devuelve una vez revisado el vehículo tras su devolución, siempre que no existan daños, cargos pendientes o incumplimientos de las condiciones de alquiler.'
            ),
        ),

        array(
            '@type' => 'Question',
            'name'  => '¿Qué incluye el alquiler?',
            'acceptedAnswer' => array(
                '@type' => 'Answer',
                'text'  => 'Cada camper se entrega equipada con los elementos necesarios para viajar y utilizar sus instalaciones. En la ficha de cada modelo puedes consultar su equipamiento y características concretas.'
            ),
        ),

        array(
            '@type' => 'Question',
            'name'  => '¿Se admiten mascotas?',
            'acceptedAnswer' => array(
                '@type' => 'Answer',
                'text'  => 'Sí, disponemos de opciones pensadas para viajar con mascota. Es importante indicarlo al realizar la reserva para elegir el vehículo más adecuado y conocer las condiciones aplicables.'
            ),
        ),

        array(
            '@type' => 'Question',
            'name'  => '¿Cómo viajan los niños?',
            'acceptedAnswer' => array(
                '@type' => 'Answer',
                'text'  => 'Los niños deben viajar siempre en una plaza homologada y utilizando el sistema de retención adecuado a su edad, altura y peso. Antes de reservar conviene comprobar las plazas disponibles en cada modelo.'
            ),
        ),

        array(
            '@type' => 'Question',
            'name'  => '¿Puedo salir de España con la camper?',
            'acceptedAnswer' => array(
                '@type' => 'Answer',
                'text'  => 'Los viajes fuera de España deben comunicarse previamente. Dependiendo del destino pueden existir condiciones específicas relacionadas con el seguro, la asistencia o la documentación del vehículo.'
            ),
        ),

        array(
            '@type' => 'Question',
            'name'  => '¿Está el kilometraje limitado?',
            'acceptedAnswer' => array(
                '@type' => 'Answer',
                'text'  => 'Las condiciones de kilometraje pueden variar según la duración del alquiler o el vehículo elegido. Antes de confirmar la reserva podrás consultar si existe algún límite y qué condiciones se aplican.'
            ),
        ),

        array(
            '@type' => 'Question',
            'name'  => '¿Cómo se entrega y cómo debe devolverse la camper?',
            'acceptedAnswer' => array(
                '@type' => 'Answer',
                'text'  => 'La camper se entrega revisada, limpia y preparada para comenzar el viaje. En la devolución deberá entregarse en las condiciones indicadas, incluyendo combustible, limpieza y estado de los depósitos según las condiciones del alquiler.'
            ),
        ),

        array(
            '@type' => 'Question',
            'name'  => '¿El agua de la camper es potable?',
            'acceptedAnswer' => array(
                '@type' => 'Answer',
                'text'  => 'El agua de los depósitos está destinada principalmente al uso cotidiano de la camper. Para beber recomendamos utilizar agua embotellada o agua cuya potabilidad puedas garantizar.'
            ),
        ),

        array(
            '@type' => 'Question',
            'name'  => '¿Dónde puedo conseguir agua durante el viaje?',
            'acceptedAnswer' => array(
                '@type' => 'Answer',
                'text'  => 'Puedes rellenar los depósitos en áreas de autocaravanas, campings y otros puntos habilitados para vehículos camper. Recomendamos utilizar siempre lugares autorizados y agua apta para este tipo de instalaciones.'
            ),
        ),

        array(
            '@type' => 'Question',
            'name'  => '¿Se puede fumar dentro de la camper?',
            'acceptedAnswer' => array(
                '@type' => 'Answer',
                'text'  => 'No. Para mantener los vehículos en buenas condiciones y garantizar el confort de todos los usuarios, no está permitido fumar en el interior de las campers.'
            ),
        ),

        array(
            '@type' => 'Question',
            'name'  => '¿Qué ocurre en caso de accidente, avería o daños?',
            'acceptedAnswer' => array(
                '@type' => 'Answer',
                'text'  => 'Debes contactar con nosotros lo antes posible y seguir las indicaciones de asistencia y seguro facilitadas con el vehículo. No realices reparaciones por tu cuenta sin autorización salvo que sea necesario por una situación de emergencia.'
            ),
        ),

        array(
            '@type' => 'Question',
            'name'  => '¿Dónde se puede pernoctar con una camper?',
            'acceptedAnswer' => array(
                '@type' => 'Answer',
                'text'  => 'Puedes utilizar campings, áreas de autocaravanas y otros lugares donde el estacionamiento o la pernocta estén permitidos. Conviene comprobar siempre la normativa del municipio o del lugar donde quieras pasar la noche.'
            ),
        ),

        array(
            '@type' => 'Question',
            'name'  => '¿Es obligatorio respetar la hora de recogida y devolución?',
            'acceptedAnswer' => array(
                '@type' => 'Answer',
                'text'  => 'Sí. Los horarios permiten organizar la revisión, limpieza y preparación de cada vehículo. Si prevés un retraso o necesitas modificar el horario, ponte en contacto con nosotros con la mayor antelación posible.'
            ),
        ),

        array(
            '@type' => 'Question',
            'name'  => '¿Cuánto tiempo lleva la devolución de la camper?',
            'acceptedAnswer' => array(
                '@type' => 'Answer',
                'text'  => 'La devolución incluye una revisión básica del estado del vehículo, equipamiento, combustible y depósitos. Normalmente es un proceso breve, aunque puede llevar algo más de tiempo si es necesario revisar alguna incidencia.'
            ),
        ),

    );

    $schema = array(
        '@context'   => 'https://schema.org',
        '@type'      => 'FAQPage',
        'mainEntity' => $faq,
    );

    echo '<script type="application/ld+json">';
    echo wp_json_encode(
        $schema,
        JSON_UNESCAPED_UNICODE |
        JSON_UNESCAPED_SLASHES |
        JSON_PRETTY_PRINT
    );
    echo '</script>';
}

add_action( 'wp_head', 'hc_schema_faq_page' );


/* ==========================================================
   HORIZONTE CAMPER · SCHEMA SERVICE
   Página: Alquiler de campers en Asturias
========================================================== */

function hc_schema_service_alquiler() {

    if ( ! is_page( 'alquiler-de-campers-en-asturias' ) ) {
        return;
    }

    $pagina_url = get_permalink();

    $schema = array(
        '@context' => 'https://schema.org',
        '@type'    => 'Service',

        '@id' => $pagina_url . '#service',

        'name' => 'Alquiler de campers en Asturias',

        'description' => 'Servicio de alquiler de campers en Asturias con diferentes modelos, consulta de disponibilidad y reserva online.',

        'url' => $pagina_url,

        'mainEntityOfPage' => array(
            '@type' => 'WebPage',
            '@id'   => $pagina_url,
        ),

        'serviceType' => 'Alquiler de campers',

        'provider' => array(
            '@type' => 'Organization',
            'name'  => 'Horizonte Camper',
            'url'   => home_url( '/' ),
        ),

        'areaServed' => array(
            '@type' => 'AdministrativeArea',
            'name'  => 'Asturias',
        ),

        'offers' => array(
            '@type' => 'Offer',

            'url' => $pagina_url,

            'availability' => 'https://schema.org/InStock',

            'description' => 'Alquiler de diferentes modelos de camper con consulta de disponibilidad, selección de fechas y reserva online.',
        ),

        'availableChannel' => array(
            '@type' => 'ServiceChannel',

            'serviceUrl' => $pagina_url,

            'availableLanguage' => array(
                '@type' => 'Language',
                'name'  => 'Español',
            ),
        ),
    );

    echo '<script type="application/ld+json">';
    echo wp_json_encode(
        $schema,
        JSON_UNESCAPED_UNICODE |
        JSON_UNESCAPED_SLASHES |
        JSON_PRETTY_PRINT
    );
    echo '</script>';
}

add_action( 'wp_head', 'hc_schema_service_alquiler' );


/* ==========================================================
   HORIZONTE CAMPER · SIDEBAR ACCESORIOS CAMPER
========================================================== */

function hc_accesorios_sidebar_inicio() {

    if ( ! is_shop() && ! is_product_category() ) {
    return;
}

    echo '<div class="hc-shop-layout">';

        echo '<aside class="hc-shop-sidebar">';

            echo '<div class="hc-shop-sidebar-bloque">';
                echo '<h2>Buscar productos</h2>';
                get_product_search_form();
            echo '</div>';

            echo '<div class="hc-shop-sidebar-bloque">';
                echo '<h2>Categorías</h2>';

                echo '<ul class="hc-shop-categorias">';

                    $categorias = array(
                        'cocina-y-menaje'       => 'Cocina y menaje',
                        'agua-y-bano'           => 'Agua y baño',
                        'organizacion'          => 'Organización',
                        'exterior-y-camping'    => 'Exterior y camping',
                        'confort-y-descanso'    => 'Confort y descanso',
                    );

                    foreach ( $categorias as $slug => $nombre ) {

                        $categoria = get_term_by(
                            'slug',
                            $slug,
                            'product_cat'
                        );

                        if ( $categoria && ! is_wp_error( $categoria ) ) {

                            $url = get_term_link( $categoria );

                            echo '<li>';
                                echo '<a href="' . esc_url( $url ) . '">';
                                    echo esc_html( $nombre );
                                echo '</a>';
                            echo '</li>';
                        }
                    }

                echo '</ul>';

            echo '</div>';

        echo '</aside>';

        echo '<div class="hc-shop-main">';
}

add_action(
    'woocommerce_before_shop_loop',
    'hc_accesorios_sidebar_inicio',
    5
);


function hc_accesorios_sidebar_fin() {

    if ( ! is_shop() && ! is_product_category() ) {
    return;
}

        echo '</div>'; // .hc-shop-main
    echo '</div>'; // .hc-shop-layout
}

add_action(
    'woocommerce_after_shop_loop',
    'hc_accesorios_sidebar_fin',
    40
);


/* Horizonte Camper: condiciones de envío en las fichas */
add_action(
    'woocommerce_single_product_summary',
    'hc_mostrar_condiciones_envio',
    35
);

function hc_mostrar_condiciones_envio() {
    global $product;

    if ( ! $product instanceof WC_Product || ! $product->needs_shipping() ) {
        return;
    }
    ?>
    <div class="hc-condiciones-envio">
        <p><strong>Envío gratuito desde 30 € de compra.</strong></p>
        <p>Pedidos inferiores a 30 €: Península - 6,20 €;
            Baleares y Canarias - 19,60 €.</p>
        <p><strong>Entrega:</strong> de 1 a 7 días hábiles.</p>
        <p>No realizamos envíos a Ceuta ni Melilla.</p>
    </div>
    <?php
}



/* Horizonte Camper: carrito con contador en el menú principal */
function hc_enlace_carrito() {
    if ( ! function_exists( 'WC' ) || ! WC()->cart ) {
        return '';
    }

    $cantidad = WC()->cart->get_cart_contents_count();
    $etiqueta = sprintf(
        'Ver carrito: %d artículos',
        $cantidad
    );

    ob_start();
    ?>
    <a class="hc-carrito"
       href="<?php echo esc_url( wc_get_cart_url() ); ?>"
       aria-label="<?php echo esc_attr( $etiqueta ); ?>">
        <svg width="26" height="26" viewBox="0 0 24 24"
             fill="none" stroke="currentColor" stroke-width="1.8"
             stroke-linecap="round" stroke-linejoin="round"
             aria-hidden="true" focusable="false">
            <path d="M3 3h2l3 12h10l3-9H6"/>
            <circle cx="9" cy="20" r="1"/>
            <circle cx="18" cy="20" r="1"/>
        </svg>
        <span class="hc-carrito-contador">
            <?php echo esc_html( $cantidad ); ?>
        </span>
    </a>
    <?php
    return ob_get_clean();
}

/* Añadirlo al menú que hemos identificado en tu cabecera */
add_filter(
    'wp_nav_menu_menu-principal_items',
    function ( $items ) {
        $enlace = hc_enlace_carrito();

        if ( $enlace ) {
            $items .= '<li class="menu-item hc-menu-carrito">'
                . $enlace . '</li>';
        }

        return $items;
    }
);

/* Actualizar ambos contadores: escritorio y móvil */
add_filter(
    'woocommerce_add_to_cart_fragments',
    function ( $fragmentos ) {
        $enlace = hc_enlace_carrito();

        if ( $enlace ) {
            $fragmentos['a.hc-carrito'] = $enlace;
        }

        return $fragmentos;
    }
);

/* Cargar la actualización del carrito en todas las páginas */
add_action(
    'wp_enqueue_scripts',
    function () {
        if ( ! function_exists( 'WC' ) ) {
            return;
        }

        wp_enqueue_script( 'wc-cart-fragments' );

        /* Actualizar también tras acciones de los bloques de WooCommerce */
        wp_add_inline_script(
            'wc-cart-fragments',
            "['wc-blocks_added_to_cart', 'wc-blocks_removed_from_cart']
            .forEach(function(evento) {
                document.addEventListener(evento, function() {
                    jQuery(document.body).trigger('wc_fragment_refresh');
                });
            });"
        );
    },
    20
);



/**
 * Galerías de Últimos trabajos.
 */
require_once get_stylesheet_directory() . '/inc/galerias-trabajos.php';

function hc_cargar_recursos_galerias() {
	if ( ! is_page( 'ultimos-trabajos' ) ) {
		return;
	}

	$directorio = get_stylesheet_directory();
	$url        = get_stylesheet_directory_uri();

	wp_enqueue_style(
		'hc-galerias-trabajos',
		$url . '/assets/css/galerias-trabajos.css',
		array(),
		filemtime( $directorio . '/assets/css/galerias-trabajos.css' )
	);

	wp_enqueue_script(
		'hc-galerias-trabajos',
		$url . '/assets/js/galerias-trabajos.js',
		array(),
		filemtime( $directorio . '/assets/js/galerias-trabajos.js' ),
		true
	);
}
add_action( 'wp_enqueue_scripts', 'hc_cargar_recursos_galerias' );


/**
 * Simulador eléctrico.
 */
require_once get_stylesheet_directory() . '/inc/simulador-electrico.php';

function hc_cargar_recursos_simulador() {
	if ( ! is_page( 'simulador-electrico' ) ) {
		return;
	}

	$directorio = get_stylesheet_directory();
	$url        = get_stylesheet_directory_uri();

	wp_enqueue_style(
		'hc-simulador-electrico',
		$url . '/assets/css/simulador-electrico.css',
		array(),
		filemtime( $directorio . '/assets/css/simulador-electrico.css' )
	);

	wp_enqueue_script(
		'hc-simulador-electrico',
		$url . '/assets/js/simulador-electrico.js',
		array(),
		filemtime( $directorio . '/assets/js/simulador-electrico.js' ),
		true
	);
}
add_action( 'wp_enqueue_scripts', 'hc_cargar_recursos_simulador' );


/**
 * Imagen de cabecera de Accesorios Camper.
 */
function hc_imagen_cabecera_tienda() {
	if ( ! is_shop() || is_search() || is_paged() ) {
		return;
	}

	$imagen = content_url( '/uploads/2026/10/accesorios_camper.webp' );
	?>
	<div class="hc-tienda-banner">
		<img
			src="<?php echo esc_url( $imagen ); ?>"
			alt="Accesorios camper con vajilla, utensilios de cocina y silla de camping junto a una furgoneta frente al mar."
			decoding="async"
		>
	</div>
	<?php
}

add_action(
	'woocommerce_shop_loop_header',
	'hc_imagen_cabecera_tienda',
	5
);


/* Quitar las migas de pan de la página general de la tienda */
function hc_ocultar_migas_tienda() {
	if ( function_exists( 'is_shop' ) && is_shop() ) {
		remove_action(
			'woocommerce_before_main_content',
			'woocommerce_breadcrumb',
			20
		);
	}
}

add_action( 'wp', 'hc_ocultar_migas_tienda' );


/**
 * Autoría de marca para entradas firmadas como Horizonte Camper.
 */
function hc_schema_autor_organizacion( $data, $jsonld ) {
	if ( ! is_singular( 'post' ) ) {
		return $data;
	}

	$autor_id = get_post_field(
		'post_author',
		get_queried_object_id()
	);

	$nombre_autor = get_the_author_meta(
		'display_name',
		$autor_id
	);

	// Conserva la autoría personal de otros colaboradores.
	if ( 'Horizonte Camper' !== trim( $nombre_autor ) ) {
		return $data;
	}

	$organizacion_id = '';

	// Localiza la organización existente.
	foreach ( $data as $nodo ) {
		if ( ! is_array( $nodo ) ) {
			continue;
		}

		$tipos = (array) ( $nodo['@type'] ?? array() );

		if (
			in_array( 'Organization', $tipos, true ) &&
			'Horizonte Camper' === ( $nodo['name'] ?? '' ) &&
			! empty( $nodo['@id'] )
		) {
			$organizacion_id = $nodo['@id'];
			break;
		}
	}

	if ( ! $organizacion_id ) {
		return $data;
	}

	// Enlaza la autoría del artículo con esa organización.
	foreach ( $data as &$nodo ) {
		if ( ! is_array( $nodo ) ) {
			continue;
		}

		$tipos = (array) ( $nodo['@type'] ?? array() );

		if (
			array_intersect(
				array( 'Article', 'BlogPosting', 'NewsArticle' ),
				$tipos
			)
		) {
			$nodo['author'] = array(
				'@id' => $organizacion_id,
			);
		}
	}
	unset( $nodo );

	return $data;
}

add_filter(
	'rank_math/json_ld',
	'hc_schema_autor_organizacion',
	100,
	2
);


/**
 * Aviso de tienda de demostración en Finalizar compra.
 */
function hc_aviso_checkout_demostracion( $contenido ) {
    if (
        is_admin() ||
        ! function_exists( 'is_checkout' ) ||
        ! is_checkout() ||
        is_wc_endpoint_url() ||
        ! is_main_query() ||
        ! in_the_loop()
    ) {
        return $contenido;
    }

    $aviso = '
    <div class="hc-aviso-tienda-demo"
         style="margin:0 0 28px;padding:20px;
                border-left:4px solid #D96B43;
                border-radius:8px;background:#F8F5EF;
                color:#0F4C5C;line-height:1.7;">
        <p style="margin:0;">
            <strong>Tienda de demostración:</strong>
            los pedidos y pagos son de prueba.
            No se realizan cobros ni envíos reales.
            Utiliza datos ficticios y tarjetas de prueba.
        </p>
    </div>';

    return $aviso . $contenido;
}

add_filter(
    'the_content',
    'hc_aviso_checkout_demostracion',
    20
);


require_once get_stylesheet_directory() . '/inc/formulario-contacto.php';

/**
 * Insertar el formulario en el bloque HTML de Contacto.
 */
function hc_insertar_formulario_contacto( $contenido, $widget ) {
    if ( 'html' !== $widget->get_name() ) {
        return $contenido;
    }

    $hueco = '<div id="hc-contacto-formulario"></div>';

    if ( false === strpos( $contenido, $hueco ) ) {
        return $contenido;
    }

    return str_replace(
        $hueco,
        do_shortcode( '[hc_contacto]' ),
        $contenido
    );
}

add_filter(
    'elementor/widget/render_content',
    'hc_insertar_formulario_contacto',
    10,
    2
);