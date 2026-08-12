<?php

/**
 * Plantilla para las entradas individuales del blog.
 *
 * Horizonte Camper
 */

get_header();

?>

<main id="primary" class="site-main">

<?php

while (have_posts()) :

    the_post();

    ?>

    <article <?php post_class('hc-single-post'); ?>>


        <!-- ==================================================
             CABECERA EDITORIAL
        =================================================== -->

        <header class="hc-single-post__header">

            <?php

            $categorias = get_the_category();

            if (!empty($categorias)) :

                ?>

                <p class="hc-single-post__categoria">
                    <?php echo esc_html($categorias[0]->name); ?>
                </p>

            <?php endif; ?>


            <h1 class="hc-single-post__titulo">
                <?php the_title(); ?>
            </h1>


            <div class="hc-single-post__meta">

                <span class="hc-single-post__fecha">
                    <?php echo esc_html(get_the_date('j F Y')); ?>
                </span>

                <span
                    class="hc-single-post__separador"
                    aria-hidden="true"
                >
                    ·
                </span>

                <span class="hc-single-post__lectura">

                    <?php

                    $contenido = get_post_field(
                        'post_content',
                        get_the_ID()
                    );

                    $contenido_limpio = wp_strip_all_tags(
                        $contenido
                    );

                    $palabras = str_word_count(
                        $contenido_limpio
                    );

                    $minutos = max(
                        1,
                        ceil($palabras / 200)
                    );

                    echo esc_html(
                        $minutos . ' min de lectura'
                    );

                    ?>

                </span>

            </div>

        </header>



        <!-- ==================================================
             CONTENIDO + ASIDE
        =================================================== -->

        <div class="hc-single-post__layout">


            <!-- Contenido principal -->

            <div class="hc-single-post__contenido">

                <?php the_content(); ?>

            </div>



            <!-- ==================================================
                 ASIDE
            =================================================== -->

            <aside
                class="hc-single-post__aside"
                aria-label="Información relacionada con el artículo"
            >


            <!-- ==============================================
     CATEGORÍAS
=============================================== -->

<?php

$categorias_aside = get_the_category();

if (!empty($categorias_aside)) :

    ?>

    <section class="hc-single-post__aside-bloque">

        <h2 class="hc-single-post__aside-titulo">
            Categorías
        </h2>

        <div class="hc-single-post__categorias">

            <?php foreach ($categorias_aside as $categoria) : ?>

                <a
                    class="hc-single-post__categoria-aside"
                    href="<?php
                        echo esc_url(
                            get_category_link(
                                $categoria->term_id
                            )
                        );
                    ?>"
                >
                    <?php
                    echo esc_html(
                        $categoria->name
                    );
                    ?>
                </a>

            <?php endforeach; ?>

        </div>

    </section>

<?php endif; ?>


                <!-- ==============================================
                     ETIQUETAS
                =============================================== -->

                <?php

                $etiquetas = get_the_tags();

                if (!empty($etiquetas)) :

                    ?>

                    <section class="hc-single-post__aside-bloque">

                        <h2 class="hc-single-post__aside-titulo">
                            Etiquetas
                        </h2>


                        <div class="hc-single-post__etiquetas">

                            <?php foreach ($etiquetas as $etiqueta) : ?>

                                <a
                                    class="hc-single-post__etiqueta"
                                    href="<?php
                                        echo esc_url(
                                            get_tag_link(
                                                $etiqueta->term_id
                                            )
                                        );
                                    ?>"
                                >
                                    <?php
                                    echo esc_html(
                                        $etiqueta->name
                                    );
                                    ?>
                                </a>

                            <?php endforeach; ?>

                        </div>

                    </section>

                <?php endif; ?>



                <!-- ==============================================
                     ANTERIOR / SIGUIENTE
                =============================================== -->

                <?php

                $entrada_anterior  = get_previous_post();
                $entrada_siguiente = get_next_post();

                if (
                    $entrada_anterior ||
                    $entrada_siguiente
                ) :

                    ?>

                    <section class="hc-single-post__aside-bloque">

                        <h2 class="hc-single-post__aside-titulo">
                            Sigue leyendo
                        </h2>


                        <div class="hc-single-post__aside-nav">


                            <?php if ($entrada_anterior) : ?>

                                <a
                                    class="hc-single-post__aside-nav-item"
                                    href="<?php
                                        echo esc_url(
                                            get_permalink(
                                                $entrada_anterior->ID
                                            )
                                        );
                                    ?>"
                                >

                                    <span class="hc-single-post__aside-nav-etiqueta">
                                        ← Anterior
                                    </span>

                                    <span class="hc-single-post__aside-nav-titulo">
                                        <?php
                                        echo esc_html(
                                            get_the_title(
                                                $entrada_anterior->ID
                                            )
                                        );
                                        ?>
                                    </span>

                                </a>

                            <?php endif; ?>


                            <?php if ($entrada_siguiente) : ?>

                                <a
                                    class="hc-single-post__aside-nav-item"
                                    href="<?php
                                        echo esc_url(
                                            get_permalink(
                                                $entrada_siguiente->ID
                                            )
                                        );
                                    ?>"
                                >

                                    <span class="hc-single-post__aside-nav-etiqueta">
                                        Siguiente →
                                    </span>

                                    <span class="hc-single-post__aside-nav-titulo">
                                        <?php
                                        echo esc_html(
                                            get_the_title(
                                                $entrada_siguiente->ID
                                            )
                                        );
                                        ?>
                                    </span>

                                </a>

                            <?php endif; ?>


                        </div>

                    </section>

                <?php endif; ?>



                <!-- ==============================================
                     TAMBIÉN TE PUEDE INTERESAR
                =============================================== -->

                <?php

                $categorias_relacionadas =
                    wp_get_post_categories(
                        get_the_ID()
                    );


                if (!empty($categorias_relacionadas)) :


                    $relacionados = new WP_Query(
                        array(
                            'post_type'           => 'post',
                            'posts_per_page'      => 3,
                            'post_status'         => 'publish',
                            'post__not_in'        => array(
                                get_the_ID()
                            ),
                            'category__in'        =>
                                $categorias_relacionadas,
                            'ignore_sticky_posts' => true
                        )
                    );


                    if ($relacionados->have_posts()) :

                        ?>

                        <section class="hc-single-post__aside-bloque">

                            <h2 class="hc-single-post__aside-titulo">
                                También te puede interesar
                            </h2>


                            <div class="hc-single-post__aside-relacionados">


                                <?php

                                while ($relacionados->have_posts()) :

                                    $relacionados->the_post();

                                    ?>

                                    <article
                                        class="
                                            hc-single-post__aside-relacionado
                                        "
                                    >

                                        <a
                                            class="
                                                hc-single-post__aside-relacionado-enlace
                                            "
                                            href="<?php the_permalink(); ?>"
                                        >


                                            <?php
                                            if (has_post_thumbnail()) :
                                                ?>

                                                <div
                                                    class="
                                                        hc-single-post__aside-relacionado-imagen
                                                    "
                                                >

                                                    <?php

                                                    the_post_thumbnail(
                                                        'medium',
                                                        array(
                                                            'class' =>
                                                                'hc-single-post__aside-relacionado-foto',
                                                            'loading' =>
                                                                'lazy'
                                                        )
                                                    );

                                                    ?>

                                                </div>

                                            <?php endif; ?>


                                            <h3
                                                class="
                                                    hc-single-post__aside-relacionado-titulo
                                                "
                                            >
                                                <?php the_title(); ?>
                                            </h3>


                                        </a>

                                    </article>

                                <?php endwhile; ?>


                            </div>

                        </section>

                        <?php

                    endif;


                    wp_reset_postdata();


                endif;

                ?>


            </aside>


        </div>



        <!-- ==================================================
             VOLVER AL BLOG
        =================================================== -->

        <?php

        $pagina_blog = get_option('page_for_posts');

        ?>


        <?php if ($pagina_blog) : ?>

            <div class="hc-single-post__volver-blog">

                <a
                    href="<?php
                        echo esc_url(
                            get_permalink(
                                $pagina_blog
                            )
                        );
                    ?>"
                >

                    <span>
                        Volver al blog
                    </span>

                    <span
                        class="hc-single-post__volver-brujula"
                        aria-hidden="true"
                    ></span>

                </a>

            </div>

        <?php endif; ?>


    </article>


<?php endwhile; ?>


</main>


<?php

get_footer();