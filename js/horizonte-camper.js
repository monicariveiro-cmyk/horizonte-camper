/**
 * ==========================================================
 * Horizonte Camper
 * JavaScript principal del tema hijo
 * Archivo: js/horizonte-camper.js
 * ==========================================================
 */


/* ==========================================================
   1. CABECERA INTELIGENTE
   Se oculta al bajar y aparece al subir
========================================================== */

(function () {

    const cabecera = document.querySelector(
        "#site-header, header.site-header, .site-header"
    );

    if (!cabecera) {
        return;
    }

    let scrollAnterior = window.scrollY;

    window.addEventListener(
        "scroll",
        function () {

            const scrollActual = window.scrollY;

            /* Cerca del inicio, la cabecera permanece visible */
            if (scrollActual <= 80) {
                cabecera.classList.remove("cabecera-oculta");
                scrollAnterior = scrollActual;
                return;
            }

            /* Al bajar, ocultamos la cabecera */
            if (scrollActual > scrollAnterior + 5) {
                cabecera.classList.add("cabecera-oculta");
            }

            /* Al subir, mostramos de nuevo la cabecera */
            else if (scrollActual < scrollAnterior - 5) {
                cabecera.classList.remove("cabecera-oculta");
            }

            scrollAnterior = scrollActual;
        },
        {
            passive: true
        }
    );

})();


/* ==========================================================
   2. BOTÓN VOLVER ARRIBA
========================================================== */

document.addEventListener(
    "DOMContentLoaded",
    function () {

        const botonArriba = document.getElementById("volver-arriba");

        /*
         * Si el botón no existe en esta página,
         * detenemos esta parte del script.
         */
        if (!botonArriba) {
            return;
        }

        window.addEventListener(
            "scroll",
            function () {

                if (window.scrollY > 500) {
                    botonArriba.classList.add("visible");
                } else {
                    botonArriba.classList.remove("visible");
                }
            },
            {
                passive: true
            }
        );

        botonArriba.addEventListener(
            "click",
            function (e) {

                e.preventDefault();

                window.scrollTo({
                    top: 0,
                    behavior: "smooth"
                });
            }
        );

    }
);


/* ==========================================================
   3. ANIMACIÓN DEL BLOG AL HACER SCROLL
========================================================== */

/*
 * Esta clase indica al CSS que JavaScript
 * se ha cargado correctamente.
 */
document.documentElement.classList.add("hc-js");

document.addEventListener(
    "DOMContentLoaded",
    function () {

        const tarjetas = document.querySelectorAll(".hc-articulo");

        /*
         * Si no hay tarjetas del blog,
         * no hacemos nada.
         */
        if (!tarjetas.length) {
            return;
        }

        /*
         * Compatibilidad con navegadores que
         * no soporten IntersectionObserver.
         */
        if (!("IntersectionObserver" in window)) {

            tarjetas.forEach(
                function (tarjeta) {
                    tarjeta.classList.add("hc-visible");
                }
            );

            return;
        }

        /*
         * Observador que detecta cuándo las tarjetas
         * entran en la zona visible de la pantalla.
         */
        const observador = new IntersectionObserver(
            function (entradas, observer) {

                entradas.forEach(
                    function (entrada) {

                        if (entrada.isIntersecting) {

                            entrada.target.classList.add("hc-visible");

                            /*
                             * Dejamos de observar la tarjeta
                             * una vez que ya se ha mostrado.
                             */
                            observer.unobserve(entrada.target);
                        }
                    }
                );
            },
            {
                threshold: 0.15,
                rootMargin: "0px 0px -40px 0px"
            }
        );

        /*
         * Empezar a observar todas las tarjetas.
         */
        tarjetas.forEach(
            function (tarjeta) {
                observador.observe(tarjeta);
            }
        );

    }
);

/* ==========================================================
   4. ANIMACIÓN DE LA SECCIÓN "NUESTRA FILOSOFÍA"
========================================================== */

document.addEventListener(
    "DOMContentLoaded",
    function () {

        const filosofia = document.getElementById("filosofia");

        /*
         * Si esta sección no existe en la página,
         * no hacemos nada.
         */
        if (!filosofia) {
            return;
        }

        /*
         * Compatibilidad con navegadores antiguos.
         */
        if (!("IntersectionObserver" in window)) {
            filosofia.classList.add("visible");
            return;
        }

        /*
         * Detectar cuándo la sección entra en pantalla.
         */
        const observadorFilosofia = new IntersectionObserver(
            function (entradas, observer) {

                entradas.forEach(
                    function (entrada) {

                        if (entrada.isIntersecting) {

                            entrada.target.classList.add("visible");

                            /*
                             * La animación solo se ejecuta una vez.
                             */
                            observer.unobserve(entrada.target);
                        }
                    }
                );
            },
            {
                threshold: 0.2,
                rootMargin: "0px 0px -50px 0px"
            }
        );

        observadorFilosofia.observe(filosofia);

    }
);