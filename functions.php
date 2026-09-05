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