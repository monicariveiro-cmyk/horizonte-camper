<?php

/**
 * ==========================================================
 * Horizonte Camper
 * Plantilla principal del blog
 * Archivo: home.php
 * ==========================================================
 */

get_header();

?>

<main id="primary" class="site-main hc-blog">


    <!-- ======================================================
         CABECERA DEL BLOG
    ======================================================= -->

    <header class="hc-blog__header">

        <p class="hc-blog__subtitulo">
            Blog
        </p>

        <h1 class="hc-blog__titulo">
            Ideas, rutas y consejos para viajar en camper
        </h1>

    </header>



    <!-- ======================================================
         ARTÍCULOS DESTACADOS
    ======================================================= -->

    <?php

    /*
     * Guardaremos aquí los ID de los artículos destacados
     * para evitar que vuelvan a aparecer en el listado inferior.
     */
    $ids_destacados = array();


    /*
     * Consulta de los tres artículos más recientes.
     */
    $destacados = new WP_Query(
        array(
            'post_type'           => 'post',
            'posts_per_page'      => 3,
            'post_status'         => 'publish',
            'ignore_sticky_posts' => true
        )
    );

    ?>


    <?php if ($destacados->have_posts()) : ?>

        <section class="hc-blog-destacados">


            <!-- Cabecera editorial -->

            <div class="hc-blog-destacados__cabecera">

                <span class="hc-blog-destacados__etiqueta">
                    Destacados
                </span>

                <span
                    class="hc-blog-destacados__linea"
                    aria-hidden="true"
                ></span>

                <span
                    class="hc-blog-destacados__brujula"
                    aria-hidden="true"
                ></span>

            </div>



            <!-- Grid de destacados -->

            <div class="hc-blog-destacados__grid">

                <?php

                $indice_destacado = 0;


                while ($destacados->have_posts()) :

                    $destacados->the_post();


                    /*
                     * Guardamos el ID para excluirlo
                     * posteriormente del listado general.
                     */
                    $ids_destacados[] = get_the_ID();


                    /*
                     * El primer artículo será el principal.
                     */
                    $indice_destacado++;


                    $clase_destacado = (
                        $indice_destacado === 1
                    )
                        ? 'hc-blog-destacado hc-blog-destacado--principal'
                        : 'hc-blog-destacado hc-blog-destacado--secundario';

                    ?>


                    <article class="<?php echo esc_attr($clase_destacado); ?>">


                        <a
                            class="hc-blog-destacado__enlace"
                            href="<?php the_permalink(); ?>"
                        >


                            <!-- Imagen destacada -->

                            <?php if (has_post_thumbnail()) : ?>

                                <div class="hc-blog-destacado__imagen">

                                    <?php

                                    the_post_thumbnail(
                                        'large',
                                        array(
                                            'class'   =>
                                                'hc-blog-destacado__foto',
                                            'loading' =>
                                                'lazy'
                                        )
                                    );

                                    ?>

                                </div>

                            <?php endif; ?>



                            <!-- Degradado -->

                            <div
                                class="hc-blog-destacado__overlay"
                                aria-hidden="true"
                            ></div>



                            <!-- Contenido -->

                            <div class="hc-blog-destacado__contenido">


                                <?php

                                $categorias = get_the_category();


                                if (!empty($categorias)) :

                                    ?>

                                    <span class="hc-blog-destacado__categoria">

                                        <?php
                                        echo esc_html(
                                            $categorias[0]->name
                                        );
                                        ?>

                                    </span>

                                <?php endif; ?>



                                <h2 class="hc-blog-destacado__titulo">
                                    <?php the_title(); ?>
                                </h2>



                                <span
                                    class="hc-blog-destacado__brujula"
                                    aria-hidden="true"
                                ></span>


                            </div>


                        </a>


                    </article>


                <?php endwhile; ?>


            </div>


        </section>


        <?php

        /*
         * Restauramos los datos del Loop principal.
         */
        wp_reset_postdata();

        ?>


    <?php endif; ?>



    <!-- ======================================================
         ÚLTIMOS ARTÍCULOS
    ======================================================= -->

    <?php if (have_posts()) : ?>


        <section class="hc-blog-ultimos">


            <!-- Cabecera editorial -->

            <div class="hc-blog-ultimos__cabecera">

                <span class="hc-blog-ultimos__etiqueta">
                    Últimos artículos
                </span>

                <span
                    class="hc-blog-ultimos__linea"
                    aria-hidden="true"
                ></span>

                <span
                    class="hc-blog-ultimos__brujula"
                    aria-hidden="true"
                ></span>

            </div>



            <!-- Listado -->

            <section class="hc-blog__listado">


                <div class="hc-articulos">


                    <?php while (have_posts()) : the_post(); ?>


                        <?php

                        /*
                         * Si esta entrada ya aparece
                         * en Destacados, no la repetimos.
                         */
                        if (
                            in_array(
                                get_the_ID(),
                                $ids_destacados,
                                true
                            )
                        ) {
                            continue;
                        }

                        ?>


                        <article <?php post_class('hc-articulo'); ?>>


                            <!-- Imagen -->

                            <?php if (has_post_thumbnail()) : ?>

                                <a
                                    class="hc-articulo-imagen"
                                    href="<?php the_permalink(); ?>"
                                    aria-label="<?php
                                        echo esc_attr(
                                            get_the_title()
                                        );
                                    ?>"
                                >

                                    <?php

                                    the_post_thumbnail(
                                        'large',
                                        array(
                                            'class'   =>
                                                'hc-articulo-imagen__foto',
                                            'loading' =>
                                                'lazy'
                                        )
                                    );

                                    ?>

                                </a>

                            <?php endif; ?>



                            <!-- Contenido -->

                            <div class="hc-articulo-contenido">


                                <!-- Categoría -->

                                <?php

                                $categorias = get_the_category();


                                if (!empty($categorias)) :

                                    ?>

                                    <p class="hc-articulo-categoria">

                                        <?php
                                        echo esc_html(
                                            $categorias[0]->name
                                        );
                                        ?>

                                    </p>

                                <?php endif; ?>



                                <!-- Título -->

                                <h2 class="hc-articulo-titulo">

                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_title(); ?>
                                    </a>

                                </h2>



                                <!-- Extracto -->

                                <p class="hc-articulo-extracto">

                                    <?php

                                    echo esc_html(
                                        wp_trim_words(
                                            get_the_excerpt(),
                                            24,
                                            '…'
                                        )
                                    );

                                    ?>

                                </p>



                                <!-- Leer artículo -->

                                <a
                                    class="hc-articulo-enlace"
                                    href="<?php the_permalink(); ?>"
                                >

                                    <span>
                                        Leer artículo
                                    </span>

                                    <span
                                        class="hc-articulo-enlace__brujula"
                                        aria-hidden="true"
                                    ></span>

                                </a>


                            </div>


                        </article>


                    <?php endwhile; ?>


                </div>


            </section>


        </section>



        <!-- ==================================================
             PAGINACIÓN
        =================================================== -->

        <nav
            class="hc-blog__paginacion"
            aria-label="Paginación del blog"
        >

            <?php

            the_posts_pagination(
                array(
                    'mid_size'  => 1,
                    'prev_text' => '← Anterior',
                    'next_text' => 'Siguiente →'
                )
            );

            ?>

        </nav>


    <?php else : ?>


        <p class="hc-blog__vacio">
            No hay artículos publicados.
        </p>


    <?php endif; ?>


</main>


<?php

get_footer();