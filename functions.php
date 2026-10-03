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

            <p>
                Mediante el envío de mis datos personales confirmo que he leído y acepto la
                <a href="<?php echo esc_url( home_url( '/politica-de-privacidad/' ) ); ?>">
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


            <label class="hc-form-privacidad">

                <input
                    type="checkbox"
                    name="privacidad"
                    value="1"
                    required
                >

                <span>
                    He leído y acepto la política de privacidad.
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
    get_page_by_path('alquiler')
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

