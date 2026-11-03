<?php
/**
 * Formulario de contacto de Horizonte Camper.
 */

defined( 'ABSPATH' ) || exit;


/* =========================================================
   FORMULARIO
   ========================================================= */

function hc_contacto_shortcode() {
    $uid = wp_unique_id( 'hc-contacto-' );

    ob_start();
    ?>

    <form
        class="hc-contacto-form"
        method="post"
        action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>"
    >
        <input
            type="hidden"
            name="action"
            value="hc_contacto_enviar"
        >

        <?php
        wp_nonce_field(
            'hc_contacto_enviar',
            'hc_contacto_nonce'
        );
        ?>

        <div class="hc-contacto-form__campo">
            <label for="<?php echo esc_attr( $uid ); ?>nombre">
                Nombre
            </label>

            <input
                id="<?php echo esc_attr( $uid ); ?>nombre"
                type="text"
                name="nombre"
                autocomplete="given-name"
                maxlength="80"
                required
            >
        </div>

        <div class="hc-contacto-form__campo">
            <label for="<?php echo esc_attr( $uid ); ?>email">
                Correo electrónico
            </label>

            <input
                id="<?php echo esc_attr( $uid ); ?>email"
                type="email"
                name="email"
                autocomplete="email"
                maxlength="254"
                required
            >
        </div>

        <div class="hc-contacto-form__campo">
            <label for="<?php echo esc_attr( $uid ); ?>asunto">
                ¿Sobre qué quieres consultar?
            </label>

            <select
                id="<?php echo esc_attr( $uid ); ?>asunto"
                name="asunto"
                required
            >
                <option value="">Selecciona una opción</option>
                <option value="camperizacion">Camperización</option>
                <option value="alquiler">Alquiler</option>
                <option value="accesorios">Accesorios</option>
                <option value="otra">Otra consulta</option>
            </select>
        </div>

        <div class="hc-contacto-form__campo">
            <label for="<?php echo esc_attr( $uid ); ?>mensaje">
                Cuéntanos qué necesitas
            </label>

            <textarea
                id="<?php echo esc_attr( $uid ); ?>mensaje"
                name="mensaje"
                rows="6"
                maxlength="3000"
                required
            ></textarea>
        </div>

        <div class="hc-contacto-form__privacidad">
            <input
                id="<?php echo esc_attr( $uid ); ?>privacidad"
                type="checkbox"
                name="privacidad"
                value="1"
                required
            >

            <label for="<?php echo esc_attr( $uid ); ?>privacidad">
                He leído y acepto la
                <a
                    href="<?php echo esc_url( home_url( '/privacy-policy/' ) ); ?>"
                    target="_blank"
                    rel="noopener noreferrer"
                >política de privacidad</a>.
            </label>
        </div>

        <button
            type="submit"
            class="hc-contacto-form__enviar"
        >
            Enviar mensaje
        </button>
    </form>

    <?php

    return ob_get_clean();
}

add_shortcode( 'hc_contacto', 'hc_contacto_shortcode' );


/* =========================================================
   VENTANA DE CONFIRMACIÓN
   Se carga fuera del contenido de Elementor.
   ========================================================= */

function hc_contacto_ventana_confirmacion() {
    if ( ! is_page( 'contacto' ) ) {
        return;
    }
    ?>

    <dialog
        id="hc-contacto-ventana"
        class="hc-contacto-confirmacion"
        aria-labelledby="hc-contacto-confirmacion-titulo"
        aria-describedby="hc-contacto-confirmacion-texto"
    >
        <div class="hc-contacto-confirmacion__contenido">

            <span
                class="hc-contacto-confirmacion__icono"
                aria-hidden="true"
            >✓</span>

            <h2 id="hc-contacto-confirmacion-titulo">
                ¡Mensaje enviado!
            </h2>

            <p id="hc-contacto-confirmacion-texto">
                Tu mensaje de prueba se ha procesado correctamente.
                En el entorno local puedes revisarlo en Mailpit.
            </p>

            <form method="dialog">
                <button type="submit" autofocus>
                    Cerrar
                </button>
            </form>

        </div>
    </dialog>

    <script>
    (() => {
        function iniciarConfirmacion() {
            const url = new URL(window.location.href);
            const estado = url.searchParams.get("hc_contacto");

            if (estado !== "enviado" && estado !== "error") {
                return;
            }

            const ventana = document.getElementById(
                "hc-contacto-ventana"
            );

            if (!ventana) return;

            const titulo = ventana.querySelector(
                "#hc-contacto-confirmacion-titulo"
            );

            const texto = ventana.querySelector(
                "#hc-contacto-confirmacion-texto"
            );

            const icono = ventana.querySelector(
                ".hc-contacto-confirmacion__icono"
            );

            if (estado === "error") {
                titulo.textContent = "No se ha podido enviar";
                texto.textContent =
                    "Revisa los datos del formulario e inténtalo de nuevo.";
                icono.textContent = "!";
            }

            if (typeof ventana.showModal === "function") {
                if (!ventana.open) {
                    ventana.showModal();
                }
            } else {
                window.alert(
                    titulo.textContent + "\n\n" + texto.textContent
                );
            }

            url.searchParams.delete("hc_contacto");

            window.history.replaceState(
                window.history.state,
                "",
                url.pathname + url.search + url.hash
            );
        }

        if (document.readyState === "loading") {
            document.addEventListener(
                "DOMContentLoaded",
                iniciarConfirmacion,
                { once: true }
            );
        } else {
            iniciarConfirmacion();
        }
    })();
    </script>

    <?php
}

add_action(
    'wp_footer',
    'hc_contacto_ventana_confirmacion',
    30
);


/* =========================================================
   PROCESAR EL ENVÍO
   ========================================================= */

function hc_contacto_procesar() {
    if ( 'POST' !== ( $_SERVER['REQUEST_METHOD'] ?? '' ) ) {
        wp_die(
            'Método no permitido.',
            '',
            array( 'response' => 405 )
        );
    }

    $campo = static function ( $nombre ) {
        return isset( $_POST[ $nombre ] )
            && is_string( $_POST[ $nombre ] )
            ? wp_unslash( $_POST[ $nombre ] )
            : '';
    };

    $nonce = sanitize_text_field(
        $campo( 'hc_contacto_nonce' )
    );

    if ( ! wp_verify_nonce( $nonce, 'hc_contacto_enviar' ) ) {
        wp_die( 'La comprobación de seguridad ha fallado.' );
    }

    $nombre  = sanitize_text_field( $campo( 'nombre' ) );
    $email   = sanitize_email( $campo( 'email' ) );
    $asunto  = sanitize_key( $campo( 'asunto' ) );
    $mensaje = sanitize_textarea_field( $campo( 'mensaje' ) );

    $asuntos = array(
        'camperizacion' => 'Camperización',
        'alquiler'      => 'Alquiler',
        'accesorios'    => 'Accesorios',
        'otra'         => 'Otra consulta',
    );

    $retorno = home_url( '/contacto/' );

    if (
        '' === $nombre ||
        strlen( $nombre ) > 320 ||
        ! is_email( $email ) ||
        ! isset( $asuntos[ $asunto ] ) ||
        '' === $mensaje ||
        strlen( $mensaje ) > 12000 ||
        '1' !== $campo( 'privacidad' )
    ) {
        wp_safe_redirect(
            add_query_arg(
                'hc_contacto',
                'error',
                $retorno
            )
        );
        exit;
    }

    $cuerpo  = "CONTACTO · HORIZONTE CAMPER\n\n";
    $cuerpo .= "Nombre: {$nombre}\n";
    $cuerpo .= "Email: {$email}\n";
    $cuerpo .= "Asunto: " . $asuntos[ $asunto ] . "\n\n";
    $cuerpo .= "Mensaje:\n{$mensaje}\n";

    $enviado = wp_mail(
        get_option( 'admin_email' ),
        'Contacto de prueba: ' . $asuntos[ $asunto ],
        $cuerpo,
        array(
            'Content-Type: text/plain; charset=UTF-8',
            'Reply-To: ' . $email,
        )
    );

    wp_safe_redirect(
        add_query_arg(
            'hc_contacto',
            $enviado ? 'enviado' : 'error',
            $retorno
        )
    );
    exit;
}

add_action(
    'admin_post_hc_contacto_enviar',
    'hc_contacto_procesar'
);

add_action(
    'admin_post_nopriv_hc_contacto_enviar',
    'hc_contacto_procesar'
);